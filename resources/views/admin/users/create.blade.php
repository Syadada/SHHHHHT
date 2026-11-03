@extends('layouts.admin')

@section('title', 'Buat Akun Pengguna Baru — Admin Panel')

@section('content')
<div class="ticket-stack-container">

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.users.index') }}"><i class="fi fi-rr-users"></i> User</a>
        <span class="separator">/</span>
        <span class="current">Buat Akun Baru</span>
    </nav>

    <!-- Top Action Bar -->
    <div class="page-top-bar">
        <div>
            <h1 class="page-top-title">
                <i class="fi fi-rr-user-add" style="color: #0b57d0;"></i>
                <span>Buat Akun Pengguna Baru</span>
            </h1>
            <p class="page-top-subtitle">
                Isi data kredensial akun pengguna atau pejabat instansi. Setelah klik simpan, Anda akan langsung dialihkan ke halaman konfigurasi hak akses.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn-pill-secondary">
                <i class="fi fi-rr-arrow-left"></i>
                <span>Kembali ke User</span>
            </a>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="form-alert-danger" style="margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulir Pendaftaran Akun -->
    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-id-badge"></i>
                    <span>Informasi Kredensial & Profil Akun</span>
                </h2>
            </div>
            <span class="role-badge-super" style="background: #eef2ff; color: #4338ca; border-color: #c7d2fe;">Langkah 1 dari 2</span>
        </div>

        <div class="admin-card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="admin-form-grid-3">
                    <div class="admin-form-group" style="grid-column: span 2;">
                        <label class="admin-form-label">
                            Nama Instansi / Perangkat Daerah <span class="admin-form-req">*</span>
                        </label>
                        <input type="text" name="nama_instansi" class="form-input" placeholder="Contoh: Dinas Sosial / Bappeda" required value="{{ old('nama_instansi') }}">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">
                            Peran / Tingkat Akun Awal <span class="admin-form-req">*</span>
                        </label>
                        <select name="role_id" class="form-input" required>
                            @foreach(($roles ?? []) as $r)
                                <option value="{{ $r->id }}" {{ (old('role_id') == $r->id || $r->slug === 'eselon2') ? 'selected' : '' }}>
                                    {{ $r->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">
                            Username Login <span class="admin-form-req">*</span>
                        </label>
                        <input type="text" name="username" class="form-input" placeholder="Contoh: dinsos_provinsi" required value="{{ old('username') }}" style="font-family: var(--font-mono);">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">
                            Alamat Email Resmi Instansi <span class="admin-form-req">*</span>
                        </label>
                        <input type="email" name="email" class="form-input" placeholder="Contoh: dinsos@pemda.go.id" required value="{{ old('email') }}">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">
                            Kata Sandi (Password) <span class="admin-form-req">*</span>
                        </label>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 4 karakter" required>
                    </div>
                </div>

                <!-- Info Box Alur Hak Akses -->
                <div style="margin: 24px 0 20px; padding: 16px 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; display: flex; align-items: flex-start; gap: 14px;">
                    <div style="font-size: 24px; line-height: 1; color: #16a34a;">🛡️</div>
                    <div>
                        <div style="font-weight: 700; color: #166534; font-size: 13.5px; margin-bottom: 3px;">
                            Alur Otomatis Edit Hak Akses:
                        </div>
                        <div style="font-size: 13px; color: #15803d; line-height: 1.5;">
                            Saat Anda menekan tombol <strong>"Buat Akun & Lanjut Edit Hak Akses"</strong> di bawah, akun akan didaftarkan dan Anda langsung diarahkan ke halaman khusus untuk mengatur hak akses akun (misal: akun Pejabat Eselon 2 hanya boleh memperbarui proses tiket).
                        </div>
                    </div>
                </div>

                <div class="admin-form-actions" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <a href="{{ route('admin.users.index') }}" class="btn-pill-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-pill-primary" style="padding: 10px 24px; font-size: 13.5px; display: inline-flex; align-items: center; gap: 8px;">
                        <span>Buat Akun & Lanjut Edit Hak Akses</span>
                        <i class="fi fi-rr-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
