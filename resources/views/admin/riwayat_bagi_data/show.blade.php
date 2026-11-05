@extends('layouts.admin')

@section('title', 'Detail Berita Acara DTSEN — ' . $riwayat->no_surat_edaran . ' — Admin Panel')

@section('content')
<div class="ticket-stack-container">

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.users.index', ['tab' => 'riwayat-terbit']) }}"><i class="fi fi-rr-document"></i> Riwayat Bagi Data</a>
        <span class="separator">/</span>
        <span class="current">{{ $riwayat->no_surat_edaran }}</span>
    </nav>

    <!-- Top Action Bar -->
    <div class="ticket-top-actions">
        <a href="{{ route('admin.users.index', ['tab' => 'riwayat-terbit']) }}" class="btn-pill-secondary">
            <i class="fi fi-rr-arrow-left"></i> Kembali ke Riwayat Terbit
        </a>

        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="bast-badge-done">
                <i class="fi fi-rr-check"></i> {{ $riwayat->status_bast }}
            </span>
            <span class="badge-verified">
                ADMIN RECORD
            </span>
        </div>
    </div>

    <!-- 1. HERO SUMMARY CARD -->
    <div class="admin-card-section" style="margin-bottom: 24px;">
        <div class="admin-card-body" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                        Nomor Surat Edaran Resmi
                    </div>
                    <div style="font-family: var(--font-mono, monospace); font-size: 22px; font-weight: 800; color: #0b57d0; background: #eff6ff; padding: 6px 14px; border-radius: 8px; border: 1px solid #bfdbfe; display: inline-block;">
                        {{ $riwayat->no_surat_edaran }}
                    </div>
                    <div style="margin-top: 10px; font-size: 15px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                        <i class="fi fi-rr-building" style="color: #64748b;"></i>
                        <span>{{ $riwayat->nama_instansi }}</span>
                    </div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Nomor Dokumen BAST:</div>
                    <div style="font-family: var(--font-mono, monospace); font-size: 16px; font-weight: 700; color: #0f172a;">
                        {{ $riwayat->no_bast ?: '-' }}
                    </div>
                    <div style="font-size: 12px; color: #166534; font-weight: 600; margin-top: 4px;">
                        <i class="fi fi-rr-calendar"></i> Waktu BAST: {{ $riwayat->tanggal_bast_selesai ? $riwayat->tanggal_bast_selesai->format('d F Y, H:i') . ' WIB' : 'Selesai Sesuai Jadwal' }}
                    </div>
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 20px 0;">

            <!-- Rincian Parameter Berita Acara -->
            <div class="grid grid-cols-4" style="gap: 16px;">
                <div style="background: #f8fafc; padding: 12px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; color: #64748b; font-weight: 600;">WAKTU TIKET DIBUAT</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 4px;">
                        {{ $riwayat->tanggal_tiket_dibuat ? $riwayat->tanggal_tiket_dibuat->format('d/m/Y H:i') . ' WIB' : '-' }}
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 12px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; color: #64748b; font-weight: 600;">REFERENSI TIKET DTSEN</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0b57d0; margin-top: 4px;">
                        @if($riwayat->tiket)
                            <a href="{{ route('admin.tiket.show', $riwayat->tiket->id) }}" style="color: #0b57d0; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                #{{ $riwayat->tiket->nomor_tiket }}
                                <i class="fi fi-rr-arrow-right" style="font-size: 11px;"></i>
                            </a>
                        @else
                            <span style="color: #94a3b8;">Tidak Terkait Tiket Langsung</span>
                        @endif
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 12px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; color: #64748b; font-weight: 600;">STATUS PENGESAHAN</div>
                    <div style="font-size: 14px; font-weight: 700; color: #166534; margin-top: 4px;">
                        {{ $riwayat->status_bast }}
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 12px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; color: #64748b; font-weight: 600;">PETUGAS INPUT ADMIN</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 4px;">
                        {{ $riwayat->created_by ?: 'Walidata' }}
                    </div>
                </div>
            </div>

            @if($riwayat->catatan)
                <div style="margin-top: 16px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #92400e;">
                    <strong>Catatan Administrasi:</strong> {{ $riwayat->catatan }}
                </div>
            @endif
        </div>
    </div>

    <!-- 2. PEMISAHAN DATA: ORANG OPD YANG HADIR & NOMOR LAPTOP (TERPISAH JELAS) -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">

        <!-- CARD 1: DATA PERWAKILAN / ORANG OPD YANG HADIR -->
        <div class="admin-card-section" style="border: 1px solid #bfdbfe; background: #ffffff;">
            <div class="admin-card-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 16px 20px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: #eff6ff; color: #0b57d0; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fi fi-rr-users"></i>
                    </div>
                    <div>
                        <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">
                            Data Petugas / Orang OPD yang Hadir
                        </h2>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0 0;">
                            Daftar pejabat / staf instansi yang menghadiri serah terima data
                        </p>
                    </div>
                </div>
            </div>

            <div class="admin-card-body" style="padding: 20px;">
                @php
                    $namaList = preg_split('/[\r\n,;]+/', $riwayat->orang_hadir);
                    $namaList = array_filter(array_map('trim', $namaList));
                @endphp

                @if(count($namaList) > 0)
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($namaList as $idx => $nama)
                            <div style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                                    {{ strtoupper(substr($nama, 0, 1)) }}
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 14px;">
                                        {{ $nama }}
                                    </div>
                                    <div style="font-size: 11px; color: #64748b;">
                                        Perwakilan Instansi (Hadir Fisik & Tanda Tangan)
                                    </div>
                                </div>
                                <div>
                                    <span style="font-size: 11px; font-weight: 600; background: #f0fdf4; color: #166534; padding: 2px 8px; border-radius: 4px; border: 1px solid #bbf7d0;">
                                        Hadir
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="color: #64748b; font-size: 13px; font-style: italic; padding: 10px;">
                        {{ $riwayat->orang_hadir ?: 'Belum ada rincian nama petugas yang hadir.' }}
                    </div>
                @endif

                <div style="margin-top: 16px; padding: 10px 12px; background: #f1f5f9; border-radius: 6px; font-size: 11.5px; color: #475569; display: flex; align-items: center; gap: 6px;">
                    <i class="fi fi-rr-info" style="color: #0b57d0;"></i>
                    <span>Total hadir resmi: <strong>{{ count($namaList) }} Orang Petugas</strong></span>
                </div>
            </div>
        </div>

        <!-- CARD 2: IDENTITAS PERANGKAT & NOMOR LAPTOP (TERPISAH) -->
        <div class="admin-card-section" style="border: 1px solid #d8b4fe; background: #ffffff;">
            <div class="admin-card-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 16px 20px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: #f3e8ff; color: #7e22ce; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fi fi-rr-laptop"></i>
                    </div>
                    <div>
                        <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">
                            Data Perangkat & Nomor Laptop
                        </h2>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0 0;">
                            Hardware resmi OPD yang digunakan saat penerimaan data DTSEN
                        </p>
                    </div>
                </div>
            </div>

            <div class="admin-card-body" style="padding: 20px;">
                <div style="background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); border: 1px solid #d8b4fe; border-radius: 10px; padding: 18px; text-align: center; margin-bottom: 16px;">
                    <div style="width: 52px; height: 52px; border-radius: 50%; background: #ffffff; color: #7e22ce; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 10px auto; box-shadow: 0 4px 12px rgba(126, 34, 206, 0.15);">
                        <i class="fi fi-rr-laptop"></i>
                    </div>
                    
                    <div style="font-size: 11px; font-weight: 700; color: #6b21a8; text-transform: uppercase; letter-spacing: 0.5px;">
                        Nomor Seri Laptop / Alamat IP Terdaftar
                    </div>
                    
                    <div style="font-family: var(--font-mono, monospace); font-size: 20px; font-weight: 800; color: #581c87; margin-top: 6px;">
                        {{ $riwayat->ip_atau_nomor_laptop ?: 'Belum Ada Identitas Laptop' }}
                    </div>

                    <div style="margin-top: 8px;">
                        <span style="font-size: 11px; font-weight: 600; background: #ffffff; color: #7e22ce; padding: 3px 10px; border-radius: 20px; border: 1px solid #d8b4fe; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fi fi-rr-shield-check"></i> Perangkat Terverifikasi & Tersegel BAST
                        </span>
                    </div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; font-size: 12px; color: #475569; line-height: 1.5;">
                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                        <i class="fi fi-rr-lock" style="color: #64748b;"></i> Protokol Keamanan Data DTSEN:
                    </div>
                    Data DTSEN diserahkan secara offline/langsung ke dalam memori perangkat ini dan dilarang untuk disebarluaskan di luar ketentuan klausul Berita Acara Serah Terima.
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
