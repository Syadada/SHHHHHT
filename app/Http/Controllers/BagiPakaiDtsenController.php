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
        $nomorTiket = $request->filled('nomor_tiket') ? trim($request->input('nomor_tiket')) : ('TIK-DTSEN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)));

        // Matriks SOP Dokumen Persyaratan
        $extraSop = [
            'status_kak' => $request->input('status_kak', 'Ada'),
            'nomor_kak' => $request->input('nomor_kak', $request->input('no_surat_edaran')),
            'status_tim' => $request->input('status_tim', 'Ada'),
            'nomor_tim' => $request->input('nomor_tim', $request->input('no_surat_tugas', 'ST.090/182/DISKOMINFO SP/' . date('Y'))),
            'status_ropa' => $request->input('status_ropa', 'Sesuai'),
            'nomor_ropa' => $request->input('nomor_ropa', 'ROPA-DTSEN-' . date('Y')),
            'status_dpia' => $request->input('status_dpia', 'Sesuai'),
            'nomor_dpia' => $request->input('nomor_dpia', 'DPIA-DTSEN-' . date('Y')),
            'status_klasifikasi' => $request->input('status_klasifikasi', 'Sesuai (Data Terbatas)'),
            'nomor_klasifikasi' => $request->input('nomor_klasifikasi', 'KLAS-DTSEN-' . date('Y')),
            'status_hak_akses' => $request->input('status_hak_akses', 'Diverifikasi'),
            'nomor_hak_akses' => $request->input('nomor_hak_akses', 'HA-DTSEN-' . date('Y')),
            'jadwal_serah_terima' => $request->input('jadwal_serah_terima', date('Y-m-d')),
            'no_formulir_pengolahan' => $request->input('no_formulir_pengolahan'),
            'no_formulir_transfer' => $request->input('no_formulir_transfer'),
            'no_bast' => $request->input('no_bast'),
        ];

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
            'catatan_admin' => $request->filled('catatan_admin') ? trim($request->input('catatan_admin')) : 'Permintaan layanan dibuat oleh Walidata atas nama OPD (SOP: ' . self::NO_SOP . '). Menunggu verifikasi berkas.',

            // Data Elemen Lampiran I SOP Form
            'nomor_naskah' => trim($request->input('nomor_naskah')) ?: self::NO_SOP,
            'no_agenda_disposisi' => trim($request->input('no_agenda_disposisi')) ?: ('DSP-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4))),
            'tgl_permintaan' => $request->input('tgl_permintaan') ?: date('Y-m-d'),
            'tgl_diterima' => $request->input('tgl_diterima') ?: date('Y-m-d'),
            'no_surat_tugas' => trim($request->input('no_surat_tugas')) ?: ('ST.090/182/DISKOMINFO SP/' . date('Y')),
            'tgl_surat_tugas' => $request->input('tgl_surat_tugas') ?: date('Y-m-d'),
            'deskripsi_pekerjaan' => trim($request->input('deskripsi_pekerjaan')) ?: ('Memenuhi permintaan layanan bagi pakai data mikro DTSEN dari Walidata ke ' . trim($request->input('nama_instansi')) . '.'),
            'penanggung_jawab' => trim($request->input('penanggung_jawab')) ?: 'Chobir Sabbaha, S.Kom., M.T.',
            'tempat_pelaksanaan' => trim($request->input('tempat_pelaksanaan')) ?: 'Gedung Diskominfo SP Provinsi Banten (KP3B Serang)',
            'pelaksana_1' => trim($request->input('pelaksana_1')) ?: 'Tia Fitria Saumi, S.Kom.',
            'pelaksana_2' => trim($request->input('pelaksana_2')) ?: 'Ahmad Fauzi, S.T.',
            'pelaksana_3' => trim($request->input('pelaksana_3')) ?: '-',
            'pelaksana_4' => trim($request->input('pelaksana_4')) ?: '-',
            'tgl_mulai' => $request->input('tgl_mulai') ?: date('Y-m-d'),
            'tgl_selesai' => $request->input('tgl_selesai') ?: date('Y-m-d', strtotime('+3 days')),
            'sop_rujukan' => trim($request->input('sop_rujukan')) ?: self::NO_SOP,
            'hasil_pekerjaan' => trim($request->input('hasil_pekerjaan')) ?: ('Pelaksana telah melaksanakan pemenuhan permintaan Layanan Bagi Pakai DTSEN ke ' . trim($request->input('nama_instansi')) . ' sesuai dengan penugasan pimpinan dan SOP ' . self::NO_SOP . '.'),
            'data_sop_extra' => $extraSop,
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

        // Simpan Data Elemen Lampiran I SOP Form
        if ($request->filled('nomor_naskah')) $tiket->nomor_naskah = trim($request->input('nomor_naskah'));
        if ($request->filled('no_agenda_disposisi')) $tiket->no_agenda_disposisi = trim($request->input('no_agenda_disposisi'));
        if ($request->filled('tgl_permintaan')) $tiket->tgl_permintaan = $request->input('tgl_permintaan');
        if ($request->filled('tgl_diterima')) $tiket->tgl_diterima = $request->input('tgl_diterima');
        if ($request->filled('no_surat_tugas')) $tiket->no_surat_tugas = trim($request->input('no_surat_tugas'));
        if ($request->filled('tgl_surat_tugas')) $tiket->tgl_surat_tugas = $request->input('tgl_surat_tugas');
        if ($request->filled('deskripsi_pekerjaan')) $tiket->deskripsi_pekerjaan = trim($request->input('deskripsi_pekerjaan'));
        if ($request->filled('penanggung_jawab')) $tiket->penanggung_jawab = trim($request->input('penanggung_jawab'));
        if ($request->filled('tempat_pelaksanaan')) $tiket->tempat_pelaksanaan = trim($request->input('tempat_pelaksanaan'));
        if ($request->filled('pelaksana_1')) $tiket->pelaksana_1 = trim($request->input('pelaksana_1'));
        if ($request->filled('pelaksana_2')) $tiket->pelaksana_2 = trim($request->input('pelaksana_2'));
        if ($request->filled('pelaksana_3')) $tiket->pelaksana_3 = trim($request->input('pelaksana_3'));
        if ($request->filled('pelaksana_4')) $tiket->pelaksana_4 = trim($request->input('pelaksana_4'));
        if ($request->filled('tgl_mulai')) $tiket->tgl_mulai = $request->input('tgl_mulai');
        if ($request->filled('tgl_selesai')) $tiket->tgl_selesai = $request->input('tgl_selesai');
        if ($request->filled('sop_rujukan')) $tiket->sop_rujukan = trim($request->input('sop_rujukan'));
        if ($request->filled('hasil_pekerjaan')) $tiket->hasil_pekerjaan = trim($request->input('hasil_pekerjaan'));

        // Update data_sop_extra
        $existingExtra = is_array($tiket->data_sop_extra) ? $tiket->data_sop_extra : [];
        $extraSop = array_merge($existingExtra, [
            'status_kak' => $request->input('status_kak', $existingExtra['status_kak'] ?? 'Ada'),
            'nomor_kak' => $request->input('nomor_kak', $existingExtra['nomor_kak'] ?? $tiket->no_surat_edaran),
            'status_tim' => $request->input('status_tim', $existingExtra['status_tim'] ?? 'Ada'),
            'nomor_tim' => $request->input('nomor_tim', $existingExtra['nomor_tim'] ?? $tiket->no_surat_tugas),
            'status_ropa' => $request->input('status_ropa', $existingExtra['status_ropa'] ?? 'Sesuai'),
            'nomor_ropa' => $request->input('nomor_ropa', $existingExtra['nomor_ropa'] ?? ('ROPA-DTSEN-' . date('Y'))),
            'status_dpia' => $request->input('status_dpia', $existingExtra['status_dpia'] ?? 'Sesuai'),
            'nomor_dpia' => $request->input('nomor_dpia', $existingExtra['nomor_dpia'] ?? ('DPIA-DTSEN-' . date('Y'))),
            'status_klasifikasi' => $request->input('status_klasifikasi', $existingExtra['status_klasifikasi'] ?? 'Sesuai (Data Terbatas)'),
            'nomor_klasifikasi' => $request->input('nomor_klasifikasi', $existingExtra['nomor_klasifikasi'] ?? ('KLAS-DTSEN-' . date('Y'))),
            'status_hak_akses' => $request->input('status_hak_akses', $existingExtra['status_hak_akses'] ?? 'Diverifikasi'),
            'nomor_hak_akses' => $request->input('nomor_hak_akses', $existingExtra['nomor_hak_akses'] ?? ('HA-DTSEN-' . date('Y'))),
            'jadwal_serah_terima' => $request->input('jadwal_serah_terima', $existingExtra['jadwal_serah_terima'] ?? date('Y-m-d')),
            'no_formulir_pengolahan' => $request->input('no_formulir_pengolahan', $existingExtra['no_formulir_pengolahan'] ?? ''),
            'no_formulir_transfer' => $request->input('no_formulir_transfer', $existingExtra['no_formulir_transfer'] ?? ''),
            'no_bast' => $request->input('no_bast', $existingExtra['no_bast'] ?? ''),
        ]);
        $tiket->data_sop_extra = $extraSop;

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

    /**
     * 7. Tampilkan Formulir Pemenuhan Permintaan Layanan (Format SOP Lampiran I)
     */
    public function formulir($id)
    {
        $tiket = TiketDtsen::findOrFail($id);
        $noSop = self::NO_SOP;

        return view('admin.bagi_pakai.formulir_pemenuhan', compact('tiket', 'noSop'));
    }

    /**
     * 8. Generate & Unduh Berkas Microsoft Word (.docx) Resmi dari Template Root
     */
    public function downloadDocx($id)
    {
        $tiket = TiketDtsen::findOrFail($id);

        $scriptPath = base_path('python_engine/generate_formulir_docx.py');
        $cmd = "python " . escapeshellarg($scriptPath) . " " . escapeshellarg($tiket->id);

        $output = shell_exec($cmd);
        $res = json_decode($output, true);

        if ($res && !empty($res['success']) && file_exists($res['path'])) {
            return response()->download($res['path'], $res['filename'], [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ]);
        }

        return back()->with('error', 'Gagal men-generate berkas dokumen Word: ' . ($res['error'] ?? 'Terjadi kesalahan pada script generator.'));
    }
}

