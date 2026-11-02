@extends('layouts.admin')

@section('styles')
<style>
    .instansi-title-link {
        font-weight: 700;
        color: #0b57d0;
        text-decoration: none;
        font-size: 14px;
        line-height: 1.4;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }
    .instansi-title-link:hover {
        color: #0842a0;
        text-decoration: underline;
    }
</style>
@endsection

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
                                    PIC: <a href="{{ route('admin.users.show', $u->id) }}" style="color: var(--text-secondary); text-decoration: none; font-weight: 600;" title="Buka Detail Akun">{{ $u->name }}</a>
                                    @if($u->nomor_kontak)
                                        • {{ $u->nomor_kontak }}
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

                            <!-- 4. Terakhir Login -->
                            <td class="table-col-nowrap">
                                @if($u->last_login_at)
                                    <div class="user-title-name">{{ \Carbon\Carbon::parse($u->last_login_at)->format('d M Y') }}</div>
                                    <div class="user-sub-muted">{{ \Carbon\Carbon::parse($u->last_login_at)->format('H:i') }} WIB</div>
                                @else
                                    <span class="user-sub-muted">Belum Pernah Login</span>
                                @endif
                            </td>

                            <!-- 5. Aksi (Edit Akses, Hapus) -->
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
@endsection
