@extends('layouts.admin')

@section('title', 'Detail Akun Pengguna: ' . $user->username . ' — Admin Panel')

@section('content')
<div class="ticket-stack-container">

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.users.index') }}"><i class="fi fi-rr-users"></i> Manajemen Pengguna</a>
        <span class="separator">/</span>
        <span class="current">{{ $user->username }}</span>
    </nav>

    <!-- Top Action Bar -->
    <div class="page-top-bar">
        <div>
            <h1 class="page-top-title">
                <i class="fi fi-rr-user"></i>
                <span>Detail Akun Pengguna</span>
            </h1>
            
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.users.edit-access', $user->id) }}" class="btn-pill-primary" style="background: #f59e0b; border-color: #d97706; display: inline-flex; align-items: center; gap: 6px;" title="Atur hak akses & wewenang akun ini">
                <i class="fi fi-rr-shield-check"></i>
                <span>Edit Hak Akses Akun</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-pill-secondary">
                <i class="fi fi-rr-arrow-left"></i>
                <span>Kembali ke User</span>
            </a>
        </div>
    </div>

    <!-- Profil Card Strip -->
    <div class="admin-card-section">
        <div class="admin-card-body">
            <div class="user-modal-profile-strip">
                <div class="user-modal-strip-left">
                    <div class="user-modal-avatar">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </div>
                    <div>
                        <div class="user-modal-strip-uname">{{ $user->username }}</div>
                        <div class="user-modal-strip-instansi">{{ $user->nama_instansi ?: 'Pemerintah Daerah' }}</div>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                    <div>
                        @if($user->isSuperAdmin())
                            <span class="role-badge-super">
                                <i class="fi fi-rr-shield-check"></i> {{ $user->roleRelation ? $user->roleRelation->name : 'Walidata' }}
                            </span>
                        @else
                            <span class="role-badge-opd">
                                <i class="fi fi-rr-building"></i> {{ $user->roleRelation ? $user->roleRelation->name : 'Perangkat Daerah (OPD)' }}
                            </span>
                        @endif
                    </div>
                    @if($user->roleRelation && $user->roleRelation->privileges && $user->roleRelation->privileges->count() > 0)
                        <div style="display: flex; gap: 4px; flex-wrap: wrap; justify-content: flex-end; max-width: 380px;">
                            @foreach($user->roleRelation->privileges as $priv)
                                <span style="font-size: 10px; font-weight: 700; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 2px 7px; border-radius: 6px;">
                                    ✓ {{ $priv->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bagian 1: Informasi Profil & Kontak -->
            <div class="user-modal-section-title">
                <i class="fi fi-rr-id-badge"></i>
                <span>Informasi Profil & Kontak</span>
            </div>
            <div class="user-modal-grid-2">
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">ID Pengguna</div>
                    <div class="user-modal-field-value">#{{ $user->id }}</div>
                </div>
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">
                        {{ $user->role === 'perangkat_daerah' ? 'Nama Instansi Pemohon' : 'Nama Pengguna' }}
                    </div>
                    <div class="user-modal-field-value">{{ $user->nama_instansi ?: $user->name }}</div>
                </div>
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">Alamat Email Resmi</div>
                    <div class="user-modal-field-value">{{ $user->email }}</div>
                </div>
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">Username Login</div>
                    <div class="user-modal-field-value" style="font-family: var(--font-mono); color: #0b57d0;">{{ $user->username }}</div>
                </div>
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">Nama Instansi / Satker</div>
                    <div class="user-modal-field-value">{{ $user->nama_instansi ?: 'Pemerintah Daerah' }}</div>
                </div>
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">Kata Sandi Akun</div>
                    <div class="user-modal-field-value">
                        @if(auth()->user()->isSuperAdmin())
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span id="pwd-detail-val" style="font-family: var(--font-mono); font-size: 13px; font-weight: 700; color: #0f172a;">••••••••</span>
                                <button type="button" 
                                        class="btn-pill-outline" 
                                        style="padding: 2px 8px; font-size: 11px; border-color: #cbd5e1; color: #475569;" 
                                        onclick="toggleDetailPwd('pwd-detail-val', '{{ addslashes($user->plain_password) }}', this)" 
                                        title="Lihat / Sembunyikan Kata Sandi">
                                    <i class="fi fi-rr-eye"></i>
                                </button>
                                <button type="button" 
                                        class="btn-pill-outline" 
                                        style="padding: 2px 8px; font-size: 11px; color: #b91c1c; border-color: #fca5a5; background: #fff5f5;" 
                                        onclick="promptResetDetail('{{ $user->id }}', '{{ $user->username }}')" 
                                        title="Reset Kata Sandi">
                                    <i class="fi fi-rr-key"></i> Reset
                                </button>
                            </div>
                        @else
                            <span style="color: #94a3b8; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fi fi-rr-lock"></i> Terkunci (Hanya Walidata)
                            </span>
                        @endif
                    </div>
                </div>
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">Tanggal Akun Diterbitkan</div>
                    <div class="user-modal-field-value">
                        {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') . ' WIB' : '-' }}
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Jejak Perangkat & Keamanan -->
            <div class="user-modal-section-title">
                <i class="fi fi-rr-laptop"></i>
                <span>Jejak Digital Perangkat & Keamanan Login</span>
            </div>
            <div class="user-modal-grid-2">
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">Nomor Laptop / Device ID Terakhir</div>
                    <div class="user-modal-field-value">
                        @if($user->last_device_id)
                            <span class="device-badge">
                                <i class="fi fi-rr-laptop"></i>
                                {{ $user->last_device_id }}
                            </span>
                        @else
                            <span class="device-badge">
                                <i class="fi fi-rr-minus"></i>
                                Belum Pernah Login
                            </span>
                        @endif
                    </div>
                </div>
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">Alamat IP Terakhir</div>
                    <div class="user-modal-field-value">
                        <span class="table-cell-ip">{{ $user->last_login_ip ?: '-' }}</span>
                    </div>
                </div>
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">Waktu Login Terakhir</div>
                    <div class="user-modal-field-value">
                        {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d M Y, H:i:s') . ' WIB' : 'Belum pernah login' }}
                    </div>
                </div>
                <div class="user-modal-card-item">
                    <div class="user-modal-field-label">Status Akun</div>
                    <div class="user-modal-field-value">
                        <span class="role-badge-opd">
                            <i class="fi fi-rr-check-circle"></i> Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-modal-footer">
            <a href="{{ route('admin.users.index') }}" class="btn-pill-secondary">
                <i class="fi fi-rr-arrow-left"></i>
                <span>Kembali</span>
            </a>
            @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->username }} ({{ $user->nama_instansi }})? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-pill-danger">
                        <i class="fi fi-rr-trash"></i>
                        <span>Hapus Akun Pengguna</span>
                    </button>
                </form>
            @else
                <span class="role-badge-super">Akun Anda Sendiri (Aktif)</span>
            @endif
        </div>
    </div>

    <!-- Riwayat Tiket Permohonan Terkait Akun Ini -->
    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-ticket"></i>
                    <span>Riwayat Tiket Permohonan dari Instansi Ini</span>
                </h2>
            </div>
            
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="table-col-id">No Tiket</th>
                        <th>Perihal / Surat Edaran</th>
                        <th class="table-col-nowrap">Status Tiket</th>
                        <th class="table-col-nowrap">Tanggal Pengajuan</th>
                        <th class="table-col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($userTikets ?? []) as $t)
                        <tr>
                            <td class="table-col-id">
                                <a href="{{ route('admin.tiket.show', $t->id) }}" class="user-subject-link" title="Buka Detail Tiket">
                                    <span class="user-subject-uname">#{{ $t->nomor_tiket }}</span>
                                </a>
                            </td>
                            <td>
                                <div class="user-title-bold">{{ $t->perihal ?: 'Permohonan Bagi Pakai Data DTSEN' }}</div>
                                <div class="user-sub-muted">Dasar Surat: {{ $t->no_surat_edaran ?: '-' }}</div>
                            </td>
                            <td class="table-col-nowrap">
                                @if($t->step5_done)
                                    <span class="role-badge-opd"><i class="fi fi-rr-check"></i> Selesai (100%)</span>
                                @elseif($t->status === 'PERLU_PERBAIKAN')
                                    <span class="role-badge-super"><i class="fi fi-rr-exclamation"></i> Perlu Perbaikan</span>
                                @else
                                    <span class="role-badge-opd"><i class="fi fi-rr-time-forward"></i> {{ $t->current_status_label }}</span>
                                @endif
                            </td>
                            <td class="table-col-nowrap">
                                <div class="user-title-name">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</div>
                                <div class="user-sub-muted">{{ \Carbon\Carbon::parse($t->created_at)->format('H:i') }} WIB</div>
                            </td>
                            <td class="table-col-action">
                                <a href="{{ route('admin.tiket.show', $t->id) }}" class="btn-pill-secondary" title="Lihat Progress Tiket">
                                    <i class="fi fi-rr-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty-box">
                                <div class="table-empty-icon">
                                    <i class="fi fi-rr-ticket"></i>
                                </div>
                                <div class="table-empty-text">Belum ada riwayat tiket permohonan dari akun instansi ini.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Audit Login Laptop Akun Ini -->
    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-laptop"></i>
                    <span>Jejak Audit Aktivitas Login Laptop Akun Ini</span>
                </h2>
            </div>
            
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="table-col-id">ID Log</th>
                        <th class="table-col-nowrap">Waktu & Tanggal</th>
                        <th class="table-col-nowrap">Nomor Laptop / Device ID</th>
                        <th class="table-col-nowrap">Alamat IP</th>
                        <th>Keterangan Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($userLogs ?? []) as $log)
                        <tr>
                            <td class="table-col-id">#{{ $log->id }}</td>
                            <td class="table-col-nowrap">
                                <div class="user-title-name">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                <div class="user-sub-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</div>
                            </td>
                            <td class="table-col-nowrap">
                                @if($log->device_id)
                                    <span class="device-badge">
                                        <i class="fi fi-rr-laptop"></i>
                                        {{ $log->device_id }}
                                    </span>
                                @else
                                    <span class="device-badge">
                                        <i class="fi fi-rr-shield-question"></i>
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="table-col-nowrap">
                                <span class="table-cell-ip">{{ $log->ip_address ?: '-' }}</span>
                            </td>
                            <td>
                                <div class="user-title-name">{{ $log->description }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty-box">
                                <div class="table-empty-icon">
                                    <i class="fi fi-rr-laptop"></i>
                                </div>
                                <div class="table-empty-text">Belum ada riwayat jejak audit login untuk pengguna ini.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Tersembunyi Reset Password Superadmin -->
    <form id="formSuperadminResetDetail" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="password" id="inputResetDetailNewPassword">
    </form>

    <script>
    function toggleDetailPwd(elId, plainPwd, btn) {
        const el = document.getElementById(elId);
        if (!el) return;
        if (el.innerText === '••••••••') {
            el.innerText = plainPwd;
            btn.innerHTML = '<i class="fi fi-rr-eye-crossed"></i>';
        } else {
            el.innerText = '••••••••';
            btn.innerHTML = '<i class="fi fi-rr-eye"></i>';
        }
    }

    function promptResetDetail(userId, username) {
        const newPwd = prompt(`Masukkan kata sandi baru untuk akun ${username}:`, 'opd123');
        if (newPwd && newPwd.trim() !== '') {
            const form = document.getElementById('formSuperadminResetDetail');
            form.action = `{{ url('admin/users') }}/${userId}/reset-password`;
            document.getElementById('inputResetDetailNewPassword').value = newPwd.trim();
            form.submit();
        }
    }
    </script>

</div>
@endsection
