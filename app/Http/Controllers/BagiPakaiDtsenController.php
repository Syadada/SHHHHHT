<?php

namespace App\Http\Controllers;

use App\Models\TiketDtsen;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BagiPakaiDtsenController extends Controller
{
    /**
     * SOP Resmi Layanan Bagi Pakai DTSEN
     */
    public const NO_SOP = '555/07-SOP/DISKOMINFO SP/2026';

    /**
     * 1. Daftar Permintaan Layanan Bagi Pakai DTSEN
     */
    public function index(Request $request)
    {
        $query = TiketDtsen::query();

        // 1. Pencarian Kata Kunci
        if ($request->filled('search')) {
            $search = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nomor_tiket', 'like', $search)
                  ->orWhere('nama_instansi', 'like', $search)
                  ->orWhere('no_surat_edaran', 'like', $search)
                  ->orWhere('nama_pemohon', 'like', $search);
            });
        }

        // 2. Filter Status
        if ($request->filled('status')) {
            if ($request->status === 'SELESAI') {
                $query->where('step5_done', true);
            } elseif ($request->status === 'REVISI') {
                $query->where('status', 'PERLU_PERBAIKAN');
            } elseif ($request->status === 'PROSES') {
                $query->where('step5_done', false)
                      ->where('status', '!=', 'PERLU_PERBAIKAN');
            }
        }

        // 3. Filter Urutan (Sort By)
        $sortBy = $request->get('sort', 'terbaru');
        switch ($sortBy) {
            case 'terlama':
            case 'asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'updated_desc':
                $query->orderBy('updated_at', 'desc');
                break;
            case 'updated_asc':
                $query->orderBy('updated_at', 'asc');
                break;
            case 'alphabet_asc':
            case 'nama_asc':
            case 'instansi_asc':
                $query->orderBy('nama_instansi', 'asc');
                break;
            case 'alphabet_desc':
            case 'nama_desc':
            case 'instansi_desc':
                $query->orderBy('nama_instansi', 'desc');
                break;
            case 'tiket_asc':
                $query->orderBy('nomor_tiket', 'asc');
                break;
            case 'tiket_desc':
                $query->orderBy('nomor_tiket', 'desc');
                break;
            case 'progress_desc':
                $query->orderByRaw('(step1_done + step2_done + step3_done + step4_done + step5_done) DESC')
                      ->orderBy('created_at', 'desc');
                break;
            case 'progress_asc':
                $query->orderByRaw('(step1_done + step2_done + step3_done + step4_done + step5_done) ASC')
                      ->orderBy('created_at', 'desc');
                break;
            case 'terbaru':
            case 'desc':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $tikets = $query->paginate(12)->withQueryString();

        // Metrik Ringkas
        $totalPermintaan = TiketDtsen::count();
        $totalProses = TiketDtsen::where('step5_done', false)->where('status', '!=', 'PERLU_PERBAIKAN')->count();
        $totalSelesai = TiketDtsen::where('step5_done', true)->count();
        $totalRevisi = TiketDtsen::where('status', 'PERLU_PERBAIKAN')->count();

        $noSop = self::NO_SOP;

        return view('admin.bagi_pakai.index', compact(
            'tikets',
            'sortBy',
            'totalPermintaan',
            'totalProses',
            'totalSelesai',
            'totalRevisi',
            'noSop'
        ));
    }

    /**
     * 2. Form Pembuatan Permintaan Layanan Baru (Atas Nama OPD)
     */
    public function create()
    {
        $opdUsers = User::where('role', 'perangkat_daerah')
            ->orderBy('nama_instansi')
            ->get();

        $noSop = self::NO_SOP;

        return view('admin.bagi_pakai.create', compact('opdUsers', 'noSop'));
    }

    /**
     * 3. Simpan Permintaan Layanan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:200',
            'no_surat_edaran' => 'required|string|max:150',
            'nama_pemohon' => 'nullable|string|max:150',
            'kontak_pemohon' => 'nullable|string|max:100',
            'email_pemohon' => 'nullable|email|max:150',
            'tujuan_pemanfaatan' => 'nullable|string',
            'variabel_dtsen' => 'required|array|min:1',
            'file_surat' => 'nullable|file|max:51200', // max 50MB
        ], [
            'nama_instansi.required' => 'Nama Instansi / OPD pemohon wajib diisi.',
            'no_surat_edaran.required' => 'Nomor Surat Edaran resmi wajib diisi.',
            'variabel_dtsen.required' => 'Minimal salah satu variabel data DTSEN harus diisi.',
            'file_surat.max' => 'Ukuran berkas surat tidak boleh melebihi 50MB.',
        ]);

        // Bersihkan variabel kosong
        $variabelInput = array_filter(array_map('trim', (array) $request->input('variabel_dtsen')));
        if (empty($variabelInput)) {
            return back()->withInput()->withErrors([
                'variabel_dtsen' => 'Mohon isi setidaknya satu kolom variabel DTSEN yang dimohonkan.'
            ]);
        }

        // Upload berkas jika ada
        $filePath = null;
        $originalName = null;
        $formattedSize = null;
        $extension = null;
        $hashSha256 = null;

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension() ?: 'pdf');
            $sizeBytes = $file->getSize();

            if ($sizeBytes >= 1048576) {
                $formattedSize = round($sizeBytes / 1048576, 2) . ' MB';
            } else {
                $formattedSize = round($sizeBytes / 1024, 2) . ' KB';
            }

            $filePath = $file->store('surat_edaran', 'public');
            $hashSha256 = hash_file('sha256', $file->getRealPath());
        }

        // Generate Nomor Tiket Unik
        $nomorTiket = 'TIK-DTSEN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $tiket = TiketDtsen::create([
            'nomor_tiket' => $nomorTiket,
            'nama_instansi' => trim($request->input('nama_instansi')),
            'no_surat_edaran' => trim($request->input('no_surat_edaran')),
            'nama_pemohon' => trim($request->input('nama_pemohon')) ?: 'PIC ' . trim($request->input('nama_instansi')),
            'kontak_pemohon' => trim($request->input('kontak_pemohon')),
            'email_pemohon' => trim($request->input('email_pemohon')),
            'tujuan_pemanfaatan' => trim($request->input('tujuan_pemanfaatan')) ?: 'Permohonan bagi pakai data DTSEN sesuai dasar surat edaran resmi.',
            'variabel_dtsen' => array_values($variabelInput),
            'file_surat' => $filePath,
            'nama_file_asli' => $originalName,
            'ukuran_file' => $formattedSize,
            'ekstensi_file' => $extension,
            'hash_sha256' => $hashSha256,
            'current_step' => 1,
            'step1_done' => false,
            'step2_done' => false,
            'step3_done' => false,
            'step4_done' => false,
            'step5_done' => false,
            'status' => 'DIPROSES',
            'catatan_admin' => 'Permintaan layanan dibuat oleh Walidata/Admin atas nama OPD (SOP: ' . self::NO_SOP . '). Menunggu verifikasi berkas.',
        ]);

        AuditLogger::log(
            'ADMIN_CREATE_TIKET',
            "Admin " . auth()->user()->name . " membuat permohonan layanan bagi pakai DTSEN atas nama OPD [{$tiket->nama_instansi}] (No. Tiket: {$tiket->nomor_tiket}, SOP: " . self::NO_SOP . ").",
            'SUCCESS',
            ['nomor_tiket' => $tiket->nomor_tiket, 'instansi' => $tiket->nama_instansi]
        );

        return redirect()->route('admin.bagi-pakai-dtsen.index')
            ->with('success', "Permintaan layanan bagi pakai DTSEN [#{$tiket->nomor_tiket}] untuk OPD {$tiket->nama_instansi} berhasil dibuat!");
    }

    /**
     * 4. Form Edit Permintaan Layanan
     */
    public function edit($id)
    {
        $tiket = TiketDtsen::findOrFail($id);
        $opdUsers = User::where('role', 'perangkat_daerah')
            ->orderBy('nama_instansi')
            ->get();
        $noSop = self::NO_SOP;

        return view('admin.bagi_pakai.edit', compact('tiket', 'opdUsers', 'noSop'));
    }

    /**
     * 5. Perbarui Permintaan Layanan
     */
    public function update(Request $request, $id)
    {
        $tiket = TiketDtsen::findOrFail($id);

        $request->validate([
            'nama_instansi' => 'required|string|max:200',
            'no_surat_edaran' => 'required|string|max:150',
            'nama_pemohon' => 'nullable|string|max:150',
            'kontak_pemohon' => 'nullable|string|max:100',
            'email_pemohon' => 'nullable|email|max:150',
            'tujuan_pemanfaatan' => 'nullable|string',
            'variabel_dtsen' => 'required|array|min:1',
            'status' => 'required|string|in:DIPROSES,SELESAI,PERLU_PERBAIKAN',
            'file_surat' => 'nullable|file|max:51200',
        ], [
            'nama_instansi.required' => 'Nama Instansi / OPD pemohon wajib diisi.',
            'no_surat_edaran.required' => 'Nomor Surat Edaran resmi wajib diisi.',
            'variabel_dtsen.required' => 'Minimal salah satu variabel data DTSEN harus diisi.',
            'file_surat.max' => 'Ukuran berkas surat tidak boleh melebihi 50MB.',
        ]);

        $variabelInput = array_filter(array_map('trim', (array) $request->input('variabel_dtsen')));
        if (empty($variabelInput)) {
            return back()->withInput()->withErrors([
                'variabel_dtsen' => 'Mohon isi setidaknya satu kolom variabel DTSEN yang dimohonkan.'
            ]);
        }

        $tiket->nama_instansi = trim($request->input('nama_instansi'));
        $tiket->no_surat_edaran = trim($request->input('no_surat_edaran'));
        $tiket->nama_pemohon = trim($request->input('nama_pemohon')) ?: $tiket->nama_pemohon;
        $tiket->kontak_pemohon = trim($request->input('kontak_pemohon'));
        $tiket->email_pemohon = trim($request->input('email_pemohon'));
        $tiket->tujuan_pemanfaatan = trim($request->input('tujuan_pemanfaatan')) ?: $tiket->tujuan_pemanfaatan;
        $tiket->variabel_dtsen = array_values($variabelInput);
        $tiket->status = $request->input('status');

        if ($request->filled('catatan_admin')) {
            $tiket->catatan_admin = trim($request->input('catatan_admin'));
        }

        // Jika status diubah jadi SELESAI, aktifkan step5
        if ($tiket->status === 'SELESAI' && !$tiket->step5_done) {
            $tiket->step1_done = true;
            $tiket->step2_done = true;
            $tiket->step3_done = true;
            $tiket->step4_done = true;
            $tiket->step5_done = true;
            $tiket->step5_at = now();
        }

        // Upload berkas baru jika diunggah
        if ($request->hasFile('file_surat')) {
            if ($tiket->file_surat && Storage::disk('public')->exists($tiket->file_surat)) {
                Storage::disk('public')->delete($tiket->file_surat);
            }

            $file = $request->file('file_surat');
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension() ?: 'pdf');
            $sizeBytes = $file->getSize();

            $formattedSize = ($sizeBytes >= 1048576)
                ? round($sizeBytes / 1048576, 2) . ' MB'
                : round($sizeBytes / 1024, 2) . ' KB';

            $tiket->file_surat = $file->store('surat_edaran', 'public');
            $tiket->nama_file_asli = $originalName;
            $tiket->ukuran_file = $formattedSize;
            $tiket->ekstensi_file = $extension;
            $tiket->hash_sha256 = hash_file('sha256', $file->getRealPath());
        }

        $tiket->save();

        AuditLogger::log(
            'ADMIN_EDIT_TIKET',
            "Admin " . auth()->user()->name . " memperbarui data permohonan layanan bagi pakai DTSEN [#{$tiket->nomor_tiket}] ({$tiket->nama_instansi}).",
            'SUCCESS',
            ['nomor_tiket' => $tiket->nomor_tiket, 'instansi' => $tiket->nama_instansi]
        );

        return redirect()->route('admin.bagi-pakai-dtsen.index')
            ->with('success', "Permintaan layanan [#{$tiket->nomor_tiket}] berhasil diperbarui!");
    }

    /**
     * 6. Hapus Permintaan Layanan
     */
    public function destroy($id)
    {
        $tiket = TiketDtsen::findOrFail($id);
        $nomor = $tiket->nomor_tiket;
        $instansi = $tiket->nama_instansi;

        if ($tiket->file_surat && Storage::disk('public')->exists($tiket->file_surat)) {
            Storage::disk('public')->delete($tiket->file_surat);
        }

        $tiket->delete();

        AuditLogger::log(
            'ADMIN_DELETE_TIKET',
            "Admin " . auth()->user()->name . " menghapus permintaan layanan bagi pakai DTSEN [#{$nomor}] ({$instansi}).",
            'WARNING',
            ['nomor_tiket' => $nomor, 'instansi' => $instansi]
        );

        return redirect()->route('admin.bagi-pakai-dtsen.index')
            ->with('success', "Permintaan layanan [#{$nomor}] milik {$instansi} berhasil dihapus dari sistem.");
    }
}
