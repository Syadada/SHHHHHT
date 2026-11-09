@extends('layouts.admin')

@section('title', 'Dashboard Layanan Bagi Pakai DTSEN — Admin Panel')

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
    .wp-row-actions a:hover,
    .wp-row-actions button:hover {
        text-decoration: underline !important;
    }
</style>
@endsection

@section('content')

<!-- Hero Header -->
                <div class="dash-hero">
                    <div>
                        <h1 class="dash-hero-title">
                            Dashboard Layanan Bagi Pakai DTSEN
                        </h1>
                        
                    </div>
                    <div class="dash-hero-actions">
                        <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="btn-pill-primary">
                            <i class="fi fi-rr-share"></i> Kelola Layanan Bagi Pakai
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
                        
                    </div>

                    <div class="dash-metric-card">
                        <div class="dash-metric-label">Sedang Diproses</div>
                        <div class="dash-metric-value" style="color: var(--blue-primary);">{{ number_format($totalDiproses ?? 0) }}</div>
                        
                    </div>

                    <div class="dash-metric-card">
                        <div class="dash-metric-label">Selesai Dibagipakaikan</div>
                        <div class="dash-metric-value" style="color: var(--green-primary);">{{ number_format($totalSelesai ?? 0) }}</div>
                        
                    </div>

                    <div class="dash-metric-card">
                        <div class="dash-metric-label">Tiket Hari Ini</div>
                        <div class="dash-metric-value" style="color: var(--purple-primary);">{{ number_format($totalHariIni ?? 0) }}</div>
                        
                    </div>
                </div>

                <!-- 1. Permintaan Layanan Terbaru (5 Item) -->
                <div class="card" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fi fi-rr-share" style="color: #2563eb; font-size: 20px; display: flex; align-items: center;"></i>
                            <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; letter-spacing: -0.2px;">
                                Permintaan Layanan Terbaru
                            </h2>
                            <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 600; padding: 2px 10px; border-radius: 9999px; border: 1px solid #bfdbfe;">
                                5 Item Teratas
                            </span>
                        </div>
                        <div>
                            <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" 
                               style="background: #0f172a; color: #ffffff; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s ease;"
                               onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
                                <span>Semua Permintaan</span>
                                <i class="fi fi-rr-arrow-right" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 170px;">
                                        ID PEKERJAAN
                                    </th>
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                                        OPD
                                    </th>
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 180px;">
                                        SURAT EDARAN
                                    </th>
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                        VARIABEL DTSEN
                                    </th>
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                        STATUS & PROGRESS
                                    </th>
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
                                        $activeStepNum = 1;
                                        if (!$item->step1_done) $activeStepNum = 1;
                                        elseif (!$item->step2_done) $activeStepNum = 2;
                                        elseif (!$item->step3_done) $activeStepNum = 3;
                                        elseif (!$item->step4_done) $activeStepNum = 4;
                                        elseif (!$item->step5_done) $activeStepNum = 5;
                                        else $activeStepNum = 5;
                                    @endphp
                                    <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                                        <!-- 1. ID PEKERJAAN & Baris Aksi Hover -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <div>
                                                <a href="{{ route('admin.tiket.show', $item->id) }}" style="font-family: var(--font-mono); font-weight: 700; color: #2563eb; font-size: 13px; text-decoration: none;" title="Buka Detail Tiket">
                                                    {{ $item->nomor_tiket }}
                                                </a>
                                            </div>
                                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                                {{ $item->created_at->format('d/m/Y H:i') }} WIB
                                            </div>

                                            <!-- Baris Aksi: Muncul saat mouse hover baris ini -->
                                            <div class="wp-row-actions">
                                                <a href="{{ route('admin.tiket.show', $item->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                                    Detail
                                                </a>
                                                <span style="color: #cbd5e1; margin: 0 4px;">|</span>
                                                @if(!$item->isCompleted())
                                                    <a href="{{ route('admin.bagi-pakai-dtsen.edit', $item->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                                        Edit
                                                    </a>
                                                    <span style="color: #cbd5e1; margin: 0 4px;">|</span>
                                                    <form action="{{ route('admin.riwayat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus tiket [{{ $item->nomor_tiket }}] milik {{ $item->nama_instansi }}? Tindakan ini tidak dapat dibatalkan.');" style="display: inline; margin: 0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="color: #dc2626; background: none; border: none; padding: 0; font-size: 12px; font-weight: 500; cursor: pointer; text-decoration: none;">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.bagi-pakai-dtsen.formulir', $item->id) }}" target="_blank" style="color: #166534; text-decoration: none; font-weight: 600;">
                                                        Formulir Bukti
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- 2. Nama Instansi & PIC -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <div>
                                                <a href="{{ route('admin.tiket.show', $item->id) }}" style="font-weight: 700; color: #2563eb; text-decoration: none; font-size: 13px;" title="Klik untuk membuka informasi selengkapnya">
                                                    {{ $item->nama_instansi }}
                                                </a>
                                            </div>
                                            @if($item->nama_pemohon)
                                                <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                                    PIC: {{ $item->nama_pemohon }} ({{ $item->kontak_pemohon ?: '-' }})
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 3. Surat Edaran -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <div style="font-weight: 600; color: #1e293b; font-size: 13px;">
                                                {{ $item->no_surat_edaran }}
                                            </div>
                                            @if($item->file_surat)
                                                <div style="margin-top: 4px;">
                                                    <a href="{{ route('admin.riwayat.download', $item->id) }}" style="font-size: 11px; color: #2563eb; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas Surat Edaran">
                                                        <i class="fi fi-rr-download" style="font-size: 10px;"></i> Berkas SE
                                                    </a>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 4. Variabel DTSEN & Status Verifikasi -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <span style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 9999px; display: inline-block;">
                                                {{ count($item->variabel_dtsen ?? []) }} Variabel
                                            </span>
                                            @if($item->status === 'PERLU_PERBAIKAN')
                                                <div style="margin-top: 5px;">
                                                    <span style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                                        Perlu Perbaikan
                                                    </span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 5. Status & Progress -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <div style="background: #e2e8f0; border-radius: 9999px; height: 6px; width: 60px; overflow: hidden;">
                                                    <div style="background: {{ $item->step5_done ? '#16a34a' : '#0284c7' }}; width: {{ $item->progress_percent }}%; height: 100%; border-radius: 9999px;"></div>
                                                </div>
                                                <span style="font-size: 12px; font-weight: 600; color: {{ $item->step5_done ? '#16a34a' : '#0284c7' }};">
                                                    {{ $item->progress_percent }}%
                                                </span>
                                            </div>
                                            <div style="font-size: 11px; margin-top: 4px; font-weight: 500; color: #64748b;">
                                                @if($item->step5_done)
                                                    <span style="color: #16a34a; font-weight: 600;">Selesai 100%</span>
                                                @else
                                                    Progress {{ $activeStepNum }}/5
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                                            <div style="font-size: 32px; margin-bottom: 8px; color: #16a34a;"><i class="fi fi-rr-check-circle"></i></div>
                                            <div style="font-weight: 700; font-size: 14px; color: #334155;">Tidak ada permintaan layanan yang sedang aktif</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Aktivitas Terbaru (10 Entri) -->
                <div class="card" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fi fi-rr-time-past" style="color: #2563eb; font-size: 20px; display: flex; align-items: center;"></i>
                            <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; letter-spacing: -0.2px;">
                                Aktivitas Terbaru
                            </h2>
                            <span style="background: #f1f5f9; color: #475569; font-size: 11px; font-weight: 600; padding: 2px 10px; border-radius: 9999px;">
                                10 Log Terakhir
                            </span>
                        </div>
                        <div>
                            <a href="{{ route('admin.logs.index') }}" 
                               style="background: #0f172a; color: #ffffff; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s ease;"
                               onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
                                <span>Semua Log</span>
                                <i class="fi fi-rr-arrow-right" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                                        WAKTU KEJADIAN
                                    </th>
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 140px;">
                                        AKSI / EVENT
                                    </th>
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 160px;">
                                        PENGGUNA & PERAN
                                    </th>
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                                        KETERANGAN AKTIVITAS
                                    </th>
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 140px;">
                                        IP & PERANGKAT
                                    </th>
                                    <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 100px; text-align: center;">
                                        STATUS
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $logsToShow = (isset($latestAuditLogs) && $latestAuditLogs->isNotEmpty()) 
                                        ? $latestAuditLogs 
                                        : ((isset($auditLogs) && is_iterable($auditLogs)) ? collect($auditLogs)->take(10) : collect());
                                @endphp
                                @forelse($logsToShow as $log)
                                    <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                                        <!-- 1. Waktu -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <div style="font-weight: 600; color: #1e293b; font-size: 12px;">
                                                {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }} WIB
                                            </div>
                                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                                {{ $log->created_at ? $log->created_at->diffForHumans() : '' }}
                                            </div>
                                        </td>

                                        <!-- 2. Event / Aksi -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <span style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px; font-family: var(--font-mono); display: inline-block;">
                                                {{ $log->action }}
                                            </span>
                                        </td>

                                        <!-- 3. Pengguna & Peran -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <div style="font-weight: 600; color: #1e293b; font-size: 13px;">
                                                {{ $log->user_name ?: ($log->user ? $log->user->name : 'Sistem / Tamu') }}
                                            </div>
                                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                                {{ $log->role ?: ($log->user ? $log->user->role : 'system') }}
                                            </div>
                                        </td>

                                        <!-- 4. Keterangan -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <div style="font-size: 13px; color: #334155; line-height: 1.45;">
                                                {{ $log->description ?: '-' }}
                                            </div>
                                        </td>

                                        <!-- 5. IP & Perangkat -->
                                        <td style="padding: 14px 16px; vertical-align: top;">
                                            <div style="font-family: var(--font-mono); font-size: 12px; color: #475569;">
                                                {{ $log->ip_address ?: '-' }}
                                            </div>
                                            @php
                                                $devId = $log->device_id ?: ($log->details['device_id'] ?? null);
                                            @endphp
                                            @if($devId)
                                                <div style="font-size: 11px; color: #2563eb; font-weight: 600; margin-top: 2px;">
                                                    💻 {{ $devId }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 6. Status -->
                                        <td style="padding: 14px 16px; vertical-align: top; text-align: center;">
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
                                        <td colspan="6" style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                                            <div style="font-size: 30px; margin-bottom: 8px; color: #94a3b8;"><i class="fi fi-rr-time-past"></i></div>
                                            <div style="font-weight: 700; font-size: 14px; color: #334155;">Belum ada rekaman aktivitas</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

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
                                
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafd; border: 1px solid #dadce0; border-radius: 10px; cursor: pointer;">
                            <input type="checkbox" name="step2_done" id="chkStep2" value="1" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f1f1f; font-size: 13px;">Progress 2</strong>
                                
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafd; border: 1px solid #dadce0; border-radius: 10px; cursor: pointer;">
                            <input type="checkbox" name="step3_done" id="chkStep3" value="1" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f1f1f; font-size: 13px;">Progress 3</strong>
                                
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafd; border: 1px solid #dadce0; border-radius: 10px; cursor: pointer;">
                            <input type="checkbox" name="step4_done" id="chkStep4" value="1" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f1f1f; font-size: 13px;">Progress 4</strong>
                                
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafd; border: 1px solid #dadce0; border-radius: 10px; cursor: pointer;">
                            <input type="checkbox" name="step5_done" id="chkStep5" value="1" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f1f1f; font-size: 13px;">Progress 5 (Selesai 100%)</strong>
                                
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

@endsection

@section('scripts')
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
@endsection
