<?php

namespace App\Http\Controllers;

use App\Models\TiketDtsen;
use App\Models\AuditLog;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Dashboard Pengelolaan Tiket Layanan Bagi Pakai DTSEN (Sisi Admin)
     */
    public function index(Request $request)
    {
        // 1. Cek apakah filter diterapkan oleh admin
        $filterApplied = $request->has('filter') || 
                         $request->filled('search') || 
                         $request->filled('status') || 
                         $request->filled('instansi') || 
                         $request->filled('date_start') || 
                         $request->filled('date_end');

        if ($filterApplied) {
            $query = TiketDtsen::query()->latest();

            if ($request->filled('search')) {
                $search = '%' . trim($request->search) . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_tiket', 'like', $search)
                      ->orWhere('nama_instansi', 'like', $search)
                      ->orWhere('no_surat_edaran', 'like', $search)
                      ->orWhere('nama_pemohon', 'like', $search);
                });
            }

            if ($request->filled('status')) {
                if ($request->status === 'SELESAI') {
                    $query->where('step5_done', true);
                } elseif ($request->status === 'PROSES') {
                    $query->where('step5_done', false);
                }
            }

            if ($request->filled('instansi')) {
                $query->where('nama_instansi', $request->instansi);
            }

            if ($request->filled('date_start')) {
                $query->whereDate('created_at', '>=', $request->date_start);
            }
            if ($request->filled('date_end')) {
                $query->whereDate('created_at', '<=', $request->date_end);
            }

            $tikets = $query->paginate(15)->withQueryString();
        } else {
            // Tampilkan tiket permohonan layanan terbaru yang masih aktif (belum selesai)
            $tikets = TiketDtsen::where('step5_done', false)
                ->where(function ($q) {
                    $q->whereNull('status')->orWhere('status', '!=', 'SELESAI');
                })
                ->latest()
                ->take(5)
                ->get();
        }

        // Permintaan layanan terbaru (5 entri): HANYA untuk proses yang baru dan belum selesai
        $latestTikets = TiketDtsen::where('step5_done', false)
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'SELESAI');
            })
            ->latest()
            ->take(5)
            ->get();
        $latestAuditLogs = AuditLog::latest()->take(10)->get();

        $instansis = TiketDtsen::whereNotNull('nama_instansi')
            ->where('nama_instansi', '!=', '')
            ->distinct()
            ->orderBy('nama_instansi')
            ->pluck('nama_instansi');

        // 2. Metrik KPI Ringkas
        $totalTiket = TiketDtsen::count();
        $totalHariIni = TiketDtsen::whereDate('created_at', Carbon::today())->count();
        $totalSelesai = TiketDtsen::where('step5_done', true)->count();
        $totalDiproses = TiketDtsen::where('step5_done', false)->count();

        // 3. Tab Audit Logs
        $logQuery = AuditLog::query()->orderBy('created_at', 'desc');

        if ($request->filled('log_action')) {
            $logQuery->where('action', $request->log_action);
        }
        if ($request->filled('log_status')) {
            $logQuery->where('status', $request->log_status);
        }
        if ($request->filled('log_search')) {
            $lSearch = '%' . trim($request->log_search) . '%';
            $logQuery->where(function ($q) use ($lSearch) {
                $q->where('description', 'like', $lSearch)
                  ->orWhere('user_name', 'like', $lSearch)
                  ->orWhere('ip_address', 'like', $lSearch);
            });
        }

        $auditLogs = $logQuery->paginate(20, ['*'], 'log_page')->withQueryString();
        $totalLogsToday = AuditLog::whereDate('created_at', Carbon::today())->count();
        $totalAuthFailed = AuditLog::where('action', 'AUTH_FAILED')->count();
        $actionTypes = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');

        return view('admin.riwayat', compact(
            'tikets',
            'latestTikets',
            'latestAuditLogs',
            'filterApplied',
            'totalTiket',
            'totalHariIni',
            'totalSelesai',
            'totalDiproses',
            'instansis',
            'auditLogs',
            'totalLogsToday',
            'totalAuthFailed',
            'actionTypes'
        ));
    }

    /**
     * Aksi Cepat Admin: Klik "OK" untuk menyelesaikan tahap proses yang sedang berjalan
     */
    public function stepOk(Request $request, $id)
    {
        // Pemeriksaan Hak Akses: Hanya Admin dengan privilege 'manipulate-steps' (Admin 2 / Super Admin)
        if (!auth()->user()->canManipulateSteps()) {
            AuditLogger::log(
                'UNAUTHORIZED_ACTION',
                "Pengguna " . auth()->user()->name . " mencoba menyetujui tahapan tiket #{$id} tanpa hak akses 'manipulate-steps'.",
                'WARNING',
                ['user_id' => auth()->id(), 'attempted_action' => 'manipulate-steps']
            );
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses Ditolak: Anda tidak memiliki hak akses untuk memanipulasi atau memperbarui tahapan proses tiket.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Akses Ditolak: Akun Anda tidak memiliki hak akses untuk memanipulasi atau memperbarui tahapan proses tiket (Khusus Operator / Super Admin).');
        }

        $tiket = TiketDtsen::findOrFail($id);
        $stepFinished = null;

        // Tentukan tahap mana yang diselesaikan
        if (!$tiket->step1_done) {
            $tiket->step1_done = true;
            $tiket->step1_at = now();
            $tiket->current_step = 2;
            $stepFinished = 1;
        } elseif (!$tiket->step2_done) {
            $tiket->step2_done = true;
            $tiket->step2_at = now();
            $tiket->current_step = 3;
            $stepFinished = 2;
        } elseif (!$tiket->step3_done) {
            $tiket->step3_done = true;
            $tiket->step3_at = now();
            $tiket->current_step = 4;
            $stepFinished = 3;
        } elseif (!$tiket->step4_done) {
            $tiket->step4_done = true;
            $tiket->step4_at = now();
            $tiket->current_step = 5;
            $stepFinished = 4;
        } elseif (!$tiket->step5_done) {
            $tiket->step5_done = true;
            $tiket->step5_at = now();
            $tiket->current_step = 5;
            $tiket->status = 'SELESAI';
            $stepFinished = 5;
        } else {
            return back()->with('info', "Tiket [{$tiket->nomor_tiket}] sudah berada di status Selesai sepenuhnya (100%).");
        }

        if ($request->filled('catatan_admin')) {
            $tiket->catatan_admin = trim($request->catatan_admin);
        } else {
            $tiket->catatan_admin = "Progress {$stepFinished} telah disetujui & diverifikasi oleh Admin pada " . now()->format('d/m/Y H:i') . " WIB.";
        }

        $tiket->save();

        AuditLogger::log(
            'TIKET_STEP_APPROVED',
            "Progress {$stepFinished} pada tiket [{$tiket->nomor_tiket}] ({$tiket->nama_instansi}) disetujui OK oleh admin.",
            'SUCCESS',
            ['nomor_tiket' => $tiket->nomor_tiket, 'step' => $stepFinished]
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Progress {$stepFinished} berhasil disetujui (OK)! Di sisi user status sudah centang hijau.",
                'step' => $stepFinished,
                'progress' => $tiket->progress_percent
            ]);
        }

        return redirect()->route('admin.riwayat')
            ->with('success', "Progress {$stepFinished} tiket [{$tiket->nomor_tiket}] berhasil disetujui (OK)! Pada posisi pengguna tahapan ini sudah bercentang hijau.");
    }

    /**
     * Manipulasi Lengkap Tahapan Proses oleh Admin (Form Modal Edit)
     */
    public function updateSteps(Request $request, $id)
    {
        // Pemeriksaan Hak Akses: manipulate-steps
        if (!auth()->user()->canManipulateSteps()) {
            return redirect()->back()->with('error', 'Akses Ditolak: Akun Anda tidak memiliki hak akses untuk mengubah alur tahapan proses tiket.');
        }

        $tiket = TiketDtsen::findOrFail($id);

        $tiket->step1_done = $request->boolean('step1_done');
        if ($tiket->step1_done && !$tiket->step1_at) $tiket->step1_at = now();
        
        $tiket->step2_done = $request->boolean('step2_done');
        if ($tiket->step2_done && !$tiket->step2_at) $tiket->step2_at = now();

        $tiket->step3_done = $request->boolean('step3_done');
        if ($tiket->step3_done && !$tiket->step3_at) $tiket->step3_at = now();

        $tiket->step4_done = $request->boolean('step4_done');
        if ($tiket->step4_done && !$tiket->step4_at) $tiket->step4_at = now();

        $tiket->step5_done = $request->boolean('step5_done');
        if ($tiket->step5_done && !$tiket->step5_at) $tiket->step5_at = now();

        if ($request->filled('catatan_admin')) {
            $tiket->catatan_admin = trim($request->catatan_admin);
        }

        // Tentukan current_step berdasarkan status
        if ($tiket->step5_done) {
            $tiket->status = 'SELESAI';
            $tiket->current_step = 5;
        } else {
            $tiket->status = 'DIPROSES';
            if (!$tiket->step1_done) $tiket->current_step = 1;
            elseif (!$tiket->step2_done) $tiket->current_step = 2;
            elseif (!$tiket->step3_done) $tiket->current_step = 3;
            elseif (!$tiket->step4_done) $tiket->current_step = 4;
            else $tiket->current_step = 5;
        }

        $tiket->save();

        AuditLogger::log(
            'TIKET_PROCESS_MANIPULATED',
            "Administrator memanipulasi alur tahapan tiket [{$tiket->nomor_tiket}] ({$tiket->nama_instansi}). Status sekarang: {$tiket->status} ({$tiket->progress_percent}%).",
            'SUCCESS'
        );

        return redirect()->route('admin.riwayat')
            ->with('success', "Proses tahapan tiket [{$tiket->nomor_tiket}] berhasil diperbarui.");
    }

    /**
     * Verifikasi Variabel Tiket vs Surat Edaran oleh Admin (Approve vs Perlu Perbaikan)
     */
    public function verifikasiVariabel(Request $request, $id)
    {
        // Pemeriksaan Hak Akses: verify-se
        if (!auth()->user()->canVerifySe()) {
            return redirect()->back()->with('error', 'Akses Ditolak: Akun Anda tidak memiliki hak akses untuk memverifikasi dokumen atau variabel.');
        }

        $tiket = TiketDtsen::findOrFail($id);

        $request->validate([
            'hasil_verifikasi' => 'required|in:APPROVE,PERLU_PERBAIKAN',
            'catatan_admin' => 'nullable|string',
        ]);

        $hasil = $request->input('hasil_verifikasi');
        $catatanInput = trim((string) $request->input('catatan_admin'));

        if ($hasil === 'APPROVE') {
            // Sesuai Surat Edaran -> Loloskan verifikasi (Progress 1 Selesai / Centang Hijau)
            $tiket->status = 'DIPROSES';
            $tiket->step1_done = true;
            $tiket->step1_at = now();
            if ($tiket->current_step < 2) {
                $tiket->current_step = 2;
            }
            $tiket->catatan_admin = $catatanInput 
                ? "Verifikasi Variabel Sesuai SE: {$catatanInput}"
                : "Variabel tiket telah diverifikasi dan dinyatakan SESUAI dengan Surat Edaran No. {$tiket->no_surat_edaran}. Permohonan disetujui untuk diproses ke tahap selanjutnya.";

            $tiket->save();

            AuditLogger::log(
                'TIKET_VARIABEL_APPROVED',
                "Admin menyetujui variabel tiket [{$tiket->nomor_tiket}] ({$tiket->nama_instansi}) sesuai Surat Edaran.",
                'SUCCESS',
                ['nomor_tiket' => $tiket->nomor_tiket]
            );

            return redirect()->route('admin.riwayat')
                ->with('success', "Variabel tiket [{$tiket->nomor_tiket}] berhasil di-APPROVE (Sesuai Surat Edaran)! Progress 1 di sisi OPD kini bercentang hijau.");
        } else {
            // Tidak Sesuai Surat Edaran -> Perlu Perbaikan oleh OPD
            $tiket->status = 'PERLU_PERBAIKAN';
            $tiket->step1_done = false;
            $tiket->current_step = 1;
            $tiket->catatan_admin = $catatanInput 
                ? "PERLU PERBAIKAN: {$catatanInput}"
                : "Variabel yang diajukan belum sesuai dengan Surat Edaran No. {$tiket->no_surat_edaran}. Mohon perangkat daerah segera memperbaiki kolom variabel yang diminta.";

            $tiket->save();

            AuditLogger::log(
                'TIKET_VARIABEL_REVISION_REQUESTED',
                "Admin meminta perbaikan variabel untuk tiket [{$tiket->nomor_tiket}] ({$tiket->nama_instansi}). Catatan: {$tiket->catatan_admin}",
                'WARNING',
                ['nomor_tiket' => $tiket->nomor_tiket]
            );

            return redirect()->route('admin.riwayat')
                ->with('info', "Tiket [{$tiket->nomor_tiket}] ditandai PERLU PERBAIKAN. Pemberitahuan revisi dan catatan perbaikan telah dikirim ke halaman Profile instansi OPD terkait.");
        }
    }

    /**
     * Unduh Berkas Surat Edaran Tiket
     */
    public function downloadSurat($id)
    {
        $tiket = TiketDtsen::findOrFail($id);

        if ($tiket->file_surat && Storage::disk('public')->exists($tiket->file_surat)) {
            AuditLogger::log(
                'TIKET_SURAT_DOWNLOADED',
                "Surat edaran tiket [{$tiket->nomor_tiket}] diunduh oleh admin.",
                'SUCCESS'
            );
            return Storage::disk('public')->download($tiket->file_surat, $tiket->nama_file_asli ?: "Surat_{$tiket->nomor_tiket}.{$tiket->ekstensi_file}");
        }

        return back()->with('error', 'Berkas fisik surat edaran tidak ditemukan di server.');
    }

    /**
     * Hapus Tiket Layanan Bagi Pakai DTSEN
     */
    public function destroy($id)
    {
        $tiket = TiketDtsen::findOrFail($id);
        $noTiket = $tiket->nomor_tiket;
        $instansi = $tiket->nama_instansi;

        if ($tiket->file_surat && Storage::disk('public')->exists($tiket->file_surat)) {
            Storage::disk('public')->delete($tiket->file_surat);
        }

        $tiket->delete();

        AuditLogger::log(
            'TIKET_DELETED',
            "Tiket Bagi Pakai DTSEN [{$noTiket}] ({$instansi}) dihapus oleh administrator.",
            'WARNING',
            ['nomor_tiket' => $noTiket, 'nama_instansi' => $instansi]
        );

        return redirect()->route('admin.riwayat')
            ->with('success', "Tiket [{$noTiket}] milik {$instansi} berhasil dihapus.");
    }

    /**
     * Ekspor Data Tiket DTSEN ke CSV
     */
    public function exportCsv()
    {
        $tikets = TiketDtsen::latest()->get();

        AuditLogger::log(
            'TIKET_DATA_EXPORT',
            "Administrator mengekspor " . $tikets->count() . " data tiket layanan DTSEN ke CSV.",
            'SUCCESS'
        );

        $filename = 'tiket_bagi_pakai_dtsen_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($tikets) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($output, [
                'Nomor Tiket',
                'Nama Instansi',
                'No Surat Edaran',
                'Nama Pemohon / PIC',
                'Kontak',
                'Email',
                'Tujuan Pemanfaatan',
                'Variabel DTSEN Diminta',
                'Progress (%)',
                'Tahap 1 (Verifikasi Surat)',
                'Tahap 2 (Validasi Variabel)',
                'Tahap 3 (Komitmen & BAST)',
                'Tahap 4 (Konfigurasi Endpoint)',
                'Tahap 5 (Penyerahan Data)',
                'Status Keseluruhan',
                'Catatan Admin',
                'Tanggal Pengajuan'
            ]);

            foreach ($tikets as $t) {
                $varList = is_array($t->variabel_dtsen) ? implode('; ', $t->variabel_dtsen) : $t->variabel_dtsen;
                fputcsv($output, [
                    $t->nomor_tiket,
                    $t->nama_instansi,
                    $t->no_surat_edaran,
                    $t->nama_pemohon,
                    $t->kontak_pemohon ?? '-',
                    $t->email_pemohon ?? '-',
                    $t->tujuan_pemanfaatan ?? '-',
                    $varList,
                    $t->progress_percent . '%',
                    $t->step1_done ? 'SELESAI (' . ($t->step1_at ? $t->step1_at->format('d/m/Y H:i') : 'OK') . ')' : 'BELUM',
                    $t->step2_done ? 'SELESAI (' . ($t->step2_at ? $t->step2_at->format('d/m/Y H:i') : 'OK') . ')' : 'BELUM',
                    $t->step3_done ? 'SELESAI (' . ($t->step3_at ? $t->step3_at->format('d/m/Y H:i') : 'OK') . ')' : 'BELUM',
                    $t->step4_done ? 'SELESAI (' . ($t->step4_at ? $t->step4_at->format('d/m/Y H:i') : 'OK') . ')' : 'BELUM',
                    $t->step5_done ? 'SELESAI (' . ($t->step5_at ? $t->step5_at->format('d/m/Y H:i') : 'OK') . ')' : 'BELUM',
                    $t->status,
                    $t->catatan_admin ?? '-',
                    $t->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Ekspor Audit Trail ke CSV / TXT
     */
    public function exportLogs(Request $request)
    {
        $logs = AuditLog::orderBy('created_at', 'desc')->get();
        $format = $request->query('format', 'csv');

        AuditLogger::log(
            'AUDIT_EXPORT',
            "Administrator mengekspor rekam jejak audit sistem ({$format}).",
            'SUCCESS'
        );

        if ($format === 'txt') {
            $filename = 'audit_logs_' . date('Ymd_His') . '.txt';
            $content = "=== AUDIT TRAIL FORENSIK DIGITAL LAYANAN DTSEN ===\n";
            $content .= "Tanggal Cetak: " . now()->format('Y-m-d H:i:s') . "\n";
            $content .= "Total Entri: " . $logs->count() . "\n\n";

            foreach ($logs as $log) {
                $content .= sprintf(
                    "[%s] [%s] [%s | %s] %s | IP: %s (%s) | Status: %s\n",
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->action,
                    $log->device_identifier,
                    $log->device_os,
                    $log->description,
                    $log->ip_address,
                    $log->device_browser,
                    $log->status
                );
            }

            return response($content, 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }

        $filename = 'audit_logs_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, ['ID', 'Waktu', 'ID Perangkat', 'Sistem Operasi', 'Browser', 'IP Address', 'Aksi', 'Deskripsi', 'Status']);

            foreach ($logs as $l) {
                fputcsv($output, [
                    $l->id,
                    $l->created_at->format('Y-m-d H:i:s'),
                    $l->device_identifier,
                    $l->device_os,
                    $l->device_browser,
                    $l->ip_address,
                    $l->action,
                    $l->description,
                    $l->status,
                ]);
            }
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Halaman Daftar Tiket Support Sisi Admin (Ala DomaiNesia)
     */
    public function tiketIndex(Request $request)
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

        // 2. Filter Status Tiket
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
            case 'update_terbaru':
                $query->orderBy('updated_at', 'desc');
                break;
            case 'updated_asc':
            case 'update_terlama':
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

        return view('admin.tiket.index', compact('tikets', 'sortBy'));
    }

    /**
     * Halaman Detail Progress Tiket Sisi Admin
     * Menampilkan visual progress 5 tahap secara lebih jelas dengan kontrol verifikasi
     */
    public function tiketShow($id)
    {
        $tiket = TiketDtsen::with(['pengolahan', 'hakAkses', 'pengamanan', 'ropa', 'dpia', 'riwayatBagiData'])
            ->findOrFail($id);

        return view('admin.tiket.show', compact('tiket'));
    }
}

