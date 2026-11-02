@extends('layouts.opd')

@section('title', 'Detail Tiket #' . $tiket->nomor_tiket . ' — Layanan DTSEN')
@section('page_title', 'Detail Progress Tiket')
@section('page_subtitle', '#' . $tiket->nomor_tiket . ' — ' . $tiket->nama_instansi)

@section('content')
<div class="ticket-stack-container">

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb">
        <a href="{{ route('opd.dashboard') }}"><i class="fi fi-rr-home"></i> Dashboard</a>
        <span class="separator">/</span>
        <a href="{{ route('opd.tiket.index') }}"><i class="fi fi-rr-ticket"></i> Tiket Saya</a>
        <span class="separator">/</span>
        <span class="current">#{{ $tiket->nomor_tiket }}</span>
    </nav>

    <!-- Header Action Bar -->
    <div class="ticket-top-actions">
        <a href="{{ route('opd.tiket.index') }}" class="btn-pill-outline ticket-btn-back">
            <i class="fi fi-rr-arrow-left"></i> Kembali ke Daftar Tiket
        </a>

        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('opd.tiket.formulir', $tiket->id) }}" target="_blank" class="btn-pill-primary" style="font-size: 12px; padding: 6px 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Cetak / Unduh Formulir Pemenuhan Layanan Resmi">
                <i class="fi fi-rr-document-signed"></i> <span>Formulir Pemenuhan Layanan</span>
            </a>
            <div class="ticket-last-updated">
                <i class="fi fi-rr-clock"></i>
                <span>Terakhir diperbarui: <strong>{{ $tiket->updated_at ? $tiket->updated_at->format('d/m/Y H:i') . ' WIB' : '-' }}</strong></span>
            </div>
        </div>
    </div>

    <!-- 1. Progress Status Permohonan Bagi Pakai DTSEN (Visual 5 Tahap) -->
    <div class="progress-card">
        @if($tiket->status === 'PERLU_PERBAIKAN')
            <!-- Warning Banner: Verifikasi Mengharuskan Perbaikan Variabel -->
            <div class="revisi-banner">
                <div class="ticket-top-actions">
                    <div class="form-shell">
                        <div class="revisi-banner-header">
                            <span class="icon-size-18"><i class="fi fi-rr-exclamation"></i></span>
                            <span>PERBAIKAN VARIABEL DIPERLUKAN (HASIL VERIFIKASI WALIDATA)</span>
                        </div>
                        <div class="revisi-banner-desc">
                            Variabel yang Anda ajukan pada tiket <strong>{{ $tiket->nomor_tiket }}</strong> belum sesuai dengan klausul <strong>Surat Edaran No. {{ $tiket->no_surat_edaran }}</strong>.
                            <div class="revisi-catatan-box">
                                <strong>Catatan Hasil Pengecekan Walidata:</strong><br>
                                {{ $tiket->catatan_admin }}
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="button" onclick="toggleRevisiForm()" class="btn-pill-primary btn-revisi-trigger">
                            <span><i class="fi fi-rr-edit"></i> Perbaiki Variabel Sekarang</span>
                        </button>
                    </div>
                </div>

                <!-- Collapsible Form Edit / Revisi Variabel -->
                <div id="boxRevisiVariabel" class="revisi-form-collapsible">
                    <form action="{{ route('tiket.update-variabel', $tiket->id) }}" method="POST">
                        @csrf
                        <div class="revisi-form-title">
                            Silakan sesuaikan daftar variabel DTSEN berikut sesuai catatan Walidata di atas:
                        </div>
                        <div class="revisi-form-hint">
                            Pastikan variabel yang Anda inputkan tercantum pada lampiran Surat Edaran resmi.
                        </div>

                        <div id="revisiVarContainer" class="revisi-vars-stack">
                            @php
                                $curVars = $tiket->variabel_dtsen ?? [];
                                if (empty($curVars)) $curVars = [''];
                            @endphp
                            @foreach($curVars as $idx => $v)
                                <div class="revisi-row">
                                    <span class="revisi-label">Variabel {{ $idx + 1 }}:</span>
                                    <input type="text" name="variabel_dtsen[]" value="{{ $v }}" class="form-input ticket-field-input" placeholder="Contoh: NIK, Nama, Alamat, Status Kesejahteraan..." required>
                                    <button type="button" onclick="hapusBarisRevisi(this)" class="btn-pill-outline revisi-btn-remove" title="Hapus baris variabel ini">✕</button>
                                </div>
                            @endforeach
                        </div>

                        <div class="ticket-top-actions">
                            <button type="button" onclick="tambahKolomRevisi()" class="btn-pill-outline revisi-btn-add">
                                + Tambah Kolom Variabel
                            </button>
                            <div class="ticket-filter-form">
                                <button type="button" onclick="toggleRevisiForm()" class="btn-pill-outline revisi-btn-close">
                                    Tutup
                                </button>
                                <button type="submit" class="btn-pill-primary revisi-btn-submit">
                                    <i class="fi fi-rr-disk"></i> Simpan & Ajukan Ulang ke Walidata
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="progress-top-info">
            <div>
                <div class="ticket-tags-row">
                    <span class="ticket-tag-chip">#{{ $tiket->nomor_tiket }}</span>
                    @if($tiket->status === 'PERLU_PERBAIKAN')
                        <span class="badge-status badge-status-revisi"><i class="fi fi-rr-exclamation"></i> PERLU PERBAIKAN OPD</span>
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
                    Surat Edaran: <strong>{{ $tiket->no_surat_edaran }}</strong> | Waktu Buat: {{ $tiket->created_at->format('d/m/Y H:i') }} WIB
                </div>
            </div>

            <div class="ticket-status-summary">
                <span class="ticket-status-summary-label">Status Terkini:</span>
                <div class="ticket-status-summary-value {{ ($tiket->status === 'PERLU_PERBAIKAN') ? 'color-status-revisi' : (($tiket->step5_done) ? 'color-status-done' : 'color-status-progress') }}">
                    @if($tiket->status === 'PERLU_PERBAIKAN')
                        Perlu Perbaikan Variabel vs SE
                    @else
                        {{ $tiket->current_status_label }}
                    @endif
                </div>
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

        <div class="ticket-verifier-footer-simple">
            <span class="ticket-verifier-note-label">Catatan Verifikator:</span>
            <div class="ticket-verifier-note-text">
                {{ $tiket->catatan_admin ?: 'Tiket permohonan berada dalam antrean proses resmi Walidata.' }}
            </div>
        </div>
    </div>

    <!-- 2. Rincian Data Surat Edaran & Variabel Permohonan -->
    <div class="form-card ticket-section-card-large">
        <h3 class="ticket-section-header ticket-section-header-mb18">
            <i class="fi fi-rr-document ticket-section-icon"></i>
            <span>Rincian Berkas Permohonan Bagi Pakai DTSEN</span>
        </h3>

        <div class="ticket-details-grid">
            <div class="ticket-detail-item">
                <div class="ticket-detail-label">Instansi Pemohon:</div>
                <div class="ticket-detail-val">{{ $tiket->nama_instansi }}</div>
                <div class="ticket-detail-sub">PIC: {{ $tiket->nama_pemohon }} ({{ $tiket->kontak_pemohon }})</div>
            </div>

            <div class="ticket-detail-item">
                <div class="ticket-detail-label">Surat Edaran Resmi:</div>
                <div class="ticket-detail-val">{{ $tiket->no_surat_edaran }}</div>
                @if($tiket->file_surat)
                    <div class="ticket-file-download-wrap">
                        <a href="{{ route('tiket.download-surat', $tiket->id) }}" class="btn-pill-outline ticket-btn-download">
                            <i class="fi fi-rr-download"></i> Unduh File Surat ({{ $tiket->nama_file_asli ?: 'PDF' }})
                        </a>
                    </div>
                @endif
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

@section('scripts')
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
        div.innerHTML = `
            <span class="revisi-label">Variabel ${nextIdx}:</span>
            <input type="text" name="variabel_dtsen[]" value="" class="form-input ticket-field-input" placeholder="Isi variabel DTSEN yang dibutuhkan..." required>
            <button type="button" onclick="hapusBarisRevisi(this)" class="btn-pill-outline revisi-btn-remove" title="Hapus baris variabel ini">✕</button>
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
@endsection
