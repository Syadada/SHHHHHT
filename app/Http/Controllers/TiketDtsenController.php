<?php

namespace App\Http\Controllers;

use App\Models\TiketDtsen;
use App\Models\AuditLog;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TiketDtsenController extends Controller
{
    /**
     * Halaman Utama: Layanan Permintaan Bagi Pakai DTSEN (Sisi Pengguna / Instansi)
     */
    public function index(Request $request)
    {
        // Admin tidak mengakses halaman tiket pemohon, arahkan langsung ke Dashboard Pengelolaan Tiket
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $query = TiketDtsen::query()->latest();

        // Pencarian tiket berdasarkan nomor tiket atau nama instansi
        if ($request->filled('search')) {
            $search = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nomor_tiket', 'like', $search)
                  ->orWhere('nama_instansi', 'like', $search)
                  ->orWhere('no_surat_edaran', 'like', $search)
                  ->orWhere('nama_pemohon', 'like', $search);
            });
        }

        $tikets = $query->paginate(10)->withQueryString();
        
        // Tiket yang sedang disorot (bisa dari session tiket baru, parameter URL, atau tiket teratas)
        $selectedTiket = null;
        if ($request->filled('track_id')) {
            $selectedTiket = TiketDtsen::find($request->track_id);
        } elseif (session('new_tiket_id')) {
            $selectedTiket = TiketDtsen::find(session('new_tiket_id'));
        } elseif ($tikets->count() > 0) {
            $selectedTiket = $tikets->first();
        }

        // Metrik Layanan
        $totalTiket = TiketDtsen::count();
        $totalSelesai = TiketDtsen::where('step5_done', true)->count();
        $totalProses = TiketDtsen::where('step5_done', false)->count();

        // Jika pengguna sudah login sebagai OPD, tampilkan form tiket ber-sidebar & ber-header
        if (auth()->check()) {
            return view('opd.tiket.create');
        }

        return view('formulir.dtsen_tiket', compact(
            'tikets',
            'selectedTiket',
            'totalTiket',
            'totalSelesai',
            'totalProses'
        ));
    }

    /**
     * Halaman Khusus Buat Tiket Baru (OPD Portal)
     */
    public function create()
    {
        return view('opd.tiket.create');
    }

    /**
     * Halaman Edit Formulir Permohonan (Khusus Status PERLU_PERBAIKAN)
     * KUNCI FORM: Sebelum di-reject oleh admin, OPD tidak dapat mengedit formulir ini.
     */
    public function edit($id)
    {
        $tiket = TiketDtsen::findOrFail($id);
        $user = auth()->user();

        // Validasi hak akses kepemilikan jika bukan super admin
        if (!$user->isSuperAdmin() && !str_contains(strtolower($tiket->nama_instansi), strtolower($user->nama_instansi ?: '')) && $tiket->email_pemohon !== $user->email) {
            return redirect()->route('opd.tiket.index')
                ->with('error', 'Anda tidak memiliki hak untuk mengedit permohonan instansi lain.');
        }

        // KUNCI FORM: Hanya status PERLU_PERBAIKAN yang boleh diedit
        if ($tiket->status !== 'PERLU_PERBAIKAN') {
            return redirect()->route('opd.tiket.show', $tiket->id)
                ->with('error', 'Permohonan sedang dalam proses resmi dan tidak dapat diedit sebelum ada permintaan perbaikan dari Admin Walidata.');
        }

        return view('opd.tiket.edit', compact('tiket'));
    }

    /**
     * Submit Ulang Hasil Perbaikan Formulir & Berkas oleh OPD
     * Status dikembalikan ke PENDING dan form otomatis terkunci kembali.
     */
    public function update(Request $request, $id)
    {
        $tiket = TiketDtsen::findOrFail($id);
        $user = auth()->user();

        // Validasi hak akses kepemilikan jika bukan super admin
        if (!$user->isSuperAdmin() && !str_contains(strtolower($tiket->nama_instansi), strtolower($user->nama_instansi ?: '')) && $tiket->email_pemohon !== $user->email) {
            return redirect()->route('opd.tiket.index')
                ->with('error', 'Anda tidak memiliki hak untuk mengedit permohonan instansi lain.');
        }

        // KUNCI FORM: Hanya status PERLU_PERBAIKAN yang boleh diupdate
        if ($tiket->status !== 'PERLU_PERBAIKAN') {
            return redirect()->route('opd.tiket.show', $tiket->id)
                ->with('error', 'Permohonan tidak dapat diubah karena tidak dalam status perbaikan.');
        }

        // Validasi Form Perbaikan
        $request->validate([
            'no_surat_permohonan' => 'nullable|string|max:150',
            'tgl_surat_permohonan' => 'nullable|date',
            'perihal' => 'nullable|string|max:500',
            'nama_narahubung' => 'required|string|max:150',
            'no_narahubung' => 'required|string|max:100',
            'file_kak' => 'nullable|file|max:51200',
            'file_surat_permohonan' => 'nullable|file|max:51200',
            'file_ropa' => 'nullable|file|max:51200',
            'file_dpia' => 'nullable|file|max:51200',
            'file_sktm' => 'nullable|file|max:51200',
            'file_klasifikasi_dtsen' => 'nullable|file|max:51200',
            'file_hak_akses' => 'nullable|file|max:51200',
            'variabel_dtsen' => 'nullable|array',
            'catatan_opd' => 'nullable|string|max:1000',
        ], [
            'nama_narahubung.required' => 'Nama Narahubung wajib diisi.',
            'no_narahubung.required' => 'Nomor Narahubung wajib diisi.',
            'file_kak.max' => 'Ukuran berkas Surat KAK tidak boleh melebihi 50MB.',
            'file_surat_permohonan.max' => 'Ukuran berkas Surat Permohonan tidak boleh melebihi 50MB.',
        ]);

        // Helper simpan berkas baru
        $saveFile = function($fieldName) use ($request) {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $originalName = $file->getClientOriginalName();
                $path = $file->store('dokumen_dtsen', 'public');
                return [$path, $originalName];
            }
            return [null, null];
        };

        // Perbarui data identitas & surat
        if ($request->filled('no_surat_permohonan')) {
            $tiket->no_surat_permohonan = trim($request->input('no_surat_permohonan'));
            $tiket->no_surat_edaran = $tiket->no_surat_permohonan;
        }
        if ($request->filled('tgl_surat_permohonan')) {
            $tiket->tgl_surat_permohonan = $request->input('tgl_surat_permohonan');
            $tiket->tgl_permintaan = $tiket->tgl_surat_permohonan;
        }
        if ($request->filled('perihal')) {
            $tiket->perihal = trim($request->input('perihal'));
        }
        $tiket->nama_narahubung = trim($request->input('nama_narahubung'));
        $tiket->no_narahubung = trim($request->input('no_narahubung'));
        $tiket->nama_pemohon = $tiket->nama_narahubung;
        $tiket->kontak_pemohon = $tiket->no_narahubung;

        // Perbarui berkas hanya jika OPD mengunggah berkas baru
        if ($request->hasFile('file_kak')) {
            [$pathKak, $nameKak] = $saveFile('file_kak');
            $tiket->file_kak = $pathKak;
            $tiket->nama_file_kak = $nameKak;
        }

        if ($request->hasFile('file_surat_permohonan')) {
            [$pathSurat, $nameSurat] = $saveFile('file_surat_permohonan');
            $tiket->file_surat_permohonan = $pathSurat;
            $tiket->nama_file_surat_permohonan = $nameSurat;
            $tiket->file_surat = $pathSurat;
            $tiket->nama_file_asli = $nameSurat;
            $primaryFile = $request->file('file_surat_permohonan');
            $tiket->ekstensi_file = strtolower($primaryFile->getClientOriginalExtension() ?: 'pdf');
            $sizeBytes = $primaryFile->getSize();
            $tiket->ukuran_file = ($sizeBytes >= 1048576) 
                ? round($sizeBytes / 1048576, 2) . ' MB' 
                : round($sizeBytes / 1024, 2) . ' KB';
            $tiket->hash_sha256 = hash_file('sha256', $primaryFile->getRealPath());
        }

        if ($request->hasFile('file_ropa')) {
            [$pathRopa, $nameRopa] = $saveFile('file_ropa');
            $tiket->file_ropa = $pathRopa;
            $tiket->nama_file_ropa = $nameRopa;
        }

        if ($request->hasFile('file_dpia')) {
            [$pathDpia, $nameDpia] = $saveFile('file_dpia');
            $tiket->file_dpia = $pathDpia;
            $tiket->nama_file_dpia = $nameDpia;
        }

        if ($request->hasFile('file_sktm')) {
            [$pathSktm, $nameSktm] = $saveFile('file_sktm');
            $tiket->file_sktm = $pathSktm;
            $tiket->nama_file_sktm = $nameSktm;
        }

        if ($request->hasFile('file_klasifikasi_dtsen')) {
            [$pathKlasifikasi, $nameKlasifikasi] = $saveFile('file_klasifikasi_dtsen');
            $tiket->file_klasifikasi_dtsen = $pathKlasifikasi;
            $tiket->nama_file_klasifikasi_dtsen = $nameKlasifikasi;
        }

        if ($request->hasFile('file_hak_akses')) {
            [$pathHakAkses, $nameHakAkses] = $saveFile('file_hak_akses');
            $tiket->file_hak_akses = $pathHakAkses;
            $tiket->nama_file_hak_akses = $nameHakAkses;
        }

        // Hapus berkas jika ditandai hapus oleh OPD (hanya berkas opsional)
        $fieldsHapus = ['file_ropa', 'file_dpia', 'file_sktm', 'file_klasifikasi_dtsen', 'file_hak_akses'];
        foreach ($fieldsHapus as $f) {
            $keyHapus = 'hapus_' . $f;
            if ($request->input($keyHapus) == '1') {
                if ($tiket->$f && Storage::disk('public')->exists($tiket->$f)) {
                    Storage::disk('public')->delete($tiket->$f);
                }
                $tiket->$f = null;
                $namaCol = 'nama_' . $f;
                $tiket->$namaCol = null;
            }
        }

        // Perbarui variabel DTSEN
        if ($request->has('variabel_dtsen')) {
            $variabelInput = array_filter(array_map('trim', (array) $request->input('variabel_dtsen', [])));
            $tiket->variabel_dtsen = array_values($variabelInput);
        }

        // KUNCI KEMBALI FORM & KEMBALIKAN STATUS KE PENDING
        $tiket->status = 'PENDING';
        $catatanOpd = trim((string) $request->input('catatan_opd'));
        $catatanHistori = 'Berkas dan data permohonan telah diperbaiki dan dikirim ulang oleh ' . ($user->nama_instansi ?: $user->name) . ' pada ' . now()->format('d/m/Y H:i') . ' WIB.' . ($catatanOpd ? ' Catatan OPD: ' . $catatanOpd : '');
        $tiket->catatan_admin = $catatanHistori;
        $tiket->save();

        AuditLogger::log(
            'TIKET_FORM_REVISED',
            "Instansi {$tiket->nama_instansi} telah mengedit dan mengirim ulang formulir permohonan tiket [{$tiket->nomor_tiket}]. Status kembali PENDING.",
            'SUCCESS',
            ['nomor_tiket' => $tiket->nomor_tiket]
        );

        return redirect()->route('opd.tiket.show', $tiket->id)
            ->with('success', "Permohonan tiket #{$tiket->nomor_tiket} berhasil diperbaiki dan dikirim ulang ke Walidata! Status kini kembali PENDING menunggu verifikasi.");
    }

    /**
     * Buat Tiket Permintaan Bagi Pakai DTSEN Baru
     */
    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Validasi Field Permintaan Bagi Pakai dari Sisi OPD
        $request->validate([
            'nama_instansi' => 'required|string|max:200',
            'no_surat_permohonan' => 'nullable|string|max:150',
            'no_surat_edaran' => 'nullable|string|max:150',
            'tgl_surat_permohonan' => 'nullable|date',
            'perihal' => 'nullable|string|max:500',
            'nama_narahubung' => 'required|string|max:150',
            'no_narahubung' => 'required|string|max:100',
            'file_kak' => 'required|file|max:51200',
            'file_surat_permohonan' => 'nullable|file|max:51200',
            'file_surat' => 'nullable|file|max:51200',
            'file_ropa' => 'nullable|file|max:51200',
            'file_dpia' => 'nullable|file|max:51200',
            'file_sktm' => 'nullable|file|max:51200',
            'file_klasifikasi_dtsen' => 'nullable|file|max:51200',
            'file_hak_akses' => 'nullable|file|max:51200',
            'variabel_dtsen' => 'nullable|array',
        ], [
            'nama_instansi.required' => 'Nama Instansi pemohon wajib terisi.',
            'nama_narahubung.required' => 'Nama Narahubung wajib diisi (Required).',
            'no_narahubung.required' => 'Nomor Narahubung wajib diisi (Required).',
            'file_kak.required' => 'Berkas Surat KAK wajib diunggah (Required).',
            'file_kak.max' => 'Ukuran berkas Surat KAK tidak boleh melebihi 50MB.',
            'file_surat_permohonan.max' => 'Ukuran berkas Surat Permohonan tidak boleh melebihi 50MB.',
        ]);

        // Pastikan Berkas Surat Permohonan Diunggah (file_surat_permohonan atau file_surat)
        if (!$request->hasFile('file_surat_permohonan') && !$request->hasFile('file_surat')) {
            return back()->withInput()->withErrors([
                'file_surat_permohonan' => 'Berkas Surat Permohonan resmi wajib diunggah (Required).'
            ]);
        }

        // Variabel DTSEN dibuat opsional (bisa diisi atau dikosongkan dahulu)
        $variabelInput = array_filter(array_map('trim', (array) $request->input('variabel_dtsen', [])));

        // Helper simpan berkas
        $saveFile = function($fieldName) use ($request) {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $originalName = $file->getClientOriginalName();
                $path = $file->store('dokumen_dtsen', 'public');
                return [$path, $originalName];
            }
            return [null, null];
        };

        // 1. Surat KAK (Required)
        [$pathKak, $nameKak] = $saveFile('file_kak');

        // 2. Surat Permohonan (Required)
        [$pathSurat, $nameSurat] = $request->hasFile('file_surat_permohonan') 
            ? $saveFile('file_surat_permohonan') 
            : $saveFile('file_surat');

        // 3. ROPA (Opsional)
        [$pathRopa, $nameRopa] = $saveFile('file_ropa');

        // 4. DPIA (Opsional)
        [$pathDpia, $nameDpia] = $saveFile('file_dpia');

        // 5. SKTM (Opsional)
        [$pathSktm, $nameSktm] = $saveFile('file_sktm');

        // 6. Klasifikasi DTSEN (Opsional)
        [$pathKlasifikasi, $nameKlasifikasi] = $saveFile('file_klasifikasi_dtsen');

        // 7. Hak Akses (Opsional)
        [$pathHakAkses, $nameHakAkses] = $saveFile('file_hak_akses');

        // Formatted size & hash untuk berkas utama surat permohonan
        $formattedSize = null;
        $extension = null;
        $hashSha256 = null;
        $primaryFile = $request->file('file_surat_permohonan') ?: $request->file('file_surat');
        if ($primaryFile) {
            $extension = strtolower($primaryFile->getClientOriginalExtension() ?: 'pdf');
            $sizeBytes = $primaryFile->getSize();
            $formattedSize = ($sizeBytes >= 1048576) 
                ? round($sizeBytes / 1048576, 2) . ' MB' 
                : round($sizeBytes / 1024, 2) . ' KB';
            $hashSha256 = hash_file('sha256', $primaryFile->getRealPath());
        }

        // Generate Nomor Tiket Unik
        $nomorTiket = 'TIK-DTSEN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $namaNarahubung = trim($request->input('nama_narahubung'));
        $noNarahubung = trim($request->input('no_narahubung'));
        $noSuratPermohonan = trim((string) $request->input('no_surat_permohonan'));
        $tglSuratPermohonan = $request->input('tgl_surat_permohonan');
        $perihal = trim((string) $request->input('perihal'));

        $noSuratEdaran = $noSuratPermohonan ?: ($request->input('no_surat_edaran') ?: ('SP/' . date('Ymd') . '/' . strtoupper(substr(uniqid(), -4))));

        $tiket = TiketDtsen::create([
            'nomor_tiket' => $nomorTiket,
            'nama_instansi' => $request->input('nama_instansi'),
            'no_surat_permohonan' => $noSuratPermohonan ?: $noSuratEdaran,
            'tgl_surat_permohonan' => $tglSuratPermohonan ?: now()->toDateString(),
            'perihal' => $perihal ?: 'Permohonan bagi pakai data DTSEN',
            'no_surat_edaran' => $noSuratEdaran,
            'tgl_permintaan' => $tglSuratPermohonan ?: now()->toDateString(),
            'nama_narahubung' => $namaNarahubung,
            'no_narahubung' => $noNarahubung,
            'nama_pemohon' => $namaNarahubung, // Fallback backward compatibility
            'kontak_pemohon' => $noNarahubung,  // Fallback backward compatibility
            'email_pemohon' => $request->input('email_pemohon') ?: (auth()->user()->email ?? null),
            'tujuan_pemanfaatan' => $perihal ?: ($request->input('tujuan_pemanfaatan') ?: 'Permohonan bagi pakai data DTSEN untuk kebutuhan program instansi.'),
            'variabel_dtsen' => array_values($variabelInput),
            // Berkas Dokumen
            'file_kak' => $pathKak,
            'nama_file_kak' => $nameKak,
            'file_surat_permohonan' => $pathSurat,
            'nama_file_surat_permohonan' => $nameSurat,
            'file_ropa' => $pathRopa,
            'nama_file_ropa' => $nameRopa,
            'file_dpia' => $pathDpia,
            'nama_file_dpia' => $nameDpia,
            'file_sktm' => $pathSktm,
            'nama_file_sktm' => $nameSktm,
            'file_klasifikasi_dtsen' => $pathKlasifikasi,
            'nama_file_klasifikasi_dtsen' => $nameKlasifikasi,
            'file_hak_akses' => $pathHakAkses,
            'nama_file_hak_akses' => $nameHakAkses,
            // Legacy file surat columns
            'file_surat' => $pathSurat,
            'nama_file_asli' => $nameSurat,
            'ukuran_file' => $formattedSize,
            'ekstensi_file' => $extension,
            'hash_sha256' => $hashSha256,
            'current_step' => 1,
            'step1_done' => false,
            'step2_done' => false,
            'step3_done' => false,
            'step4_done' => false,
            'step5_done' => false,
            'status' => 'PENDING',
            'catatan_admin' => 'Permintaan baru berhasil diajukan. Menunggu verifikasi berkas oleh Tim Walidata.',
        ]);

        // Catat Audit Trail
        AuditLogger::log(
            'TIKET_DTSEN_CREATED',
            "Permintaan Layanan Bagi Pakai DTSEN [{$nomorTiket}] diajukan oleh {$tiket->nama_instansi}. Narahubung: {$namaNarahubung} ({$noNarahubung}). Jumlah variabel: " . count($variabelInput),
            'SUCCESS',
            ['nomor_tiket' => $nomorTiket, 'instansi' => $tiket->nama_instansi]
        );

        return redirect()->route('opd.tiket.index')
            ->with('success', "Permintaan Layanan DTSEN berhasil diajukan dengan Nomor: {$nomorTiket}! Status dan tahapan alur dapat dipantau pada daftar layanan.")
            ->with('new_tiket_id', $tiket->id);
    }

    /**
     * Download Berkas Surat Edaran Tiket
     */
    public function downloadSurat($id)
    {
        $tiket = TiketDtsen::findOrFail($id);

        if (!$tiket->file_surat || !Storage::disk('public')->exists($tiket->file_surat)) {
            return back()->with('error', 'Berkas surat edaran tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($tiket->file_surat, $tiket->nama_file_asli ?: "Surat_{$tiket->nomor_tiket}.{$tiket->ekstensi_file}");
    }

    /**
     * Download Berkas Dokumen Pendukung (KAK, Surat Permohonan, ROPA, DPIA, SKTM)
     */
    public function downloadDokumen($id, $tipe)
    {
        $tiket = TiketDtsen::findOrFail($id);

        $fileMap = [
            'kak' => [
                'path' => $tiket->file_kak,
                'name' => $tiket->nama_file_kak ?: "Surat_KAK_{$tiket->nomor_tiket}.pdf"
            ],
            'surat_permohonan' => [
                'path' => $tiket->file_surat_permohonan ?: $tiket->file_surat,
                'name' => $tiket->nama_file_surat_permohonan ?: ($tiket->nama_file_asli ?: "Surat_Permohonan_{$tiket->nomor_tiket}.pdf")
            ],
            'ropa' => [
                'path' => $tiket->file_ropa,
                'name' => $tiket->nama_file_ropa ?: "Dokumen_ROPA_{$tiket->nomor_tiket}.pdf"
            ],
            'dpia' => [
                'path' => $tiket->file_dpia,
                'name' => $tiket->nama_file_dpia ?: "Dokumen_DPIA_{$tiket->nomor_tiket}.pdf"
            ],
            'sktm' => [
                'path' => $tiket->file_sktm,
                'name' => $tiket->nama_file_sktm ?: "Dokumen_SKTM_{$tiket->nomor_tiket}.pdf"
            ],
            'klasifikasi_dtsen' => [
                'path' => $tiket->file_klasifikasi_dtsen,
                'name' => $tiket->nama_file_klasifikasi_dtsen ?: "Dokumen_Klasifikasi_DTSEN_{$tiket->nomor_tiket}.pdf"
            ],
            'hak_akses' => [
                'path' => $tiket->file_hak_akses,
                'name' => $tiket->nama_file_hak_akses ?: "Dokumen_Hak_Akses_{$tiket->nomor_tiket}.pdf"
            ],
        ];

        if (!isset($fileMap[$tipe])) {
            abort(404, 'Tipe dokumen tidak valid.');
        }

        $doc = $fileMap[$tipe];
        if (!$doc['path'] || !Storage::disk('public')->exists($doc['path'])) {
            return back()->with('error', 'Berkas dokumen tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($doc['path'], $doc['name']);
    }

    /**
     * Perbarui / Revisi Variabel Tiket oleh Perangkat Daerah (Sesuai Catatan Admin)
     */
    public function updateVariabel(Request $request, $id)
    {
        $tiket = TiketDtsen::findOrFail($id);
        $user = auth()->user();

        // Validasi kepemilikan jika bukan admin
        if (!$user->isSuperAdmin() && !str_contains(strtolower($tiket->nama_instansi), strtolower($user->nama_instansi ?: '')) && $tiket->email_pemohon !== $user->email) {
            return back()->with('error', 'Anda tidak memiliki hak untuk merevisi tiket instansi lain.');
        }

        $request->validate([
            'variabel_dtsen' => 'required|array|min:1',
            'catatan_opd' => 'nullable|string',
        ], [
            'variabel_dtsen.required' => 'Minimal salah satu variabel DTSEN harus diisi.',
            'variabel_dtsen.min' => 'Silakan isi variabel DTSEN yang diminta.',
        ]);

        $variabelInput = array_filter(array_map('trim', (array) $request->input('variabel_dtsen')));

        if (empty($variabelInput)) {
            return back()->withInput()->withErrors([
                'variabel_dtsen' => 'Mohon isi setidaknya satu kolom variabel DTSEN yang Anda butuhkan.'
            ]);
        }

        $tiket->variabel_dtsen = array_values($variabelInput);
        $tiket->status = 'DIPROSES';
        $tiket->catatan_admin = 'Perbaikan variabel telah diajukan ulang oleh ' . $user->name . ' pada ' . now()->format('d/m/Y H:i') . ' WIB. Menunggu verifikasi kesesuaian dengan Surat Edaran oleh Admin.';
        $tiket->save();

        AuditLogger::log(
            'TIKET_VARIABEL_REVISED',
            "Instansi {$tiket->nama_instansi} memperbarui variabel tiket [{$tiket->nomor_tiket}]. Jumlah variabel: " . count($variabelInput),
            'SUCCESS',
            ['nomor_tiket' => $tiket->nomor_tiket]
        );

        return back()->with('success', "Variabel tiket [{$tiket->nomor_tiket}] berhasil diperbarui dan diajukan ulang untuk diverifikasi oleh Admin sesuai Surat Edaran.");
    }

    /**
     * Unggah Ulang / Submit Ulang Berkas Perbaikan oleh OPD (KAK / Surat Permohonan)
     */
    public function revisiBerkas(Request $request, $id)
    {
        $tiket = TiketDtsen::findOrFail($id);
        $user = auth()->user();

        // Validasi kepemilikan jika bukan admin
        if (!$user->isSuperAdmin() && !str_contains(strtolower($tiket->nama_instansi), strtolower($user->nama_instansi ?: '')) && $tiket->email_pemohon !== $user->email) {
            return back()->with('error', 'Anda tidak memiliki hak untuk merevisi tiket instansi lain.');
        }

        $request->validate([
            'file_kak' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'file_surat_permohonan' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'catatan_opd' => 'nullable|string|max:500',
        ]);

        $uploaded = false;

        if ($request->hasFile('file_kak')) {
            $pathKak = $request->file('file_kak')->store('berkas_kak', 'public');
            $tiket->file_kak = $pathKak;
            $tiket->nama_file_kak = $request->file('file_kak')->getClientOriginalName();
            $uploaded = true;
        }

        if ($request->hasFile('file_surat_permohonan')) {
            $pathPermohonan = $request->file('file_surat_permohonan')->store('berkas_permohonan', 'public');
            $tiket->file_surat_permohonan = $pathPermohonan;
            $tiket->file_surat = $pathPermohonan;
            $tiket->nama_file_surat_permohonan = $request->file('file_surat_permohonan')->getClientOriginalName();
            $uploaded = true;
        }

        $tiket->status = 'PENDING';
        $catatanOpd = trim((string) $request->input('catatan_opd'));
        $tiket->catatan_admin = 'Berkas perbaikan telah dikirim ulang oleh ' . ($user->nama_instansi ?: $user->name) . ' pada ' . now()->format('d/m/Y H:i') . ' WIB.' . ($catatanOpd ? ' Catatan: ' . $catatanOpd : '');
        $tiket->save();

        AuditLogger::log(
            'TIKET_BERKAS_REVISED',
            "Instansi {$tiket->nama_instansi} telah mengunggah ulang berkas perbaikan untuk tiket [{$tiket->nomor_tiket}]. Status kembali PENDING.",
            'SUCCESS',
            ['nomor_tiket' => $tiket->nomor_tiket]
        );

        return back()->with('success', "Berkas perbaikan tiket [{$tiket->nomor_tiket}] berhasil dikirim ulang ke Walidata. Status kini kembali PENDING menunggu verifikasi.");
    }
}
