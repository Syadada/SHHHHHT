@extends('layouts.opd')

@section('title', 'Dashboard OPD — Layanan Bagi Pakai DTSEN')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Portal Utama Perangkat Daerah')

@section('content')
<div class="opd-clean-container">

    <!-- Active Ticket Notice (Jika Ada Permintaan Layanan yang Sedang Berjalan) -->
    @if($latestTiket)
        <div class="opd-quick-ticket-banner">
            <div class="quick-banner-left">
                <div class="quick-banner-icon">
                    <i class="fi fi-rr-time-forward"></i>
                </div>
                <div>
                    <div class="quick-banner-label">Permintaan Layanan Terakhir:</div>
                    <div class="quick-banner-num">
                        {{ $latestTiket->nomor_tiket }} 
                        <span class="quick-banner-se">(SE: {{ $latestTiket->no_surat_edaran }})</span>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('opd.tiket.show', $latestTiket->id) }}" class="btn-pill-outline ticket-btn-download">
                    <i class="fi fi-rr-eye"></i> Lihat Status Layanan &rarr;
                </a>
            </div>
        </div>
    @endif

    <!-- Clean Hero Card (Fokus Utama: Tombol Permintaan Bagi Pakai) -->
    <div class="opd-clean-hero-card">
        <div class="opd-clean-icon-bubble">
            <i class="fi fi-rr-layers"></i>
        </div>

        <div class="opd-clean-instansi">
            <i class="fi fi-rr-building"></i>
            <span>{{ $user->nama_instansi ?: $user->name }}</span>
        </div>

        <h1 class="opd-clean-title">
            Pengajuan Bagi Pakai Data DTSEN
        </h1>

        <p class="opd-clean-desc">
            Selamat datang di Portal Layanan DTSEN. Silakan ajukan permintaan bagi pakai data terpadu sosial ekonomi nasional untuk perencanaan program instansi Anda.
        </p>

        <!-- Aksi Utama: Tombol Permintaan Bagi Pakai -->
        <div>
            <a href="{{ route('opd.tiket.create') }}" class="btn-clean-cta" id="btnBuatTiketBaru">
                <i class="fi fi-rr-plus"></i>
                <span>Permintaan Bagi Pakai</span>
            </a>
        </div>
    </div>

    <!-- Alur Panduan Layanan (3 Langkah Ringkas & Bersih) -->
    <div>
        <div class="guide-section-heading">
            Alur Layanan Bagi Pakai DTSEN:
        </div>

        <div class="opd-guide-grid">
            <div class="opd-guide-card">
                <div class="opd-guide-number">1</div>
                <div class="opd-guide-card-title">Ajukan Permintaan Bagi Pakai</div>
                <div class="opd-guide-card-desc">
                    Isi formulir dan cantumkan nomor Surat Edaran resmi beserta rincian variabel data yang dibutuhkan.
                </div>
            </div>

            <div class="opd-guide-card">
                <div class="opd-guide-number">2</div>
                <div class="opd-guide-card-title">Verifikasi Walidata</div>
                <div class="opd-guide-card-desc">
                    Tim Walidata memeriksa kesesuaian variabel dan menyiapkan data melalui 5 tahap progress resmi.
                </div>
            </div>

            <div class="opd-guide-card">
                <div class="opd-guide-number">3</div>
                <div class="opd-guide-card-title">Tanda Tangan BAST</div>
                <div class="opd-guide-card-desc">
                    Petugas OPD hadir dengan laptop terdaftar untuk penyerahan data dan penandatanganan Berita Acara (BAST).
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
