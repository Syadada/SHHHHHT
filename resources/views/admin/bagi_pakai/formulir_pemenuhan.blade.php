<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pemenuhan Layanan Bagi Pakai DTSEN — {{ $tiket->nomor_tiket }}</title>
    
    <!-- Modern Typography & Flaticon Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root {
            --doc-border: #1e293b;
            --doc-bg: #ffffff;
            --doc-font: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
            font-family: var(--doc-font);
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Top Action Bar (Hidden in Print) */
        .doc-action-bar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #ffffff;
            border-bottom: 1px solid #cbd5e1;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .doc-action-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .doc-badge-pill {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .doc-action-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-doc-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-doc-back {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            color: #475569;
        }
        .btn-doc-back:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn-doc-docx {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            color: #1d4ed8;
        }
        .btn-doc-docx:hover {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-doc-print {
            background: #0b57d0;
            border: 1px solid #0b57d0;
            color: #ffffff;
        }
        .btn-doc-print:hover {
            background: #0842a0;
        }

        /* Printable Paper Container */
        .doc-sheet-container {
            max-width: 900px;
            margin: 28px auto 60px;
            background: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 40px 48px;
            box-sizing: border-box;
            border: 1px solid #e2e8f0;
        }

        /* Official Document Header (Kop Surat) */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-bottom: 12px;
        }

        .kop-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        .kop-logo-td {
            width: 90px;
            text-align: center;
        }

        .kop-logo-img {
            max-width: 75px;
            max-height: 75px;
            object-fit: contain;
        }

        .kop-text-td {
            text-align: center;
            padding-left: 10px;
        }

        .kop-title-1 {
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            color: #0f172a;
            line-height: 1.25;
        }

        .kop-title-2 {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 2px 0 0 0;
            color: #0f172a;
            line-height: 1.25;
        }

        .kop-address {
            font-size: 11px;
            color: #334155;
            margin-top: 4px;
            line-height: 1.35;
        }

        .kop-divider {
            border-top: 3px double #0f172a;
            margin: 10px 0 16px;
        }

        /* Form Tables Styling (Strict Government Grid ala Docx) */
        .form-grid-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            line-height: 1.4;
            margin-bottom: 14px;
        }

        .form-grid-table th,
        .form-grid-table td {
            border: 1px solid #0f172a;
            padding: 6px 9px;
            vertical-align: top;
        }

        .bg-light-header {
            background-color: #f8fafc;
            font-weight: 700;
        }

        .title-banner {
            font-size: 13px;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px !important;
            background: #f1f5f9;
        }

        .doc-meta-label {
            width: 140px;
            background: #f8fafc;
            font-weight: 700;
        }

        .doc-section-heading {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 12px 0 6px;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Checkbox & Status in Tables */
        .doc-check-box {
            display: inline-block;
            width: 13px;
            height: 13px;
            border: 1px solid #0f172a;
            text-align: center;
            line-height: 11px;
            font-size: 10px;
            font-weight: 800;
            margin-right: 4px;
            vertical-align: middle;
        }

        /* Signature Row */
        .tte-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
            page-break-inside: avoid;
        }

        .tte-col {
            width: 45%;
            text-align: center;
            font-size: 12px;
        }

        .tte-box {
            height: 70px;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 8px auto;
            color: #0b57d0;
            font-size: 11px;
            font-weight: 700;
            background: #f8fafd;
            max-width: 200px;
        }

        .tte-name {
            font-weight: 700;
            text-decoration: underline;
        }

        .tte-nip {
            font-size: 11px;
            color: #475569;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .doc-action-bar {
                display: none !important;
            }
            .doc-sheet-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 15mm 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- 1. Top Action Toolbar (Sticky, Screen Only) -->
    <header class="doc-action-bar">
        <div class="doc-action-left">
            @if(auth()->check() && auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="btn-doc-action btn-doc-back">
                    <i class="fi fi-rr-arrow-left"></i> Kembali ke Layanan
                </a>
            @else
                <a href="{{ route('opd.tiket.index') }}" class="btn-doc-action btn-doc-back">
                    <i class="fi fi-rr-arrow-left"></i> Kembali ke Tiket
                </a>
            @endif
            <span class="doc-badge-pill">
                #{{ $tiket->nomor_tiket }}
            </span>
            <span style="font-size: 13px; font-weight: 700; color: #1e293b;">
                {{ $tiket->nama_instansi }}
            </span>
        </div>

        <div class="doc-action-right">
            <!-- Download DOCX resmi dari Word template -->
            @php
                $docxUrl = (auth()->check() && auth()->user()->isSuperAdmin()) 
                    ? route('admin.bagi-pakai-dtsen.download-docx', $tiket->id) 
                    : route('opd.tiket.download-docx', $tiket->id);
            @endphp
            <a href="{{ $docxUrl }}" class="btn-doc-action btn-doc-docx" title="Unduh berkas Microsoft Word yang telah terisi lengkap">
                <i class="fi fi-rr-file-word"></i> Unduh Word (.docx)
            </a>

            <!-- Print / PDF -->
            <button type="button" onclick="window.print()" class="btn-doc-action btn-doc-print" title="Cetak atau Simpan sebagai PDF">
                <i class="fi fi-rr-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </header>

    <!-- 2. Sheet Paper A4 Container -->
    <div class="doc-sheet-container">
        
        <!-- Kop Surat Resmi Banten (KP3B) -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo-td">
                    <img src="{{ asset('images/image1.png') }}" alt="Lambang Provinsi Banten" class="kop-logo-img">
                </td>
                <td class="kop-text-td">
                    <div class="kop-title-1">PEMERINTAH PROVINSI BANTEN</div>
                    <div class="kop-title-2">DINAS KOMUNIKASI, INFORMATIKA, STATISTIK DAN PERSANDIAN</div>
                    <div class="kop-address">
                        Kawasan Pusat Pemerintahan Provinsi Banten (KP3B)<br>
                        Jalan Syech Nawawi Al-Bantani, Palima Serang Banten<br>
                        Laman: https://diskominfo.bantenprov.go.id, Pos-el: diskominfo@bantenprov.go.id, Kode Pos: 42171
                    </div>
                </td>
            </tr>
        </table>
        <div class="kop-divider"></div>

        <!-- Judul & No Dokumen SOP -->
        <table class="form-grid-table">
            <tr>
                <td rowspan="3" class="title-banner" style="width: 55%; vertical-align: middle;">
                    FORMULIR PEMENUHAN PERMINTAAN LAYANAN BAGI PAKAI DTSEN
                </td>
                <td style="width: 20%; font-weight: 700; background: #f8fafc;">No. Dokumen</td>
                <td style="width: 25%; font-family: 'JetBrains Mono', monospace; font-size: 11px;">FR.01-SOP.DTSEN-09/DKISP</td>
            </tr>
            <tr>
                <td style="font-weight: 700; background: #f8fafc;">Revisi</td>
                <td style="font-family: 'JetBrains Mono', monospace; font-size: 11px;">00</td>
            </tr>
            <tr>
                <td style="font-weight: 700; background: #f8fafc;">Tanggal Berlaku</td>
                <td>22 Oktober 2026</td>
            </tr>
        </table>

        @php
            $extra = is_array($tiket->data_sop_extra) ? $tiket->data_sop_extra : (json_decode($tiket->data_sop_extra, true) ?: []);
            $tglPermintaan = $tiket->tgl_permintaan ? $tiket->tgl_permintaan->translatedFormat('d F Y') : $tiket->created_at->translatedFormat('d F Y');
            $tglDiterima = $tiket->tgl_diterima ? $tiket->tgl_diterima->translatedFormat('d F Y') : $tglPermintaan;
            $tglSuratTugas = $tiket->tgl_surat_tugas ? $tiket->tgl_surat_tugas->translatedFormat('d F Y') : $tglPermintaan;
            $tglMulai = $tiket->tgl_mulai ? $tiket->tgl_mulai->translatedFormat('d F Y') : $tiket->created_at->translatedFormat('d F Y');
            $tglSelesai = $tiket->tgl_selesai ? $tiket->tgl_selesai->translatedFormat('d F Y') : ($tiket->step5_at ? $tiket->step5_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y'));
        @endphp

        <!-- Metadata Nomor & ID Pekerjaan -->
        <table class="form-grid-table">
            <tr>
                <td class="doc-meta-label">Nomor</td>
                <td>{{ $tiket->nomor_naskah ?: '555/07-SOP/DISKOMINFO SP/2026' }}</td>
            </tr>
            <tr>
                <td class="doc-meta-label">ID Pekerjaan</td>
                <td style="font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0b57d0;">
                    {{ $tiket->nomor_tiket }}
                </td>
            </tr>
        </table>

        <!-- Bagian 1: Permintaan Layanan -->
        <div class="doc-section-heading">Permintaan Layanan</div>
        <table class="form-grid-table">
            <tr>
                <td class="doc-meta-label">Perangkat Daerah</td>
                <td style="width: 35%; font-weight: 700;">{{ $tiket->nama_instansi }}</td>
                <td class="doc-meta-label">Kontak</td>
                <td style="width: 35%;">
                    <strong>{{ $tiket->nama_pemohon ?: 'PIC Terdata' }}</strong><br>
                    Hp: {{ $tiket->kontak_pemohon ?: '-' }}
                </td>
            </tr>
            <tr>
                <td class="doc-meta-label">Nomor Surat Permintaan</td>
                <td style="font-weight: 600;">{{ $tiket->no_surat_edaran }}</td>
                <td class="doc-meta-label">No. Agenda Disposisi</td>
                <td style="font-family: 'JetBrains Mono', monospace;">
                    {{ $tiket->no_agenda_disposisi ?: ('DSP-' . $tiket->created_at->format('Y') . '-' . str_pad($tiket->id, 4, '0', STR_PAD_LEFT)) }}
                </td>
            </tr>
            <tr>
                <td class="doc-meta-label">Tanggal Permintaan</td>
                <td>{{ $tglPermintaan }}</td>
                <td class="doc-meta-label">Tanggal Diterima</td>
                <td>{{ $tglDiterima }}</td>
            </tr>
        </table>

        <!-- Bagian 2: Penugasan -->
        <div class="doc-section-heading">Penugasan</div>
        <table class="form-grid-table">
            <tr>
                <td class="doc-meta-label">No. Surat Tugas</td>
                <td>{{ $tiket->no_surat_tugas ?: ('ST.090/182/DISKOMINFO SP/' . $tiket->created_at->format('Y')) }}</td>
            </tr>
            <tr>
                <td class="doc-meta-label">Tanggal Surat Tugas</td>
                <td>{{ $tglSuratTugas }}</td>
            </tr>
        </table>

        <!-- Bagian 3: Deskripsi Pekerjaan -->
        <div class="doc-section-heading">Deskripsi Pekerjaan</div>
        <table class="form-grid-table">
            <tr>
                <td class="doc-meta-label">Deskripsi</td>
                <td style="width: 35%;">
                    {{ $tiket->deskripsi_pekerjaan ?: ('Memenuhi permintaan layanan bagi pakai data mikro DTSEN dari Walidata ke ' . $tiket->nama_instansi . '.') }}
                </td>
                <td class="doc-meta-label">Penanggung Jawab</td>
                <td style="width: 35%; font-weight: 700;">{{ $tiket->penanggung_jawab ?: 'Chobir Sabbaha, S.Kom., M.T.' }}</td>
            </tr>
            <tr>
                <td class="doc-meta-label">Tempat</td>
                <td>{{ $tiket->tempat_pelaksanaan ?: 'Gedung Diskominfo SP Provinsi Banten (KP3B Serang)' }}</td>
                <td class="doc-meta-label">Pelaksana 1</td>
                <td>{{ $tiket->pelaksana_1 ?: 'Tia Fitria Saumi, S.Kom.' }}</td>
            </tr>
            <tr>
                <td class="doc-meta-label">Tanggal Mulai</td>
                <td>{{ $tglMulai }}</td>
                <td class="doc-meta-label">Pelaksana 2</td>
                <td>{{ $tiket->pelaksana_2 ?: 'Ahmad Fauzi, S.T.' }}</td>
            </tr>
            <tr>
                <td class="doc-meta-label">Tanggal Selesai</td>
                <td>{{ $tglSelesai }}</td>
                <td class="doc-meta-label">Pelaksana 3</td>
                <td>{{ $tiket->pelaksana_3 ?: '-' }}</td>
            </tr>
            <tr>
                <td class="doc-meta-label">SOP</td>
                <td>{{ $tiket->sop_rujukan ?: '555/07-SOP/DISKOMINFO SP/2026' }}</td>
                <td class="doc-meta-label">Pelaksana 4</td>
                <td>{{ $tiket->pelaksana_4 ?: '-' }}</td>
            </tr>
        </table>

        <!-- Bagian 3b: Tim Pelaksana Walidata -->
        <div class="doc-section-heading">Tim Pelaksana Walidata</div>
        <table class="form-grid-table">
            <thead>
                <tr class="bg-light-header">
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 32%;">Nama & NIP</th>
                    <th style="width: 28%;">Instansi / Unit Kerja</th>
                    <th style="width: 22%;">Jabatan / Peran</th>
                    <th style="width: 13%; text-align: center;">Status Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <!-- Walidata: Penanggung Jawab -->
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>
                        <strong>{{ $tiket->penanggung_jawab ?: 'Chobir Sabbaha, S.Kom., M.T.' }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">NIP. {{ $tiket->nip_penanggung_jawab ?: '19780218 200501 1 009' }}</span>
                    </td>
                    <td>Diskominfo SP Prov. Banten (Walidata)</td>
                    <td>Penanggung Jawab Walidata</td>
                    <td style="text-align: center; color: #166534; font-weight: 600;">✓ Hadir / Pengesah</td>
                </tr>
                <!-- Walidata: Pelaksana 1 -->
                <tr>
                    <td style="text-align: center;">2</td>
                    <td>
                        <strong>{{ $tiket->pelaksana_1 ?: 'Tia Fitria Saumi, S.Kom.' }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">NIP. {{ $tiket->nip_pelaksana_1 ?: '19930812 202012 2 008' }}</span>
                    </td>
                    <td>Diskominfo SP Prov. Banten (Walidata)</td>
                    <td>{{ $tiket->jabatan_pelaksana_1 ?: 'Pranata Komputer Ahli Muda (Pelaksana 1)' }}</td>
                    <td style="text-align: center; color: #166534; font-weight: 600;">✓ Hadir</td>
                </tr>
                <!-- Walidata: Pelaksana 2 -->
                <tr>
                    <td style="text-align: center;">3</td>
                    <td>
                        <strong>{{ $tiket->pelaksana_2 ?: 'Ahmad Fauzi, S.T.' }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">NIP. {{ $tiket->nip_pelaksana_2 ?: '19890514 201503 1 004' }}</span>
                    </td>
                    <td>Diskominfo SP Prov. Banten (Walidata)</td>
                    <td>{{ $tiket->jabatan_pelaksana_2 ?: 'Statistisi Ahli Pertama (Pelaksana 2)' }}</td>
                    <td style="text-align: center; color: #166534; font-weight: 600;">✓ Hadir</td>
                </tr>
                @if(!empty($tiket->pelaksana_3))
                <tr>
                    <td style="text-align: center;">4</td>
                    <td>
                        <strong>{{ $tiket->pelaksana_3 }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">NIP. {{ $tiket->nip_pelaksana_3 ?: '-' }}</span>
                    </td>
                    <td>Diskominfo SP Prov. Banten (Walidata)</td>
                    <td>{{ $tiket->jabatan_pelaksana_3 ?: 'Tim Teknis Walidata (Pelaksana 3)' }}</td>
                    <td style="text-align: center; color: #166534; font-weight: 600;">✓ Hadir</td>
                </tr>
                @endif
                @if(!empty($tiket->pelaksana_4))
                <tr>
                    <td style="text-align: center;">5</td>
                    <td>
                        <strong>{{ $tiket->pelaksana_4 }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">NIP. {{ $tiket->nip_pelaksana_4 ?: '-' }}</span>
                    </td>
                    <td>Diskominfo SP Prov. Banten (Walidata)</td>
                    <td>{{ $tiket->jabatan_pelaksana_4 ?: 'Tim Teknis Walidata (Pelaksana 4)' }}</td>
                    <td style="text-align: center; color: #166534; font-weight: 600;">✓ Hadir</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div style="margin-bottom: 12px;"></div>

        <!-- Bagian 3c: Tim Pelaksana Pemohon -->
        <div class="doc-section-heading">Tim Pelaksana Pemohon</div>
        <table class="form-grid-table">
            <thead>
                <tr class="bg-light-header">
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 32%;">Nama & NIP</th>
                    <th style="width: 28%;">Instansi / Perangkat Daerah</th>
                    <th style="width: 22%;">Jabatan / Peran</th>
                    <th style="width: 13%; text-align: center;">Status Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <!-- Perwakilan OPD 1 -->
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>
                        <strong>{{ $tiket->pelaksana_opd_1 ?: ($tiket->nama_narahubung ?: $tiket->nama_pemohon) }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">NIP. {{ $tiket->nip_pelaksana_opd_1 ?: ($tiket->nip_narahubung ?: ($tiket->nip_pemohon ?: '-')) }}</span>
                    </td>
                    <td>{{ $tiket->nama_instansi }}</td>
                    <td>{{ $tiket->jabatan_pelaksana_opd_1 ?: ($tiket->jabatan_narahubung ?: ($tiket->jabatan_pemohon ?: 'PIC Narahubung / Penerima Data')) }}</td>
                    <td style="text-align: center; color: #166534; font-weight: 600;">✓ Hadir</td>
                </tr>
                @if(!empty($tiket->pelaksana_opd_2))
                <tr>
                    <td style="text-align: center;">2</td>
                    <td>
                        <strong>{{ $tiket->pelaksana_opd_2 }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">NIP. {{ $tiket->nip_pelaksana_opd_2 ?: '-' }}</span>
                    </td>
                    <td>{{ $tiket->nama_instansi }}</td>
                    <td>{{ $tiket->jabatan_pelaksana_opd_2 ?: 'Staf Pendamping OPD' }}</td>
                    <td style="text-align: center; color: #166534; font-weight: 600;">✓ Hadir</td>
                </tr>
                @endif
                @if(!empty($tiket->pelaksana_opd_3))
                <tr>
                    <td style="text-align: center;">3</td>
                    <td>
                        <strong>{{ $tiket->pelaksana_opd_3 }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">NIP. {{ $tiket->nip_pelaksana_opd_3 ?: '-' }}</span>
                    </td>
                    <td>{{ $tiket->nama_instansi }}</td>
                    <td>{{ $tiket->jabatan_pelaksana_opd_3 ?: 'Staf Pendamping OPD' }}</td>
                    <td style="text-align: center; color: #166534; font-weight: 600;">✓ Hadir</td>
                </tr>
                @endif
                @if(!empty($tiket->pelaksana_opd_4))
                <tr>
                    <td style="text-align: center;">4</td>
                    <td>
                        <strong>{{ $tiket->pelaksana_opd_4 }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">NIP. {{ $tiket->nip_pelaksana_opd_4 ?: '-' }}</span>
                    </td>
                    <td>{{ $tiket->nama_instansi }}</td>
                    <td>{{ $tiket->jabatan_pelaksana_opd_4 ?: 'Staf Pendamping OPD' }}</td>
                    <td style="text-align: center; color: #166534; font-weight: 600;">✓ Hadir</td>
                </tr>
                @endif
            </tbody>
        </table>
        @if(!empty($tiket->keterangan_kehadiran))
        <div style="font-size: 11px; margin-top: 5px; margin-bottom: 15px; color: #334155; font-style: italic;">
            * <strong>Catatan / Keterangan Kehadiran:</strong> {{ $tiket->keterangan_kehadiran }}
        </div>
        @else
        <div style="margin-bottom: 15px;"></div>
        @endif

        <!-- Bagian 4: Pelaksanaan (Tabel 5 Tahapan SOP) -->
        <div class="doc-section-heading">Pelaksanaan</div>
        <table class="form-grid-table">
            <thead>
                <tr class="bg-light-header">
                    <th style="width: 38%;">Item</th>
                    <th style="width: 22%; text-align: center;">Status / Waktu</th>
                    <th style="width: 40%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <!-- Tahap 1: Koordinasi -->
                <tr>
                    <td colspan="3" style="background: #f1f5f9; font-weight: 700;">
                        1. Koordinasi dengan Narahubung Perangkat Daerah Pengguna DTSEN
                    </td>
                </tr>
                <tr>
                    <td>&bull; Pelaksana</td>
                    <td colspan="2">{{ $tiket->pelaksana_1 ?: 'Tia Fitria Saumi, S.Kom.' }} / Tim Verifikator Walidata</td>
                </tr>
                <tr>
                    <td>&bull; Waktu Mulai</td>
                    <td colspan="2">{{ $tglMulai }}; {{ $tiket->created_at->format('H:i') }} WIB</td>
                </tr>
                <tr>
                    <td>&bull; Koordinasi pemenuhan dokumen persyaratan</td>
                    <td style="text-align: center; font-weight: 700; color: #166534;">
                        {{ $tiket->step1_done ? '✓ Terpenuhi' : '⏳ Dalam Proses' }}
                    </td>
                    <td>
                        <div style="font-size: 11px;">
                            <strong>Daftar Dokumen:</strong><br>
                            - Kerangka Acuan Kerja (KAK): <em>{{ $extra['status_kak'] ?? 'Ada' }}</em> (No: {{ $extra['nomor_kak'] ?? $tiket->no_surat_edaran }})<br>
                            - Penetapan Tim Pelaksana: <em>{{ $extra['status_tim'] ?? 'Ada' }}</em> (No: {{ $extra['nomor_tim'] ?? ($tiket->no_surat_tugas ?: ('ST.090/182/DISKOMINFO SP/' . $tiket->created_at->format('Y'))) }})<br>
                            - Dokumen ROPA DTSEN: <em>{{ $extra['status_ropa'] ?? 'Sesuai' }}</em> (No: {{ $extra['nomor_ropa'] ?? ('ROPA-DTSEN-' . $tiket->created_at->format('Y') . '-' . $tiket->id) }})<br>
                            - Dokumen DPIA DTSEN: <em>{{ $extra['status_dpia'] ?? 'Sesuai' }}</em> (No: {{ $extra['nomor_dpia'] ?? ('DPIA-DTSEN-' . $tiket->created_at->format('Y') . '-' . $tiket->id) }})<br>
                            - Klasifikasi DTSEN: <em>{{ $extra['status_klasifikasi'] ?? 'Sesuai (Data Terbatas)' }}</em> (No: {{ $extra['nomor_klasifikasi'] ?? ('KLAS-DTSEN-' . $tiket->created_at->format('Y')) }})<br>
                            - Hak Akses DTSEN: <em>{{ $extra['status_hak_akses'] ?? 'Diverifikasi' }}</em> (No: {{ $extra['nomor_hak_akses'] ?? ('HA-DTSEN-' . $tiket->created_at->format('Y')) }})
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>&bull; Koordinasi jadwal pelaksanaan serah terima</td>
                    <td style="text-align: center; font-weight: 700; color: #166534;">
                        {{ $tiket->step2_done ? '✓ Terjadwal' : '-' }}
                    </td>
                    <td>
                        Jadwal: {{ !empty($extra['jadwal_serah_terima']) ? \Carbon\Carbon::parse($extra['jadwal_serah_terima'])->translatedFormat('l, d F Y') : $tiket->created_at->translatedFormat('l, d F Y') }}<br>
                        Catatan: Serah terima data mikro via portal resmi.
                    </td>
                </tr>
                <tr>
                    <td>&bull; Waktu Selesai</td>
                    <td colspan="2">{{ $tglSelesai }}; {{ ($tiket->step5_at ?? $tiket->created_at)->format('H:i') }} WIB</td>
                </tr>

                <!-- Tahap 2: Pengolahan -->
                <tr>
                    <td colspan="3" style="background: #f1f5f9; font-weight: 700;">
                        2. Pengolahan DTSEN
                    </td>
                </tr>
                <tr>
                    <td>&bull; Memilah DTSEN sesuai KAK pemohon</td>
                    <td style="text-align: center; font-weight: 700; color: {{ $tiket->step3_done ? '#166534' : '#64748b' }};">
                        {{ $tiket->step3_done ? '✓ Selesai' : '⏳ Proses' }}
                    </td>
                    <td>
                        Variabel diproses: <strong>{{ count($tiket->variabel_dtsen ?? []) }} Atribut Data</strong>.<br>
                        Formulir Pengolahan: {{ $extra['no_formulir_pengolahan'] ?? ('F.PGL-' . $tiket->id) }}
                    </td>
                </tr>

                <!-- Tahap 3: Transfer DTSEN -->
                <tr>
                    <td colspan="3" style="background: #f1f5f9; font-weight: 700;">
                        3. Transfer DTSEN
                    </td>
                </tr>
                <tr>
                    <td>&bull; Serah terima DTSEN kepada perangkat daerah</td>
                    <td style="text-align: center; font-weight: 700; color: {{ $tiket->step4_done ? '#166534' : '#64748b' }};">
                        {{ $tiket->step4_done ? '✓ Diserahkan' : '⏳ Proses' }}
                    </td>
                    <td>
                        Berita Acara Serah Terima (BAST): <strong>{{ $extra['no_bast'] ?? ('BAST/' . $tiket->nomor_tiket) }}</strong>
                    </td>
                </tr>

                <!-- Tahap 4: Pembaruan ROPA -->
                <tr>
                    <td colspan="3" style="background: #f1f5f9; font-weight: 700;">
                        4. Pembaruan ROPA DTSEN
                    </td>
                </tr>
                <tr>
                    <td>&bull; Perekaman aktivitas pemrosesan (ROPA)</td>
                    <td style="text-align: center; font-weight: 700; color: {{ $tiket->step5_done ? '#166534' : '#64748b' }};">
                        {{ $tiket->step5_done ? '✓ Terekam' : '⏳ Proses' }}
                    </td>
                    <td>
                        Dokumen ROPA: <strong>{{ $extra['nomor_ropa'] ?? ('ROPA-DTSEN-' . $tiket->created_at->format('Y') . '-' . $tiket->id) }}</strong>
                    </td>
                </tr>

                <!-- Tahap 5: DPIA -->
                <tr>
                    <td colspan="3" style="background: #f1f5f9; font-weight: 700;">
                        5. Penilaian Dampak Pelindungan Data Pribadi (DPIA)
                    </td>
                </tr>
                <tr>
                    <td>&bull; Melaksanakan penilaian dampak PDP</td>
                    <td style="text-align: center; font-weight: 700; color: {{ $tiket->step5_done ? '#166534' : '#64748b' }};">
                        {{ $tiket->step5_done ? '✓ Lengkap' : '⏳ Proses' }}
                    </td>
                    <td>
                        Dokumen DPIA: <strong>{{ $extra['nomor_dpia'] ?? ('DPIA-DTSEN-' . $tiket->created_at->format('Y') . '-' . $tiket->id) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Bagian 5: Catatan Pelaksana -->
        <div class="doc-section-heading">Catatan Pelaksana</div>
        <table class="form-grid-table">
            <tr>
                <td>{{ $tiket->catatan_admin ?: '-' }}</td>
            </tr>
        </table>

        <!-- Bagian 6: Hasil -->
        <div class="doc-section-heading">Hasil</div>
        <table class="form-grid-table">
            <tr>
                <td>
                    {{ $tiket->hasil_pekerjaan ?: ('Pelaksana telah melaksanakan pemenuhan permintaan Layanan Bagi Pakai DTSEN ke ' . $tiket->nama_instansi . ' sesuai dengan penugasan pimpinan dan ketentuan SOP Nomor ' . ($tiket->sop_rujukan ?: '555/07-SOP/DISKOMINFO SP/2026') . '.') }}
                </td>
            </tr>
        </table>

        <!-- Bagian 7: Tanda Tangan & Visualisasi TTE -->
        <div class="tte-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 24px; page-break-inside: avoid;">
            <div class="tte-col" style="width: auto;">
                <div style="font-weight: 600;">Tim Pelaksana Walidata 1,</div>
                <div style="font-size: 10px; color: #64748b; margin-bottom: 4px;">{{ $tiket->jabatan_pelaksana_1 ?: 'Pranata Komputer Ahli Muda' }}</div>
                <div class="tte-box">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 3px;">
                        <i class="fi fi-rr-shield-check" style="font-size: 16px; color: #2563eb;"></i>
                        <span style="color: #2563eb; font-weight: 700; font-size: 10px;">TTE TERVERIFIKASI</span>
                        <span style="font-size: 8px; color: #64748b; font-family: monospace;">BSrE BSSN</span>
                    </div>
                </div>
                <div class="tte-name">{{ $tiket->pelaksana_1 ?: 'Tia Fitria Saumi, S.Kom.' }}</div>
                <div class="tte-nip">NIP. {{ $tiket->nip_pelaksana_1 ?: '19930812 202012 2 008' }}</div>
            </div>

            <div class="tte-col" style="width: auto;">
                <div style="font-weight: 600;">Tim Pelaksana Walidata 2,</div>
                <div style="font-size: 10px; color: #64748b; margin-bottom: 4px;">{{ $tiket->jabatan_pelaksana_2 ?: 'Statistisi Ahli Pertama' }}</div>
                <div class="tte-box">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 3px;">
                        <i class="fi fi-rr-shield-check" style="font-size: 16px; color: #2563eb;"></i>
                        <span style="color: #2563eb; font-weight: 700; font-size: 10px;">TTE TERVERIFIKASI</span>
                        <span style="font-size: 8px; color: #64748b; font-family: monospace;">BSrE BSSN</span>
                    </div>
                </div>
                <div class="tte-name">{{ $tiket->pelaksana_2 ?: 'Ahmad Fauzi, S.T.' }}</div>
                <div class="tte-nip">NIP. {{ $tiket->nip_pelaksana_2 ?: '19890514 201503 1 004' }}</div>
            </div>

            <div class="tte-col" style="width: auto;">
                <div style="font-weight: 600;">Tim Pelaksana Pemohon,</div>
                <div style="font-size: 10px; color: #64748b; margin-bottom: 4px;">{{ $tiket->jabatan_pelaksana_opd_1 ?: ($tiket->jabatan_narahubung ?: ($tiket->jabatan_pemohon ?: 'PIC / Narahubung OPD')) }}</div>
                <div class="tte-box">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 3px;">
                        <i class="fi fi-rr-shield-check" style="font-size: 16px; color: #16a34a;"></i>
                        <span style="color: #16a34a; font-weight: 700; font-size: 10px;">TTE / TANDA TANGAN</span>
                        <span style="font-size: 8px; color: #64748b; font-family: monospace;">BERITA ACARA DTSEN</span>
                    </div>
                </div>
                <div class="tte-name">{{ $tiket->pelaksana_opd_1 ?: ($tiket->nama_narahubung ?: $tiket->nama_pemohon) }}</div>
                <div class="tte-nip">NIP. {{ $tiket->nip_pelaksana_opd_1 ?: ($tiket->nip_narahubung ?: ($tiket->nip_pemohon ?: '-')) }}</div>
            </div>
        </div>

        <div style="margin-top: 26px; text-align: center; page-break-inside: avoid;">
            <div style="font-size: 12px;">Mengetahui,</div>
            <div style="font-size: 12px; font-weight: 700;">Kepala Bidang Statistik, Persandian dan Keamanan Informasi</div>
            <div style="font-size: 11px; color: #475569;">selaku Penanggung Jawab Walidata</div>
            <div class="tte-box" style="margin: 10px auto; width: 260px;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 3px;">
                    <i class="fi fi-rr-shield-check" style="font-size: 18px; color: #16a34a;"></i>
                    <span style="color: #16a34a; font-weight: 700;">TTE DISAHKAN RESMI</span>
                    <span style="font-size: 9px; color: #64748b; font-family: monospace;">ID: TTE-WALIDATA-{{ $tiket->id }}</span>
                </div>
            </div>
            <div class="tte-name" style="font-size: 13px;">{{ $tiket->penanggung_jawab ?: 'Chobir Sabbaha, S.Kom., M.T.' }}</div>
            <div class="tte-nip">NIP. {{ $tiket->nip_penanggung_jawab ?: '19780218 200501 1 009' }}</div>
        </div>
    </div>

</body>
</html>
