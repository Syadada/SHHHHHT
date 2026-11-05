@extends('layouts.admin')







@section('title', 'Detail Progress Tiket #' . $tiket->nomor_tiket . ' — Walidata')







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







    <!-- 1. Card Alur Kerja & Progress Tahapan Admin (Proses 1 & Proses 2) -->



    <div class="progress-card">



        <div class="progress-top-info">



            <div>



                <div class="ticket-tags-row">



                    <span class="ticket-tag-chip">#{{ $tiket->nomor_tiket }}</span>



                    @if($tiket->status === 'PERLU_PERBAIKAN')



                        <span class="badge-status badge-status-revisi"><i class="fi fi-rr-exclamation"></i> STATUS: PERLU PERBAIKAN OPD</span>



                    @elseif($tiket->status === 'PENDING')



                        <span class="badge-status pending badge-status-pending-sm"><i class="fi fi-rr-time-forward"></i> STATUS: PENDING (MENUNGGU VERIFIKASI BERKAS)</span>



                    @elseif($tiket->isOpdStepDone(4))



                        <span class="badge-status success badge-status-selesai"><i class="fi fi-rr-check"></i> SELESAI (100%)</span>



                    @else



                        <span class="badge-status pending badge-status-pending-sm">● SEDANG DIPROSES</span>



                    @endif



                </div>



                <h2 class="ticket-heading-title">



                    Alur Kerja Walidata DTSEN — 5 Tahapan Layanan Bagi Pakai



                </h2>



                <div class="ticket-heading-meta">



                    Instansi: <strong>{{ $tiket->nama_instansi }}</strong> | No Rujukan/SE: <strong>{{ $tiket->no_surat_edaran ?: '-' }}</strong> | Diajukan: {{ $tiket->created_at->format('d/m/Y H:i') }} WIB



                </div>



            </div>







            <div class="ticket-status-summary">



                <span class="ticket-status-summary-label">Status Terkini:</span>



                <div class="ticket-status-summary-value {{ ($tiket->status === 'PERLU_PERBAIKAN') ? 'color-status-revisi' : (($tiket->step5_done || $tiket->status === 'SELESAI') ? 'color-status-done' : 'color-status-progress') }}">



                    @if($tiket->status === 'PERLU_PERBAIKAN')



                        Menunggu Submit Ulang Berkas OPD



                    @elseif($tiket->status === 'PENDING')



                        Menunggu Verifikasi Berkas Permohonan



                    @elseif($tiket->step5_done || $tiket->status === 'SELESAI')



                        Layanan Selesai (100%)



                    @elseif($tiket->step4_done)



                        Sedang Berjalan: Proses 5 (Layanan Selesai)



                    @elseif($tiket->step3_done)



                        Sedang Berjalan: Proses 4 (Pengesahan BAST)



                    @elseif($tiket->step2_done)



                        Sedang Berjalan: Proses 3 (Transfer Data)



                    @elseif($tiket->step1_done)



                        Sedang Berjalan: Proses 2 (Pengolahan DTSEN)



                    @else



                        Sedang Berjalan: Proses 1 (Pemenuhan Dokumen)



                    @endif



                </div>



            </div>



        </div>







        <!-- 5-Step Process Flow Sisi Admin (Proses 1 s/d 5) -->



        <div class="step-flow admin-step-flow">



            @foreach(\App\Models\TiketDtsen::$adminStepsDefinition as $stepNum => $stepDef)



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



                            Proses {{ $stepNum }}: {{ $stepDef['title'] }}



                        </div>







                        @if($isDone)



                            <span class="step-badge-status ticket-stepper-badge-done">



                                ✓ Selesai {{ $timeDone ? '(' . $timeDone->format('d/m H:i') . ')' : '' }}



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







        <!-- Sinkronisasi Otomatis OPD Box -->



        <div style="margin-top: 14px; padding: 12px 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; font-size: 12.5px; color: #166534; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">



            <div style="display: flex; align-items: center; gap: 10px;">



                <i class="fi fi-rr-refresh" style="font-size: 18px; color: #16a34a; flex-shrink: 0;"></i>



                <div>



                    <strong>Sinkronisasi Progress OPD:</strong> Tahap 1: Pengajuan ({{ $tiket->isOpdStepDone(1) ? '✓ Selesai' : ($tiket->status === 'PERLU_PERBAIKAN' ? '⚠️ Perlu Perbaikan' : 'Sedang Berjalan') }}) &bull; Tahap 2: Pengolahan ({{ ($tiket->step1_done && $tiket->step2_done) ? '✓ Selesai' : 'Sedang Berjalan' }}) &bull; Tahap 3: Transfer ({{ $tiket->step3_done ? '✓ Selesai' : 'Menunggu' }}) &bull; Tahap 4: Selesai ({{ ($tiket->step4_done || $tiket->step5_done || $tiket->status === 'SELESAI') ? '✓ Selesai' : 'Menunggu' }})



                </div>



            </div>



            @if($tiket->step5_done || $tiket->status === 'SELESAI')



                <div>



                    <span style="background: #16a34a; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">



                        ✓ Selesai 100% di OPD



                    </span>



                </div>



            @endif



        </div>







        <!-- Tombol Aksi Cepat Centang Tahapan Admin (Proses 1 s/d 5) -->



        <div class="ticket-verifier-footer-box" style="margin-top: 16px; display: flex; align-items: center; justify-content: flex-end; flex-wrap: wrap; gap: 12px;">







            @php



                $nextStep = 1;



                for ($i = 1; $i <= 5; $i++) {



                    if (!$tiket->isStepDone($i)) {



                        $nextStep = $i;



                        break;



                    }



                }



                $allDone = $tiket->step1_done && $tiket->step2_done && $tiket->step3_done && $tiket->step4_done && $tiket->step5_done;



                



                $lastDoneStep = 0;



                for ($i = 5; $i >= 1; $i--) {



                    if ($tiket->isStepDone($i)) {



                        $lastDoneStep = $i;



                        break;



                    }



                }



            @endphp







            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">



                @if(!$allDone)



                    <form action="{{ route('admin.tiket.toggle-step', [$tiket->id, $nextStep]) }}" method="POST" style="margin: 0;">



                        @csrf



                        <button type="submit" class="btn-pill-primary" style="font-size: 13px; padding: 8px 24px; background: #059669; border-color: #059669; display: inline-flex; align-items: center; gap: 6px;">



                            <i class="fi fi-rr-check"></i> Selesai



                        </button>



                    </form>



                @else



                    <span style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 13px; font-weight: 700; padding: 8px 20px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;">



                        <i class="fi fi-rr-check"></i> Selesai



                    </span>



                @endif







                @if($lastDoneStep > 0)



                    <form action="{{ route('admin.tiket.toggle-step', [$tiket->id, $lastDoneStep]) }}" method="POST" style="margin: 0;">



                        @csrf



                        <button type="submit" class="btn-pill-outline" style="font-size: 12px; padding: 7px 14px; color: #64748b;" title="Batalkan proses sebelumnya">



                            <i class="fi fi-rr-undo"></i> Batal



                        </button>



                    </form>



                @endif



            </div>



        </div>



    </div>







    <!-- 2. Pemeriksaan Berkas Permohonan (Pending Check & Reject Flow) -->



    <div class="form-card ticket-section-card">



        <h3 class="ticket-section-header">



            <i class="fi fi-rr-shield-check ticket-section-icon"></i>



            <span>Verifikasi Berkas yang Tersubmit (Surat KAK & Surat Permohonan)</span>



        </h3>







        <form action="{{ route('admin.tiket.verifikasi-berkas', $tiket->id) }}" method="POST">



            @csrf



            <div>



                <label class="ticket-field-label">Catatan Hasil Pemeriksaan Berkas (Untuk OPD):</label>



                <input type="text" 



                       name="catatan_admin" 



                       value="{{ $tiket->catatan_admin }}" 



                       class="form-input ticket-field-input" 



                       placeholder="Catatan walidata untuk OPD...">



            </div>







            <!-- Checklist Pilihan Highlight Berkas yang Bermasalah (Jika Reject) -->



            <div style="margin-top: 14px; padding: 14px 16px; background: #fff5f5; border: 1.5px solid #fecaca; border-radius: 10px;">



                <div style="margin-bottom: 8px;">



                    <label style="font-size: 12.5px; font-weight: 700; color: #991b1b; display: flex; align-items: center; gap: 6px; margin: 0;">



                        <i class="fi fi-rr-flag"></i> Centang Dokumen / Bagian yang Perlu Diperbaiki oleh OPD (Jika Reject):



                    </label>



                </div>







                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px;">



                    @php



                        $curRevisi = (array) ($tiket->items_revisi ?? []);



                    @endphp



                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b; cursor: pointer; background: #ffffff; padding: 6px 10px; border: 1px solid #fed7d7; border-radius: 6px;">



                        <input type="checkbox" name="items_revisi[]" value="surat_permohonan" {{ in_array('surat_permohonan', $curRevisi) ? 'checked' : '' }}>



                        <span style="font-weight: 600;">Surat Permohonan</span>



                    </label>







                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b; cursor: pointer; background: #ffffff; padding: 6px 10px; border: 1px solid #fed7d7; border-radius: 6px;">



                        <input type="checkbox" name="items_revisi[]" value="kak" {{ in_array('kak', $curRevisi) ? 'checked' : '' }}>



                        <span style="font-weight: 600;">Berkas KAK</span>



                    </label>







                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b; cursor: pointer; background: #ffffff; padding: 6px 10px; border: 1px solid #fed7d7; border-radius: 6px;">



                        <input type="checkbox" name="items_revisi[]" value="sktm" {{ in_array('sktm', $curRevisi) ? 'checked' : '' }}>



                        <span style="font-weight: 600;">SK Tim Pelaksana</span>



                    </label>







                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b; cursor: pointer; background: #ffffff; padding: 6px 10px; border: 1px solid #fed7d7; border-radius: 6px;">



                        <input type="checkbox" name="items_revisi[]" value="ropa" {{ in_array('ropa', $curRevisi) ? 'checked' : '' }}>



                        <span style="font-weight: 600;">Berkas ROPA</span>



                    </label>







                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b; cursor: pointer; background: #ffffff; padding: 6px 10px; border: 1px solid #fed7d7; border-radius: 6px;">



                        <input type="checkbox" name="items_revisi[]" value="dpia" {{ in_array('dpia', $curRevisi) ? 'checked' : '' }}>



                        <span style="font-weight: 600;">Berkas DPIA</span>



                    </label>







                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b; cursor: pointer; background: #ffffff; padding: 6px 10px; border: 1px solid #fed7d7; border-radius: 6px;">



                        <input type="checkbox" name="items_revisi[]" value="klasifikasi_dtsen" {{ in_array('klasifikasi_dtsen', $curRevisi) ? 'checked' : '' }}>



                        <span style="font-weight: 600;">Klasifikasi DTSEN</span>



                    </label>







                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b; cursor: pointer; background: #ffffff; padding: 6px 10px; border: 1px solid #fed7d7; border-radius: 6px;">



                        <input type="checkbox" name="items_revisi[]" value="hak_akses" {{ in_array('hak_akses', $curRevisi) ? 'checked' : '' }}>



                        <span style="font-weight: 600;">Hak Akses</span>



                    </label>







                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b; cursor: pointer; background: #ffffff; padding: 6px 10px; border: 1px solid #fed7d7; border-radius: 6px;">



                        <input type="checkbox" name="items_revisi[]" value="variabel_dtsen" {{ in_array('variabel_dtsen', $curRevisi) ? 'checked' : '' }}>



                        <span style="font-weight: 600;">Variabel DTSEN</span>



                    </label>







                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b; cursor: pointer; background: #ffffff; padding: 6px 10px; border: 1px solid #fed7d7; border-radius: 6px;">



                        <input type="checkbox" name="items_revisi[]" value="narahubung" {{ in_array('narahubung', $curRevisi) ? 'checked' : '' }}>



                        <span style="font-weight: 600;">Kontak Narahubung</span>



                    </label>



                </div>



            </div>







            <!-- Tombol Keputusan -->



            <div style="margin-top: 14px; display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">



                <button type="submit" name="keputusan" value="APPROVE" class="btn-pill-primary" style="font-size: 13px; padding: 8px 18px; background: #059669; border-color: #059669;">



                    <i class="fi fi-rr-check"></i> Benar



                </button>



                <button type="submit" name="keputusan" value="REJECT" class="btn-pill-outline" style="font-size: 13px; padding: 8px 18px; color: #dc2626; border-color: #fca5a5; background: #fff1f2;">



                    <i class="fi fi-rr-cross-circle"></i> Reject / Minta Submit Ulang



                </button>



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







                <!-- Klasifikasi DTSEN -->



                <div style="padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">



                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">



                        Klasifikasi DTSEN (Opsional)



                    </div>



                    @if($tiket->file_klasifikasi_dtsen)



                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'klasifikasi_dtsen']) }}" class="btn-pill-outline" style="font-size: 11px; padding: 3px 8px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">



                            <i class="fi fi-rr-download"></i> Unduh Klasifikasi



                        </a>



                    @else



                        <span style="font-size: 11px; color: #94a3b8;">- Tidak ada berkas -</span>



                    @endif



                </div>







                <!-- Hak Akses -->



                <div style="padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">



                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">



                        Hak Akses (Opsional)



                    </div>



                    @if($tiket->file_hak_akses)



                        <a href="{{ route('tiket.download-dokumen', [$tiket->id, 'hak_akses']) }}" class="btn-pill-outline" style="font-size: 11px; padding: 3px 8px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">



                            <i class="fi fi-rr-download"></i> Unduh Hak Akses



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







</div>



@endsection



