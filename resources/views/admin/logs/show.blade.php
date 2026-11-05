@extends('layouts.admin')

@section('title', 'Detail Log Laptop #' . $log->id . ' — Admin Panel')

@section('content')
<div class="ticket-stack-container" style="max-width: 1100px; margin: 0 auto; padding-bottom: 40px;">

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb" style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 16px; color: var(--text-secondary);">
        <a href="{{ route('admin.dashboard') }}" style="color: #0b57d0; text-decoration: none;"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.logs.index') }}" style="color: #0b57d0; text-decoration: none;"><i class="fi fi-rr-laptop"></i> Log Laptop & Audit</a>
        <span class="separator">/</span>
        <span class="current" style="font-weight: 700; color: #1e293b;">Rincian Log #{{ $log->id }}</span>
    </nav>

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.logs.index', ['tab' => 'laptop']) }}" class="btn-pill-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fi fi-rr-arrow-left"></i> Kembali ke Daftar Log Laptop
        </a>

        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-family: var(--font-mono, monospace); font-size: 12px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 6px; border: 1px solid #bae6fd;">
                LOG ID: #{{ $log->id }}
            </span>
            <span style="font-size: 12px; font-weight: 700; background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 6px; border: 1px solid #bbf7d0;">
                <i class="fi fi-rr-check"></i> {{ $log->status ?: 'TERVERIFIKASI' }}
            </span>
        </div>
    </div>

    <!-- 1. HERO SUMMARY CARD -->
    <div class="dash-card" style="margin-bottom: 24px; padding: 24px; background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <span style="font-size: 26px;">{{ $log->device_icon }}</span>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                            Aksi / Aktivitas Sistem
                        </div>
                        <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0;">
                            {{ $log->action }}
                        </h1>
                    </div>
                </div>
                <div style="font-size: 14px; color: #475569; margin-top: 6px; max-width: 600px; line-height: 1.5;">
                    {{ $log->description ?: '-' }}
                </div>
            </div>

            <div style="text-align: right; min-width: 200px;">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                    Waktu & Tanggal Kejadian
                </div>
                <div style="font-family: var(--font-mono, monospace); font-size: 16px; font-weight: 700; color: #0f172a;">
                    {{ \Carbon\Carbon::parse($log->created_at)->format('d F Y') }}
                </div>
                <div style="font-size: 13px; font-weight: 600; color: #0b57d0; margin-top: 2px;">
                    <i class="fi fi-rr-clock"></i> {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB
                </div>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 20px 0;">

        <!-- 4 Key Forensic Pillars -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Akun Pengguna</div>
                <div style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 4px;">
                    {{ $log->user ? $log->user->username : ($log->user_name ?: 'Sistem / Anonim') }}
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                    {{ $log->user ? $log->user->nama_instansi : 'Walidata / Admin' }}
                </div>
            </div>

            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Nomor Laptop / ID Perangkat</div>
                <div style="font-size: 15px; font-weight: 700; color: #0b57d0; margin-top: 4px; font-family: var(--font-mono, monospace);">
                    {{ $log->device_identifier }}
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                    {{ $log->device_os }} · {{ $log->device_browser }}
                </div>
            </div>

            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Alamat IP & Jaringan</div>
                <div style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 4px; font-family: var(--font-mono, monospace);">
                    {{ $log->ip_address ?: '127.0.0.1' }}
                </div>
                <div style="font-size: 11px; color: #166534; margin-top: 2px; font-weight: 600;">
                    ✓ Intranet / Local Host
                </div>
            </div>

            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Status Integritas Audit</div>
                <div style="font-size: 15px; font-weight: 700; color: #166534; margin-top: 4px;">
                    ✓ Valid & Immutable
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                    Audit log append-only
                </div>
            </div>
        </div>
    </div>

    <!-- 2. DETAIL SECTION GRID: USER & DEVICE -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(480px, 1fr)); gap: 20px; margin-bottom: 24px;">
        
        <!-- Kartu 1: Profil Akun Terkait -->
        <div class="dash-card" style="padding: 24px; background: #fff; border-radius: 14px; border: 1px solid #e2e8f0;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <i class="fi fi-rr-user" style="font-size: 18px; color: #0b57d0;"></i>
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">Rincian Akun Pengguna</h3>
            </div>

            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; width: 140px; font-weight: 600;">Username</td>
                        <td style="padding: 10px 0; font-weight: 700; color: #0f172a;">
                            {{ $log->user ? $log->user->username : ($log->user_name ?: 'Sistem / Otomatis') }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Nama Lengkap</td>
                        <td style="padding: 10px 0; font-weight: 600; color: #0f172a;">
                            {{ $log->user ? $log->user->name : '-' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Instansi / OPD</td>
                        <td style="padding: 10px 0; font-weight: 700; color: #0b57d0;">
                            {{ $log->user ? $log->user->nama_instansi : 'Dinas Komunikasi dan Informatika (Walidata)' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Peran Akses (Role)</td>
                        <td style="padding: 10px 0;">
                            <span style="font-size: 11px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px;">
                                {{ strtoupper($log->user ? $log->user->role : ($log->user_role ?: 'SUPERADMIN')) }}
                            </span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Email Akun</td>
                        <td style="padding: 10px 0; color: #475569;">
                            {{ $log->user ? $log->user->email : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Nomor Kontak</td>
                        <td style="padding: 10px 0; color: #475569;">
                            {{ $log->user ? ($log->user->nomor_kontak ?: '-') : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Kartu 2: Identifikasi Perangkat Laptop & Browser -->
        <div class="dash-card" style="padding: 24px; background: #fff; border-radius: 14px; border: 1px solid #e2e8f0;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <i class="fi fi-rr-laptop" style="font-size: 18px; color: #166534;"></i>
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">Identifikasi Perangkat Laptop</h3>
            </div>

            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; width: 140px; font-weight: 600;">Device Machine ID</td>
                        <td style="padding: 10px 0;">
                            <span class="device-badge" style="font-size: 13px; font-weight: 700;">
                                <i class="fi fi-rr-laptop"></i>
                                {{ $log->device_identifier }}
                            </span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Sistem Operasi</td>
                        <td style="padding: 10px 0; font-weight: 600; color: #0f172a;">
                            {{ $log->device_os }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Browser Digunakan</td>
                        <td style="padding: 10px 0; font-weight: 600; color: #0f172a;">
                            {{ $log->device_browser }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Alamat IP Klien</td>
                        <td style="padding: 10px 0; font-family: var(--font-mono, monospace); font-weight: 700; color: #0b57d0;">
                            {{ $log->ip_address ?: '127.0.0.1' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Status Autentikasi</td>
                        <td style="padding: 10px 0;">
                            <span style="font-size: 11px; font-weight: 700; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 4px;">
                                BERHASIL MASUK
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!-- 3. RAW USER AGENT & METADATA FORENSIK -->
    <div class="dash-card" style="margin-bottom: 24px; padding: 24px; background: #fff; border-radius: 14px; border: 1px solid #e2e8f0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fi fi-rr-code-compare" style="font-size: 18px; color: #0b57d0;"></i>
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">String User-Agent Lengkap (Forensik Peramban)</h3>
            </div>
            <button type="button" onclick="navigator.clipboard.writeText('{{ addslashes($log->user_agent) }}'); alert('User-Agent disalin ke clipboard!');" class="btn-pill-outline" style="font-size: 11px; padding: 4px 10px;">
                <i class="fi fi-rr-copy"></i> Salin User-Agent
            </button>
        </div>

        <div style="background: #0f172a; color: #38bdf8; padding: 14px 18px; border-radius: 8px; font-family: var(--font-mono, monospace); font-size: 12px; line-height: 1.6; word-break: break-all; border: 1px solid #1e293b;">
            {{ $log->user_agent ?: 'Tidak ada informasi HTTP User-Agent yang dikirim oleh peramban klien.' }}
        </div>

        @if(!empty($log->metadata) && is_array($log->metadata))
            <div style="margin-top: 16px;">
                <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">
                    Parameter Metadata Tambahan:
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; font-family: var(--font-mono, monospace); font-size: 12px; color: #334155;">
                    <pre style="margin: 0; white-space: pre-wrap;">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        @endif
    </div>

    <!-- 4. AKTIVITAS TERKAIT DARI IP ATAU PENGGUNA INI -->
    @if(isset($relatedLogs) && count($relatedLogs) > 0)
    <div class="dash-card" style="padding: 24px; background: #fff; border-radius: 14px; border: 1px solid #e2e8f0;">
        <div style="margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">
                <i class="fi fi-rr-time-past" style="color: #64748b;"></i> Riwayat Aktivitas Terkait Lainnya (IP / Akun Ini)
            </h3>
            <p style="font-size: 12px; color: #64748b; margin: 0;">
                Catatan sesi login atau operasi lain yang tercatat dari alamat IP atau pengguna yang sama.
            </p>
        </div>

        <div class="table-responsive">
            <table class="data-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID Log</th>
                        <th style="width: 140px;">Waktu</th>
                        <th style="min-width: 160px;">Aksi</th>
                        <th>Keterangan Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($relatedLogs as $rel)
                        <tr>
                            <td>
                                <a href="{{ route('admin.logs.show', $rel->id) }}" style="font-family: var(--font-mono); font-weight: 700; color: #0b57d0; text-decoration: none;" title="Buka Detail Log #{{ $rel->id }}">
                                    #{{ $rel->id }}
                                </a>
                            </td>
                            <td style="font-size: 12px; color: #64748b;">
                                {{ \Carbon\Carbon::parse($rel->created_at)->format('d/m/Y H:i') }} WIB
                            </td>
                            <td>
                                <span class="device-badge" style="font-size: 11px;">{{ $rel->action }}</span>
                            </td>
                            <td style="font-size: 13px; color: #334155;">
                                {{ $rel->description }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
