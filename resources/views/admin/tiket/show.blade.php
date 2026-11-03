@extends('layouts.admin')

@section('title', 'Detail Progress Tiket #' . $tiket->nomor_tiket . ' — Admin Walidata')

@section('content')
<div class="ticket-stack-container">

    <!-- Breadcrumb Navigasi Admin -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.tiket.index') }}"><i class="fi fi-rr-ticket"></i> Daftar Tiket Support</a>
        <span class="separator">/</span>
        <span class="current">#{{ $tiket->nomor_tiket }}</span>
    </nav>

    <!-- Header Action Bar -->
    <div class="ticket-top-actions">
        <a href="{{ route('admin.tiket.index') }}" class="btn-pill-outline ticket-btn-back">
            <i class="fi fi-rr-arrow-left"></i> Kembali ke Daftar Tiket
        </a>

        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.bagi-pakai-dtsen.formulir', $tiket->id) }}" target="_blank" class="btn-pill-primary" style="font-size: 12px; padding: 6px 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Cetak / Unduh Formulir Pemenuhan Layanan (Format SOP Lampiran I)">
                <i class="fi fi-rr-document-signed"></i> <span>Formulir Pemenuhan SOP</span>
            </a>
            <div class="ticket-last-updated">
                <i class="fi fi-rr-clock"></i>
                <span>Terakhir diperbarui: <strong>{{ $tiket->updated_at ? $tiket->updated_at->format('d/m/Y H:i') . ' WIB' : '-' }}</strong></span>
            </div>
        </div>
    </div>

    <!-- 1. Card Visual Progress 5 Tahapan DTSEN -->
    <div class="progress-card">
        <div class="progress-top-info">
            <div>
                <div class="ticket-tags-row">
                    <span class="ticket-tag-chip">#{{ $tiket->nomor_tiket }}</span>
                    @if($tiket->status === 'PERLU_PERBAIKAN')
                        <span class="badge-status badge-status-revisi"><i class="fi fi-rr-exclamation"></i> STATUS: PERLU PERBAIKAN OPD</span>
                    @elseif($tiket->step5_done)
                        <span class="badge-status success badge-status-selesai"><i class="fi fi-rr-check"></i> SELESAI (Semua Progress Selesai)</span>
                    @else
                        <span class="badge-status pending badge-status-pending-sm">⏳ SEDANG DIPROSES</span>
                    @endif
                </div>
                <h2 class="ticket-heading-title">
                    Progress Status Permohonan Bagi Pakai DTSEN
                </h2>
                <div class="ticket-heading-meta">
                    Instansi: <strong>{{ $tiket->nama_instansi }}</strong> | No SE: <strong>{{ $tiket->no_surat_edaran }}</strong> | Waktu Buat: {{ $tiket->created_at->format('d/m/Y H:i') }} WIB
                </div>
            </div>

            <div class="ticket-status-summary">
                <span class="ticket-status-summary-label">Status Terkini:</span>
                <div class="ticket-status-summary-value {{ ($tiket->status === 'PERLU_PERBAIKAN') ? 'color-status-revisi' : (($tiket->step5_done) ? 'color-status-done' : 'color-status-progress') }}">
                    {{ $tiket->current_status_label }}
                </div>
            </div>
        </div>

        @php
            $pct = $tiket->progress_percent;
        @endphp

        <!-- Overall Progress Bar Track -->
        <div class="progress-gauge-box">
            <div class="progress-gauge-labels">
                <span style="color: #475569; font-weight: 600;">Progress Alur Tahapan Layanan DTSEN</span>
                <span class="{{ $tiket->step5_done ? 'color-status-done' : 'color-status-progress' }}" style="font-weight: 700;">
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
                    $isDone = $tiket->isStepDone($stepNum);
                    $isActive = $tiket->isStepActive($stepNum);
                    $nodeClass = $isDone ? 'is-done' : ($isActive ? 'is-active' : 'is-pending');
                    $stepColAt = "step{$stepNum}_at";
                    $timeDone = $tiket->$stepColAt;
                @endphp
                
                <div class="step-node {{ $nodeClass }}">
                    <div class="step-node-icon">
                        @if($isDone)
                            <!-- Centang Hijau (✓) -->
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        @else
                            <span>{{ $stepNum }}</span>
                        @endif
                    </div>

                    <div class="step-node-content">
                        <div class="step-node-title ticket-stepper-node-title">
                            Progress {{ $stepNum }}
                        </div>

                        @if($isDone)
                            <span class="step-badge-status ticket-stepper-badge-done">
                                ✓ Selesai {{ $timeDone ? '(' . $timeDone->format('d/m H:i') . ')' : '' }}
                            </span>
                        @elseif($stepNum === 1 && $tiket->status === 'PERLU_PERBAIKAN')
                            <span class="step-badge-status ticket-stepper-badge-revisi">
                                ⚠️ Perlu Perbaikan
                            </span>
                        @elseif($isActive)
                            <span class="step-badge-status ticket-stepper-badge-active">
                                ● Sedang Berjalan
                            </span>
                        @else
                            <span class="step-badge-status ticket-stepper-badge-pending">
                                Menunggu
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="ticket-verifier-footer-box">
            <div>
                <span class="ticket-verifier-note-label">Catatan Verifikator:</span>
                <div class="ticket-verifier-note-text">
                    {{ $tiket->catatan_admin ?: 'Belum ada catatan verifikator.' }}
                </div>
            </div>

            <!-- Tombol Aksi Verifikasi Step Cepat -->
            @if(!$tiket->step5_done)
                @php
                    $nextStep = 1;
                    for ($s = 1; $s <= 5; $s++) {
                        if (!$tiket->isStepDone($s)) { $nextStep = $s; break; }
                    }
                @endphp
                <form action="{{ route('admin.tiket.step-ok', $tiket->id) }}" method="POST" class="form-inline">
                    @csrf
                    <input type="hidden" name="step" value="{{ $nextStep }}">
                    <button type="submit" class="btn-pill-primary btn-approve-step">
                        <i class="fi fi-rr-check"></i> Setujui Progress {{ $nextStep }} Selesai
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- 2. Kontrol Panel Verifikasi Admin (Catatan & Status Variabel) -->
    <div class="form-card ticket-section-card">
        <h3 class="ticket-section-header">
            <i class="fi fi-rr-settings ticket-section-icon"></i>
            <span>Kelola Status Verifikasi & Catatan Verifikator</span>
        </h3>

        <form action="{{ route('admin.tiket.verifikasi-variabel', $tiket->id) }}" method="POST">
            @csrf
            <div class="ticket-verifier-form-grid">
                <div>
                    <label class="ticket-field-label">Keputusan Verifikasi Variabel:</label>
                    <select name="status_verifikasi" class="form-select ticket-field-select" required>
                        <option value="SESUAI" {{ $tiket->status !== 'PERLU_PERBAIKAN' ? 'selected' : '' }}>✓ Sesuai Klausul Surat Edaran</option>
                        <option value="PERLU_PERBAIKAN" {{ $tiket->status === 'PERLU_PERBAIKAN' ? 'selected' : '' }}>⚠️ Belum Sesuai (Minta Perbaikan OPD)</option>
                    </select>
                </div>

                <div>
                    <label class="ticket-field-label">Catatan Hasil Pemeriksaan Walidata:</label>
                    <div class="ticket-field-input-group">
                        <input type="text" name="catatan_admin" value="{{ $tiket->catatan_admin }}" class="form-input ticket-field-input" placeholder="Isi catatan verifikasi atau alasan perlunya perbaikan variabel...">
                        <button type="submit" class="btn-pill-primary btn-save-note">
                            <i class="fi fi-rr-disk"></i> Simpan Catatan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- 3. Rincian Berkas & Variabel Permohonan -->
    <div class="form-card ticket-section-card-large">
        <h3 class="ticket-section-header ticket-section-header-mb18">
            <i class="fi fi-rr-document ticket-section-icon"></i>
            <span>Rincian Berkas Permohonan Bagi Pakai DTSEN</span>
        </h3>

        <div class="ticket-details-grid">
            <div class="ticket-detail-item">
                <div class="ticket-detail-label">Instansi Pemohon:</div>
                <div class="ticket-detail-val">{{ $tiket->nama_instansi }}</div>
                <div class="ticket-detail-sub">
                    <strong>Narahubung:</strong> {{ $tiket->nama_narahubung ?: ($tiket->nama_pemohon ?: '-') }} 
                    @if($tiket->no_narahubung ?: $tiket->kontak_pemohon)
                        ({{ $tiket->no_narahubung ?: $tiket->kontak_pemohon }})
                    @endif
                </div>
            </div>

            <div class="ticket-detail-item">
                <div class="ticket-detail-label">Nomor Surat / Rujukan:</div>
                <div class="ticket-detail-val">{{ $tiket->no_surat_edaran }}</div>
                <div class="ticket-detail-sub">
                    Tanggal Diajukan: {{ $tiket->created_at ? $tiket->created_at->format('d/m/Y H:i') : '-' }} WIB
                </div>
            </div>
        </div>

        <!-- 5 Berkas Dokumen Permintaan OPD -->
        <div style="margin-top: 16px; margin-bottom: 20px;">
            <div class="ticket-detail-label form-group-mb-16">
                Berkas Dokumen Lampiran Permohonan:
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px;">
                <!-- Surat KAK -->
                <div style="padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 11px; font-weight: 700; color: #dc2626; text-transform: uppercase; margin-bottom: 3px;">
                        Surat KAK (Wajib)
                    </div>
                    @if($tiket->file_kak)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'kak']) }}" class="btn-pill-outline" style="font-size: 11px; padding: 3px 8px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh Berkas KAK
                        </a>
                    @else
                        <span style="font-size: 11px; color: #94a3b8;">- Tidak terlampir -</span>
                    @endif
                </div>

                <!-- Surat Permohonan -->
                <div style="padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 11px; font-weight: 700; color: #dc2626; text-transform: uppercase; margin-bottom: 3px;">
                        Surat Permohonan (Wajib)
                    </div>
                    @if($tiket->file_surat_permohonan || $tiket->file_surat)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'surat_permohonan']) }}" class="btn-pill-outline" style="font-size: 11px; padding: 3px 8px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh Permohonan
                        </a>
                    @else
                        <span style="font-size: 11px; color: #94a3b8;">- Tidak terlampir -</span>
                    @endif
                </div>

                <!-- ROPA -->
                <div style="padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">
                        ROPA (Opsional)
                    </div>
                    @if($tiket->file_ropa)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'ropa']) }}" class="btn-pill-outline" style="font-size: 11px; padding: 3px 8px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh Berkas ROPA
                        </a>
                    @else
                        <span style="font-size: 11px; color: #94a3b8;">- Tidak ada berkas -</span>
                    @endif
                </div>

                <!-- DPIA -->
                <div style="padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">
                        DPIA (Opsional)
                    </div>
                    @if($tiket->file_dpia)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'dpia']) }}" class="btn-pill-outline" style="font-size: 11px; padding: 3px 8px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh Berkas DPIA
                        </a>
                    @else
                        <span style="font-size: 11px; color: #94a3b8;">- Tidak ada berkas -</span>
                    @endif
                </div>

                <!-- SKTM -->
                <div style="padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">
                        SKTM (Opsional)
                    </div>
                    @if($tiket->file_sktm)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'sktm']) }}" class="btn-pill-outline" style="font-size: 11px; padding: 3px 8px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh Berkas SKTM
                        </a>
                    @else
                        <span style="font-size: 11px; color: #94a3b8;">- Tidak ada berkas -</span>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="ticket-detail-label form-group-mb-16">
                Daftar Variabel DTSEN yang Diajukan:
            </div>
            <div class="ticket-vars-container">
                @foreach(($tiket->variabel_dtsen ?? []) as $v)
                    <span class="ticket-var-pill">
                        {{ $v }}
                    </span>
                @endforeach
            </div>
        </div>

        @if($tiket->tujuan_pemanfaatan)
            <div class="ticket-purpose-container">
                <div class="ticket-detail-label form-group-mb-16">
                    Tujuan Pemanfaatan Data:
                </div>
                <div class="ticket-purpose-desc">
                    {{ $tiket->tujuan_pemanfaatan }}
                </div>
            </div>
        @endif
    </div>

    <!-- 4. Tata Kelola Data & Kepatuhan UU PDP (ROPA, DPIA, Pengolahan & Pengamanan) -->
    @include('partials.governance_cards')

</div>
@endsection
