<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Perangkat Daerah') — Layanan Bagi Pakai DTSEN</title>
    
    <!-- Modern Typography (Roboto & JetBrains Mono) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Unified Master Stylesheet (Single CSS + Flaticon UIcons) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('styles')
</head>
<body>
    <div class="opd-layout">
        <!-- 1. SIDEBAR NAVIGASI OPD -->
        <aside class="opd-sidebar">
            <!-- Header Brand -->
            <div class="opd-sidebar-header">
                <a href="{{ route('opd.dashboard') }}" class="opd-brand-box">
                    <div class="opd-brand-icon">
                        DTSEN
                    </div>
                    <div>
                        <div class="opd-brand-title">Layanan DTSEN</div>
                        <span class="opd-brand-subtitle">Portal Perangkat Daerah</span>
                    </div>
                </a>
            </div>

            <!-- Menu Navigasi -->
            <div class="opd-sidebar-body">
                <div class="opd-nav-section-title">Menu Utama</div>

                <!-- 1. Dashboard Clean (Halaman Utama) -->
                <a href="{{ route('opd.dashboard') }}" class="opd-nav-link {{ request()->routeIs('opd.dashboard') ? 'active' : '' }}">
                    <i class="fi fi-rr-apps"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Tiket Saya (Support Tickets ala DomaiNesia) -->
                <a href="{{ route('opd.tiket.index') }}" class="opd-nav-link {{ request()->routeIs('opd.tiket.*') ? 'active' : '' }}">
                    <i class="fi fi-rr-ticket"></i>
                    <span>Tiket Saya</span>
                </a>

                <!-- 3. Layanan Bagi Pakai DTSEN (Progress 5 Tahap & Riwayat BAST) -->
                <a href="{{ route('opd.layanan-bagi-pakai') }}" class="opd-nav-link {{ request()->routeIs('opd.layanan-bagi-pakai') || request()->routeIs('profile') ? 'active' : '' }}">
                    <i class="fi fi-rr-document"></i>
                    <span>Layanan Bagi Pakai DTSEN</span>
                </a>

                <!-- 4. Permintaan Bagi Pakai (Formulir Pengajuan) -->
                <a href="{{ route('opd.tiket.create') }}" class="opd-nav-link {{ request()->routeIs('formulir.*') || request()->routeIs('opd.tiket.create') ? 'active' : '' }}">
                    <i class="fi fi-rr-plus"></i>
                    <span>Permintaan Bagi Pakai</span>
                    <span class="opd-nav-badge">+Form</span>
                </a>

            </div>

            <!-- Footer User Profile & Device ID -->
            <div class="opd-sidebar-footer">
                <div class="opd-user-box">
                    <div class="opd-user-name" title="{{ Auth::user()->name }}">
                        <i class="fi fi-rr-user"></i> {{ Auth::user()->name }}
                    </div>
                    <div class="opd-user-instansi" title="{{ Auth::user()->nama_instansi ?: Auth::user()->name }}">
                        {{ Auth::user()->nama_instansi ?: Auth::user()->name }}
                    </div>
                    <div>
                        <span class="opd-device-pill" title="ID Perangkat Laptop Login">
                            <i class="fi fi-rr-laptop"></i> {{ Auth::user()->last_login_device ?: 'WIN-DEV-OPD01' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="form-inline">
                    @csrf
                    <button type="submit" class="opd-logout-btn">
                        <i class="fi fi-rr-sign-out-alt"></i>
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- 2. MAIN CONTENT WRAPPER -->
        <div class="opd-main">
            <!-- Topbar OPD -->
            <header class="opd-topbar">
                <div class="opd-topbar-left">
                    <div>
                        <span class="opd-page-title">@yield('page_title', 'Dashboard OPD')</span>
                        <span class="opd-page-subtitle d-none d-md-inline">@yield('page_subtitle', 'Portal Layanan Bagi Pakai DTSEN')</span>
                    </div>
                </div>

                <div class="opd-topbar-right">
                    <div class="opd-topbar-date">
                        <i class="fi fi-rr-calendar"></i>
                        <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>

                    <a href="{{ route('formulir.index') }}" class="btn-pill-primary ticket-btn-back">
                        <i class="fi fi-rr-plus"></i> Permintaan Bagi Pakai
                    </a>
                </div>
            </header>

            <!-- Alert Flash Messages -->
            <div class="opd-flash-container">
                @if(session('success'))
                    <div class="form-alert-success opd-flash-alert">
                        <i class="fi fi-rr-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="form-alert-error opd-flash-alert">
                        <i class="fi fi-rr-cross-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="opd-content">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
    @include('partials.idle_session_timer')
</body>
</html>
