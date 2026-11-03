<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiketDtsenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BagiPakaiDtsenController;

/*
|--------------------------------------------------------------------------
| Web Routes - Layanan Permintaan Bagi Pakai DTSEN (Sistem Tiket & Tracking)
|--------------------------------------------------------------------------
*/

// --- 1. AUTENTIKASI: URL LOGIN BERBEDA UNTUK OPD VS ADMINISTRATOR ---
Route::middleware('guest')->group(function () {
    // A. Portal Login Khusus Perangkat Daerah (OPD)
    Route::get('/login', [AuthController::class, 'showOpdLogin'])->name('login');
    Route::get('/opd/login', [AuthController::class, 'showOpdLogin'])->name('opd.login');
    Route::post('/login', [AuthController::class, 'loginOpd'])->name('login.process');
    Route::post('/opd/login', [AuthController::class, 'loginOpd'])->name('opd.login.process');

    // B. Portal Login Khusus Administrator Walidata (Secret URL: dkisp-secret)
    Route::get('/dkisp-secret', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/dkisp-secret', [AuthController::class, 'loginAdmin'])->name('admin.login.process');

    // Decoy: Mengalihkan /admin/login biasa ke portal umum agar URL rahasia tidak bocor
    Route::get('/admin/login', function () {
        return redirect()->route('login');
    });

    // Fitur Lupa Password & Reset Password
    Route::post('/lupa-password', [AuthController::class, 'requestResetPassword'])->name('password.request-reset');
    Route::post('/reset-password-mandiri', [AuthController::class, 'resetPasswordMandiri'])->name('password.reset-mandiri');
});

// Logout Sesi Pengguna (Mendukung GET & POST untuk OPD maupun Administrator)
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/opd/logout', [AuthController::class, 'logout']);
Route::match(['get', 'post'], '/admin/logout', [AuthController::class, 'logout']);

// --- 2. SISTEM TERPROTEKSI (WAJIB LOGIN) & PENCEGAHAN PERUBAHAN URL ---
Route::middleware(['auth', 'session.timeout'])->group(function () {
    
    // Root URL & Dashboard generic: Arahkan ke dashboard masing-masing sesuai peran
    Route::get('/', function () {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('opd.dashboard');
    });

    Route::get('/dashboard', function () {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('opd.dashboard');
    });

    // --- A. PORTAL & DASHBOARD PERANGKAT DAERAH (OPD) ---
    // Diproteksi middleware 'opd' sehingga Admin TIDAK BISA mengakses dengan hanya ganti URL
    Route::middleware('opd')->group(function () {
        // 1. Dashboard OPD (Clean Page: Hanya Tombol Buat Tiket Baru & Info Instansi)
        Route::get('/opd/dashboard', [ProfileController::class, 'dashboard'])->name('opd.dashboard');

        // 2. Layanan Permintaan Bagi Pakai DTSEN (Formulir Pengajuan Tiket Permohonan DTSEN)
        Route::get('/opd/tiket/create', [TiketDtsenController::class, 'create'])->name('opd.tiket.create');
        Route::get('/opd/layanan/create', [TiketDtsenController::class, 'create'])->name('opd.layanan.create');
        Route::get('/formulir', [TiketDtsenController::class, 'index'])->name('formulir.index');
        Route::post('/formulir', [TiketDtsenController::class, 'store'])->name('formulir.store');
        Route::post('/tiket', [TiketDtsenController::class, 'store'])->name('tiket.store');

        // 3. Daftar Layanan Bagi Pakai DTSEN & Detail Progress
        Route::get('/opd/tiket', [ProfileController::class, 'daftarTiket'])->name('opd.tiket.index');
        Route::get('/opd/layanan', [ProfileController::class, 'daftarTiket'])->name('opd.layanan.index');
        Route::get('/opd/tiket/{id}', [ProfileController::class, 'showTiket'])->whereNumber('id')->name('opd.tiket.show');
        Route::get('/opd/tiket/{id}/edit', [TiketDtsenController::class, 'edit'])->whereNumber('id')->name('opd.tiket.edit');
        Route::put('/opd/tiket/{id}', [TiketDtsenController::class, 'update'])->whereNumber('id')->name('opd.tiket.update');
        Route::post('/opd/tiket/{id}/update', [TiketDtsenController::class, 'update'])->whereNumber('id');
        Route::get('/opd/tiket/{id}/formulir', [BagiPakaiDtsenController::class, 'formulir'])->whereNumber('id')->name('opd.tiket.formulir');
        Route::get('/opd/tiket/{id}/download-docx', [BagiPakaiDtsenController::class, 'downloadDocx'])->whereNumber('id')->name('opd.tiket.download-docx');

        // 4. Halaman Layanan Bagi Pakai DTSEN (Progress 5 Tahapan & Tabel Riwayat Bagi Data Resmi)
        Route::get('/opd/layanan-bagi-pakai', [ProfileController::class, 'layananBagiPakai'])->name('opd.layanan-bagi-pakai');
        Route::get('/opd/riwayat-bagi-data/{id}', [ProfileController::class, 'showRiwayatBagiData'])->whereNumber('id')->name('opd.riwayat-bagi-data.show');
        Route::get('/profile', [ProfileController::class, 'layananBagiPakai'])->name('profile'); // Dukungan alias

        // 5. Halaman Profil Pengguna / User & Ubah Password
        Route::get('/opd/user', [ProfileController::class, 'userProfile'])->name('opd.user.index');
        Route::post('/opd/ubah-password', [ProfileController::class, 'updatePassword'])->name('opd.user.update-password');

        // 6. Unduh Dokumen, Surat & Revisi Berkas
        Route::get('/tiket/{id}/surat', [TiketDtsenController::class, 'downloadSurat'])->name('tiket.download-surat');
        Route::get('/tiket/{id}/download-dokumen/{tipe}', [TiketDtsenController::class, 'downloadDokumen'])->name('tiket.download-dokumen');
        Route::post('/tiket/{id}/update-variabel', [TiketDtsenController::class, 'updateVariabel'])->name('tiket.update-variabel');
        Route::post('/opd/tiket/{id}/revisi-berkas', [TiketDtsenController::class, 'revisiBerkas'])->name('tiket.revisi-berkas');
    });

    // --- B. PORTAL & DASHBOARD SUPER ADMINISTRATOR ---
    // Diproteksi middleware 'admin' sehingga OPD TIDAK BISA mengakses dengan hanya ganti URL
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Root admin URL (/admin) diarahkan ke dashboard admin
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        // Dashboard Admin (Pengelolaan Seluruh Tiket, Verifikasi SE, Manipulasi Proses)
        Route::get('/dashboard', [HistoryController::class, 'index'])->name('dashboard');
        Route::get('/riwayat', [HistoryController::class, 'index'])->name('riwayat'); // Dukungan alias

        // Redirect legacy /tiket ke Layanan Bagi Pakai DTSEN
        Route::get('/tiket', function () {
            return redirect()->route('admin.bagi-pakai-dtsen.index');
        })->name('tiket.index');
        Route::get('/tiket/{id}', [HistoryController::class, 'tiketShow'])->whereNumber('id')->name('tiket.show');

        // Layanan Bagi Pakai DTSEN (SOP: 555/07-SOP/DISKOMINFO SP/2026 - Permintaan Layanan Buat/Edit/Hapus)
        Route::get('/bagi-pakai-dtsen', [BagiPakaiDtsenController::class, 'index'])->name('bagi-pakai-dtsen.index');
        Route::get('/bagi-pakai-dtsen/create', [BagiPakaiDtsenController::class, 'create'])->name('bagi-pakai-dtsen.create');
        Route::post('/bagi-pakai-dtsen', [BagiPakaiDtsenController::class, 'store'])->name('bagi-pakai-dtsen.store');
        Route::get('/bagi-pakai-dtsen/{id}/edit', [BagiPakaiDtsenController::class, 'edit'])->whereNumber('id')->name('bagi-pakai-dtsen.edit');
        Route::put('/bagi-pakai-dtsen/{id}', [BagiPakaiDtsenController::class, 'update'])->whereNumber('id')->name('bagi-pakai-dtsen.update');
        Route::delete('/bagi-pakai-dtsen/{id}', [BagiPakaiDtsenController::class, 'destroy'])->whereNumber('id')->name('bagi-pakai-dtsen.destroy');
        Route::get('/bagi-pakai-dtsen/{id}/formulir', [BagiPakaiDtsenController::class, 'formulir'])->whereNumber('id')->name('bagi-pakai-dtsen.formulir');
        Route::get('/bagi-pakai-dtsen/{id}/download-docx', [BagiPakaiDtsenController::class, 'downloadDocx'])->whereNumber('id')->name('bagi-pakai-dtsen.download-docx');

        Route::post('/tiket/{id}/step-ok', [HistoryController::class, 'stepOk'])->name('tiket.step-ok');
        Route::post('/tiket/{id}/toggle-step/{step}', [HistoryController::class, 'toggleStep'])->whereNumber('step')->name('tiket.toggle-step');
        Route::post('/tiket/{id}/update-steps', [HistoryController::class, 'updateSteps'])->name('tiket.update-steps');
        Route::post('/tiket/{id}/verifikasi-variabel', [HistoryController::class, 'verifikasiVariabel'])->name('tiket.verifikasi-variabel');
        Route::post('/tiket/{id}/verifikasi-berkas', [HistoryController::class, 'verifikasiBerkas'])->name('tiket.verifikasi-berkas');
        Route::get('/tiket/{id}/download', [HistoryController::class, 'downloadSurat'])->name('riwayat.download');
        Route::delete('/riwayat/{id}', [HistoryController::class, 'destroy'])->name('riwayat.destroy');
        Route::get('/riwayat/export', [HistoryController::class, 'exportCsv'])->name('riwayat.export');
        Route::get('/riwayat/export-logs', [HistoryController::class, 'exportLogs'])->name('riwayat.export-logs');
        
        // User: 1.a User Management, 1.b Buat Akun & Edit Hak Akses, 1.c Log Akun, 1.d Profile
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users/{id}', [UserController::class, 'show'])->whereNumber('id')->name('users.show');
        Route::post('/users', [UserController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}/access', [UserController::class, 'editAccess'])->whereNumber('id')->name('users.edit-access');
        Route::post('/users/{id}/access', [UserController::class, 'updateAccess'])->whereNumber('id')->name('users.update-access');
        Route::post('/users/{id}/reset-password', [UserController::class, 'superAdminResetPassword'])->whereNumber('id')->name('users.reset-password');
        Route::post('/reset-requests/{id}/approve', [UserController::class, 'approveResetRequest'])->whereNumber('id')->name('reset-requests.approve');
        Route::delete('/users/{id}', [UserController::class, 'destroyUser'])->whereNumber('id')->name('users.destroy');
        
        // Log Laptop & Audit Keamanan
        Route::get('/logs', [UserController::class, 'logsIndex'])->name('logs.index');
        Route::get('/logs/{id}', [UserController::class, 'logsShow'])->whereNumber('id')->name('logs.show');

        // Profile Administrator
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
        
        // Input & Kelola Tabel Riwayat Bagi Pakai Data DTSEN Resmi
        Route::get('/riwayat-bagi-data/{id}', [UserController::class, 'showRiwayatBagiData'])->whereNumber('id')->name('riwayat-bagi-data.show');
        Route::post('/riwayat-bagi-data', [UserController::class, 'storeRiwayatBagiData'])->name('riwayat-bagi-data.store');
        Route::delete('/riwayat-bagi-data/{id}', [UserController::class, 'destroyRiwayatBagiData'])->name('riwayat-bagi-data.destroy');

        // Hak Akses & Peran Administrator (Filament Access Control)
        Route::get('/access-control', [\App\Http\Controllers\AdminAccessController::class, 'index'])->name('access.index');
        Route::post('/access-control', [\App\Http\Controllers\AdminAccessController::class, 'storeAdmin'])->name('access.store');
        Route::post('/access-control/{id}/update', [\App\Http\Controllers\AdminAccessController::class, 'updateAccess'])->name('access.update');
        Route::delete('/access-control/{id}', [\App\Http\Controllers\AdminAccessController::class, 'destroyAdmin'])->name('access.destroy');
    });
});

// --- 3. FALLBACK ROUTE: MENCEGAH ERROR 404 NOT FOUND PADA SELURUH SISTEM ---
Route::fallback(function () {
    if (auth()->check()) {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('opd.dashboard');
    }
    return redirect()->route('login');
});
