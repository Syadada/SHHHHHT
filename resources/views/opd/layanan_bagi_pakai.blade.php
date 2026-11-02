@extends('layouts.opd')

@section('title', 'Layanan Bagi Pakai DTSEN — Portal Perangkat Daerah')
@section('page_title', 'Layanan Bagi Pakai DTSEN')
@section('page_subtitle', 'Pantauan Progress Alur & Rekapitulasi Riwayat Data Resmi')

@section('content')
<div class="ticket-stack-container">

    <!-- 1. Header Profil Akun Perangkat Daerah & Nomor Laptop Login -->
    <div class="form-card ticket-section-card">
        <div class="ticket-top-actions">
            <div>
                <div class="form-badge-system form-group-mb-16">
                    <span><i class="fi fi-rr-building"></i> AKUN PERANGKAT DAERAH (OPD)</span>
                </div>
                <h1 class="ticket-heading-title">
                    {{ $user->nama_instansi ?: $user->name }}
                </h1>
                <div class="ticket-heading-meta">
                    <span><i class="fi fi-rr-user"></i> PIC: <strong>{{ $user->name }}</strong></span> &bull; 
                    <span><i class="fi fi-rr-envelope"></i> Email: <strong>{{ $user->email }}</strong></span> &bull; 
                    <span><i class="fi fi-rr-phone-call"></i> Kontak: <strong>{{ $user->nomor_kontak ?: '-' }}</strong></span>
                </div>
            </div>

            <!-- Badge Nomor Laptop / Perangkat Terdaftar Saat Login -->
            <div class="ticket-detail-item">
                <div class="ticket-status-summary-label">
                    Perangkat / Laptop Terdaftar:
                </div>
                <div class="ticket-status-summary-value color-status-progress">
                    <i class="fi fi-rr-laptop"></i> {{ $user->last_login_device ?: 'WIN-DEV-OPD01' }}
                </div>
                <div class="ticket-detail-sub">
                    IP Address: <strong class="color-status-progress">{{ $user->last_login_ip ?: '127.0.0.1' }}</strong>
                </div>
            </div>
        </div>
    </div>

    @if(isset($datasets) && $datasets->count() > 0)
        <!-- 2. Katalog Master Dataset DTSEN & Dokumen SOP / Manual Book -->
        @foreach($datasets as $ds)
            <div class="form-card ticket-section-card-large form-group-mb-20">
                <div class="ticket-top-actions form-group-mb-16">
                    <div>
                        <div class="form-badge-system form-group-mb-16">
                            <span><i class="fi fi-rr-database"></i> KATALOG MASTER DATASET DTSEN</span>
                        </div>
                        <h2 class="ticket-heading-title" style="font-size: 18px;">
                            {{ $ds->nama_dataset }}
                        </h2>
                        <div class="ticket-heading-meta">
                            Kode: <strong>{{ $ds->kode_dataset }}</strong> &bull; Cakupan: <strong>{{ $ds->kategori_desil }}</strong>
                        </div>
                    </div>

                    @if($ds->nama_file_sop)
                        <div>
                            <span class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px; cursor: default; background: #f8fafc;" title="Dokumen SOP Layanan">
                                <i class="fi fi-rr-document"></i> {{ $ds->nama_file_sop }}
                            </span>
                        </div>
                    @endif
                </div>

                <p class="portal-subtitle" style="margin-bottom: 16px;">
                    {{ $ds->deskripsi }}
                </p>

                <div>
                    <div class="ticket-detail-label form-group-mb-16" style="margin-bottom: 8px;">
                        Variabel Baku yang Tersedia dalam Master Dataset:
                    </div>
                    <div class="ticket-vars-container">
                        @foreach(($ds->daftar_variabel_tersedia ?? []) as $vItem)
                            <span class="ticket-var-pill" style="font-size: 11px;">
                                {{ $vItem }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <!-- 3. Tabel Data Riwayat Bagi Data DTSEN Resmi (Read-Only untuk Akun OPD) -->
    <div class="form-card ticket-section-card-large">
        <div class="ticket-top-actions form-group-mb-20">
            <div>
                <h3 class="ticket-section-header">
                    <span><i class="fi fi-rr-document ticket-section-icon"></i> Tabel Data Riwayat Bagi Data DTSEN Resmi</span>
                    <span class="badge-verified">
                        TERVERIFIKASI
                    </span>
                </h3>
                <p class="portal-subtitle">
                    Rekapitulasi resmi berita acara serah terima dan proses bagi pakai data DTSEN instansi Anda.
                </p>
            </div>

            <div class="ticket-detail-sub">
                <i class="fi fi-rr-lock"></i> <em>Tabel ini diverifikasi resmi oleh Tim Walidata dan bersifat Read-Only bagi Perangkat Daerah.</em>
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
                                <div class="ticket-table-instansi">
                                    <a href="{{ route('opd.riwayat-bagi-data.show', $item->id) }}" style="color: #0b57d0; text-decoration: none; font-weight: 700;" title="Buka Detail">
                                        {{ $item->nama_instansi }}
                                    </a>
                                </div>
                                @if($item->tiket)
                                    <div class="ticket-detail-sub">
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
                            <td class="nowrap">
                                <div class="ticket-table-updated">
                                    {{ $item->tanggal_tiket_dibuat ? $item->tanggal_tiket_dibuat->format('d/m/Y') : '-' }}
                                </div>
                                <div class="ticket-table-ago">
                                    {{ $item->tanggal_tiket_dibuat ? $item->tanggal_tiket_dibuat->format('H:i') . ' WIB' : '-' }}
                                </div>
                            </td>

                            <!-- 4. Tanda Tangan BAST / Proses Bagi Data Selesai -->
                            <td>
                                <span class="bast-badge-done">
                                    ✓ {{ $item->status_bast }}
                                </span>
                                <div class="ticket-detail-val">
                                    No. BAST: {{ $item->no_bast ?: '-' }}
                                </div>
                                <div class="ticket-detail-sub">
                                    Waktu BAST: {{ $item->tanggal_bast_selesai ? $item->tanggal_bast_selesai->format('d F Y, H:i') . ' WIB' : 'Selesai Sesuai Jadwal' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="ticket-empty-cell">
                                <div class="ticket-empty-icon"><i class="fi fi-rr-folder"></i></div>
                                <div class="ticket-empty-title">Belum Ada Data Riwayat Bagi Data DTSEN Resmi</div>
                                <div class="ticket-empty-desc">
                                    Setelah tiket permohonan Anda diselesaikan dan BAST ditandatangani, data resmi akan diterbitkan oleh Tim Walidata dan tercatat di sini.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
