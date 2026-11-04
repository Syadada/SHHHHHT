@extends('layouts.admin')

@section('content')
<div class="page-top-bar" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 24px;">
    <div>
        <h1 class="page-top-title" style="margin-bottom: 4px;">
            <i class="fi fi-rr-users-alt"></i>
            <span>User Management</span>
        </h1>
        <p class="page-top-subtitle">
            Kelola data seluruh akun pengguna, wewenang hak akses, dan status login perangkat terdaftar.
        </p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="{{ route('admin.users.create') }}" class="btn-pill-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 13px; text-decoration: none;">
            <i class="fi fi-rr-user-add"></i>
            <span>Buat Akun Baru</span>
        </a>
    </div>
</div>

<!-- ============================================================== -->
<!-- DAFTAR AKUN PENGGUNA TERDAFTAR (USER MANAGEMENT)              -->
<!-- ============================================================== -->
<div id="panel-users" style="display: block;">
    @if(session('success'))
        <div style="background: #f0fdf4; color: #166534; border: 1.5px solid #bbf7d0; padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; display: flex; align-items: center; gap: 10px;">
            <i class="fi fi-rr-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Banner Notifikasi Permintaan Reset Password (Walidata) -->
    @if(isset($pendingResetRequests) && $pendingResetRequests->count() > 0 && auth()->user()->isSuperAdmin())
        <div style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fi fi-rr-bell"></i>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 700; color: #92400e;">
                            {{ $pendingResetRequests->count() }} Permintaan Reset Password Masuk
                        </div>
                        <div style="font-size: 12.5px; color: #b45309;">
                            Pengguna salah memasukkan password 3 kali dan meminta bantuan reset kata sandi ke Walidata.
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @foreach($pendingResetRequests as $req)
                        <form action="{{ route('admin.reset-requests.approve', $req->id) }}" method="POST" onsubmit="return confirm('Reset kata sandi akun {{ $req->username }} menjadi: opd123 ?');" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn-pill-primary" style="background: #b45309; border-color: #b45309; padding: 6px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fi fi-rr-refresh"></i> Reset <strong>{{ $req->username }}</strong> ({{ $req->nama_instansi }})
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Ringkasan Statistik Pengguna -->
    <div class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-label">Total Akun Terdaftar</div>
            <div class="admin-stat-val admin-stat-val-blue">{{ number_format($totalUsers, 0, ',', '.') }}</div>
            <div class="admin-stat-sub">Seluruh pengguna aktif di sistem</div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-label">Akun Perangkat Daerah</div>
            <div class="admin-stat-val admin-stat-val-green">{{ number_format($totalOpd, 0, ',', '.') }}</div>
            <div class="admin-stat-sub">Dinas, Badan, dan Satker pemohon</div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-label">Super Administrator</div>
            <div class="admin-stat-val admin-stat-val-purple">{{ number_format($totalAdmin, 0, ',', '.') }}</div>
            <div class="admin-stat-sub">Pengelola sistem kendali penuh</div>
        </div>
    </div>

    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-users-alt"></i>
                    <span>Daftar Akun Pengguna Terdaftar</span>
                </h2>
            </div>
            <div class="admin-card-header-sub">
                Klik pada <strong>Nama Instansi</strong> atau <strong>Username</strong> untuk melihat informasi akun, kontak, dan jejak perangkat secara detail.
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Instansi / Perangkat Daerah</th>
                        <th>Akun Pengguna (Username)</th>
                        <th class="table-col-nowrap">Role / Hak Akses</th>
                        <th class="table-col-nowrap">Kata Sandi</th>
                        <th class="table-col-nowrap">Terakhir Login</th>
                        <th class="table-col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <!-- 1. Nama Instansi & PIC (Klik menuju page baru info lengkap) -->
                            <td>
                                <div>
                                    <a href="{{ route('admin.users.show', $u->id) }}" class="instansi-title-link" title="Klik untuk membuka informasi lengkap akun ini">
                                        <span>{{ $u->nama_instansi ?: 'Pemerintah Daerah' }}</span>
                                    </a>
                                </div>
                                <div class="user-sub-muted" style="margin-top: 3px;">
                                    @if($u->role === 'perangkat_daerah')
                                        <span style="color: #64748b;">Akun Resmi: {{ $u->email }}</span>
                                    @else
                                        <span>{{ $u->name }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- 2. Username (Clickable Link Biru ke Halaman Detail) -->
                            <td>
                                <a href="{{ route('admin.users.show', $u->id) }}" class="user-subject-link" title="Buka Halaman Lengkap Akun {{ $u->username }}">
                                    <span class="user-subject-uname">{{ $u->username }}</span>
                                </a>
                                <span class="user-subject-subtitle">{{ $u->email }}</span>
                            </td>

                            <!-- 3. Role / Hak Akses -->
                            <td class="table-col-nowrap">
                                @if($u->role === 'superadmin')
                                    <span class="role-badge-super">
                                        <i class="fi fi-rr-shield-check"></i>
                                        {{ $u->roleRelation ? $u->roleRelation->name : 'Super Administrator' }}
                                    </span>
                                @else
                                    <span class="role-badge-opd">
                                        <i class="fi fi-rr-building"></i>
                                        {{ $u->roleRelation ? $u->roleRelation->name : 'Perangkat Daerah' }}
                                    </span>
                                @endif
                            </td>

                            <!-- 4. Kata Sandi (Hanya Walidata yang berhak melihat & mereset) -->
                            <td class="table-col-nowrap">
                                @if(auth()->user()->isSuperAdmin())
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span id="pwd-val-{{ $u->id }}" style="font-family: var(--font-mono); font-size: 12px; font-weight: 700; color: #0f172a; min-width: 70px;">••••••••</span>
                                        <button type="button" 
                                                class="btn-pill-outline" 
                                                style="padding: 2px 7px; font-size: 11px; border-color: #cbd5e1; color: #475569;" 
                                                onclick="togglePasswordVisibility('pwd-val-{{ $u->id }}', '{{ addslashes($u->plain_password) }}', this)" 
                                                title="Lihat / Sembunyikan Kata Sandi">
                                            <i class="fi fi-rr-eye"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn-pill-outline" 
                                                style="padding: 2px 7px; font-size: 11px; color: #b91c1c; border-color: #fca5a5; background: #fff5f5;" 
                                                onclick="promptResetPassword('{{ $u->id }}', '{{ $u->username }}')" 
                                                title="Reset Kata Sandi Akun Ini">
                                            <i class="fi fi-rr-key"></i>
                                        </button>
                                    </div>
                                @else
                                    <div style="display: flex; align-items: center; gap: 4px; color: #94a3b8; font-size: 11.5px;">
                                        <i class="fi fi-rr-lock"></i>
                                        <span>Terkunci</span>
                                    </div>
                                @endif
                            </td>

                            <!-- 5. Terakhir Login -->
                            <td class="table-col-nowrap">
                                @if($u->last_login_at)
                                    <div class="user-title-name">{{ \Carbon\Carbon::parse($u->last_login_at)->format('d M Y') }}</div>
                                    <div class="user-sub-muted">{{ \Carbon\Carbon::parse($u->last_login_at)->format('H:i') }} WIB</div>
                                @else
                                    <span class="user-sub-muted">Belum Pernah Login</span>
                                @endif
                            </td>

                            <!-- 6. Aksi (Edit Akses, Hapus) -->
                            <td class="table-col-action">
                                <div style="display: inline-flex; align-items: center; gap: 6px; justify-content: flex-end;">
                                    <!-- Tombol Edit Hak Akses -->
                                    <a href="{{ route('admin.users.edit-access', $u->id) }}" class="btn-pill-outline" style="padding: 4px 8px; font-size: 11px; text-decoration: none; color: #b45309; border-color: #fde68a; background: #fffbeb; display: inline-flex; align-items: center; gap: 3px;" title="Edit Hak Akses Akun Ini">
                                        <i class="fi fi-rr-shield-check" style="font-size: 11px;"></i>
                                        <span>Akses</span>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->username }} ({{ $u->nama_instansi }})? Tindakan ini tidak dapat dibatalkan.');" style="margin: 0; display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="table-btn-delete" style="padding: 4px 8px;" title="Hapus Akun Pengguna">
                                                <i class="fi fi-rr-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty-box">
                                <div class="table-empty-icon">
                                    <i class="fi fi-rr-users-slash"></i>
                                </div>
                                <div class="table-empty-text">Belum ada akun pengguna terdaftar.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="admin-card-pagination">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@if(isset($riwayatBagiDatas) && $riwayatBagiDatas->count() > 0)
<!-- DAFTAR RIWAYAT BAGI DATA / BAST RESMI TERBIT -->
<div class="admin-card-section" style="margin-top: 28px;">
    <div class="admin-card-header">
        <div class="admin-card-header-left">
            <h2 class="admin-card-header-title">
                <i class="fi fi-rr-document-signed"></i>
                <span>Daftar Riwayat Bagi Data DTSEN Resmi Terbit (BAST)</span>
            </h2>
        </div>
        <div class="admin-card-header-sub">
            Klik pada <strong>Nama Perangkat Daerah</strong> atau <strong>No. Surat Edaran</strong> untuk membuka rincian berita acara, petugas hadir, dan laptop.
        </div>
    </div>

    <div class="table-responsive" style="overflow-x: hidden;">
        <table class="data-table" style="width: 100%; table-layout: auto;">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Perangkat Daerah</th>
                    <th style="min-width: 200px;">No. Surat Edaran & BAST</th>
                    <th style="width: 160px;">Tanggal Selesai BAST</th>
                    <th style="width: 130px; text-align: center;">Status BAST</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatBagiDatas as $idx => $r)
                    <tr>
                        <td class="table-col-id">
                            {{ $riwayatBagiDatas->firstItem() + $idx }}
                        </td>
                        <td>
                            <div>
                                <a href="{{ route('admin.riwayat-bagi-data.show', $r->id) }}" class="instansi-title-link" title="Buka Detail Berita Acara, Petugas Hadir & Laptop">
                                    <span>{{ $r->nama_instansi }}</span>
                                </a>
                            </div>
                            @if($r->tiket_dtsen_id)
                                <div class="user-sub-muted" style="margin-top: 3px;">
                                    Tiket Terkait: <a href="{{ route('admin.tiket.show', $r->tiket_dtsen_id) }}" style="color: #0b57d0; text-decoration: none; font-weight: 600;">#{{ $r->tiket_dtsen_id }}</a>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>
                                <a href="{{ route('admin.riwayat-bagi-data.show', $r->id) }}" class="badge-se-clickable" title="Buka Detail Berita Acara">
                                    <i class="fi fi-rr-document"></i>
                                    <span>{{ $r->no_surat_edaran ?: '-' }}</span>
                                </a>
                            </div>
                            @if($r->no_bast)
                                <div class="user-sub-muted" style="margin-top: 4px; font-family: var(--font-mono); font-size: 11px;">
                                    BAST: <strong>{{ $r->no_bast }}</strong>
                                </div>
                            @endif
                        </td>
                        <td class="table-col-nowrap">
                            <div class="user-title-name">{{ \Carbon\Carbon::parse($r->tanggal_terbit_bast)->format('d M Y') }}</div>
                            <div class="user-sub-muted">{{ \Carbon\Carbon::parse($r->tanggal_terbit_bast)->format('H:i') }} WIB</div>
                        </td>
                        <td class="table-col-nowrap" style="text-align: center;">
                            <span class="role-badge-opd" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                                <i class="fi fi-rr-check"></i>
                                <span>BAST Terbit</span>
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            Belum ada data penerbitan BAST resmi yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($riwayatBagiDatas->hasPages())
        <div class="admin-card-pagination">
            {{ $riwayatBagiDatas->links() }}
        </div>
    @endif
</div>
@endif

<!-- Form Tersembunyi Reset Password Superadmin -->
<form id="formSuperadminReset" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="password" id="inputResetNewPassword">
</form>

<script>
function togglePasswordVisibility(elId, plainPwd, btn) {
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

function promptResetPassword(userId, username) {
    const newPwd = prompt(`Masukkan kata sandi baru untuk akun ${username}:`, 'opd123');
    if (newPwd && newPwd.trim() !== '') {
        const form = document.getElementById('formSuperadminReset');
        form.action = `{{ url('admin/users') }}/${userId}/reset-password`;
        document.getElementById('inputResetNewPassword').value = newPwd.trim();
        form.submit();
    }
}
</script>
@endsection
