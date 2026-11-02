<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengelolaan Tiket Layanan Bagi Pakai DTSEN — Admin Panel</title>
    <!-- Modern Typography (Roboto & JetBrains Mono) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Unified Master Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
</head>
<body>

    <div class="app-shell">
        
        <!-- Top App Bar -->
        <header class="app-header">
            <div class="app-header-left">
                <a href="{{ route('admin.riwayat') }}" class="app-brand">
                    <div class="app-brand-icon" style="background: #0b57d0; color: #fff; font-weight: 800; font-size: 11px;">
                        DTSEN
                    </div>
                    <div class="app-brand-title">
                        <span>Layanan Bagi Pakai DTSEN</span>
                        <span class="app-brand-badge">ADMIN</span>
                    </div>
                </a>
            </div>

            <!-- Right Controls -->
            <div class="app-header-right">
                <div class="app-user-chip">
                    <div class="app-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span>{{ Auth::user()->name ?? 'Administrator' }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="btn-header-logout" title="Keluar dari akun">
                        <i class="fi fi-rr-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Body Layout -->
        <div class="app-body">
            
            <!-- Left Sidebar Navigation -->
            <aside class="app-sidebar">
                <ul class="app-nav-list">
                    <!-- 1. Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="app-nav-item active">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-apps app-nav-icon"></i>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>

                    <!-- 2. Ticket -->
                    <li>
                        <a href="{{ route('admin.tiket.index') }}" class="app-nav-item {{ request()->routeIs('admin.tiket.*') ? 'active' : '' }}">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-ticket app-nav-icon"></i>
                                <span>Ticket</span>
                            </div>
                            <span class="app-nav-badge app-nav-badge-tiket">{{ $totalTiket ?? '' }}</span>
                        </a>
                    </li>

                    @php
                        $isUserActive = request()->routeIs('admin.users.*') || request()->routeIs('admin.logs.*') || request()->routeIs('admin.profile');
                    @endphp
                    <!-- 3. User (Dropdown Flyout WordPress Style) -->
                    <li class="app-nav-dropdown {{ $isUserActive ? 'active open' : '' }}">
                        <button type="button" class="app-nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-users app-nav-icon"></i>
                                <span>User</span>
                            </div>
                            <i class="fi fi-rr-angle-small-right app-nav-caret"></i>
                        </button>
                        <ul class="app-nav-submenu">
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="app-nav-subitem {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                    <i class="fi fi-rr-users-alt"></i>
                                    <span>User Management</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.create') }}" class="app-nav-subitem {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                                    <i class="fi fi-rr-user-add"></i>
                                    <span>Buat Akun</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.logs.index') }}" class="app-nav-subitem {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                                    <i class="fi fi-rr-laptop"></i>
                                    <span>Log</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.profile') }}" class="app-nav-subitem {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                                    <i class="fi fi-rr-user"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @php
                        $isEksporActive = request()->routeIs('admin.riwayat.export*');
                    @endphp
                    <!-- 4. Ekspor (Dropdown Flyout WordPress Style) -->
                    <li class="app-nav-dropdown {{ $isEksporActive ? 'active open' : '' }}">
                        <button type="button" class="app-nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-download app-nav-icon"></i>
                                <span>Ekspor</span>
                            </div>
                            <i class="fi fi-rr-angle-small-right app-nav-caret"></i>
                        </button>
                        <ul class="app-nav-submenu">
                            <li>
                                <a href="{{ route('admin.riwayat.export') }}" class="app-nav-subitem {{ request()->routeIs('admin.riwayat.export') ? 'active' : '' }}">
                                    <i class="fi fi-rr-file-chart-line"></i>
                                    <span>Ekspor Tiket</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.riwayat.export-logs', ['format' => 'csv']) }}" class="app-nav-subitem {{ request()->routeIs('admin.riwayat.export-logs') ? 'active' : '' }}">
                                    <i class="fi fi-rr-document"></i>
                                    <span>Ekspor Log</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </aside>

            <!-- Main Content Area -->
            <main class="app-main-content">

                @if(session('success'))
                    <div class="form-alert-success" style="margin-bottom: 20px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="form-notice-box" style="margin-bottom: 20px;">
                        <span>ℹ️</span>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif
                
                <!-- Hero Header -->
                <div class="dash-hero">
                    <div>
                        <h1 class="dash-hero-title">
                            Dashboard Layanan Bagi Pakai DTSEN
                        </h1>
                        <p class="dash-hero-subtitle">
                            Ringkasan statistik, 5 permintaan layanan terbaru, dan 10 aktivitas jejak audit sistem DTSEN.
                        </p>
                    </div>
                    <div class="dash-hero-actions">
                        <a href="{{ route('admin.tiket.index') }}" class="btn-pill-primary">
                            <i class="fi fi-rr-ticket"></i> Kelola Semua Ticket
                        </a>
                        <a href="{{ route('admin.riwayat.export') }}" class="btn-pill-outline">
                            <i class="fi fi-rr-download"></i> Ekspor Data
                        </a>
                    </div>
                </div>

                <!-- KPI Metric Cards Grid (Small Cards) -->
                <div class="dash-metric-grid">
                    <div class="dash-metric-card">
                        <div class="dash-metric-label">Total Tiket Masuk</div>
                        <div class="dash-metric-value">{{ number_format($totalTiket ?? 0) }}</div>
                        <div class="dash-metric-sub">Seluruh permohonan instansi</div>
                    </div>

                    <div class="dash-metric-card">
                        <div class="dash-metric-label">Sedang Diproses</div>
                        <div class="dash-metric-value" style="color: var(--blue-primary);">{{ number_format($totalDiproses ?? 0) }}</div>
                        <div class="dash-metric-sub">Menunggu verifikasi / tahapan aktif</div>
                    </div>

                    <div class="dash-metric-card">
                        <div class="dash-metric-label">Selesai Dibagipakaikan</div>
                        <div class="dash-metric-value" style="color: var(--green-primary);">{{ number_format($totalSelesai ?? 0) }}</div>
                        <div class="dash-metric-sub">Tahap 1 - 5 selesai 100%</div>
                    </div>

                    <div class="dash-metric-card">
                        <div class="dash-metric-label">Tiket Hari Ini</div>
                        <div class="dash-metric-value" style="color: var(--purple-primary);">{{ number_format($totalHariIni ?? 0) }}</div>
                        <div class="dash-metric-sub">Dalam 24 jam terakhir</div>
                    </div>
                </div>

                <!-- 1. Permintaan Layanan Terbaru (5 Item) -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <div>
                            <h2 class="dash-card-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--blue-primary)" stroke-width="2">
                                    <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                                <span>Permintaan Layanan Terbaru (5 Item)</span>
                            </h2>
                            <p class="dash-card-subtitle">
                                Menampilkan permohonan layanan bagi pakai DTSEN baru yang masih dalam proses (belum selesai). Tiket yang telah selesai 100% tersimpan di menu Semua Ticket.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.tiket.index') }}" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px; text-decoration: none;">
                                Semua Ticket
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr>
                                    <th style="width: 130px;">No. Tiket</th>
                                    <th>Nama Instansi</th>
                                    <th style="width: 150px;">Surat Edaran</th>
                                    <th style="width: 130px;">Variabel DTSEN</th>
                                    <th style="width: 130px;">Status & Progress</th>
                                    <th style="text-align: center; width: 60px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $itemsToShow = (isset($latestTikets) && $latestTikets->isNotEmpty()) 
                                        ? $latestTikets->filter(fn($t) => !$t->step5_done && $t->status !== 'SELESAI') 
                                        : ((isset($tikets) && is_iterable($tikets)) ? collect($tikets)->filter(fn($t) => !$t->step5_done && $t->status !== 'SELESAI')->take(5) : collect());
                                @endphp
                                @forelse($itemsToShow as $item)
                                    @php
                                        // Cari step aktif saat ini yang belum OK
                                        $activeStepNum = 1;
                                        if (!$item->step1_done) $activeStepNum = 1;
                                        elseif (!$item->step2_done) $activeStepNum = 2;
                                        elseif (!$item->step3_done) $activeStepNum = 3;
                                        elseif (!$item->step4_done) $activeStepNum = 4;
                                        elseif (!$item->step5_done) $activeStepNum = 5;
                                        else $activeStepNum = 6; // Selesai
                                    @endphp
                                    <tr>
                                        <!-- 1. No Tiket -->
                                        <td>
                                            <div>
                                                <a href="{{ route('admin.tiket.show', $item->id) }}" style="font-family: var(--font-mono); font-weight: 700; color: var(--blue-primary); font-size: 13px; text-decoration: none;" title="Buka Detail Tiket">
                                                    {{ $item->nomor_tiket }}
                                                </a>
                                            </div>
                                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">
                                                {{ $item->created_at->format('d/m/Y H:i') }} WIB
                                            </div>
                                        </td>

                                        <!-- 2. Nama Instansi & PIC -->
                                        <td>
                                            <div>
                                                <a href="{{ route('admin.tiket.show', $item->id) }}" class="instansi-title-link" title="Klik untuk membuka informasi selengkapnya">
                                                    <span>{{ $item->nama_instansi }}</span>
                                                </a>
                                            </div>
                                            @if($item->nama_pemohon)
                                                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                                                    PIC: <strong>{{ $item->nama_pemohon }}</strong> ({{ $item->kontak_pemohon ?: '-' }})
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 3. Surat Edaran -->
                                        <td>
                                            <div style="font-weight: 600; color: var(--text-primary); font-size: 13px;">
                                                {{ $item->no_surat_edaran }}
                                            </div>
                                            @if($item->file_surat)
                                                <div style="margin-top: 4px;">
                                                    <a href="{{ route('admin.riwayat.download', $item->id) }}" class="btn-pill-outline" style="font-size: 11px; padding: 2px 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas Surat Edaran">
                                                        <i class="fi fi-rr-download"></i> Berkas SE
                                                    </a>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 4. Variabel DTSEN & Status Verifikasi -->
                                        <td>
                                            <div style="margin-bottom: 5px;">
                                                <span class="dtsen-chip" style="font-size: 11px; font-weight: 700; color: var(--blue-primary); background: var(--blue-light); padding: 3px 8px; border-radius: 6px;">
                                                    {{ count($item->variabel_dtsen ?? []) }} Variabel Diminta
                                                </span>
                                            </div>
                                            @if($item->status === 'PERLU_PERBAIKAN')
                                                <span style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                                    ⚠️ Perlu Perbaikan
                                                </span>
                                            @elseif($item->step1_done)
                                                <span style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                                    ✓ Sesuai SE
                                                </span>
                                            @else
                                                <span style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                                    ⏳ Menunggu Cek
                                                </span>
                                            @endif
                                        </td>

                                        <!-- 5. Status & Progress -->
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <div class="progress-track" style="height: 8px; width: 75px;">
                                                    <div class="progress-fill" style="width: {{ $item->progress_percent }}%;"></div>
                                                </div>
                                                <span style="font-size: 12px; font-weight: 700; color: {{ $item->step5_done ? '#1e8e3e' : '#0b57d0' }};">
                                                    {{ $item->progress_percent }}%
                                                </span>
                                            </div>
                                            <div style="font-size: 11px; margin-top: 4px; font-weight: 600; color: {{ $item->step5_done ? '#1e8e3e' : '#444746' }};">
                                                @if($item->step5_done)
                                                    <span style="color: #1e8e3e;">✓ Selesai 100%</span>
                                                @else
                                                    Progress {{ $activeStepNum }}/5
                                                @endif
                                            </div>
                                        </td>

                                        <!-- 6. Aksi (Hapus) -->
                                        <td style="text-align: center;">
                                            <form action="{{ route('admin.riwayat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus tiket {{ $item->nomor_tiket }} milik {{ $item->nama_instansi }}?');" style="display: inline; margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-row-action delete" style="padding: 5px 8px;" title="Hapus Tiket">
                                                    <i class="fi fi-rr-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                                            <div style="font-size: 32px; margin-bottom: 8px; color: #10b981;"><i class="fi fi-rr-check-circle"></i></div>
                                            <div style="font-weight: 700; font-size: 14px; color: var(--text-primary);">Tidak ada permintaan layanan yang belum selesai</div>
                                            <div style="font-size: 12px; color: var(--text-muted); margin-top: 3px;">Semua tiket layanan telah selesai diproses (100%), atau belum ada permohonan baru yang diajukan oleh OPD.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Aktivitas Terbaru (10 Entri) -->
                <div class="dash-card" style="margin-top: 24px;">
                    <div class="dash-card-header">
                        <div>
                            <h2 class="dash-card-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--blue-primary)" stroke-width="2">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                </svg>
                                <span>Aktivitas Terbaru (10 Entri)</span>
                            </h2>
                            <p class="dash-card-subtitle">
                                Rekaman 10 jejak audit waktu-nyata atas tindakan autentikasi, pembaruan tahapan tiket, dan interaksi pengguna.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.logs.index') }}" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px; text-decoration: none;">
                                Semua Log
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr>
                                    <th style="width: 140px;">Waktu Kejadian</th>
                                    <th style="width: 120px;">Aksi / Event</th>
                                    <th style="width: 150px;">Pengguna & Peran</th>
                                    <th>Keterangan Aktivitas</th>
                                    <th style="width: 140px;">IP & Perangkat</th>
                                    <th style="width: 90px; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $logsToShow = (isset($latestAuditLogs) && $latestAuditLogs->isNotEmpty()) 
                                        ? $latestAuditLogs 
                                        : ((isset($auditLogs) && is_iterable($auditLogs)) ? collect($auditLogs)->take(10) : collect());
                                @endphp
                                @forelse($logsToShow as $log)
                                    <tr>
                                        <!-- 1. Waktu -->
                                        <td>
                                            <div style="font-weight: 600; color: #1e293b; font-size: 12px;">
                                                {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }} WIB
                                            </div>
                                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                                {{ $log->created_at ? $log->created_at->diffForHumans() : '' }}
                                            </div>
                                        </td>

                                        <!-- 2. Event / Aksi -->
                                        <td>
                                            <span style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-family: var(--font-mono); display: inline-block;">
                                                {{ $log->action }}
                                            </span>
                                        </td>

                                        <!-- 3. Pengguna & Peran -->
                                        <td>
                                            <div style="font-weight: 600; color: #1e293b; font-size: 13px;">
                                                {{ $log->user_name ?: ($log->user ? $log->user->name : 'Sistem / Tamu') }}
                                            </div>
                                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                                {{ $log->role ?: ($log->user ? $log->user->role : 'system') }}
                                            </div>
                                        </td>

                                        <!-- 4. Keterangan -->
                                        <td>
                                            <div style="font-size: 13px; color: #334155; line-height: 1.45;">
                                                {{ $log->description ?: '-' }}
                                            </div>
                                        </td>

                                        <!-- 5. IP & Perangkat -->
                                        <td>
                                            <div style="font-family: var(--font-mono); font-size: 12px; color: #475569;">
                                                {{ $log->ip_address ?: '-' }}
                                            </div>
                                            @php
                                                $devId = $log->device_id ?: ($log->details['device_id'] ?? null);
                                            @endphp
                                            @if($devId)
                                                <div style="font-size: 11px; color: var(--blue-primary); font-weight: 600; margin-top: 2px;">
                                                    💻 {{ $devId }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 6. Status -->
                                        <td style="text-align: center;">
                                            @if(strtoupper($log->status ?? '') === 'SUCCESS')
                                                <span style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: inline-block;">
                                                    ✓ SUCCESS
                                                </span>
                                            @elseif(strtoupper($log->status ?? '') === 'FAILED')
                                                <span style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: inline-block;">
                                                    ✕ FAILED
                                                </span>
                                            @else
                                                <span style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: inline-block;">
                                                    {{ strtoupper($log->status ?? 'INFO') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                                            <div style="font-size: 30px; margin-bottom: 8px; color: #94a3b8;"><i class="fi fi-rr-time-past"></i></div>
                                            <div style="font-weight: 700; font-size: 14px; color: var(--text-primary);">Belum ada rekaman aktivitas</div>
                                            <div style="font-size: 12px; color: var(--text-muted); margin-top: 3px;">Aktivitas audit sistem akan tercatat secara otomatis di sini.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>

    </div>

    <!-- Modal 1: Rincian Variabel DTSEN yang Diminta -->
    <div id="modalVariabel" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 580px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 id="varModalTitle" style="font-size: 16px; font-weight: 700; color: #1f1f1f; margin-bottom: 2px;">Rincian Variabel DTSEN</h3>
                    <div id="varModalInstansi" style="font-size: 12px; color: #5e5e5e;">Nama Instansi</div>
                </div>
                <button type="button" onclick="closeVarModal()" style="background: transparent; border: none; font-size: 22px; cursor: pointer; color: #70757a;">✕</button>
            </div>
            <div style="padding: 24px; max-height: 400px; overflow-y: auto;">
                <div style="font-size: 12px; font-weight: 700; color: #444746; text-transform: uppercase; margin-bottom: 12px;">Daftar Kolom Variabel yang Diminta:</div>
                <div id="varModalList" style="display: flex; flex-direction: column; gap: 8px;"></div>
            </div>
            <div style="padding: 16px 24px; background: #f8fafd; border-top: 1px solid #eee; text-align: right;">
                <button type="button" onclick="closeVarModal()" class="btn-pill-primary" style="font-size: 13px; padding: 6px 18px;">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Manipulasi Menyeluruh Tahapan Proses oleh Admin -->
    <div id="modalManage" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 640px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
            <form id="formManageSteps" method="POST" action="">
                @csrf
                <div style="padding: 20px 24px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 id="manageModalTitle" style="font-size: 16px; font-weight: 700; color: #1f1f1f; margin-bottom: 2px;">Kelola Tahapan Proses Tiket</h3>
                        <div id="manageModalSubtitle" style="font-size: 12px; color: #5e5e5e;">Instansi Pemohon</div>
                    </div>
                    <button type="button" onclick="closeManageModal()" style="background: transparent; border: none; font-size: 22px; cursor: pointer; color: #70757a;">✕</button>
                </div>

                <div style="padding: 24px; max-height: 480px; overflow-y: auto;">
                    <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px; line-height: 1.4;">
                        Centang tahapan yang telah selesai. Tahapan yang dicentang akan otomatis muncul sebagai <strong>Centang Hijau (✓)</strong> pada visual progress bar pengguna.
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafd; border: 1px solid #dadce0; border-radius: 10px; cursor: pointer;">
                            <input type="checkbox" name="step1_done" id="chkStep1" value="1" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f1f1f; font-size: 13px;">Progress 1</strong>
                                <div style="font-size: 11px; color: #5e5e5e;">Tahap proses ke-1 bagi pakai DTSEN.</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafd; border: 1px solid #dadce0; border-radius: 10px; cursor: pointer;">
                            <input type="checkbox" name="step2_done" id="chkStep2" value="1" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f1f1f; font-size: 13px;">Progress 2</strong>
                                <div style="font-size: 11px; color: #5e5e5e;">Tahap proses ke-2 bagi pakai DTSEN.</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafd; border: 1px solid #dadce0; border-radius: 10px; cursor: pointer;">
                            <input type="checkbox" name="step3_done" id="chkStep3" value="1" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f1f1f; font-size: 13px;">Progress 3</strong>
                                <div style="font-size: 11px; color: #5e5e5e;">Tahap proses ke-3 bagi pakai DTSEN.</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafd; border: 1px solid #dadce0; border-radius: 10px; cursor: pointer;">
                            <input type="checkbox" name="step4_done" id="chkStep4" value="1" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f1f1f; font-size: 13px;">Progress 4</strong>
                                <div style="font-size: 11px; color: #5e5e5e;">Tahap proses ke-4 bagi pakai DTSEN.</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafd; border: 1px solid #dadce0; border-radius: 10px; cursor: pointer;">
                            <input type="checkbox" name="step5_done" id="chkStep5" value="1" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f1f1f; font-size: 13px;">Progress 5 (Selesai 100%)</strong>
                                <div style="font-size: 11px; color: #5e5e5e;">Tahap proses ke-5 bagi pakai DTSEN selesai sepenuhnya.</div>
                            </div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="txtCatatanAdmin" class="form-label" style="font-size: 12px;">Catatan Admin / Verifikator (Akan tampil pada User View)</label>
                        <textarea name="catatan_admin" id="txtCatatanAdmin" class="form-textarea" rows="3" placeholder="Tulis catatan atau instruksi verifikasi untuk instansi pemohon..."></textarea>
                    </div>
                </div>

                <div style="padding: 16px 24px; background: #f8fafd; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeManageModal()" class="btn-pill-outline" style="font-size: 13px;">Batal</button>
                    <button type="submit" class="btn-pill-primary" style="font-size: 13px; padding: 8px 20px;">Simpan Perubahan Tahapan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Verifikasi Variabel DTSEN vs Surat Edaran oleh Admin -->
    <div id="modalVerifikasi" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 680px; border-radius: 20px; box-shadow: 0 10px 35px rgba(0,0,0,0.25); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
            <form id="formVerifikasiVariabel" method="POST" action="" style="display: flex; flex-direction: column; height: 100%; margin: 0;">
                @csrf
                <div style="padding: 20px 24px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 16px;"><i class="fi fi-rr-search"></i></span>
                            <h3 id="verifModalTitle" style="font-size: 16px; font-weight: 700; color: #1f1f1f; margin: 0;">Verifikasi Variabel vs Surat Edaran</h3>
                        </div>
                        <div id="verifModalSubtitle" style="font-size: 12px; color: #5e5e5e; margin-top: 3px;">Nomor Tiket & Instansi</div>
                    </div>
                    <button type="button" onclick="closeVerifModal()" style="background: transparent; border: none; font-size: 22px; cursor: pointer; color: #70757a;">✕</button>
                </div>

                <div style="padding: 24px; overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 18px;">
                    
                    <!-- Info Surat Edaran Box -->
                    <div style="background: #f0f4f9; border: 1px solid #d2e3fc; border-radius: 12px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <div style="font-size: 11px; font-weight: 700; color: #0b57d0; text-transform: uppercase;">Dasar Permohonan (Surat Edaran):</div>
                            <div id="verifNoSurat" style="font-size: 14px; font-weight: 700; color: #1f1f1f; margin-top: 3px;">No. Surat Edaran</div>
                        </div>
                        <div id="verifDownloadSuratBox">
                            <!-- Link download surat injected via JS -->
                        </div>
                    </div>

                    <!-- Variabel DTSEN Diminta Box -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-size: 12px; font-weight: 700; color: #444746; text-transform: uppercase;">Variabel DTSEN yang Diminta Instansi Pemohon:</span>
                            <span id="verifVarCountBadge" style="font-size: 11px; font-weight: 700; background: #e8f0fe; color: #0b57d0; padding: 2px 8px; border-radius: 12px;">0 Variabel</span>
                        </div>
                        <div id="verifVarList" style="display: flex; flex-direction: column; gap: 6px; max-height: 200px; overflow-y: auto; background: #fdfdfe; border: 1px solid #e0e2e5; border-radius: 10px; padding: 12px;">
                            <!-- List variabel injected via JS -->
                        </div>
                    </div>

                    <!-- Petunjuk Admin -->
                    <div style="background: #fefce8; border: 1px solid #fef08a; border-radius: 10px; padding: 12px 16px; font-size: 12px; color: #854d0e; line-height: 1.45;">
                        <strong>Petunjuk Verifikasi Admin:</strong><br>
                        • Jika semua variabel <strong>SESUAI</strong> dengan Surat Edaran: Klik <strong>"Approve (Sesuai SE)"</strong>. Progress 1 akan selesai (centang hijau) dan tiket lanjut ke Progress 2.<br>
                        • Jika ada variabel yang <strong>TIDAK SESUAI</strong>: Berikan catatan koreksi di bawah lalu klik <strong>"Minta Perbaikan Variabel"</strong> agar OPD dapat merevisi kolom isian mereka.
                    </div>

                    <!-- Input Catatan Admin -->
                    <div class="form-group" style="margin: 0;">
                        <label for="txtVerifCatatanAdmin" class="form-label" style="font-size: 12px; font-weight: 700; color: #1f1f1f;">
                            Catatan Hasil Verifikasi / Instruksi Perbaikan:
                        </label>
                        <textarea name="catatan_admin" id="txtVerifCatatanAdmin" class="form-textarea" rows="3" placeholder="Contoh: Variabel 1 s/d 5 telah sesuai dengan lampiran Surat Edaran No... Atau: Mohon hapus variabel No. 4 karena tidak tercantum dalam klausul SE..."></textarea>
                    </div>

                </div>

                <div style="padding: 16px 24px; background: #fafbfc; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <button type="button" onclick="closeVerifModal()" class="btn-pill-outline" style="font-size: 13px;">Batal</button>
                    
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" name="hasil_verifikasi" value="PERLU_PERBAIKAN" class="btn-pill-primary" style="font-size: 13px; padding: 8px 18px; background: #d97706; border-color: #d97706;">
                            <i class="fi fi-rr-exclamation"></i> Minta Perbaikan Variabel
                        </button>
                        <button type="submit" name="hasil_verifikasi" value="APPROVE" class="btn-pill-primary" style="font-size: 13px; padding: 8px 20px; background: #166534; border-color: #166534;">
                            <i class="fi fi-rr-check"></i> Approve (Sesuai SE)
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Unified Master JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Modal Variabel
        const modalVar = document.getElementById('modalVariabel');
        function openVarModal(noTiket, instansi, vars) {
            document.getElementById('varModalTitle').textContent = `Variabel DTSEN — ${noTiket}`;
            document.getElementById('varModalInstansi').textContent = `Instansi: ${instansi}`;
            const listDiv = document.getElementById('varModalList');
            listDiv.innerHTML = '';
            
            if (Array.isArray(vars) && vars.length > 0) {
                vars.forEach((v, idx) => {
                    const item = document.createElement('div');
                    item.style.padding = '8px 12px';
                    item.style.background = '#f0f4f9';
                    item.style.borderRadius = '8px';
                    item.style.fontSize = '13px';
                    item.style.fontWeight = '500';
                    item.style.color = '#1f1f1f';
                    item.style.display = 'flex';
                    item.style.gap = '8px';
                    item.innerHTML = `<span style="font-weight: 700; color: #0b57d0;">${idx + 1}.</span> <span>${v}</span>`;
                    listDiv.appendChild(item);
                });
            } else {
                listDiv.innerHTML = '<div style="color: #70757a; font-size: 13px;">Tidak ada data variabel.</div>';
            }
            modalVar.style.display = 'flex';
        }
        function closeVarModal() {
            modalVar.style.display = 'none';
        }

        // Modal Kelola Tahapan
        const modalManage = document.getElementById('modalManage');
        const formManage = document.getElementById('formManageSteps');
        function openManageModal(id, noTiket, instansi, s1, s2, s3, s4, s5, catatan) {
            formManage.action = `/admin/tiket/${id}/update-steps`;
            document.getElementById('manageModalTitle').textContent = `Kelola Tahapan — ${noTiket}`;
            document.getElementById('manageModalSubtitle').textContent = `Instansi: ${instansi}`;
            document.getElementById('chkStep1').checked = s1;
            document.getElementById('chkStep2').checked = s2;
            document.getElementById('chkStep3').checked = s3;
            document.getElementById('chkStep4').checked = s4;
            document.getElementById('chkStep5').checked = s5;
            document.getElementById('txtCatatanAdmin').value = catatan || '';
            modalManage.style.display = 'flex';
        }
        function closeManageModal() {
            modalManage.style.display = 'none';
        }

        // Modal Verifikasi Variabel vs SE
        const modalVerif = document.getElementById('modalVerifikasi');
        const formVerif = document.getElementById('formVerifikasiVariabel');
        function openVerifModal(id, noTiket, instansi, noSurat, vars, downloadUrl, catatan, status, step1Done) {
            formVerif.action = `/admin/tiket/${id}/verifikasi-variabel`;
            document.getElementById('verifModalTitle').textContent = `Verifikasi Variabel vs Surat Edaran — ${noTiket}`;
            document.getElementById('verifModalSubtitle').textContent = `Instansi Pemohon: ${instansi}`;
            document.getElementById('verifNoSurat').textContent = `Surat Edaran No. ${noSurat}`;
            
            // Berkas Surat link
            const downloadBox = document.getElementById('verifDownloadSuratBox');
            if (downloadUrl) {
                downloadBox.innerHTML = `
                    <a href="${downloadUrl}" target="_blank" class="btn-pill-outline" style="font-size: 12px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px; background: #fff;">
                        <span>📥 Unduh Berkas Surat</span>
                    </a>
                `;
            } else {
                downloadBox.innerHTML = `<span style="font-size: 12px; color: #70757a; font-style: italic;">Tidak ada lampiran berkas</span>`;
            }

            // List Variabel
            const listDiv = document.getElementById('verifVarList');
            const countBadge = document.getElementById('verifVarCountBadge');
            listDiv.innerHTML = '';
            
            if (Array.isArray(vars) && vars.length > 0) {
                countBadge.textContent = `${vars.length} Variabel Diajukan`;
                vars.forEach((v, idx) => {
                    const row = document.createElement('div');
                    row.style.padding = '8px 12px';
                    row.style.background = '#f8fafd';
                    row.style.border = '1px solid #e2e8f0';
                    row.style.borderRadius = '8px';
                    row.style.fontSize = '13px';
                    row.style.display = 'flex';
                    row.style.alignItems = 'center';
                    row.style.justifyContent = 'space-between';
                    row.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-weight: 700; color: #0b57d0; font-family: var(--font-mono); font-size: 12px;">Variabel ${idx + 1}:</span>
                            <span style="font-weight: 600; color: #1e293b;">${v}</span>
                        </div>
                        <span style="font-size: 11px; color: #166534; background: #dcfce7; padding: 2px 8px; border-radius: 6px; font-weight: 600;">Kolom ${idx + 1}</span>
                    `;
                    listDiv.appendChild(row);
                });
            } else {
                countBadge.textContent = `0 Variabel`;
                listDiv.innerHTML = `<div style="color: #64748b; font-size: 13px; text-align: center; padding: 12px;">Tidak ada rincian variabel pada tiket ini.</div>`;
            }

            // Catatan
            document.getElementById('txtVerifCatatanAdmin').value = catatan || '';
            modalVerif.style.display = 'flex';
        }
        function closeVerifModal() {
            modalVerif.style.display = 'none';
        }

        // Close on backdrop click
        window.addEventListener('click', function(e) {
            if (e.target === modalVar) closeVarModal();
            if (e.target === modalManage) closeManageModal();
            if (e.target === modalVerif) closeVerifModal();
        });
    </script>
    @include('partials.idle_session_timer')
</body>
</html>
