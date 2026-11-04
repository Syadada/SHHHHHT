<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * 1. Halaman Login Khusus Perangkat Daerah (OPD) - URL: /login atau /opd/login
     */
    public function showOpdLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('opd.dashboard');
        }

        // Ambil contoh akun OPD untuk kemudahan uji coba
        $demoOpd = User::where('role', '!=', 'admin')->where('role', '!=', 'super_admin')->where('role', '!=', 'superadmin')->first();

        return view('auth.login', compact('demoOpd'));
    }

    /**
     * Proses Login Khusus Perangkat Daerah (OPD)
     * Menolak akun Administrator agar tidak bisa masuk lewat portal user biasa
     */
    public function loginOpd(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email atau Username OPD wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($request->input('login'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $user = User::where('email', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            // Mencegah Administrator masuk lewat portal login OPD secara senyap (zero disclosure)
            if ($user->isSuperAdmin()) {
                AuditLogger::log(
                    'AUTH_REJECTED_ADMIN_ON_OPD_PORTAL',
                    "Akun Administrator [{$user->name}] mencoba masuk melalui portal login OPD. Akses ditolak secara senyap.",
                    'WARNING',
                    ['user_id' => $user->id, 'attempted_portal' => 'OPD_PORTAL']
                );

                return back()->withErrors([
                    'login' => 'Kredensial login tidak cocok. Pastikan email/username dan kata sandi benar.',
                ])->withInput($request->only('login'));
            }

            Auth::login($user, $remember);
            $request->session()->regenerate();

            // Deteksi ID Perangkat / Nomor Laptop
            $deviceId = $this->resolveDeviceId($request);
            $user->last_login_at = now();
            $user->last_login_ip = $request->ip() ?? '127.0.0.1';
            $user->last_login_device = $deviceId;
            $user->save();

            AuditLogger::log(
                'AUTH_LOGIN_OPD',
                "Perangkat Daerah {$user->name} ({$user->nama_instansi}) berhasil masuk portal melalui perangkat [{$deviceId}].",
                'SUCCESS',
                ['user_id' => $user->id, 'device_id' => $deviceId]
            );

            // Reset counter login gagal jika berhasil login
            $attemptKey = 'login_failed_attempts_' . md5(strtolower($loginInput));
            $request->session()->forget($attemptKey);

            return redirect()->route('opd.dashboard')
                ->with('success', "Selamat datang, {$user->name}! Silakan pantau tiket dan data riwayat bagi pakai DTSEN instansi Anda.");
        }

        // Hitung percobaan login gagal
        $attemptKey = 'login_failed_attempts_' . md5(strtolower($loginInput));
        $attempts = (int) $request->session()->get($attemptKey, 0) + 1;
        $request->session()->put($attemptKey, $attempts);

        $showForgot = ($attempts >= 3);

        AuditLogger::log(
            'AUTH_FAILED_OPD',
            "Percobaan login OPD gagal dengan identitas '{$loginInput}' (percobaan ke-{$attempts})",
            'FAILED',
            ['input' => $loginInput, 'attempts' => $attempts]
        );

        $errorMsg = 'Kredensial login tidak cocok. Pastikan email/username dan kata sandi benar.';
        if ($showForgot) {
            $errorMsg = 'Anda telah 3 kali salah memasukkan kata sandi.';
        }

        return back()->withErrors([
            'login' => $errorMsg,
        ])->with([
            'show_forgot_password' => $showForgot,
            'failed_username' => $loginInput,
        ])->withInput($request->only('login'));
    }

    /**
     * Pengajuan Lupa Password oleh User/OPD ke Walidata
     */
    public function requestResetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ], [
            'username.required' => 'Username atau email wajib diisi.',
        ]);

        $input = trim($request->input('username'));
        $user = User::where('username', $input)
            ->orWhere('email', $input)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Akun dengan username atau email tersebut tidak ditemukan.',
            ])->with([
                'show_forgot_password' => true,
                'failed_username' => $input,
            ]);
        }

        // Buat permintaan reset password untuk Walidata
        \App\Models\PasswordResetRequest::create([
            'user_id' => $user->id,
            'username' => $user->username,
            'nama_instansi' => $user->nama_instansi ?: $user->name,
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'status' => 'MENUNGGU',
        ]);

        AuditLogger::log(
            'AUTH_PASSWORD_RESET_REQUESTED',
            "Pengguna {$user->username} ({$user->nama_instansi}) mengajukan permintaan reset kata sandi ke Walidata.",
            'WARNING',
            ['user_id' => $user->id, 'ip' => $request->ip()]
        );

        return back()->with('success', "Permintaan reset kata sandi untuk akun {$user->username} telah dikirim ke Walidata. Silakan hubungi atau tunggu konfirmasi Walidata.");
    }

    /**
     * 2. Halaman Login Khusus Administrator Walidata - URL Rahasia: /dkisp-secret
     */
    public function showAdminLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('opd.dashboard');
        }

        // Ambil akun admin untuk referensi uji coba
        $demoAdmin = User::where(function ($q) {
            $q->where('role', 'admin')->orWhere('role', 'superadmin')->orWhere('role', 'super_admin');
        })->first();

        return view('auth.admin_login', compact('demoAdmin'));
    }

    /**
     * Proses Login Khusus Administrator Walidata
     * Menolak akun OPD agar tidak bisa masuk lewat portal admin
     */
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email atau Username Administrator wajib diisi.',
            'password.required' => 'Kata sandi Administrator wajib diisi.',
        ]);

        $loginInput = trim($request->input('login'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $user = User::where('email', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            // Mencegah akun OPD masuk lewat portal login Admin
            if (!$user->isSuperAdmin()) {
                AuditLogger::log(
                    'AUTH_REJECTED_OPD_ON_ADMIN_PORTAL',
                    "Akun OPD [{$user->name}] mencoba masuk melalui portal login Administrator. Akses ditolak.",
                    'WARNING',
                    ['user_id' => $user->id, 'attempted_portal' => 'ADMIN_PORTAL']
                );

                return back()->withErrors([
                    'login' => 'Akses Ditolak: Akun Anda adalah Perangkat Daerah (OPD). Silakan masuk melalui Portal Masuk OPD di /login.',
                ])->withInput($request->only('login'));
            }

            Auth::login($user, $remember);
            $request->session()->regenerate();

            // Deteksi ID Perangkat / Nomor Laptop
            $deviceId = $this->resolveDeviceId($request);
            $user->last_login_at = now();
            $user->last_login_ip = $request->ip() ?? '127.0.0.1';
            $user->last_login_device = $deviceId;
            $user->save();

            AuditLogger::log(
                'AUTH_LOGIN_ADMIN',
                "Administrator {$user->name} berhasil masuk sistem administrasi melalui perangkat [{$deviceId}].",
                'SUCCESS',
                ['user_id' => $user->id, 'device_id' => $deviceId]
            );

            return redirect()->route('admin.dashboard')
                ->with('success', "Selamat datang kembali, {$user->name}! Anda memiliki kendali penuh di Dashboard Administrator.");
        }

        AuditLogger::log(
            'AUTH_FAILED_ADMIN',
            "Percobaan login Administrator gagal dengan identitas '{$loginInput}'",
            'FAILED',
            ['input' => $loginInput]
        );

        return back()->withErrors([
            'login' => 'Identitas Administrator atau kata sandi tidak cocok.',
        ])->withInput($request->only('login'));
    }

    /**
     * 3. Logout Sesi Pengguna & Arahkan ke Portal yang Sesuai
     */
    public function logout(Request $request)
    {
        $userName = Auth::user() ? Auth::user()->name : 'Pengguna';

        AuditLogger::log(
            'AUTH_LOGOUT',
            "Pengguna {$userName} mengakhiri sesi dan keluar dari sistem.",
            'SUCCESS'
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Admin maupun OPD jika logout selalu dikembalikan ke login page secara bersih tanpa notifikasi
        return redirect()->route('login');
    }

    /**
     * Helper: Deteksi ID Perangkat / Laptop
     */
    private function resolveDeviceId(Request $request): string
    {
        $deviceId = $request->cookie('app_device_id');
        if (!$deviceId) {
            $ua = $request->userAgent() ?? '';
            $prefix = 'DEV';
            if (str_contains($ua, 'Windows')) $prefix = 'WIN';
            elseif (str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS X')) $prefix = 'MAC';
            elseif (str_contains($ua, 'Android')) $prefix = 'AND';
            elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) $prefix = 'IOS';
            elseif (str_contains($ua, 'Linux')) $prefix = 'LNX';
            $deviceId = $prefix . '-' . strtoupper(substr(md5(($request->ip() ?? '127.0.0.1') . $ua), 0, 4));
        }
        return $deviceId;
    }
}
