@extends('layouts.admin')

@section('title', 'Profil Administrator — Admin Panel')

@section('content')
<div class="ticket-stack-container">

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.users.index') }}"><i class="fi fi-rr-users"></i> User</a>
        <span class="separator">/</span>
        <span class="current">Profile</span>
    </nav>

    <!-- Top Action Bar -->
    <div class="page-top-bar">
        <div>
            <h1 class="page-top-title">
                <i class="fi fi-rr-user" style="color: #0b57d0;"></i>
                <span>Profil Administrator</span>
            </h1>
            <p class="page-top-subtitle">
                Kelola identitas akun Anda, preferensi keamanan, dan pantau jejak perangkat laptop yang sedang digunakan.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn-pill-secondary">
                <i class="fi fi-rr-apps"></i>
                <span>Kembali ke Tiket</span>
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

    @if(isset($errors) && $errors->any())
        <div class="form-alert-danger" style="margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Identity Header Card -->
    <div class="admin-card-section" style="margin-bottom: 24px;">
        <div class="admin-card-body" style="padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 18px;">
                    <div style="width: 64px; height: 64px; border-radius: 18px; background: linear-gradient(135deg, #0b57d0, #3b82f6); color: #fff; font-size: 26px; font-weight: 800; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(11,87,208,0.3);">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size: 20px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 10px;">
                            <span>{{ $user->name }}</span>
                            <span style="font-family: var(--font-mono); font-size: 13px; font-weight: 600; color: #0b57d0; background: #e0f2fe; padding: 2px 8px; border-radius: 6px;">
                                @ {{ $user->username }}
                            </span>
                        </div>
                        <div style="font-size: 13.5px; color: var(--text-secondary); margin-top: 4px;">
                            {{ $user->nama_instansi ?: 'Dinas Komunikasi, Informatika, Statistik dan Persandian' }} • {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Peran & Wewenang</div>
                    <span class="role-badge-super" style="font-size: 13px; padding: 6px 14px; background: #fef3c7; color: #b45309; border-color: #fde68a;">
                        <i class="fi fi-rr-shield-check"></i>
                        {{ $user->roleRelation ? $user->roleRelation->name : ($user->role === 'superadmin' ? 'Super Administrator' : 'Administrator') }}
                    </span>
                    @if($user->roleRelation && $user->roleRelation->privileges)
                        <div style="display: flex; gap: 4px; flex-wrap: wrap; justify-content: flex-end; max-width: 380px; margin-top: 8px;">
                            @foreach($user->roleRelation->privileges as $priv)
                                <span style="font-size: 10px; font-weight: 700; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 2px 6px; border-radius: 4px;">
                                    ✓ {{ $priv->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout: Keamanan & Edit Profile -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px; margin-bottom: 24px;">

        <!-- Kolom Kiri: Informasi Perangkat & Sesi Keamanan -->
        <div class="admin-card-section">
            <div class="admin-card-header">
                <div class="admin-card-header-left">
                    <h2 class="admin-card-header-title">
                        <i class="fi fi-rr-laptop"></i>
                        <span>Jejak Perangkat & Sesi Keamanan</span>
                    </h2>
                </div>
                <span class="role-badge-super" style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd;">Sesi Aktif</span>
            </div>

            <div class="admin-card-body">
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div style="padding: 12px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">ID Perangkat Laptop</div>
                        <div style="font-size: 14px; font-family: var(--font-mono); font-weight: 700; color: #0b57d0; margin-top: 4px;">
                            {{ $user->last_login_device ?: 'WIN-ADM-LAPTOP01' }}
                        </div>
                    </div>

                    <div style="padding: 12px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Alamat IP Login Saat Ini</div>
                        <div style="font-size: 14px; font-family: var(--font-mono); font-weight: 700; color: var(--text-primary); margin-top: 4px;">
                            {{ request()->ip() }} (127.0.0.1 Jaringan Lokal)
                        </div>
                    </div>

                    <div style="padding: 12px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Kebijakan Masa Aktif Sesi (Timeout)</div>
                        <div style="font-size: 14px; font-weight: 700; color: #166534; margin-top: 4px; display: flex; align-items: center; gap: 6px;">
                            <i class="fi fi-rr-clock"></i> 5 Menit Idle Logout Otomatis
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                            Sistem otomatis mengamankan sesi dan menampilkan peringatan jika tidak ada interaksi selama 5 menit.
                        </div>
                    </div>

                    <div style="padding: 12px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Waktu Login Terakhir</div>
                        <div style="font-size: 13.5px; font-weight: 600; color: var(--text-primary); margin-top: 4px;">
                            {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d F Y, H:i') . ' WIB' : 'Sesi Ini' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Edit Profil & Kata Sandi -->
        <div class="admin-card-section">
            <div class="admin-card-header">
                <div class="admin-card-header-left">
                    <h2 class="admin-card-header-title">
                        <i class="fi fi-rr-edit"></i>
                        <span>Perbarui Informasi & Kata Sandi</span>
                    </h2>
                </div>
            </div>

            <div class="admin-card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf

                    <div class="admin-form-group" style="margin-bottom: 14px;">
                        <label class="admin-form-label">Nama Lengkap PIC</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="admin-form-group" style="margin-bottom: 14px;">
                        <label class="admin-form-label">Email Resmi</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="admin-form-group" style="margin-bottom: 14px;">
                        <label class="admin-form-label">Nama Instansi / Bidang</label>
                        <input type="text" name="nama_instansi" class="form-input" value="{{ old('nama_instansi', $user->nama_instansi) }}">
                    </div>

                    <div class="admin-form-group" style="margin-bottom: 14px;">
                        <label class="admin-form-label">Nomor Kontak / WhatsApp</label>
                        <input type="text" name="nomor_kontak" class="form-input" value="{{ old('nomor_kontak', $user->nomor_kontak) }}">
                    </div>

                    <div style="margin: 18px 0 12px; border-top: 1px solid #e2e8f0; padding-top: 14px;">
                        <div style="font-size: 12.5px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                            Ganti Kata Sandi (Kosongkan jika tidak ingin mengubah)
                        </div>

                        <div class="admin-form-group" style="margin-bottom: 12px;">
                            <label class="admin-form-label" style="font-size: 12px;">Kata Sandi Baru</label>
                            <input type="password" name="password" class="form-input" placeholder="Minimal 4 karakter">
                        </div>

                        <div class="admin-form-group" style="margin-bottom: 14px;">
                            <label class="admin-form-label" style="font-size: 12px;">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi kata sandi baru">
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <button type="submit" class="btn-pill-primary" style="padding: 9px 24px; font-size: 13.5px; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fi fi-rr-disk"></i>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Tabel Riwayat Aktivitas & Sesi Terakhir Akun Ini -->
    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-time-past"></i>
                    <span>Log Aktivitas Terbaru Akun Ini</span>
                </h2>
            </div>
            <a href="{{ route('admin.logs.index') }}" class="btn-pill-outline" style="font-size: 12px; text-decoration: none;">
                Buka Semua Log
            </a>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 150px;">Waktu</th>
                        <th style="width: 160px;">Event / Aksi</th>
                        <th>Keterangan Aktivitas</th>
                        <th style="width: 140px;">IP / Perangkat</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($recentLogs ?? []) as $log)
                        <tr>
                            <td>
                                <div style="font-size: 12px; font-weight: 600; color: var(--text-primary);">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}
                                </div>
                                <div style="font-size: 11px; color: var(--text-muted); font-family: var(--font-mono);">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB
                                </div>
                            </td>
                            <td>
                                <span style="font-family: var(--font-mono); font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px; color: var(--text-primary); line-height: 1.4;">
                                    {{ $log->description }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12px; font-family: var(--font-mono); color: var(--text-secondary);">
                                    {{ $log->ip_address ?: '127.0.0.1' }}
                                </div>
                                <div style="font-size: 10px; color: var(--text-muted);">
                                    {{ $log->device_identifier }}
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span style="font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 9999px; background: #dcfce7; color: #166534; text-transform: uppercase;">
                                    {{ $log->status ?: 'SUCCESS' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                Belum ada catatan aktivitas untuk akun Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
