<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogoutTimeout
{
    /**
     * Waktu timeout sesi dalam detik (5 menit = 300 detik).
     */
    protected int $timeoutSeconds = 300;

    public function __construct()
    {
        $configuredMinutes = (int) \App\Models\AppSetting::get('session_lifetime', config('session.lifetime', 5));
        $this->timeoutSeconds = $configuredMinutes > 0 ? $configuredMinutes * 60 : 300;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity_time');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity) > $this->timeoutSeconds) {
                $user = Auth::user();
                $userName = $user ? $user->name : 'Pengguna';
                $minutes = (int) round($this->timeoutSeconds / 60);

                // Catat di Audit Logger
                AuditLogger::log(
                    'SESSION_TIMEOUT',
                    "Sesi {$userName} berakhir otomatis karena tidak ada aktivitas selama {$minutes} menit.",
                    'WARNING',
                    [
                        'user_id' => $user ? $user->id : null,
                        'idle_seconds' => $currentTime - $lastActivity,
                        'ip' => $request->ip()
                    ]
                );

                // Saat sesi berakhir otomatis, selalu arahkan ke login OPD (/login)
                $targetLogin = route('login');

                // Logout dan batalkan sesi
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => "Sesi Anda telah berakhir karena tidak ada aktivitas selama {$minutes} menit.",
                        'redirect' => $targetLogin
                    ], 401);
                }

                // Sesuai permintaan: selalu kembalikan ke login OPD secara langsung
                return redirect()->to($targetLogin);
            }

            // Perbarui waktu aktivitas terakhir
            $request->session()->put('last_activity_time', $currentTime);
        }

        return $next($request);
    }
}
