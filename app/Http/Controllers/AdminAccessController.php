<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Privilege;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminAccessController extends Controller
{
    /**
     * Halaman Utama Manajemen Hak Akses Administrator (Filament Access Control)
     */
    public function index(Request $request)
    {
        // Akses khusus Super Admin (Walidata)
        if (!auth()->user()->canManageAccess()) {
            AuditLogger::log(
                'UNAUTHORIZED_ADMIN_ACCESS_ATTEMPT',
                "Pengguna " . auth()->user()->name . " mencoba membuka halaman Manajemen Hak Akses Administrator. Akses ditolak.",
                'WARNING',
                ['user_id' => auth()->id(), 'url' => $request->path()]
            );
            return redirect()->route('admin.dashboard')->with('error', 'Akses Ditolak: Halaman Hak Akses & Role Administrator khusus Super Administrator.');
        }

        // Ambil seluruh akun admin
        $admins = User::whereIn('role', ['superadmin', 'admin'])
            ->with(['roleRelation.privileges'])
            ->orderBy('id', 'asc')
            ->get();

        // Ambil seluruh master role dan master privilege
        $roles = Role::with('privileges')->get();
        $privileges = Privilege::orderBy('module')->orderBy('id')->get()->groupBy('module');
        $allPrivilegesList = Privilege::orderBy('id')->get();

        // Statistik
        $totalAdmin = $admins->count();
        $totalUserManager = $admins->filter(fn($u) => $u->canManageUsers() && !$u->canManipulateSteps())->count();
        $totalOperator = $admins->filter(fn($u) => $u->canManipulateSteps() && !$u->canManageUsers())->count();
        $totalSuperAdmin = $admins->filter(fn($u) => $u->role === 'superadmin' || $u->username === 'admin')->count();

        return view('admin.access.index', compact(
            'admins',
            'roles',
            'privileges',
            'allPrivilegesList',
            'totalAdmin',
            'totalUserManager',
            'totalOperator',
            'totalSuperAdmin'
        ));
    }

    /**
     * Perbarui Role dan Hak Akses Administrator
     */
    public function updateAccess(Request $request, $id)
    {
        if (!auth()->user()->canManageAccess()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk mengubah hak akses.');
        }

        $admin = User::whereIn('role', ['superadmin', 'admin'])->findOrFail($id);

        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'privilege_ids' => 'nullable|array',
            'privilege_ids.*' => 'exists:privileges,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        
        // Perbarui role_id akun
        $admin->role_id = $role->id;
        if ($role->slug === 'superadmin') {
            $admin->role = 'superadmin';
        } else {
            $admin->role = 'admin';
        }
        $admin->save();

        // Jika ada kustomisasi hak akses spesifik untuk role ini
        if ($request->has('privilege_ids')) {
            $role->privileges()->sync($request->input('privilege_ids', []));
        }

        AuditLogger::log(
            'ADMIN_ACCESS_UPDATED',
            "Super Admin memperbarui peran & hak akses untuk akun [{$admin->username}] menjadi {$role->name}.",
            'SUCCESS',
            [
                'target_user_id' => $admin->id,
                'target_username' => $admin->username,
                'new_role' => $role->name,
                'assigned_by' => auth()->user()->name
            ]
        );

        return redirect()->route('admin.access.index')->with('success', "Hak akses administrator [{$admin->username}] ({$admin->name}) berhasil diperbarui.");
    }

    /**
     * Tambah Akun Administrator Baru
     */
    public function storeAdmin(Request $request)
    {
        if (!auth()->user()->canManageAccess()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses Ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:150',
            'username' => 'required|string|max:80|unique:users,username',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:4',
            'nama_instansi' => 'nullable|string|max:200',
            'nomor_kontak' => 'nullable|string|max:50',
            'role_id' => 'required|exists:roles,id',
        ], [
            'username.unique' => 'Username ini sudah digunakan.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Password minimal 4 karakter.',
        ]);

        $role = Role::findOrFail($request->role_id);
        $roleStr = $role->slug === 'superadmin' ? 'superadmin' : 'admin';

        $newAdmin = User::create([
            'name' => $request->name,
            'username' => trim($request->username),
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'role' => $roleStr,
            'role_id' => $role->id,
            'nama_instansi' => $request->nama_instansi ?: 'Dinas Komunikasi & Informatika',
            'nomor_kontak' => $request->nomor_kontak ?: '-',
        ]);

        AuditLogger::log(
            'ADMIN_CREATED',
            "Akun administrator baru [{$newAdmin->username}] ({$newAdmin->name}) dibuat dengan peran {$role->name}.",
            'SUCCESS',
            ['admin_id' => $newAdmin->id, 'role' => $role->name]
        );

        return redirect()->route('admin.access.index')->with('success', "Akun administrator baru [{$newAdmin->username}] berhasil dibuat dengan peran: {$role->name}.");
    }

    /**
     * Hapus Akun Administrator
     */
    public function destroyAdmin($id)
    {
        if (!auth()->user()->canManageAccess()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses Ditolak.');
        }

        $admin = User::whereIn('role', ['superadmin', 'admin'])->findOrFail($id);

        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.access.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($admin->username === 'admin') {
            return redirect()->route('admin.access.index')->with('error', 'Akun Super Admin Utama (admin) tidak dapat dihapus.');
        }

        $username = $admin->username;
        $admin->delete();

        AuditLogger::log(
            'ADMIN_DELETED',
            "Akun administrator [{$username}] telah dihapus.",
            'WARNING',
            ['deleted_username' => $username]
        );

        return redirect()->route('admin.access.index')->with('success', "Akun administrator [{$username}] berhasil dihapus.");
    }
}

