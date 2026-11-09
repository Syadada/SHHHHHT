<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\TiketDtsen;
use App\Models\RiwayatBagiDataDtsen;
use App\Models\AppSetting;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Halaman Manajemen Pengguna (Hak Kendali Super Admin)
     */
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'users');

        $userQuery = User::query();
        if ($request->filled('role')) {
            $userQuery->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $search = '%' . trim($request->search) . '%';
            $userQuery->where(function($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('username', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('nama_instansi', 'like', $search);
            });
        }

        $users = $userQuery->latest()->paginate(15, ['*'], 'users_page')->withQueryString();
        
        // Data Riwayat Bagi Data DTSEN Resmi
        $riwayatBagiDatas = RiwayatBagiDataDtsen::latest()->paginate(15, ['*'], 'riwayat_page')->appends(['tab' => 'riwayat-terbit']);

        // Daftar tiket untuk referensi cepat input riwayat
        $availableTikets = TiketDtsen::latest()->get();

        // Permintaan Reset Password Lupa Kata Sandi
        $pendingResetRequests = \App\Models\PasswordResetRequest::where('status', 'MENUNGGU')->latest()->get();

        // Statistik Pengguna
        $totalUsers = User::count();
        $totalOpd = User::where('role', 'perangkat_daerah')->count();
        $totalAdmin = User::where('role', 'superadmin')->count();

        return view('admin.users.index', compact('users', 'riwayatBagiDatas', 'availableTikets', 'totalUsers', 'totalOpd', 'totalAdmin', 'activeTab', 'pendingResetRequests'));
    }

    /**
     * Halaman Detail Akun Pengguna (Profil, Info Kontak, Riwayat Tiket & Jejak Laptop)
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Tiket permohonan yang diajukan pengguna / instansi ini
        $userTikets = TiketDtsen::where('user_id', $user->id)
            ->orWhere('nama_opd', $user->nama_instansi)
            ->latest()
            ->take(15)
            ->get();

        // Jejak audit login & aktivitas pengguna ini
        $userLogs = AuditLog::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('description', 'like', "%{$user->username}%");
            })
            ->latest()
            ->take(15)
            ->get();

        // Riwayat bagi data resmi DTSEN yang terkait instansi pengguna ini
        $userRiwayats = RiwayatBagiDataDtsen::where('nama_instansi', $user->nama_instansi)
            ->latest()
            ->get();

        return view('admin.users.show', compact('user', 'userTikets', 'userLogs', 'userRiwayats'));
    }

    /**
     * Halaman Khusus Log Laptop & Audit Keamanan dengan Sub Page Tab
     */
    public function logsIndex(Request $request)
    {
        $activeTab = $request->query('tab', 'laptop');

        // Log Nomor Laptop / Perangkat Login
        $laptopLogs = AuditLog::where('action', 'like', 'AUTH_LOGIN%')
            ->latest()
            ->paginate(15, ['*'], 'laptop_page')
            ->appends(['tab' => 'laptop']);

        // Log Keamanan & Akses Ditolak
        $securityLogs = AuditLog::where(function($q) {
                $q->where('action', 'like', 'AUTH_REJECTED%')
                  ->orWhere('action', 'like', 'AUTH_FAILED%')
                  ->orWhere('action', 'like', 'UNAUTHORIZED%');
            })
            ->latest()
            ->paginate(15, ['*'], 'security_page')
            ->appends(['tab' => 'keamanan']);

        // Log Aktivitas Tiket
        $tiketLogs = AuditLog::where('action', 'like', 'TIKET%')
            ->latest()
            ->paginate(15, ['*'], 'tiket_page')
            ->appends(['tab' => 'tiket']);

        // Semua Log Audit
        $allLogs = AuditLog::latest()
            ->paginate(15, ['*'], 'all_page')
            ->appends(['tab' => 'semua']);

        $totalLogs = AuditLog::count();
        $uniqueDevices = AuditLog::whereNotNull('device_id')->distinct('device_id')->count('device_id');
        $todayLogs = AuditLog::whereDate('created_at', today())->count();
        $securityAlerts = AuditLog::where(function($q) {
            $q->where('action', 'like', 'AUTH_REJECTED%')
              ->orWhere('action', 'like', 'AUTH_FAILED%')
              ->orWhere('action', 'like', 'UNAUTHORIZED%');
        })->count();

        return view('admin.logs.index', compact(
            'activeTab', 
            'laptopLogs', 
            'securityLogs', 
            'tiketLogs', 
            'allLogs', 
            'totalLogs', 
            'uniqueDevices', 
            'todayLogs', 
            'securityAlerts'
        ));
    }

    /**
     * Halaman Formulir Buat Akun Pengguna Baru
     */
    public function create()
    {
        if (!auth()->user()->canManageUsers()) {
            return redirect()->route('admin.users.index')->with('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk membuat akun pengguna.');
        }

        $roles = \App\Models\Role::orderBy('id')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Tambah Akun Pengguna Baru & Langsung Arahkan ke Pengaturan Hak Akses
     */
    public function storeUser(Request $request)
    {
        // Pemeriksaan Hak Akses: manage-users (Admin 1 / Super Admin)
        if (!auth()->user()->canManageUsers()) {
            return redirect()->route('admin.users.index', ['tab' => 'users'])->with('error', 'Akses Ditolak: Akun Anda tidak memiliki hak akses untuk membuat akun baru.');
        }

        $request->validate([
            'nama_instansi' => 'required|string|max:200',
            'name' => 'nullable|string|max:150',
            'username' => 'required|string|max:80|unique:users,username',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:4',
            'role_id' => 'nullable|exists:roles,id',
            'nomor_kontak' => 'nullable|string|max:50',
        ], [
            'username.unique' => 'Username ini sudah digunakan.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.min' => 'Password minimal 4 karakter.',
        ]);

        $roleId = $request->role_id;
        $roleObj = $roleId ? \App\Models\Role::find($roleId) : null;
        
        $roleStr = 'perangkat_daerah';
        if ($roleObj) {
            if ($roleObj->slug === 'superadmin') {
                $roleStr = 'superadmin';
            } elseif (in_array($roleObj->slug, ['admin_user_manager', 'admin_operator', 'eselon2', 'admin'])) {
                $roleStr = 'admin';
            } else {
                $roleStr = 'perangkat_daerah';
            }
        }

        $user = new User([
            'nama_instansi' => $request->nama_instansi,
            'name' => $request->name ?: $request->nama_instansi,
            'username' => trim($request->username),
            'email' => trim($request->email),
            'role' => $roleStr,
            'role_id' => $roleId,
            'nomor_kontak' => $request->nomor_kontak,
        ]);
        $user->setPlainPassword($request->password);
        $user->save();

        AuditLogger::log(
            'USER_CREATED',
            "Administrator membuat akun [{$user->username}] ({$user->nama_instansi}) dengan peran " . ($roleObj ? $roleObj->name : 'Pengguna') . ". Melanjutkan ke pengaturan hak akses.",
            'SUCCESS',
            ['target_user_id' => $user->id, 'created_by' => auth()->user()->name]
        );

        // Setelah klik buat akun, kita otomatis beralih ke page baru untuk mengedit hak akses akun tersebut!
        return redirect()->route('admin.users.edit-access', $user->id)
            ->with('success', "Akun [{$user->username}] berhasil dibuat! Silakan sesuaikan hak akses dan wewenang akun di bawah ini.");
    }

    /**
     * Halaman Baru: Edit Hak Akses untuk Akun Pengguna Tertentu
     */
    public function editAccess($id)
    {
        if (!auth()->user()->canManageUsers() && !auth()->user()->canManageAccess()) {
            return redirect()->route('admin.users.index')->with('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk mengedit hak akses.');
        }

        $user = User::with('roleRelation.privileges')->findOrFail($id);
        $roles = \App\Models\Role::with('privileges')->get();
        $privileges = \App\Models\Privilege::orderBy('module')->orderBy('id')->get()->groupBy('module');
        $allPrivilegesList = \App\Models\Privilege::orderBy('id')->get();

        return view('admin.users.edit_access', compact('user', 'roles', 'privileges', 'allPrivilegesList'));
    }

    /**
     * Simpan Perubahan Hak Akses Akun Pengguna
     */
    public function updateAccess(Request $request, $id)
    {
        if (!auth()->user()->canManageUsers() && !auth()->user()->canManageAccess()) {
            return redirect()->route('admin.users.index')->with('error', 'Akses Ditolak.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'privilege_ids' => 'nullable|array',
            'privilege_ids.*' => 'exists:privileges,id',
        ]);

        $selectedRole = \App\Models\Role::findOrFail($request->role_id);
        $privilegeIds = $request->input('privilege_ids', []);

        // Ambil default privilege ID dari role yang dipilih
        $defaultPrivilegeIds = $selectedRole->privileges->pluck('id')->toArray();
        sort($defaultPrivilegeIds);
        $newPrivIds = array_map('intval', $privilegeIds);
        sort($newPrivIds);

        // Jika hak akses dicustomize (berbeda dari preset bawaan role):
        if ($defaultPrivilegeIds != $newPrivIds && !empty($newPrivIds)) {
            $customRoleSlug = 'custom_user_' . $user->id;
            $customRole = \App\Models\Role::updateOrCreate(
                ['slug' => $customRoleSlug],
                [
                    'name' => $selectedRole->name . ' (' . $user->username . ')',
                    'deskripsi' => 'Hak akses khusus yang disesuaikan untuk akun ' . $user->username . ' (' . $user->nama_instansi . ')',
                ]
            );
            $customRole->privileges()->sync($newPrivIds);
            $user->role_id = $customRole->id;
        } else {
            $user->role_id = $selectedRole->id;
        }

        // Tentukan level peran untuk autentikasi sistem
        $allPrivs = \App\Models\Privilege::whereIn('id', $newPrivIds)->pluck('slug')->toArray();
        if ($selectedRole->slug === 'superadmin' || in_array('manage-access', $allPrivs)) {
            $user->role = 'superadmin';
        } elseif (in_array('manipulate-steps', $allPrivs) || in_array('manage-users', $allPrivs) || in_array('verify-se', $allPrivs)) {
            $user->role = 'admin';
        } else {
            $user->role = 'perangkat_daerah';
        }

        $user->save();

        AuditLogger::log(
            'USER_ACCESS_UPDATED',
            "Administrator memperbarui hak akses akun [{$user->username}] ({$user->nama_instansi}) menjadi " . ($user->roleRelation ? $user->roleRelation->name : $user->role) . ".",
            'SUCCESS',
            [
                'target_user_id' => $user->id,
                'target_username' => $user->username,
                'role_id' => $user->role_id,
                'privileges' => $allPrivs,
                'updated_by' => auth()->user()->name
            ]
        );

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "Hak akses untuk akun [{$user->username}] berhasil disimpan dan langsung aktif!");
    }

    /**
     * Halaman Profil Akun Administrator
     */
    public function profile()
    {
        $user = auth()->user()->load('roleRelation.privileges');
        $sessionLifetime = (int) AppSetting::get('session_lifetime', config('session.lifetime', 5));
        
        $recentLogs = AuditLog::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('user_name', $user->name)
                  ->orWhere('description', 'like', "%{$user->username}%");
            })
            ->latest()
            ->take(10)
            ->get();

        return view('admin.profile', compact('user', 'recentLogs', 'sessionLifetime'));
    }

    /**
     * Update Informasi Profil & Kata Sandi Administrator
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'nama_instansi' => 'nullable|string|max:200',
            'nomor_kontak' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:4|confirmed',
            'session_lifetime' => 'nullable|integer|min:1|max:1440',
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password baru minimal 4 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'session_lifetime.integer' => 'Waktu sesi harus berupa angka bulat dalam menit.',
        ]);

        $user->name = $request->name;
        $user->email = trim($request->email);
        if ($request->filled('nama_instansi')) $user->nama_instansi = $request->nama_instansi;
        if ($request->filled('nomor_kontak')) $user->nomor_kontak = $request->nomor_kontak;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Khusus Walidata (Super Admin): Dapat mengatur batas waktu masa aktif sesi sistem
        if ($user->isSuperAdmin() && $request->filled('session_lifetime')) {
            $newLifetime = max(1, min(1440, (int) $request->session_lifetime));
            AppSetting::set('session_lifetime', $newLifetime);

            // Update .env file agar sinkron
            $envPath = base_path('.env');
            if (file_exists($envPath) && is_writable($envPath)) {
                $env = file_get_contents($envPath);
                if (preg_match('/^SESSION_LIFETIME=/m', $env)) {
                    $env = preg_replace('/^SESSION_LIFETIME=.*/m', 'SESSION_LIFETIME=' . $newLifetime, $env);
                } else {
                    $env .= "\nSESSION_LIFETIME=" . $newLifetime . "\n";
                }
                @file_put_contents($envPath, $env);
            }
            config(['session.lifetime' => $newLifetime]);

            AuditLogger::log(
                'SESSION_LIFETIME_CONFIGURED',
                "Walidata memperbarui batas waktu sesi idle sistem menjadi {$newLifetime} menit.",
                'SUCCESS',
                ['new_lifetime_minutes' => $newLifetime, 'updated_by' => $user->username]
            );
        }

        AuditLogger::log(
            'PROFILE_UPDATED',
            "Walidata [{$user->username}] memperbarui informasi profil akun pribadinya.",
            'SUCCESS'
        );

        return redirect()->route('admin.profile')->with('success', 'Profil dan informasi akun Anda berhasil diperbarui.');
    }

    /**
     * Hapus Akun Pengguna
     */
    public function destroyUser($id)
    {
        // Pemeriksaan Hak Akses: manage-users (Admin 1 / Super Admin)
        if (!auth()->user()->canManageUsers()) {
            return redirect()->route('admin.users.index', ['tab' => 'users'])->with('error', 'Akses Ditolak: Akun Anda tidak memiliki hak akses untuk menghapus pengguna.');
        }

        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index', ['tab' => 'users'])->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $username = $user->username;
        $instansi = $user->nama_instansi ?: $user->name;
        $user->delete();

        AuditLogger::log(
            'USER_DELETED',
            "Akun [{$username}] ({$instansi}) telah dihapus oleh Super Admin.",
            'WARNING'
        );

        return redirect()->route('admin.users.index', ['tab' => 'users'])->with('success', "Akun [{$username}] ({$instansi}) berhasil dihapus.");
    }

    /**
     * Super Admin: Input Data Riwayat Bagi Data DTSEN Resmi
     */
    public function storeRiwayatBagiData(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:200',
            'no_surat_edaran' => 'required|string|max:150',
            'tanggal_tiket_dibuat' => 'required|date',
            'tanggal_bast_selesai' => 'nullable|date',
            'no_bast' => 'nullable|string|max:150',
            'orang_hadir' => 'required|string',
            'ip_atau_nomor_laptop' => 'required|string|max:200',
            'tiket_dtsen_id' => 'nullable|exists:tiket_dtsens,id',
            'user_id' => 'nullable|exists:users,id',
            'status_bast' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ], [
            'nama_instansi.required' => 'Nama Instansi wajib diisi.',
            'no_surat_edaran.required' => 'Nomor Surat Edaran wajib diisi.',
            'orang_hadir.required' => 'Daftar nama orang perangkat daerah yang hadir wajib diisi.',
            'ip_atau_nomor_laptop.required' => 'IP atau nomor laptop yang digunakan wajib diisi.',
        ]);

        $riwayat = RiwayatBagiDataDtsen::create([
            'tiket_dtsen_id' => $request->tiket_dtsen_id,
            'user_id' => $request->user_id,
            'nama_instansi' => $request->nama_instansi,
            'no_surat_edaran' => $request->no_surat_edaran,
            'tanggal_tiket_dibuat' => $request->tanggal_tiket_dibuat,
            'tanggal_bast_selesai' => $request->tanggal_bast_selesai,
            'no_bast' => $request->no_bast ?: ('BAST/DTSEN/' . date('Y/m') . '/' . rand(100, 999)),
            'status_bast' => $request->status_bast ?: 'SELESAI DITANDATANGANI (BAST SAH)',
            'orang_hadir' => $request->orang_hadir,
            'ip_atau_nomor_laptop' => $request->ip_atau_nomor_laptop,
            'catatan' => $request->catatan,
            'created_by' => auth()->user()->name ?? 'Super Administrator',
        ]);

        AuditLogger::log(
            'RIWAYAT_BAGI_DATA_CREATED',
            "Admin menginput data riwayat bagi pakai DTSEN resmi untuk instansi: {$riwayat->nama_instansi}.",
            'SUCCESS'
        );

        return redirect()->route('admin.users.index', ['tab' => 'riwayat-terbit'])->with('success', "Data Riwayat Bagi Data DTSEN untuk [{$riwayat->nama_instansi}] berhasil disimpan dan tampil pada Profile Perangkat Daerah.");
    }

    /**
     * Hapus Data Riwayat Bagi Data DTSEN
     */
    public function destroyRiwayatBagiData($id)
    {
        $riwayat = RiwayatBagiDataDtsen::findOrFail($id);
        $instansi = $riwayat->nama_instansi;
        $riwayat->delete();

        AuditLogger::log(
            'RIWAYAT_BAGI_DATA_DELETED',
            "Data riwayat bagi data DTSEN untuk {$instansi} dihapus oleh Super Admin.",
            'WARNING'
        );

        return redirect()->route('admin.users.index', ['tab' => 'riwayat-terbit'])->with('success', "Data Riwayat Bagi Data DTSEN untuk [{$instansi}] berhasil dihapus.");
    }

    /**
     * Halaman Detail Riwayat Bagi Data DTSEN Resmi (Sisi Super Admin)
     */
    public function showRiwayatBagiData($id)
    {
        $riwayat = RiwayatBagiDataDtsen::with(['tiket', 'user'])->findOrFail($id);
        return view('admin.riwayat_bagi_data.show', compact('riwayat'));
    }

    /**
     * Halaman Detail Log Laptop & Jejak Audit Keamanan Selengkapnya
     */
    public function logsShow($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);

        // Cari jejak aktivitas terkait pada IP atau User yang sama
        $relatedLogs = AuditLog::where('id', '!=', $log->id)
            ->where(function($q) use ($log) {
                if ($log->ip_address) {
                    $q->where('ip_address', $log->ip_address);
                }
                if ($log->user_id) {
                    $q->orWhere('user_id', $log->user_id);
                }
            })
            ->latest('created_at')
            ->take(6)
            ->get();

        return view('admin.logs.show', compact('log', 'relatedLogs'));
    }

    /**
     * Walidata Mereset Password Akun Pengguna Langsung
     */
    public function superAdminResetPassword(Request $request, $id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Hanya Walidata yang berhak mereset kata sandi akun.');
        }

        $request->validate([
            'password' => 'required|string|min:4',
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 4 karakter.',
        ]);

        $user = User::findOrFail($id);
        $user->setPlainPassword($request->password);
        $user->save();

        // Tandai permintaan reset terkait sebagai DIRESET
        \App\Models\PasswordResetRequest::where('user_id', $user->id)
            ->where('status', 'MENUNGGU')
            ->update([
                'status' => 'DIRESET',
                'password_baru' => $request->password,
                'reset_by' => auth()->id(),
            ]);

        AuditLogger::log(
            'USER_PASSWORD_RESET_WALIDATA',
            "Walidata mereset kata sandi akun [{$user->username}] ({$user->nama_instansi}).",
            'SUCCESS',
            ['target_user_id' => $user->id, 'admin_id' => auth()->id()]
        );

        return back()->with('success', "Kata sandi akun {$user->username} berhasil direset menjadi: {$request->password}");
    }

    /**
     * Walidata Menyetujui Permintaan Reset Password Masuk
     */
    public function approveResetRequest(Request $request, $id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Hanya Walidata yang berhak menyetujui permintaan reset kata sandi.');
        }

        $resetReq = \App\Models\PasswordResetRequest::findOrFail($id);
        $user = $resetReq->user ?: User::where('username', $resetReq->username)->firstOrFail();

        $newPassword = $request->input('password') ?: 'opd123';
        $user->setPlainPassword($newPassword);
        $user->save();

        $resetReq->update([
            'status' => 'DIRESET',
            'password_baru' => $newPassword,
            'reset_by' => auth()->id(),
        ]);

        AuditLogger::log(
            'USER_RESET_REQUEST_APPROVED',
            "Walidata menyetujui reset kata sandi akun [{$user->username}] menjadi [{$newPassword}].",
            'SUCCESS',
            ['target_user_id' => $user->id, 'admin_id' => auth()->id()]
        );

        return back()->with('success', "Permintaan reset kata sandi akun {$user->username} berhasil disetujui. Kata sandi telah direset ke: {$newPassword}");
    }
}
