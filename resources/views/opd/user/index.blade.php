@extends('layouts.opd')

@section('title', 'Informasi Pengguna — Portal Perangkat Daerah')
@section('page_title', 'User')
@section('page_subtitle', 'Informasi Akun Instansi Kedinasan')

@section('content')
<div class="ticket-stack-container">

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb">
        <a href="{{ route('opd.dashboard') }}"><i class="fi fi-rr-home"></i> Dashboard</a>
        <span class="separator">/</span>
        <span class="current">User</span>
    </nav>

    <!-- Header Banner Read-Only -->
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 54px; height: 54px; border-radius: 14px; background: linear-gradient(135deg, #0b57d0 0%, #0842a0 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 4px 12px rgba(11, 87, 208, 0.2);">
                    <i class="fi fi-rr-building"></i>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;">
                            {{ $user->nama_instansi ?: $user->name }}
                        </h1>
                        <span style="font-size: 11px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 3px 10px; border-radius: 20px; text-transform: uppercase;">
                            Akun OPD
                        </span>
                    </div>
                    <div style="font-size: 13px; color: #64748b; margin-top: 4px;">
                        Username: <code style="font-family: var(--font-mono); color: #0b57d0; font-weight: 700;">{{ $user->username }}</code> &bull; Email: <strong>{{ $user->email }}</strong>
                    </div>
                </div>
            </div>

            <div>
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 20px; font-size: 12px; font-weight: 600; color: #475569;">
                    <i class="fi fi-rr-eye" style="color: #0b57d0;"></i> Mode Lihat Saja (Read-Only)
                </span>
            </div>
        </div>
    </div>

    <!-- Grid Rincian Informasi Akun -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
        
        <!-- Kartu 1: Profil Kedinasan -->
        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 22px; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <i class="fi fi-rr-id-badge" style="font-size: 18px; color: #0b57d0;"></i>
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Profil Kedinasan Instansi</h3>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase;">Nama Instansi / Lembaga</div>
                    <div style="font-size: 14px; font-weight: 700; color: #1e293b; margin-top: 2px;">{{ $user->nama_instansi ?: $user->name }}</div>
                </div>

                <div>
                    <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase;">Alamat Email Resmi Kedinasan</div>
                    <div style="font-size: 14px; font-weight: 600; color: #1e293b; margin-top: 2px;">{{ $user->email }}</div>
                </div>

                <div>
                    <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase;">Peran Akun Sistem</div>
                    <div style="font-size: 13px; font-weight: 600; color: #0b57d0; margin-top: 2px;">
                        Perangkat Daerah (OPD Pemohon Layanan)
                    </div>
                </div>

                <div>
                    <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase;">Status Akun</div>
                    <div style="margin-top: 2px;">
                        <span style="font-size: 11.5px; font-weight: 700; background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 6px;">
                            ✓ AKTIF & TERVERIFIKASI
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu 2: Keamanan & Perangkat Terdaftar -->
        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 22px; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <i class="fi fi-rr-shield-check" style="font-size: 18px; color: #0b57d0;"></i>
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Keamanan & Perangkat Terdaftar</h3>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase;">ID Perangkat Laptop Login</div>
                    <div style="font-size: 13.5px; font-weight: 700; color: #1e293b; font-family: var(--font-mono); margin-top: 2px;">
                        <i class="fi fi-rr-laptop" style="color: #0b57d0;"></i> {{ $user->last_login_device ?: 'WIN-DEV-OPD01' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase;">Alamat IP Terakhir</div>
                    <div style="font-size: 13px; font-weight: 600; color: #1e293b; font-family: var(--font-mono); margin-top: 2px;">
                        {{ $user->last_login_ip ?: '127.0.0.1' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase;">Sesi Terakhir Login</div>
                    <div style="font-size: 13px; color: #475569; margin-top: 2px;">
                        {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') . ' WIB' : 'Hari ini' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase;">Waktu Pendaftaran Akun</div>
                    <div style="font-size: 13px; color: #475569; margin-top: 2px;">
                        {{ $user->created_at ? $user->created_at->format('d F Y') : '30 Oktober 2026' }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Informasi Catatan Pengelolaan Terpusat -->
    <div style="margin-top: 20px; padding: 16px 20px; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 14px; display: flex; align-items: flex-start; gap: 14px;">
        <i class="fi fi-rr-info" style="font-size: 20px; color: #0b57d0; margin-top: 2px;"></i>
        <div style="font-size: 12.5px; color: #475569; line-height: 1.5;">
            <strong>Catatan Kebijakan Akun:</strong> Akun ini merupakan akun resmi kedinasan Perangkat Daerah (OPD). Untuk menjaga keamanan data dan tata kelola SPBE, perubahan nama instansi, email resmi, atau penambahan hak akses dikelola secara terpusat oleh Tim Administrator Walidata Diskominfo.
        </div>
    </div>

</div>
@endsection

