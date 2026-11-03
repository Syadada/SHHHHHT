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
        <a href="{{ route('opd.tiket.index') }}"><i class="fi fi-rr-share"></i> Layanan</a>
        <span class="separator">/</span>
        <span class="current">#{{ $tiket->nomor_tiket }}</span>
    </nav>

    <!-- Header Action Bar -->
    <div class="ticket-top-actions">
        <a href="{{ route('opd.tiket.index') }}" class="btn-pill-outline ticket-btn-back">
            <i class="fi fi-rr-arrow-left"></i> Kembali ke Layanan
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

    <!-- 1. Progress Status Permohonan Bagi Pakai DTSEN (Sisi OPD: 4 Tahap) -->
    <div class="progress-card">
        @if($tiket->status === 'PERLU_PERBAIKAN')
            <!-- Warning Banner: Verifikasi Mengharuskan Perbaikan Berkas atau Variabel -->
            <div class="revisi-banner" style="background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 14px; padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; align-items: flex-start; gap: 14px; flex-wrap: wrap;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                        <i class="fi fi-rr-exclamation"></i>
                    </div>
                    <div style="flex: 1; min-width: 280px;">
                        <div style="font-size: 15px; font-weight: 800; color: #991b1b; margin-bottom: 4px;">
                            PERBAIKAN PERMOHONAN DIPERLUKAN (CATATAN ADMIN WALIDATA)
                        </div>
                        <div style="font-size: 13px; color: #475569; line-height: 1.5; margin-bottom: 12px;">
                            Admin Walidata meminta perbaikan berkas atau rincian permohonan pada tiket <strong>#{{ $tiket->nomor_tiket }}</strong>.
                            <div style="margin-top: 8px; padding: 10px 14px; background: #ffffff; border: 1px solid #fda4af; border-radius: 8px; font-size: 13px; color: #b91c1c;">
                                <strong>Catatan Walidata:</strong><br>
                                {{ $tiket->catatan_admin }}
                            </div>
                        </div>

                        <!-- Form Re-Upload Berkas yang Benar -->
                        <form action="{{ route('tiket.revisi-berkas', $tiket->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 14px; padding-top: 14px; border-top: 1px dashed #fecdd3;">
                            @csrf
                            <div style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                                Silakan unggah berkas KAK / Surat Permohonan yang telah diperbaiki:
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 14px; margin-bottom: 14px;">
                                <div>
                                    <label style="font-size: 12px; font-weight: 600; color: #334155; display: block; margin-bottom: 4px;">
                                        Unggah Ulang Surat KAK yang Benar:
                                    </label>
                                    <input type="file" name="file_kak" class="form-text-input" accept=".pdf,.doc,.docx" style="font-size: 12px; padding: 6px;">
                                </div>
                                <div>
                                    <label style="font-size: 12px; font-weight: 600; color: #334155; display: block; margin-bottom: 4px;">
                                        Unggah Ulang Surat Permohonan yang Benar:
                                    </label>
                                    <input type="file" name="file_surat_permohonan" class="form-text-input" accept=".pdf,.doc,.docx" style="font-size: 12px; padding: 6px;">
                                </div>
                            </div>

                            <div style="margin-bottom: 14px;">
                                <label style="font-size: 12px; font-weight: 600; color: #334155; display: block; margin-bottom: 4px;">
                                    Catatan Tambahan dari Perangkat Daerah (Opsional):
                                </label>
                                <input type="text" name="catatan_opd" class="form-text-input" placeholder="Tuliskan keterangan perbaikan berkas...">
                            </div>

                            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                <button type="submit" class="btn-pill-primary" style="background: #dc2626; border-color: #dc2626;">
                                    <i class="fi fi-rr-paper-plane"></i> Kirim Ulang Berkas Perbaikan ke Walidata
                                </button>
                                <button type="button" onclick="toggleRevisiForm()" class="btn-pill-outline" style="font-size: 12px;">
                                    <i class="fi fi-rr-edit"></i> Perbaiki Variabel DTSEN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Collapsible Form Edit / Revisi Variabel -->
                <div id="boxRevisiVariabel" class="revisi-form-collapsible" style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid #fecdd3;">
                    <form action="{{ route('tiket.update-variabel', $tiket->id) }}" method="POST">
                        @csrf
                        <div class="revisi-form-title">
                            Silakan sesuaikan daftar variabel DTSEN berikut:
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
                        <span class="badge-status badge-status-revisi"><i class="fi fi-rr-exclamation"></i> PERLU PERBAIKAN BERKAS</span>
                    @elseif($tiket->status === 'PENDING')
                        <span class="badge-status pending badge-status-pending-sm">⏳ MENUNGGU VERIFIKASI BERKAS</span>
                    @elseif($tiket->isOpdStepDone(4))
                        <span class="badge-status success badge-status-selesai"><i class="fi fi-rr-check"></i> SELESAI (100%)</span>
                    @else
                        <span class="badge-status pending badge-status-pending-sm">● SEDANG DIPROSES</span>
                    @endif
                </div>
                <h2 class="ticket-heading-title">
                    Progress Status Permohonan Bagi Pakai DTSEN
                </h2>
                <div class="ticket-heading-meta">
                    Surat Rujukan: <strong>{{ $tiket->no_surat_edaran ?: '-' }}</strong> | Waktu Buat: {{ $tiket->created_at->format('d/m/Y H:i') }} WIB
                </div>
            </div>

            <div class="ticket-status-summary">
                <span class="ticket-status-summary-label">Status Terkini:</span>
                <div class="ticket-status-summary-value {{ ($tiket->status === 'PERLU_PERBAIKAN') ? 'color-status-revisi' : (($tiket->isOpdStepDone(4)) ? 'color-status-done' : 'color-status-progress') }}">
                    @if($tiket->status === 'PERLU_PERBAIKAN')
                        Perlu Perbaikan Berkas Permohonan
                    @else
                        {{ $tiket->current_opd_status_label }}
                    @endif
                </div>
            </div>
        </div>

        <!-- 4-Step Process Flow Sisi OPD: 1. Pengajuan, 2. Pengolahan, 3. Transfer, 4. Selesai -->
        <div class="step-flow opd-4step-flow">
            @foreach(\App\Models\TiketDtsen::$opdStepsDefinition as $stepNum => $stepDef)
                @php
                    $isDone = $tiket->isOpdStepDone($stepNum);
                    $isActive = $tiket->isOpdStepActive($stepNum);
                    $nodeClass = $isDone ? 'is-done' : ($isActive ? 'is-active' : 'is-pending');
                    if ($stepNum === 1 && $tiket->status === 'PERLU_PERBAIKAN') {
                        $nodeClass = 'is-revisi';
                    }
                @endphp
                
                <div class="step-node {{ $nodeClass }}">
                    <div class="step-node-icon">
                        @if($isDone)
                            <!-- Centang Hijau (✓) -->
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        @elseif($stepNum === 1 && $tiket->status === 'PERLU_PERBAIKAN')
                            <i class="fi fi-rr-exclamation"></i>
                        @else
                            <span>{{ $stepNum }}</span>
                        @endif
                    </div>

                    <div class="step-node-content">
                        <div class="step-node-title ticket-stepper-node-title">
                            {{ $stepNum }}. {{ $stepDef['title'] }}
                        </div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 1px;">
                            {{ $stepDef['desc'] }}
                        </div>

                        @if($isDone)
                            <span class="step-badge-status ticket-stepper-badge-done">
                                ✓ Selesai
                            </span>
                        @elseif($stepNum === 1 && $tiket->status === 'PERLU_PERBAIKAN')
                            <span class="step-badge-status ticket-stepper-badge-revisi">
                                ⚠️ Perlu Perbaikan Berkas
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

    <!-- 2. Rincian Data Surat Permohonan & Berkas Dokumen -->
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
                <div class="ticket-detail-label">Surat Permohonan:</div>
                <div class="ticket-detail-val">{{ $tiket->no_surat_permohonan ?: ($tiket->no_surat_edaran ?: '-') }}</div>
                <div class="ticket-detail-sub">
                    <strong>Tanggal:</strong> {{ $tiket->tgl_surat_permohonan ? $tiket->tgl_surat_permohonan->format('d/m/Y') : ($tiket->created_at ? $tiket->created_at->format('d/m/Y') : '-') }} 
                    @if($tiket->perihal)
                        &bull; <strong>Perihal:</strong> {{ $tiket->perihal }}
                    @endif
                </div>
            </div>
        </div>

        <!-- 5 Berkas Dokumen Permintaan OPD -->
        <div style="margin-top: 16px; margin-bottom: 20px;">
            <div class="ticket-detail-label form-group-mb-16">
                Berkas Dokumen Lampiran Permohonan:
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px;">
                <!-- Surat KAK -->
                <div style="padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                    <div style="font-size: 11px; font-weight: 700; color: #dc2626; text-transform: uppercase; margin-bottom: 4px;">
                        Surat KAK (Wajib)
                    </div>
                    @if($tiket->file_kak)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'kak']) }}" class="btn-pill-outline" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh KAK
                        </a>
                    @else
                        <span style="font-size: 12px; color: #94a3b8;">- Tidak terlampir -</span>
                    @endif
                </div>

                <!-- Surat Permohonan -->
                <div style="padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                    <div style="font-size: 11px; font-weight: 700; color: #dc2626; text-transform: uppercase; margin-bottom: 4px;">
                        Surat Permohonan (Wajib)
                    </div>
                    @if($tiket->file_surat_permohonan || $tiket->file_surat)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'surat_permohonan']) }}" class="btn-pill-outline" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh Permohonan
                        </a>
                    @else
                        <span style="font-size: 12px; color: #94a3b8;">- Tidak terlampir -</span>
                    @endif
                </div>

                <!-- ROPA -->
                <div style="padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">
                        ROPA (Opsional)
                    </div>
                    @if($tiket->file_ropa)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'ropa']) }}" class="btn-pill-outline" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh ROPA
                        </a>
                    @else
                        <span style="font-size: 12px; color: #94a3b8;">- Tidak ada berkas -</span>
                    @endif
                </div>

                <!-- DPIA -->
                <div style="padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">
                        DPIA (Opsional)
                    </div>
                    @if($tiket->file_dpia)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'dpia']) }}" class="btn-pill-outline" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh DPIA
                        </a>
                    @else
                        <span style="font-size: 12px; color: #94a3b8;">- Tidak ada berkas -</span>
                    @endif
                </div>

                <!-- SKTM -->
                <div style="padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">
                        SKTM (Opsional)
                    </div>
                    @if($tiket->file_sktm)
                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'sktm']) }}" class="btn-pill-outline" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
                            <i class="fi fi-rr-download"></i> Unduh SKTM
                        </a>
                    @else
                        <span style="font-size: 12px; color: #94a3b8;">- Tidak ada berkas -</span>
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
