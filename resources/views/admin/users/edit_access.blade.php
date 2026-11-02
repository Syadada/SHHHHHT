@extends('layouts.admin')

@section('title', 'Edit Hak Akses: ' . $user->username . ' — Admin Panel')

@section('content')
<div class="ticket-stack-container">

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.users.index') }}"><i class="fi fi-rr-users"></i> User</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.users.show', $user->id) }}">{{ $user->username }}</a>
        <span class="separator">/</span>
        <span class="current">Edit Hak Akses</span>
    </nav>

    <!-- Top Action Bar -->
    <div class="page-top-bar">
        <div>
            <h1 class="page-top-title">
                <i class="fi fi-rr-shield-check" style="color: #f59e0b;"></i>
                <span>Edit Hak Akses & Wewenang Akun</span>
            </h1>
            <p class="page-top-subtitle">
                Atur peran (role) dan kustomisasi wewenang granular akun <strong>{{ $user->username }}</strong> ({{ $user->nama_instansi ?: $user->name }}).
            </p>
        </div>
        <div>
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-pill-secondary">
                <i class="fi fi-rr-arrow-left"></i>
                <span>Kembali ke Detail Akun</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="form-alert-success" style="margin-bottom: 20px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Kartu Ringkasan Akun Pengguna -->
    <div class="admin-card-section" style="margin-bottom: 24px;">
        <div class="admin-card-body" style="padding: 20px 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #0b57d0, #1d4ed8); color: #fff; font-weight: 800; font-size: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(11,87,208,0.25);">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size: 18px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                            <span>{{ $user->name }}</span>
                            <span style="font-family: var(--font-mono); font-size: 13px; font-weight: 600; color: #0b57d0; background: #e0f2fe; padding: 2px 8px; border-radius: 6px;">
                                @ {{ $user->username }}
                            </span>
                        </div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">
                            {{ $user->nama_instansi ?: 'Pemerintah Daerah' }} • {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Peran Saat Ini</div>
                    <span class="role-badge-super" style="font-size: 13px; padding: 6px 14px; background: #fef3c7; color: #b45309; border-color: #fde68a;">
                        <i class="fi fi-rr-shield-check"></i>
                        {{ $user->roleRelation ? $user->roleRelation->name : ($user->role === 'superadmin' ? 'Super Administrator' : 'Perangkat Daerah') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulir Edit Hak Akses & Privileges -->
    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-key"></i>
                    <span>Pengaturan Peran & Izin Akses Granular</span>
                </h2>
            </div>
            <span class="role-badge-super" style="background: #f0fdf4; color: #166534; border-color: #bbf7d0;">Filament Access Control</span>
        </div>

        <div class="admin-card-body">
            <form action="{{ route('admin.users.update-access', $user->id) }}" method="POST">
                @csrf

                <!-- 1. Pilihan Preset Peran (Role Selector) -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                        Pilih Preset Peran (Role)
                    </label>
                    <select name="role_id" id="selectRolePreset" class="form-input" style="height: 44px; width: 100%; font-size: 14px;" onchange="applyRolePreset(this)">
                        @foreach(($roles ?? []) as $r)
                            <option value="{{ $r->id }}" 
                                    data-slug="{{ $r->slug }}" 
                                    data-privileges='@json($r->privileges->pluck("id"))'
                                    {{ $user->role_id == $r->id ? 'selected' : '' }}>
                                {{ $r->name }} — {{ $r->deskripsi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Info Box Bantuan Skenario -->
                <div style="margin-bottom: 24px; padding: 14px 18px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; display: flex; align-items: center; gap: 12px;">
                    <div style="font-size: 20px; color: #1d4ed8;">💡</div>
                    <div style="font-size: 13px; color: #1e40af; line-height: 1.5;">
                        <strong>Contoh Skenario:</strong> Untuk membuat akun <em>Pejabat Eselon 2 yang hanya bisa update proses tiket</em>, pastikan hanya opsi <strong>"Manipulasi Tahapan Proses"</strong> di modul Tiket yang dicentang.
                    </div>
                </div>

                <!-- 2. Checklist Hak Akses Granular Per Modul -->
                <div style="margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                        <h3 style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin: 0;">
                            Daftar Hak Akses (Privileges) yang Diizinkan:
                        </h3>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" onclick="selectAllPrivileges(true)" class="btn-pill-outline" style="font-size: 11px; padding: 4px 10px;">Pilih Semua</button>
                            <button type="button" onclick="selectAllPrivileges(false)" class="btn-pill-outline" style="font-size: 11px; padding: 4px 10px;">Kosongkan</button>
                        </div>
                    </div>

                    @php
                        $userPrivilegeIds = $user->roleRelation ? $user->roleRelation->privileges->pluck('id')->toArray() : [];
                    @endphp

                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        @foreach(($privileges ?? []) as $moduleName => $privList)
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px;">
                                <div style="font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #0b57d0;"></span>
                                    <span>MODUL {{ $moduleName }}</span>
                                </div>

                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 12px;">
                                    @foreach($privList as $priv)
                                        <label style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 14px; background: #fff; border: 1px solid #cbd5e1; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;">
                                            <input type="checkbox" 
                                                   name="privilege_ids[]" 
                                                   value="{{ $priv->id }}" 
                                                   class="priv-chk" 
                                                   data-slug="{{ $priv->slug }}"
                                                   {{ in_array($priv->id, $userPrivilegeIds) ? 'checked' : '' }}
                                                   style="width: 18px; height: 18px; margin-top: 2px; accent-color: #0b57d0;">
                                            <div>
                                                <div style="font-size: 13.5px; font-weight: 700; color: #1e293b;">
                                                    {{ $priv->name }}
                                                </div>
                                                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px; line-height: 1.4;">
                                                    {{ $priv->deskripsi }}
                                                </div>
                                                <div style="font-size: 10.5px; font-family: var(--font-mono); color: #94a3b8; margin-top: 4px;">
                                                    slug: {{ $priv->slug }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="admin-form-actions" style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn-pill-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-pill-primary" style="padding: 10px 28px; font-size: 13.5px; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fi fi-rr-disk"></i>
                        <span>Simpan Hak Akses Akun</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function applyRolePreset(selectEl) {
        const option = selectEl.options[selectEl.selectedIndex];
        const privJson = option.getAttribute('data-privileges');
        if (!privJson) return;

        try {
            const privIds = JSON.parse(privJson);
            document.querySelectorAll('.priv-chk').forEach(chk => {
                chk.checked = privIds.includes(parseInt(chk.value));
            });
        } catch (e) {
            console.error('Error parsing role privileges JSON', e);
        }
    }

    function selectAllPrivileges(check) {
        document.querySelectorAll('.priv-chk').forEach(chk => {
            chk.checked = check;
        });
    }
</script>
@endsection

