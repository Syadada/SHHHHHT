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
@php
    $activeTab = $activeTab ?? 'laptop';
    $laptopLogs = $laptopLogs ?? collect([]);
    $securityLogs = $securityLogs ?? collect([]);
    $tiketLogs = $tiketLogs ?? collect([]);
    $allLogs = $allLogs ?? collect([]);
@endphp
<div class="page-top-bar">
    <div>
        <h1 class="page-top-title">
            <i class="fi fi-rr-laptop"></i>
            <span>Log Laptop & Audit Keamanan</span>
        </h1>
        <p class="page-top-subtitle">
            Audit jejak digital identitas perangkat laptop dan riwayat aktivitas login akun pengguna ke dalam sistem.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.riwayat.export-logs', ['format' => 'csv']) }}" class="btn-pill-primary">
            <i class="fi fi-rr-download"></i>
            <span>Ekspor Log Audit (CSV)</span>
        </a>
    </div>
</div>

<!-- KPI / Statistik Ringkas -->
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-label">Total Log Aktivitas</div>
        <div class="admin-stat-val admin-stat-val-blue">{{ number_format($totalLogs ?? 0, 0, ',', '.') }}</div>
        <div class="admin-stat-sub">Seluruh jejak autentikasi tersimpan</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Laptop / Device Terdeteksi</div>
        <div class="admin-stat-val admin-stat-val-green">{{ number_format($uniqueDevices ?? 0, 0, ',', '.') }}</div>
        <div class="admin-stat-sub">Nomor identitas unik perangkat</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Login Hari Ini</div>
        <div class="admin-stat-val admin-stat-val-purple">{{ number_format($todayLogs ?? 0, 0, ',', '.') }}</div>
        <div class="admin-stat-sub">Sesi masuk aktif hari ini</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Peringatan Keamanan</div>
        <div class="admin-stat-val admin-stat-val-amber">{{ number_format($securityAlerts ?? 0, 0, ',', '.') }}</div>
        <div class="admin-stat-sub">Akses tidak sah & gagal masuk</div>
    </div>
</div>

<!-- SUB PAGE MENU / TAB NAVIGATION BAR -->
<div class="sub-page-nav-bar" id="logs-tab-bar">
    <button type="button" class="sub-page-tab-item {{ $activeTab === 'laptop' ? 'active' : '' }}" onclick="switchLogTab('laptop')" id="btn-tab-laptop">
        <i class="fi fi-rr-laptop sub-page-tab-icon"></i>
        <span>Log Perangkat Laptop</span>
        <span class="sub-page-tab-count">{{ method_exists($laptopLogs, 'total') ? $laptopLogs->total() : count($laptopLogs) }}</span>
    </button>

    <button type="button" class="sub-page-tab-item {{ $activeTab === 'keamanan' ? 'active' : '' }}" onclick="switchLogTab('keamanan')" id="btn-tab-keamanan">
        <i class="fi fi-rr-shield-exclamation sub-page-tab-icon"></i>
        <span>Keamanan & Akses Ditolak</span>
        <span class="sub-page-tab-count">{{ method_exists($securityLogs, 'total') ? $securityLogs->total() : count($securityLogs) }}</span>
    </button>

    <button type="button" class="sub-page-tab-item {{ $activeTab === 'tiket' ? 'active' : '' }}" onclick="switchLogTab('tiket')" id="btn-tab-tiket">
        <i class="fi fi-rr-ticket sub-page-tab-icon"></i>
        <span>Aktivitas Tiket & Approval</span>
        <span class="sub-page-tab-count">{{ method_exists($tiketLogs, 'total') ? $tiketLogs->total() : count($tiketLogs) }}</span>
    </button>

    <button type="button" class="sub-page-tab-item {{ $activeTab === 'semua' ? 'active' : '' }}" onclick="switchLogTab('semua')" id="btn-tab-semua">
        <i class="fi fi-rr-list-check sub-page-tab-icon"></i>
        <span>Semua Jejak Audit</span>
        <span class="sub-page-tab-count">{{ method_exists($allLogs, 'total') ? $allLogs->total() : count($allLogs) }}</span>
    </button>
</div>

<!-- ============================================================== -->
<!-- SUB-PAGE 1: LOG NOMOR LAPTOP & PERANGKAT LOGIN                 -->
<!-- ============================================================== -->
<div class="sub-page-panel {{ $activeTab === 'laptop' ? 'active' : '' }}" id="panel-laptop">
    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-laptop"></i>
                    <span>Log Nomor Laptop & Perangkat yang Digunakan Login</span>
                </h2>
            </div>
            <div class="admin-card-header-sub">
                Audit jejak digital identitas laptop/device setiap kali ada akun yang melakukan login. Klik pada ID Log atau Akun Pengguna untuk melihat detail.
            </div>
        </div>

        <div class="table-responsive" style="overflow-x: hidden;">
            <table class="data-table" style="width: 100%; table-layout: auto;">
                <thead>
                    <tr>
                        <th style="width: 75px;">ID Log</th>
                        <th style="width: 140px;">Waktu & Tanggal</th>
                        <th style="min-width: 220px;">Akun Pengguna</th>
                        <th style="min-width: 180px;">Nomor Laptop / Device ID</th>
                        <th style="width: 130px;">Alamat IP</th>
                        <th style="width: 140px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laptopLogs as $log)
                        <tr>
                            <td class="table-col-id">
                                <a href="{{ route('admin.logs.show', $log->id) }}" style="font-family: var(--font-mono); font-weight: 700; color: #0b57d0; text-decoration: none;" title="Buka Rincian Log #{{ $log->id }}">
                                    #{{ $log->id }}
                                </a>
                            </td>
                            <td class="table-col-nowrap">
                                <div class="user-title-name">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                <div class="user-sub-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</div>
                            </td>
                            <td>
                                <div>
                                    <a href="{{ route('admin.logs.show', $log->id) }}" class="instansi-title-link" title="Klik untuk membuka informasi selengkapnya">
                                        <span>{{ $log->user ? $log->user->username : ($log->user_name ?: 'Sistem / Anonim') }}</span>
                                    </a>
                                </div>
                                @if($log->user && $log->user->nama_instansi)
                                    <div class="user-sub-muted" style="margin-top: 3px;">
                                        {{ $log->user->nama_instansi }}
                                    </div>
                                @endif
                            </td>
                            <td class="table-col-nowrap">
                                @php
                                    $laptopId = '-';
                                    if ($log->device_id) {
                                        $laptopId = $log->device_id;
                                    } elseif (!empty($log->details) && is_array($log->details) && isset($log->details['device_id'])) {
                                        $laptopId = $log->details['device_id'];
                                    } elseif (!empty($log->device_identifier)) {
                                        $laptopId = $log->device_identifier;
                                    }
                                @endphp
                                @if($laptopId !== '-')
                                    <span class="device-badge" title="Identitas Perangkat Laptop">
                                        <i class="fi fi-rr-laptop"></i>
                                        <strong>{{ $laptopId }}</strong>
                                        <span style="font-size: 11px; color: var(--text-muted); margin-left: 4px;">({{ $log->device_os }})</span>
                                    </span>
                                @else
                                    <span class="device-badge" style="color: var(--text-muted);">
                                        <i class="fi fi-rr-shield-question"></i>
                                        Tidak Terbaca
                                    </span>
                                @endif
                            </td>
                            <td class="table-col-nowrap">
                                <span class="table-cell-ip">
                                    {{ $log->ip_address ?: '127.0.0.1' }}
                                </span>
                            </td>
                            <td class="table-col-nowrap" style="text-align: center;">
                                <span class="role-badge-opd">
                                    <i class="fi fi-rr-check"></i>
                                    BERHASIL LOGIN
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="table-empty-box">
                                <div class="table-empty-icon">
                                    <i class="fi fi-rr-laptop"></i>
                                </div>
                                <div class="table-empty-text">Belum ada riwayat aktivitas login laptop yang tercatat.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($laptopLogs, 'hasPages') && $laptopLogs->hasPages())
            <div class="admin-card-pagination">
                {{ $laptopLogs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ============================================================== -->
<!-- SUB-PAGE 2: LOG KEAMANAN & AKSES DITOLAK                       -->
<!-- ============================================================== -->
<div class="sub-page-panel {{ $activeTab === 'keamanan' ? 'active' : '' }}" id="panel-keamanan">
    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-shield-exclamation"></i>
                    <span>Log Keamanan & Penolakan Akses Sistem</span>
                </h2>
            </div>
            <div class="admin-card-header-sub">
                Mencatat upaya akses yang diblokir, salah kata sandi, atau percobaan URL ilegal
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="table-col-id">ID</th>
                        <th class="table-col-nowrap">Waktu</th>
                        <th class="table-col-nowrap">Jenis Insiden</th>
                        <th>Keterangan / Pesan Audit</th>
                        <th class="table-col-nowrap">Alamat IP</th>
                        <th class="table-col-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($securityLogs as $log)
                        <tr>
                            <td class="table-col-id">
                                <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #0b57d0; font-weight: 700; text-decoration: none;" title="Buka Detail Log #{{ $log->id }}">
                                    #{{ $log->id }}
                                </a>
                            </td>
                            <td class="table-col-nowrap">
                                <div class="user-title-name">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                <div class="user-sub-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</div>
                            </td>
                            <td class="table-col-nowrap">
                                <span class="role-badge-super">
                                    <i class="fi fi-rr-ban"></i>
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td>
                                <div class="user-title-name">{{ $log->description }}</div>
                                @if($log->user_agent)
                                    <div class="user-log-sub">{{ \Illuminate\Support\Str::limit($log->user_agent, 80) }}</div>
                                @endif
                            </td>
                            <td class="table-col-nowrap">
                                <span class="table-cell-ip">{{ $log->ip_address ?: '-' }}</span>
                            </td>
                            <td class="table-col-nowrap">
                                <span class="role-badge-super">DITOLAK</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="table-empty-box">
                                <div class="table-empty-icon">
                                    <i class="fi fi-rr-shield-check"></i>
                                </div>
                                <div class="table-empty-text">Aman. Tidak ada catatan insiden keamanan atau penolakan akses.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($securityLogs, 'hasPages') && $securityLogs->hasPages())
            <div class="admin-card-pagination">
                {{ $securityLogs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ============================================================== -->
<!-- SUB-PAGE 3: LOG AKTIVITAS TIKET & APPROVAL                     -->
<!-- ============================================================== -->
<div class="sub-page-panel {{ $activeTab === 'tiket' ? 'active' : '' }}" id="panel-tiket">
    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-ticket"></i>
                    <span>Log Aktivitas Tiket & Verifikasi Persetujuan</span>
                </h2>
            </div>
            <div class="admin-card-header-sub">
                Riwayat approval tahapan, verifikasi variabel, revisi, dan perubahan status tiket
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="table-col-id">ID</th>
                        <th class="table-col-nowrap">Waktu</th>
                        <th class="table-col-nowrap">Tindakan</th>
                        <th>Keterangan Aktivitas</th>
                        <th class="table-col-nowrap">Pelaksana</th>
                        <th class="table-col-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tiketLogs as $log)
                        <tr>
                            <td class="table-col-id">
                                <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #0b57d0; font-weight: 700; text-decoration: none;" title="Buka Detail Log #{{ $log->id }}">
                                    #{{ $log->id }}
                                </a>
                            </td>
                            <td class="table-col-nowrap">
                                <div class="user-title-name">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                <div class="user-sub-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</div>
                            </td>
                            <td class="table-col-nowrap">
                                <span class="role-badge-opd">
                                    <i class="fi fi-rr-check"></i>
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td>
                                <div class="user-title-name">{{ $log->description }}</div>
                            </td>
                            <td class="table-col-nowrap">
                                <div class="user-title-bold">{{ $log->user ? $log->user->username : 'Admin Sistem' }}</div>
                            </td>
                            <td class="table-col-nowrap">
                                <span class="role-badge-opd">SUKSES</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="table-empty-box">
                                <div class="table-empty-icon">
                                    <i class="fi fi-rr-ticket"></i>
                                </div>
                                <div class="table-empty-text">Belum ada riwayat aktivitas tiket yang tercatat.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($tiketLogs, 'hasPages') && $tiketLogs->hasPages())
            <div class="admin-card-pagination">
                {{ $tiketLogs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ============================================================== -->
<!-- SUB-PAGE 4: SEMUA JEJAK AUDIT                                  -->
<!-- ============================================================== -->
<div class="sub-page-panel {{ $activeTab === 'semua' ? 'active' : '' }}" id="panel-semua">
    <div class="admin-card-section">
        <div class="admin-card-header">
            <div class="admin-card-header-left">
                <h2 class="admin-card-header-title">
                    <i class="fi fi-rr-list-check"></i>
                    <span>Semua Jejak Audit Sistem</span>
                </h2>
            </div>
            <div class="admin-card-header-sub">
                Seluruh catatan operasional aplikasi secara menyeluruh dan berurutan
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="table-col-id">ID</th>
                        <th class="table-col-nowrap">Waktu</th>
                        <th class="table-col-nowrap">Aksi / Event</th>
                        <th>Keterangan Aktivitas</th>
                        <th class="table-col-nowrap">User</th>
                        <th class="table-col-nowrap">Alamat IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allLogs as $log)
                        <tr>
                            <td class="table-col-id">
                                <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #0b57d0; font-weight: 700; text-decoration: none;" title="Buka Detail Log #{{ $log->id }}">
                                    #{{ $log->id }}
                                </a>
                            </td>
                            <td class="table-col-nowrap">
                                <div class="user-title-name">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                <div class="user-sub-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</div>
                            </td>
                            <td class="table-col-nowrap">
                                <span class="device-badge">{{ $log->action }}</span>
                            </td>
                            <td>
                                <div class="user-title-name">{{ $log->description }}</div>
                            </td>
                            <td class="table-col-nowrap">
                                <div class="user-title-name">{{ $log->user ? $log->user->username : 'Sistem' }}</div>
                            </td>
                            <td class="table-col-nowrap">
                                <span class="table-cell-ip">{{ $log->ip_address ?: '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="table-empty-box">
                                <div class="table-empty-icon">
                                    <i class="fi fi-rr-list"></i>
                                </div>
                                <div class="table-empty-text">Belum ada jejak audit yang tercatat.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($allLogs, 'hasPages') && $allLogs->hasPages())
            <div class="admin-card-pagination">
                {{ $allLogs->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function switchLogTab(tabName) {
        // Update URL query string without reloading page
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);

        // Update tab buttons
        const tabButtons = document.querySelectorAll('#logs-tab-bar .sub-page-tab-item');
        tabButtons.forEach(btn => btn.classList.remove('active'));
        const activeBtn = document.getElementById('btn-tab-' + tabName);
        if (activeBtn) activeBtn.classList.add('active');

        // Update panels
        const panels = document.querySelectorAll('.sub-page-panel');
        panels.forEach(panel => panel.classList.remove('active'));
        const targetPanel = document.getElementById('panel-' + tabName);
        if (targetPanel) targetPanel.classList.add('active');
    }
</script>
@endsection
