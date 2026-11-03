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

    <!-- Flash Messages -->
    @if(session('success'))
        <div style="background: #f0fdf4; color: #166534; border: 1.5px solid #bbf7d0; padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px; display: flex; align-items: center; gap: 10px;">
            <i class="fi fi-rr-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div style="background: #fef2f2; color: #991b1b; border: 1.5px solid #fecaca; padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13.5px;">
            <div style="font-weight: 700; margin-bottom: 4px;"><i class="fi fi-rr-cross-circle"></i> Periksa Isian Anda:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Banner -->
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
                <a href="#formUbahPassword" class="btn-pill-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 13px; text-decoration: none;">
                    <i class="fi fi-rr-key"></i>
                    <span>Reset Kata Sandi</span>
                </a>
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

    <!-- Kartu Reset Kata Sandi Akun -->
    <div id="formUbahPassword" style="margin-top: 24px; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
            <i class="fi fi-rr-key" style="font-size: 18px; color: #0b57d0;"></i>
            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Reset Kata Sandi Akun</h3>
        </div>

        <form action="{{ route('opd.user.update-password') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 18px;">
                <div>
                    <label class="form-group-label" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                        Kata Sandi Baru <span class="form-required">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="form-text-input" 
                           placeholder="Minimal 4 karakter" 
                           required 
                           style="padding: 9px 14px; font-size: 13px;">
                </div>

                <div>
                    <label class="form-group-label" style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                        Konfirmasi Kata Sandi Baru <span class="form-required">*</span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="form-text-input" 
                           placeholder="Ulangi kata sandi baru" 
                           required 
                           style="padding: 9px 14px; font-size: 13px;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-pill-primary" style="padding: 9px 22px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fi fi-rr-check"></i>
                    <span>Simpan Kata Sandi Baru</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

