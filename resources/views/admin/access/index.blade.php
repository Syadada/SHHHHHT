@extends('layouts.admin')

@section('title', 'Manajemen Hak Akses & Administrator (Filament Access Control)')

@section('content')
<div class="filament-shell" style="max-width: 1240px; margin: 0 auto; padding-bottom: 40px;">

    <!-- Top Filament Breadcrumbs & Actions -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748b; margin-bottom: 4px;">
                <a href="{{ route('admin.dashboard') }}" style="color: #64748b; text-decoration: none;">Dashboard</a>
                <span>/</span>
                <span style="color: #0f172a; font-weight: 600;">Filament Access Control</span>
            </div>
            <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; margin: 0; display: flex; align-items: center; gap: 10px;">
                <span style="background: #f59e0b; color: #fff; width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fi fi-rr-shield-check"></i>
                </span>
                <span>Hak Akses & Peran Administrator</span>
            </h1>
            
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="openCreateAdminModal()" class="btn-pill-primary" style="background: #0f172a; border-color: #0f172a; box-shadow: 0 2px 6px rgba(15,23,42,0.25);">
                <i class="fi fi-rr-plus"></i>
                <span>Tambah Admin Baru</span>
            </button>
        </div>
    </div>

    <!-- 1. Filament Stat Widgets Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
        
        <!-- Total Admin -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Administrator</div>
                <div style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 4px 0;">{{ $totalAdmin }}</div>
                
            </div>
            <div style="width: 44px; height: 44px; border-radius: 10px; background: #f8fafc; color: #0f172a; display: flex; align-items: center; justify-content: center; font-size: 20px; border: 1px solid #e2e8f0;">
                <i class="fi fi-rr-users"></i>
            </div>
        </div>

        <!-- Admin 1: Kelola Akun -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #0284c7; text-transform: uppercase; letter-spacing: 0.5px;">Admin 1 (Kelola Akun)</div>
                <div style="font-size: 28px; font-weight: 800; color: #0284c7; margin: 4px 0;">{{ $totalUserManager }}</div>
                
            </div>
            <div style="width: 44px; height: 44px; border-radius: 10px; background: #f0f9ff; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px; border: 1px solid #bae6fd;">
                <i class="fi fi-rr-user-add"></i>
            </div>
        </div>

        <!-- Admin 2: Operator Proses -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #d97706; text-transform: uppercase; letter-spacing: 0.5px;">Admin 2 (Operator Proses)</div>
                <div style="font-size: 28px; font-weight: 800; color: #d97706; margin: 4px 0;">{{ $totalOperator }}</div>
                
            </div>
            <div style="width: 44px; height: 44px; border-radius: 10px; background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px; border: 1px solid #fde68a;">
                <i class="fi fi-rr-play-alt"></i>
            </div>
        </div>

        <!-- Super Admin -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.5px;">Walidata</div>
                <div style="font-size: 28px; font-weight: 800; color: #7c3aed; margin: 4px 0;">{{ $totalSuperAdmin }}</div>
                
            </div>
            <div style="width: 44px; height: 44px; border-radius: 10px; background: #f5f3ff; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 20px; border: 1px solid #ddd6fe;">
                <i class="fi fi-rr-crown"></i>
            </div>
        </div>

    </div>

    <!-- 2. Filament Table Card: Daftar Akun Administrator & Hak Akses -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.03); margin-bottom: 30px;">
        
        <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background: #fafafa; flex-wrap: wrap; gap: 10px;">
            <div>
                <h2 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fi fi-rr-key" style="color: #f59e0b;"></i>
                    <span>Tabel Akun Administrator & Segmentasi Akses</span>
                </h2>
                <p style="font-size: 12.5px; color: #64748b; margin: 4px 0 0;">
                    Klik tombol <strong>Edit Akses</strong> untuk mengubah peran atau mengatur wewenang spesifik setiap administrator.
                </p>
            </div>
            <span style="font-size: 12px; font-weight: 700; background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 20px; border: 1px solid #e2e8f0;">
                {{ $admins->count() }} Akun Dikonfigurasi
            </span>
        </div>

        <div class="table-responsive">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 12px 18px; text-align: left; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Administrator</th>
                        <th style="padding: 12px 18px; text-align: left; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Peran Utama (Role)</th>
                        <th style="padding: 12px 18px; text-align: left; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Hak Akses Fitur (Privileges)</th>
                        <th style="padding: 12px 18px; text-align: left; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Wewenang Khusus</th>
                        <th style="padding: 12px 18px; text-align: right; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        @php
                            $roleObj = $admin->roleRelation;
                            $roleSlug = $roleObj ? $roleObj->slug : $admin->role;
                            $privList = $roleObj ? $roleObj->privileges : collect();
                            $isSuper = ($admin->role === 'superadmin' || $admin->username === 'admin');
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            
                            <!-- User Info -->
                            <td style="padding: 14px 18px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 38px; height: 38px; border-radius: 50%; background: {{ $isSuper ? '#7c3aed' : ($roleSlug === 'admin_user_manager' ? '#0284c7' : '#d97706') }}; color: #fff; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center;">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 14px;">{{ $admin->name }}</div>
                                        <div style="font-size: 12px; color: #64748b; font-family: var(--font-mono);">
                                            {{ '@' . $admin->username }} &bull; {{ $admin->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role Badge -->
                            <td style="padding: 14px 18px;">
                                @if($isSuper)
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700;">
                                        <i class="fi fi-rr-crown"></i> Walidata
                                    </span>
                                @elseif($roleSlug === 'admin_user_manager')
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700;">
                                        <i class="fi fi-rr-user-add"></i> Admin 1 (Kelola Akun)
                                    </span>
                                @elseif($roleSlug === 'admin_operator')
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: #fffbeb; color: #b45309; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700;">
                                        <i class="fi fi-rr-play-alt"></i> Admin 2 (Operator Proses)
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $roleObj ? $roleObj->name : 'Administrator' }}
                                    </span>
                                @endif
                            </td>

                            <!-- Privileges Pills -->
                            <td style="padding: 14px 18px; max-width: 380px;">
                                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @if($isSuper)
                                        <span style="background: #ecfdf5; color: #047857; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; border: 1px solid #a7f3d0;">
                                            ✓ Akses Penuh Seluruh Modul (All Privileges)
                                        </span>
                                    @else
                                        @forelse($privList as $priv)
                                            <span style="background: #f8fafc; color: #334155; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 4px;">
                                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #0ea5e9;"></span>
                                                {{ $priv->name }}
                                            </span>
                                        @empty
                                            <span style="color: #94a3b8; font-size: 12px; font-style: italic;">Belum ada hak akses khusus</span>
                                        @endforelse
                                    @endif
                                </div>
                            </td>

                            <!-- Capability Highlights -->
                            <td style="padding: 14px 18px;">
                                <div style="font-size: 12px; display: flex; flex-direction: column; gap: 4px;">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span style="color: {{ $admin->canManageUsers() ? '#10b981' : '#cbd5e1' }}; font-weight: 800;">
                                            {{ $admin->canManageUsers() ? '✔' : '✖' }}
                                        </span>
                                        <span style="color: {{ $admin->canManageUsers() ? '#0f172a' : '#94a3b8' }}; font-weight: {{ $admin->canManageUsers() ? '600' : '400' }};">
                                            Bikin & Kelola Akun
                                        </span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span style="color: {{ $admin->canManipulateSteps() ? '#10b981' : '#cbd5e1' }}; font-weight: 800;">
                                            {{ $admin->canManipulateSteps() ? '✔' : '✖' }}
                                        </span>
                                        <span style="color: {{ $admin->canManipulateSteps() ? '#0f172a' : '#94a3b8' }}; font-weight: {{ $admin->canManipulateSteps() ? '600' : '400' }};">
                                            Update Proses Tiket
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Action Buttons -->
                            <td style="padding: 14px 18px; text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center;">
                                    <button type="button" 
                                            onclick='openEditAccessModal(@json($admin), @json($admin->role_id), @json($privList->pluck("id")))'
                                            class="btn-pill-outline" 
                                            style="padding: 6px 12px; font-size: 12px; font-weight: 600; background: #fff;">
                                        <i class="fi fi-rr-edit"></i> Edit Akses
                                    </button>

                                    @if($admin->id !== auth()->id() && $admin->username !== 'admin')
                                        <form action="{{ route('admin.access.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Hapus akun administrator [{{ $admin->username }}]?');" style="display: inline; margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-pill-outline" style="padding: 6px 10px; font-size: 12px; color: #ef4444; border-color: #fecaca; background: #fff;" title="Hapus Akun">
                                                <i class="fi fi-rr-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- 3. Dokumentasi Panduan Peran Filament Access Control -->
    <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 20px 24px;">
        <h3 style="font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
            <i class="fi fi-rr-info" style="color: #0284c7;"></i>
            <span>Konsep Pembagian Peran Administrator Sesuai SOP</span>
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 14px; font-size: 13px; color: #475569;">
            <div style="background: #fff; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <strong style="color: #0284c7;">1. Admin 1 (Kelola Akun):</strong>
                <p style="margin: 4px 0 0; font-size: 12.5px;">
                    Diberikan wewenang membuat akun perangkat daerah (OPD) dan ekspor laporan. <em>Tidak dapat menyetujui atau memperbarui tahapan proses tiket layanan</em>.
                </p>
            </div>
            <div style="background: #fff; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <strong style="color: #d97706;">2. Admin 2 (Operator Proses Tiket):</strong>
                <p style="margin: 4px 0 0; font-size: 12.5px;">
                    Diberikan wewenang memverifikasi variabel SE dan mengklik "OK" pada tahapan proses 1 s/d 5. <em>Tidak dapat menambah atau menghapus akun pengguna</em>.
                </p>
            </div>
            <div style="background: #fff; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <strong style="color: #7c3aed;">3. Walidata:</strong>
                <p style="margin: 4px 0 0; font-size: 12.5px;">
                    Walidata tingkat daerah yang berwenang penuh atas seluruh fitur, termasuk memodifikasi penugasan hak akses setiap admin pada panel ini.
                </p>
            </div>
        </div>
    </div>

</div>

<!-- MODAL: Edit Hak Akses & Peran Admin -->
<div id="modalEditAccess" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; border-radius: 16px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);">
        
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 id="modalEditTitle" style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0;">Edit Hak Akses Administrator</h3>
                <p id="modalEditSubtitle" style="font-size: 13px; color: #64748b; margin: 4px 0 0;">Ubah peran atau sesuaikan izin modul fitur.</p>
            </div>
            <button type="button" onclick="closeEditAccessModal()" style="background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer;">&times;</button>
        </div>

        <form id="formEditAccess" method="POST" action="" style="padding: 24px;">
            @csrf

            <!-- Pilihan Role Utama -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                    Pilih Peran Utama (Role Preset) <span style="color: red;">*</span>
                </label>
                <select name="role_id" id="editRoleId" class="form-input" style="height: 44px; width: 100%; font-size: 13.5px;" onchange="onRolePresetChange(this.value)">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" data-slug="{{ $role->slug }}" data-privileges='@json($role->privileges->pluck("id"))'>
                            {{ $role->name }} ({{ $role->deskripsi }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Granular Privileges Checkboxes -->
            <div class="form-group" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <label style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0;">
                        Izin Modul Spesifik (Granular Privileges)
                    </label>
                    <span style="font-size: 11px; color: #64748b;">Dapat dicentang kustom</span>
                </div>

                <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; background: #f8fafc; display: flex; flex-direction: column; gap: 10px; max-height: 240px; overflow-y: auto;">
                    @foreach($allPrivilegesList as $priv)
                        <label style="display: flex; align-items: flex-start; gap: 10px; background: #fff; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; cursor: pointer;">
                            <input type="checkbox" name="privilege_ids[]" value="{{ $priv->id }}" class="priv-checkbox" style="margin-top: 3px; accent-color: #0f172a;">
                            <div style="flex: 1;">
                                <div style="font-size: 13px; font-weight: 700; color: #0f172a;">
                                    {{ $priv->name }}
                                    <span style="font-size: 10px; font-weight: 700; color: #0284c7; background: #e0f2fe; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">{{ $priv->module }}</span>
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">{{ $priv->deskripsi }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 18px;">
                <button type="button" onclick="closeEditAccessModal()" class="btn-pill-outline" style="padding: 8px 18px;">Batal</button>
                <button type="submit" class="btn-pill-primary" style="background: #0f172a; border-color: #0f172a; padding: 8px 20px;">
                    Simpan Perubahan Akses
                </button>
            </div>
        </form>

    </div>
</div>

<!-- MODAL: Tambah Administrator Baru -->
<div id="modalCreateAdmin" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; border-radius: 16px; max-width: 520px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);">
        
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0;">Tambah Akun Administrator Baru</h3>
                <p style="font-size: 13px; color: #64748b; margin: 4px 0 0;">Buat akun admin dan tentukan hak akses peran.</p>
            </div>
            <button type="button" onclick="closeCreateAdminModal()" style="background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer;">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.access.store') }}" style="padding: 24px;">
            @csrf

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                    Nama Lengkap Admin <span style="color: red;">*</span>
                </label>
                <input type="text" name="name" required class="form-input" placeholder="Contoh: Budi Santoso (Tim Verifikator)" style="height: 42px; width: 100%;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 12.5px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                        Username <span style="color: red;">*</span>
                    </label>
                    <input type="text" name="username" required class="form-input" placeholder="misal: admin3" style="height: 42px; width: 100%;">
                </div>
                <div>
                    <label style="display: block; font-size: 12.5px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                        Password <span style="color: red;">*</span>
                    </label>
                    <input type="password" name="password" required class="form-input" placeholder="Minimal 4 karakter" style="height: 42px; width: 100%;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                    Alamat Email <span style="color: red;">*</span>
                </label>
                <input type="email" name="email" required class="form-input" placeholder="admin@pemda.go.id" style="height: 42px; width: 100%;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                    Peran & Hak Akses (Role) <span style="color: red;">*</span>
                </label>
                <select name="role_id" required class="form-input" style="height: 44px; width: 100%; font-size: 13.5px;">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 18px;">
                <button type="button" onclick="closeCreateAdminModal()" class="btn-pill-outline" style="padding: 8px 18px;">Batal</button>
                <button type="submit" class="btn-pill-primary" style="background: #0f172a; border-color: #0f172a; padding: 8px 20px;">
                    Simpan Akun Admin
                </button>
            </div>
        </form>

    </div>
</div>

@endsection

@section('scripts')
<script>
    function openCreateAdminModal() {
        document.getElementById('modalCreateAdmin').style.display = 'flex';
    }

    function closeCreateAdminModal() {
        document.getElementById('modalCreateAdmin').style.display = 'none';
    }

    function openEditAccessModal(admin, currentRoleId, currentPrivIds) {
        document.getElementById('formEditAccess').action = `/admin/access-control/${admin.id}/update`;
        document.getElementById('modalEditTitle').textContent = `Edit Hak Akses: ${admin.name}`;
        document.getElementById('modalEditSubtitle').textContent = `Username: @${admin.username} (ID: #${admin.id})`;
        
        const sel = document.getElementById('editRoleId');
        if (currentRoleId) {
            sel.value = currentRoleId;
        }

        // Set centang checkbox hak akses
        const checkboxes = document.querySelectorAll('.priv-checkbox');
        checkboxes.forEach(cb => {
            const privId = parseInt(cb.value);
            cb.checked = Array.isArray(currentPrivIds) && currentPrivIds.includes(privId);
        });

        document.getElementById('modalEditAccess').style.display = 'flex';
    }

    function closeEditAccessModal() {
        document.getElementById('modalEditAccess').style.display = 'none';
    }

    function onRolePresetChange(roleId) {
        const sel = document.getElementById('editRoleId');
        const opt = sel.options[sel.selectedIndex];
        if (!opt) return;

        try {
            const privIds = JSON.parse(opt.getAttribute('data-privileges') || '[]');
            const checkboxes = document.querySelectorAll('.priv-checkbox');
            checkboxes.forEach(cb => {
                const privId = parseInt(cb.value);
                cb.checked = privIds.includes(privId);
            });
        } catch(e) {}
    }

    window.addEventListener('click', function(e) {
        const m1 = document.getElementById('modalCreateAdmin');
        const m2 = document.getElementById('modalEditAccess');
        if (e.target === m1) closeCreateAdminModal();
        if (e.target === m2) closeEditAccessModal();
    });
</script>
@endsection

