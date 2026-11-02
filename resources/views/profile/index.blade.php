<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Akun Perangkat Daerah — Layanan Bagi Pakai DTSEN</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Unified Master Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .device-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 11px;
            border-radius: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 600;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .data-table th {
            padding: 16px 20px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            color: #4b5563 !important;
            background: #f8fafc !important;
            border-bottom: 2px solid #e2e8f0 !important;
            letter-spacing: 0.3px;
            vertical-align: middle;
        }

        .data-table td {
            padding: 18px 20px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #edf2f7 !important;
            color: #1f2937 !important;
            line-height: 1.45;
        }

        .data-table tbody tr:hover {
            background-color: #f8fafc !important;
        }
    </style>
</head>
<body class="form-page">

    <div class="form-shell" style="max-width: 1040px;">
        
        <!-- Top App Bar / Utility Header -->
        <div class="form-card" style="padding: 16px 24px; margin-bottom: 24px; border-radius: 18px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 13px; font-weight: 800; background: #0b57d0; color: #fff; width: 36px; height: 36px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">DTSEN</span>
                    <div>
                        <div style="font-weight: 700; font-size: 15px; color: #0f172a;">Layanan Bagi Pakai DTSEN</div>
                        <div style="font-size: 11px; color: #64748b;">Portal Akun Perangkat Daerah</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    @if(!$user->isSuperAdmin())
                        <a href="{{ route('formulir.index') }}" class="btn-pill-primary" style="font-size: 12px; padding: 7px 16px;">
                            <i class="fi fi-rr-plus"></i> Formulir Tiket Baru
                        </a>
                    @endif

                    @if($user->isSuperAdmin())
                        <a href="{{ route('admin.riwayat') }}" class="btn-pill-outline" style="font-size: 12px; padding: 7px 16px;">
                            <i class="fi fi-rr-crown"></i> Panel Admin
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn-pill-outline" style="font-size: 12px; padding: 7px 16px;">
                            <i class="fi fi-rr-users"></i> User & Laptop Log
                        </a>
                    @endif

                    <div class="app-user-chip">
                        <div class="app-user-avatar" style="background: {{ $user->isSuperAdmin() ? '#0f172a' : '#0b57d0' }}; color: #fff;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span style="font-size: 13px; font-weight: 600; color: #1f2937;">{{ $user->name }}</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                        @csrf
                        <button type="submit" class="btn-header-logout" title="Keluar">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="form-alert-success" style="margin-bottom: 24px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="form-alert-error" style="margin-bottom: 24px;">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- 1. Header Profil Akun Perangkat Daerah & Nomor Laptop Login -->
        <div class="form-card" style="padding: 28px 32px; border-radius: 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div class="form-badge-system" style="margin-bottom: 10px;">
                        <span><i class="fi fi-rr-building"></i> AKUN PERANGKAT DAERAH (OPD)</span>
                    </div>
                    <h1 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                        {{ $user->nama_instansi ?: $user->name }}
                    </h1>
                    <div style="font-size: 13px; color: #64748b; display: flex; gap: 18px; flex-wrap: wrap; margin-top: 8px;">
                        <span><i class="fi fi-rr-user"></i> PIC: <strong style="color: #1f2937;">{{ $user->name }}</strong></span>
                        <span><i class="fi fi-rr-envelope"></i> Email: <strong style="color: #1f2937;">{{ $user->email }}</strong></span>
                        <span><i class="fi fi-rr-phone-call"></i> Kontak: <strong style="color: #1f2937;">{{ $user->nomor_kontak ?: '-' }}</strong></span>
                    </div>
                </div>

                <!-- Badge Nomor Laptop / Perangkat Terdaftar Saat Login -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 20px; text-align: right; min-width: 230px;">
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                        Perangkat / Laptop Terdaftar Login:
                    </div>
                    <div style="font-size: 14px; font-weight: 700; color: #0f172a; font-family: var(--font-mono); margin-top: 5px; display: flex; align-items: center; justify-content: flex-end; gap: 6px;">
                        <span><i class="fi fi-rr-laptop"></i></span>
                        <span>{{ $user->last_login_device ?: 'WIN-DEV-OPD01' }}</span>
                    </div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                        IP Address: <strong style="color: #334155;">{{ $user->last_login_ip ?: '127.0.0.1' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Progress Bar Status Tiket Bagi Pakai DTSEN (Progress 1..5) -->
        <div class="progress-card" style="margin-bottom: 24px;">
            @if($selectedTiket && $selectedTiket->status === 'PERLU_PERBAIKAN')
                <!-- Warning Banner: Verifikasi Mengharuskan Perbaikan Variabel -->
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-left: 5px solid #d97706; border-radius: 14px; padding: 18px 22px; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 260px;">
                            <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #92400e; font-size: 15px;">
                                <span style="font-size: 18px;"><i class="fi fi-rr-exclamation"></i></span>
                                <span>PERBAIKAN VARIABEL DIPERLUKAN (HASIL VERIFIKASI ADMIN)</span>
                            </div>
                            <div style="font-size: 13px; color: #78350f; margin-top: 6px; line-height: 1.5;">
                                Variabel yang Anda ajukan pada tiket <strong>{{ $selectedTiket->nomor_tiket }}</strong> belum sesuai dengan klausul <strong>Surat Edaran No. {{ $selectedTiket->no_surat_edaran }}</strong>.
                                <div style="margin-top: 10px; background: #ffffff; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 14px; font-size: 13px; color: #92400e;">
                                    <strong>Catatan Hasil Pengecekan Admin:</strong><br>
                                    {{ $selectedTiket->catatan_admin }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="button" onclick="toggleRevisiForm()" class="btn-pill-primary" style="background: #d97706; border-color: #d97706; font-size: 13px; padding: 8px 18px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);">
                                <span><i class="fi fi-rr-edit"></i> Perbaiki Variabel Sekarang</span>
                            </button>
                        </div>
                    </div>

                    <!-- Collapsible Form Edit / Revisi Variabel -->
                    <div id="boxRevisiVariabel" style="margin-top: 20px; padding-top: 18px; border-top: 1px dashed #fcd34d; display: none;">
                        <form action="{{ route('tiket.update-variabel', $selectedTiket->id) }}" method="POST">
                            @csrf
                            <div style="font-size: 13px; font-weight: 700; color: #78350f; margin-bottom: 6px;">
                                Silakan sesuaikan daftar variabel DTSEN berikut sesuai catatan admin di atas:
                            </div>
                            <div style="font-size: 12px; color: #92400e; margin-bottom: 12px;">
                                Pastikan variabel yang Anda inputkan tercantum pada lampiran Surat Edaran resmi.
                            </div>

                            <div id="revisiVarContainer" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 14px;">
                                @php
                                    $curVars = $selectedTiket->variabel_dtsen ?? [];
                                    if (empty($curVars)) $curVars = [''];
                                @endphp
                                @foreach($curVars as $idx => $v)
                                    <div class="revisi-row" style="display: flex; gap: 10px; align-items: center;">
                                        <span class="revisi-label" style="font-size: 12px; font-weight: 700; color: #b45309; min-width: 90px; font-family: var(--font-mono);">Variabel {{ $idx + 1 }}:</span>
                                        <input type="text" name="variabel_dtsen[]" value="{{ $v }}" class="form-input" style="flex: 1; height: 38px; font-size: 13px;" placeholder="Contoh: NIK, Nama, Alamat, Status Kesejahteraan..." required>
                                        <button type="button" onclick="hapusBarisRevisi(this)" class="btn-pill-outline" style="color: #dc2626; border-color: #fca5a5; padding: 4px 10px; font-size: 12px;" title="Hapus baris variabel ini">✕</button>
                                    </div>
                                @endforeach
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <button type="button" onclick="tambahKolomRevisi()" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px; color: #b45309; border-color: #fcd34d; background: #fff;">
                                    + Tambah Kolom Variabel
                                </button>
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" onclick="toggleRevisiForm()" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px; background: #fff;">
                                        Tutup
                                    </button>
                                    <button type="submit" class="btn-pill-primary" style="background: #166534; border-color: #166534; font-size: 12px; padding: 7px 18px;">
                                        <i class="fi fi-rr-disk"></i> Simpan & Ajukan Ulang ke Admin
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="progress-top-info">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                        <span class="ticket-tag-chip">{{ $selectedTiket ? $selectedTiket->nomor_tiket : 'TIKET-TERKINI' }}</span>
                        @if($selectedTiket && $selectedTiket->status === 'PERLU_PERBAIKAN')
                            <span class="badge-status pending" style="font-size: 12px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;"><i class="fi fi-rr-exclamation"></i> PERLU PERBAIKAN OPD</span>
                        @elseif($selectedTiket && $selectedTiket->step5_done)
                            <span class="badge-status success" style="font-size: 12px;"><i class="fi fi-rr-check"></i> SELESAI (100%)</span>
                        @else
                            <span class="badge-status pending" style="font-size: 12px;">⏳ SEDANG DIPROSES</span>
                        @endif
                    </div>
                    <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                        Progress Status Permohonan Bagi Pakai DTSEN
                    </h2>
                    <div style="font-size: 13px; color: #64748b;">
                        @if($selectedTiket)
                            Surat Edaran: <strong style="color: #1f2937;">{{ $selectedTiket->no_surat_edaran }}</strong> | Waktu Buat: {{ $selectedTiket->created_at->format('d/m/Y H:i') }} WIB
                        @else
                            Pantauan real-time alur proses bagi pakai data DTSEN Anda.
                        @endif
                    </div>
                </div>

                <div style="text-align: right;">
                    <span style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Status Terkini:</span>
                    <div style="font-size: 14px; font-weight: 700; color: {{ ($selectedTiket && $selectedTiket->status === 'PERLU_PERBAIKAN') ? '#d97706' : (($selectedTiket && $selectedTiket->step5_done) ? '#166534' : '#0b57d0') }}; margin-top: 2px;">
                        @if($selectedTiket && $selectedTiket->status === 'PERLU_PERBAIKAN')
                            Perlu Perbaikan Variabel vs SE
                        @else
                            {{ $selectedTiket ? $selectedTiket->current_status_label : 'Menunggu Pengajuan Tiket' }}
                        @endif
                    </div>
                </div>
            </div>

            @php
                $pct = $selectedTiket ? $selectedTiket->progress_percent : 20;
            @endphp

            <!-- Overall Progress Bar Track -->
            <div class="progress-gauge-box">
                <div class="progress-gauge-labels">
                    <span style="color: #475569; font-weight: 600;">Progress Alur Tahapan Layanan DTSEN</span>
                    <span style="color: {{ ($selectedTiket && $selectedTiket->step5_done) ? '#166534' : '#0b57d0' }}; font-weight: 700;">
                        {{ $pct }}% Selesai
                    </span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ $pct }}%;"></div>
                </div>
            </div>

            <!-- 5-Step Process Flow: Progress 1, Progress 2, Progress 3, dst. -->
            <div class="step-flow">
                @foreach(\App\Models\TiketDtsen::$stepsDefinition as $stepNum => $stepDef)
                    @php
                        $isDone = $selectedTiket ? $selectedTiket->isStepDone($stepNum) : ($stepNum === 1);
                        $isActive = $selectedTiket ? $selectedTiket->isStepActive($stepNum) : ($stepNum === 2);
                        $nodeClass = $isDone ? 'is-done' : ($isActive ? 'is-active' : 'is-pending');
                        $stepColAt = "step{$stepNum}_at";
                        $timeDone = $selectedTiket ? $selectedTiket->$stepColAt : null;
                    @endphp
                    
                    <div class="step-node {{ $nodeClass }}">
                        <div class="step-node-icon">
                            @if($isDone)
                                <!-- Centang Hijau (✓) -->
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            @elseif($isActive)
                                <span>{{ $stepNum }}</span>
                            @else
                                <span>{{ $stepNum }}</span>
                            @endif
                        </div>

                        <div class="step-node-content">
                            <div class="step-node-title" style="font-size: 14px; font-weight: 700; color: #0f172a;">
                                Progress {{ $stepNum }}
                            </div>

                            @if($isDone)
                                <span class="step-badge-status" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                                    ✓ Selesai {{ $timeDone ? '(' . $timeDone->format('d/m H:i') . ')' : '' }}
                                </span>
                            @elseif($stepNum === 1 && $selectedTiket && $selectedTiket->status === 'PERLU_PERBAIKAN')
                                <span class="step-badge-status" style="background: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
                                    ⚠️ Perlu Perbaikan
                                </span>
                            @elseif($isActive)
                                <span class="step-badge-status" style="background: #eef2fa; color: #0b57d0; border: 1px solid #d2e3fc;">
                                    ● Sedang Berjalan
                                </span>
                            @else
                                <span class="step-badge-status" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;">
                                    Menunggu
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($selectedTiket)
                <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-divider); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Catatan Verifikator:</span>
                        <div style="font-size: 13px; color: #1f2937; margin-top: 2px;">
                            {{ $selectedTiket->catatan_admin ?: 'Tiket sedang dalam antrean verifikasi alur progress.' }}
                        </div>
                    </div>
                    @if(!$user->isSuperAdmin())
                        <a href="{{ route('formulir.index', ['track_id' => $selectedTiket->id]) }}" class="btn-row-action print" style="font-size: 12px;">
                            <span><i class="fi fi-rr-eye"></i> Lihat Detail Tiket Ini</span>
                        </a>
                    @else
                        <a href="{{ route('admin.riwayat') }}" class="btn-row-action print" style="font-size: 12px;">
                            <span><i class="fi fi-rr-crown"></i> Buka Panel Pengelolaan Admin</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- 3. Tabel Data Riwayat Bagi Data DTSEN Resmi (Read-Only untuk Akun OPD) -->
        <div class="form-card" style="padding: 28px 32px; border-radius: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                        <span><i class="fi fi-rr-document"></i> Tabel Data Riwayat Bagi Data DTSEN Resmi</span>
                        <span style="font-size: 11px; font-weight: 700; background: #f0fdf4; color: #166534; padding: 3px 8px; border-radius: 6px; border: 1px solid #bbf7d0;">
                            TERVERIFIKASI
                        </span>
                    </h3>
                    <p style="font-size: 13px; color: #64748b; margin-top: 4px;">
                        Rekapitulasi resmi berita acara serah terima dan proses bagi pakai data DTSEN instansi Anda.
                    </p>
                </div>

                <div style="font-size: 11px; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; padding: 7px 14px; border-radius: 8px;">
                    <i class="fi fi-rr-lock"></i> <em>Tabel ini diinput resmi oleh Admin dan bersifat Read-Only bagi Perangkat Daerah.</em>
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="min-width: 220px;">1. Nama Instansi</th>
                            <th style="min-width: 220px;">2. Surat Edaran</th>
                            <th style="min-width: 150px; white-space: nowrap;">3. Waktu Tiket Dibuat</th>
                            <th style="min-width: 220px;">4. Status & Nomor BAST</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($riwayatBagiDatas ?? []) as $item)
                            <tr>
                                <!-- 1. Nama Instansi -->
                                <td>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 14px;">
                                        <a href="{{ route('opd.riwayat-bagi-data.show', $item->id) }}" style="color: #0b57d0; text-decoration: none;" title="Buka Detail">
                                            {{ $item->nama_instansi }}
                                        </a>
                                    </div>
                                    @if($item->tiket)
                                        <div style="font-size: 11px; color: #64748b; font-family: var(--font-mono); margin-top: 2px;">
                                            Ref Tiket: <a href="{{ route('opd.tiket.show', $item->tiket->id) }}" style="color: #0b57d0; text-decoration: none; font-weight: 600;">#{{ $item->tiket->nomor_tiket }}</a>
                                        </div>
                                    @endif
                                </td>

                                <!-- 2. Surat Edaran (CLICKABLE KE PAGE BARU) -->
                                <td>
                                    <a href="{{ route('opd.riwayat-bagi-data.show', $item->id) }}" class="badge-se-clickable" title="Klik untuk membuka detail lengkap Surat Edaran, Petugas Hadir & Laptop">
                                        <i class="fi fi-rr-document"></i>
                                        <span>{{ $item->no_surat_edaran }}</span>
                                    </a>
                                </td>

                                <!-- 3. Tanggal dan Waktu Tiket Dibuat -->
                                <td style="white-space: nowrap;">
                                    <div style="font-size: 12px; font-weight: 600; color: #1e293b;">
                                        {{ $item->tanggal_tiket_dibuat ? $item->tanggal_tiket_dibuat->format('d/m/Y') : '-' }}
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                        {{ $item->tanggal_tiket_dibuat ? $item->tanggal_tiket_dibuat->format('H:i') . ' WIB' : '-' }}
                                    </div>
                                </td>

                                <!-- 4. Tanda Tangan BAST / Proses Bagi Data Selesai -->
                                <td>
                                    <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; margin-bottom: 4px;">
                                        ✓ {{ $item->status_bast }}
                                    </span>
                                    <div style="font-size: 12px; color: #0f172a; font-weight: 600;">
                                        No. BAST: {{ $item->no_bast ?: '-' }}
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                        Waktu BAST: {{ $item->tanggal_bast_selesai ? $item->tanggal_bast_selesai->format('d F Y, H:i') . ' WIB' : 'Selesai Sesuai Jadwal' }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 48px 20px; color: #64748b;">
                                    <div style="font-size: 32px; margin-bottom: 10px; color: #94a3b8;"><i class="fi fi-rr-folder"></i></div>
                                    <div style="font-weight: 700; font-size: 15px; color: #0f172a;">Belum Ada Data Riwayat Bagi Data DTSEN Resmi</div>
                                    <div style="font-size: 13px; color: #64748b; margin-top: 4px;">
                                        Setelah tiket permohonan Anda diselesaikan dan BAST ditandatangani, data resmi akan diinput oleh Admin dan tercatat di sini.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Unified Master JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function toggleRevisiForm() {
            const box = document.getElementById('boxRevisiVariabel');
            if (!box) return;
            if (box.style.display === 'none' || box.style.display === '') {
                box.style.display = 'block';
                box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                box.style.display = 'none';
            }
        }

        function tambahKolomRevisi() {
            const container = document.getElementById('revisiVarContainer');
            if (!container) return;
            const rows = container.querySelectorAll('.revisi-row');
            const nextIdx = rows.length + 1;

            const div = document.createElement('div');
            div.className = 'revisi-row';
            div.style.cssText = 'display: flex; gap: 10px; align-items: center;';
            div.innerHTML = `
                <span class="revisi-label" style="font-size: 12px; font-weight: 700; color: #b45309; min-width: 90px; font-family: var(--font-mono);">Variabel ${nextIdx}:</span>
                <input type="text" name="variabel_dtsen[]" value="" class="form-input" style="flex: 1; height: 38px; font-size: 13px;" placeholder="Isi variabel DTSEN yang dibutuhkan..." required>
                <button type="button" onclick="hapusBarisRevisi(this)" class="btn-pill-outline" style="color: #dc2626; border-color: #fca5a5; padding: 4px 10px; font-size: 12px;" title="Hapus baris variabel ini">✕</button>
            `;
            container.appendChild(div);
            div.querySelector('input').focus();
        }

        function hapusBarisRevisi(btn) {
            const row = btn.closest('.revisi-row');
            const container = document.getElementById('revisiVarContainer');
            if (container && container.querySelectorAll('.revisi-row').length <= 1) {
                alert('Setidaknya minimal 1 kolom variabel harus tetap ada.');
                return;
            }
            row.remove();
            renumberRevisiVars();
        }

        function renumberRevisiVars() {
            const container = document.getElementById('revisiVarContainer');
            if (!container) return;
            const rows = container.querySelectorAll('.revisi-row');
            rows.forEach((row, i) => {
                const label = row.querySelector('.revisi-label');
                if (label) label.textContent = `Variabel ${i + 1}:`;
            });
        }
    </script>
</body>
</html>
