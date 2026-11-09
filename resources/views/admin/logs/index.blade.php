@extends('layouts.admin')

@section('content')
@php
    $activeTab = request('tab', $activeTab ?? 'laptop');
    $laptopLogs = $laptopLogs ?? collect([]);
    $securityLogs = $securityLogs ?? collect([]);
    $tiketLogs = $tiketLogs ?? collect([]);
    $allLogs = $allLogs ?? collect([]);
@endphp

<div class="ticket-stack-container" style="max-width: 100%; margin: 0 auto;">

    <!-- Card Utama Log & Audit -->
    <div class="card" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        
        <!-- Header: Judul & Tombol Ekspor CSV -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fi fi-rr-laptop" style="color: #2563eb; font-size: 24px; display: flex; align-items: center;"></i>
                <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">
                    Log Laptop & Audit Keamanan
                </h1>
            </div>

            <div>
                <a href="{{ route('admin.riwayat.export-logs', ['format' => 'csv']) }}" 
                   style="background: #0f172a; color: #ffffff; padding: 9px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background 0.15s ease;"
                   onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
                    <i class="fi fi-rr-download" style="font-size: 13px;"></i>
                    <span>Ekspor Log Audit (CSV)</span>
                </a>
            </div>
        </div>

        <!-- KPI / Statistik Ringkas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 24px;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px;">
                <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Total Log Aktivitas</div>
                <div style="font-size: 22px; font-weight: 800; color: #0f172a;">{{ number_format($totalLogs ?? 0, 0, ',', '.') }}</div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px;">
                <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Laptop / Device Terdeteksi</div>
                <div style="font-size: 22px; font-weight: 800; color: #16a34a;">{{ number_format($uniqueDevices ?? 0, 0, ',', '.') }}</div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px;">
                <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Login Hari Ini</div>
                <div style="font-size: 22px; font-weight: 800; color: #2563eb;">{{ number_format($todayLogs ?? 0, 0, ',', '.') }}</div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px;">
                <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Peringatan Keamanan</div>
                <div style="font-size: 22px; font-weight: 800; color: #dc2626;">{{ number_format($securityAlerts ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Tab Navigasi Subsubsub WordPress Style -->
        <div class="wp-subsubsub" style="margin-bottom: 20px; font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
            <a href="javascript:void(0)" onclick="switchLogTab('laptop')" id="tab-laptop" 
               class="wp-sub-tab {{ $activeTab === 'laptop' ? 'active' : '' }}" 
               style="text-decoration: none; color: {{ $activeTab === 'laptop' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $activeTab === 'laptop' ? '700' : '500' }};">
                Log Laptop <span style="color: #64748b; font-weight: normal;">({{ number_format(method_exists($laptopLogs, 'total') ? $laptopLogs->total() : count($laptopLogs)) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="javascript:void(0)" onclick="switchLogTab('keamanan')" id="tab-keamanan" 
               class="wp-sub-tab {{ $activeTab === 'keamanan' ? 'active' : '' }}" 
               style="text-decoration: none; color: {{ $activeTab === 'keamanan' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $activeTab === 'keamanan' ? '700' : '500' }};">
                Keamanan & Akses Ditolak <span style="color: #64748b; font-weight: normal;">({{ number_format(method_exists($securityLogs, 'total') ? $securityLogs->total() : count($securityLogs)) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="javascript:void(0)" onclick="switchLogTab('tiket')" id="tab-tiket" 
               class="wp-sub-tab {{ $activeTab === 'tiket' ? 'active' : '' }}" 
               style="text-decoration: none; color: {{ $activeTab === 'tiket' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $activeTab === 'tiket' ? '700' : '500' }};">
                Aktivitas Tiket <span style="color: #64748b; font-weight: normal;">({{ number_format(method_exists($tiketLogs, 'total') ? $tiketLogs->total() : count($tiketLogs)) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="javascript:void(0)" onclick="switchLogTab('semua')" id="tab-semua" 
               class="wp-sub-tab {{ $activeTab === 'semua' ? 'active' : '' }}" 
               style="text-decoration: none; color: {{ $activeTab === 'semua' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $activeTab === 'semua' ? '700' : '500' }};">
                Semua Jejak Audit <span style="color: #64748b; font-weight: normal;">({{ number_format(method_exists($allLogs, 'total') ? $allLogs->total() : count($allLogs)) }})</span>
            </a>
        </div>

        <!-- ============================================================== -->
        <!-- SUB-PAGE 1: LOG LAPTOP                                          -->
        <!-- ============================================================== -->
        <div class="sub-page-panel {{ $activeTab === 'laptop' ? 'active' : '' }}" id="panel-laptop">
            <div class="table-responsive" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 90px;">
                                ID LOG
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                WAKTU & TANGGAL
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                                AKUN PENGGUNA
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 200px;">
                                NOMOR LAPTOP / DEVICE ID
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 140px;">
                                ALAMAT IP
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 130px; text-align: center;">
                                STATUS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laptopLogs as $log)
                            <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                                <!-- 1. ID Log & Hover Action -->
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div>
                                        <a href="{{ route('admin.logs.show', $log->id) }}" style="font-family: var(--font-mono); font-weight: 700; color: #2563eb; font-size: 13px; text-decoration: none;" title="Buka Rincian Log #{{ $log->id }}">
                                            #{{ $log->id }}
                                        </a>
                                    </div>
                                    <div class="wp-row-actions">
                                        <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </td>

                                <!-- 2. Waktu -->
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div style="font-weight: 600; color: #1e293b; font-size: 12px;">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</div>
                                </td>

                                <!-- 3. Akun Pengguna -->
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div>
                                        <a href="{{ route('admin.logs.show', $log->id) }}" style="font-weight: 700; color: #2563eb; text-decoration: none; font-size: 13px;">
                                            {{ $log->user ? $log->user->username : ($log->user_name ?: 'Sistem / Anonim') }}
                                        </a>
                                    </div>
                                    @if($log->user && $log->user->nama_instansi)
                                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                            {{ $log->user->nama_instansi }}
                                        </div>
                                    @endif
                                </td>

                                <!-- 4. Nomor Laptop -->
                                <td style="padding: 14px 16px; vertical-align: top;">
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
                                        <div style="display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; padding: 4px 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-family: var(--font-mono); font-size: 12px; color: #1e293b; font-weight: 600;">
                                            <i class="fi fi-rr-laptop" style="color: #2563eb;"></i>
                                            <span>{{ $laptopId }}</span>
                                        </div>
                                        @if($log->device_os)
                                            <div style="font-size: 11px; color: #64748b; margin-top: 3px;">OS: {{ $log->device_os }}</div>
                                        @endif
                                    @else
                                        <span style="color: #94a3b8; font-size: 12px; font-style: italic;">Tidak Terbaca</span>
                                    @endif
                                </td>

                                <!-- 5. IP Address -->
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <span style="font-family: var(--font-mono); font-size: 12px; color: #475569;">
                                        {{ $log->ip_address ?: '127.0.0.1' }}
                                    </span>
                                </td>

                                <!-- 6. Status -->
                                <td style="padding: 14px 16px; vertical-align: top; text-align: center;">
                                    <span style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: inline-block;">
                                        ✓ BERHASIL LOGIN
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                                    <div style="font-size: 32px; margin-bottom: 8px;"><i class="fi fi-rr-laptop"></i></div>
                                    <div style="font-weight: 700; font-size: 14px; color: #334155;">Belum Ada Riwayat Aktivitas Login Laptop</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($laptopLogs, 'hasPages') && $laptopLogs->hasPages())
                <div style="padding: 16px 0 0 0; margin-top: 16px; border-top: 1px solid #f1f5f9;">
                    {{ $laptopLogs->links() }}
                </div>
            @endif
        </div>

        <!-- ============================================================== -->
        <!-- SUB-PAGE 2: LOG KEAMANAN                                        -->
        <!-- ============================================================== -->
        <div class="sub-page-panel {{ $activeTab === 'keamanan' ? 'active' : '' }}" id="panel-keamanan">
            <div class="table-responsive" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 90px;">
                                ID
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                WAKTU
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 160px;">
                                JENIS INSIDEN
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                                KETERANGAN / PESAN AUDIT
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 140px;">
                                ALAMAT IP
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 110px; text-align: center;">
                                STATUS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($securityLogs as $log)
                            <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #2563eb; font-weight: 700; font-family: var(--font-mono); font-size: 13px; text-decoration: none;">
                                        #{{ $log->id }}
                                    </a>
                                    <div class="wp-row-actions">
                                        <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div style="font-weight: 600; color: #1e293b; font-size: 12px;">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <span style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px; font-family: var(--font-mono); display: inline-block;">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div style="font-weight: 600; color: #1e293b; font-size: 13px;">{{ $log->description }}</div>
                                    @if($log->user_agent)
                                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">{{ \Illuminate\Support\Str::limit($log->user_agent, 80) }}</div>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <span style="font-family: var(--font-mono); font-size: 12px; color: #475569;">{{ $log->ip_address ?: '-' }}</span>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top; text-align: center;">
                                    <span style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: inline-block;">
                                        ✕ DITOLAK
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                                    <div style="font-size: 32px; margin-bottom: 8px; color: #16a34a;"><i class="fi fi-rr-shield-check"></i></div>
                                    <div style="font-weight: 700; font-size: 14px; color: #334155;">Sistem Aman</div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Tidak ada catatan insiden keamanan atau penolakan akses.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($securityLogs, 'hasPages') && $securityLogs->hasPages())
                <div style="padding: 16px 0 0 0; margin-top: 16px; border-top: 1px solid #f1f5f9;">
                    {{ $securityLogs->links() }}
                </div>
            @endif
        </div>

        <!-- ============================================================== -->
        <!-- SUB-PAGE 3: LOG AKTIVITAS TIKET                                -->
        <!-- ============================================================== -->
        <div class="sub-page-panel {{ $activeTab === 'tiket' ? 'active' : '' }}" id="panel-tiket">
            <div class="table-responsive" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 90px;">
                                ID
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                WAKTU
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 160px;">
                                TINDAKAN
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                                KETERANGAN AKTIVITAS
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                PELAKSANA
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 100px; text-align: center;">
                                STATUS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tiketLogs as $log)
                            <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #2563eb; font-weight: 700; font-family: var(--font-mono); font-size: 13px; text-decoration: none;">
                                        #{{ $log->id }}
                                    </a>
                                    <div class="wp-row-actions">
                                        <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div style="font-weight: 600; color: #1e293b; font-size: 12px;">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <span style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px; font-family: var(--font-mono); display: inline-block;">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div style="font-size: 13px; color: #334155;">{{ $log->description }}</div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div style="font-weight: 600; color: #1e293b; font-size: 13px;">{{ $log->user ? $log->user->username : 'Admin Sistem' }}</div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top; text-align: center;">
                                    <span style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: inline-block;">
                                        ✓ SUKSES
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                                    <div style="font-size: 32px; margin-bottom: 8px;"><i class="fi fi-rr-ticket"></i></div>
                                    <div style="font-weight: 700; font-size: 14px; color: #334155;">Belum Ada Riwayat Aktivitas Tiket</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($tiketLogs, 'hasPages') && $tiketLogs->hasPages())
                <div style="padding: 16px 0 0 0; margin-top: 16px; border-top: 1px solid #f1f5f9;">
                    {{ $tiketLogs->links() }}
                </div>
            @endif
        </div>

        <!-- ============================================================== -->
        <!-- SUB-PAGE 4: SEMUA JEJAK AUDIT                                  -->
        <!-- ============================================================== -->
        <div class="sub-page-panel {{ $activeTab === 'semua' ? 'active' : '' }}" id="panel-semua">
            <div class="table-responsive" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 90px;">
                                ID
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                WAKTU
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                AKSI / EVENT
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                                KETERANGAN AKTIVITAS
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                USER
                            </th>
                            <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 140px;">
                                ALAMAT IP
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allLogs as $log)
                            <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #2563eb; font-weight: 700; font-family: var(--font-mono); font-size: 13px; text-decoration: none;">
                                        #{{ $log->id }}
                                    </a>
                                    <div class="wp-row-actions">
                                        <a href="{{ route('admin.logs.show', $log->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div style="font-weight: 600; color: #1e293b; font-size: 12px;">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <span style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px; font-family: var(--font-mono); display: inline-block;">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div style="font-size: 13px; color: #334155;">{{ $log->description }}</div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <div style="font-weight: 600; color: #1e293b; font-size: 13px;">{{ $log->user ? $log->user->username : 'Sistem' }}</div>
                                </td>
                                <td style="padding: 14px 16px; vertical-align: top;">
                                    <span style="font-family: var(--font-mono); font-size: 12px; color: #475569;">{{ $log->ip_address ?: '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                                    <div style="font-size: 32px; margin-bottom: 8px;"><i class="fi fi-rr-list"></i></div>
                                    <div style="font-weight: 700; font-size: 14px; color: #334155;">Belum Ada Jejak Audit yang Tercatat</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($allLogs, 'hasPages') && $allLogs->hasPages())
                <div style="padding: 16px 0 0 0; margin-top: 16px; border-top: 1px solid #f1f5f9;">
                    {{ $allLogs->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    .sub-page-panel {
        display: none;
    }
    .sub-page-panel.active {
        display: block;
    }
    .wp-table-row:hover {
        background-color: #f8fafc !important;
    }
    .wp-row-actions {
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.12s ease-in-out, visibility 0.12s ease-in-out;
        font-size: 12px;
        margin-top: 4px;
        line-height: 1.2;
    }
    .wp-table-row:hover .wp-row-actions {
        visibility: visible;
        opacity: 1;
    }
    .wp-row-actions a:hover {
        text-decoration: underline !important;
    }
</style>

<script>
    function switchLogTab(tabName) {
        // Update URL query string without reloading page
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);

        // Update tab links
        const tabs = document.querySelectorAll('.wp-subsubsub .wp-sub-tab');
        tabs.forEach(tab => {
            tab.style.color = '#2563eb';
            tab.style.fontWeight = '500';
            tab.classList.remove('active');
        });
        const activeTabEl = document.getElementById('tab-' + tabName);
        if (activeTabEl) {
            activeTabEl.style.color = '#0f172a';
            activeTabEl.style.fontWeight = '700';
            activeTabEl.classList.add('active');
        }

        // Update panels
        const panels = document.querySelectorAll('.sub-page-panel');
        panels.forEach(panel => panel.classList.remove('active'));
        const targetPanel = document.getElementById('panel-' + tabName);
        if (targetPanel) targetPanel.classList.add('active');
    }
</script>
@endsection