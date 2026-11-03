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

                @php
                    $isLayananActive = request()->routeIs('opd.tiket.*') || request()->routeIs('opd.layanan.*') || request()->routeIs('formulir.*') || request()->routeIs('opd.layanan-bagi-pakai');
                @endphp
                <!-- 2. Layanan (Satu kata persis seperti di Admin dengan Dropdown Submenu) -->
                <div class="app-nav-dropdown {{ $isLayananActive ? 'active' : '' }}" style="position: relative;">
                    <a href="{{ route('opd.tiket.index') }}" class="opd-nav-link app-nav-dropdown-toggle {{ $isLayananActive ? 'active' : '' }}" style="justify-content: space-between;" title="Layanan Bagi Pakai DTSEN">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fi fi-rr-layers" style="font-size: 18px;"></i>
                            <span>Layanan</span>
                        </div>
                        <i class="fi fi-rr-angle-small-right app-nav-caret"></i>
                    </a>
                    <ul class="app-nav-submenu">
                        <li>
                            <a href="{{ route('opd.tiket.index') }}" class="app-nav-subitem {{ request()->routeIs('opd.tiket.index') || request()->routeIs('opd.layanan.index') ? 'active' : '' }}">
                                <i class="fi fi-rr-share"></i>
                                <span>Bagi Pakai DTSEN</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('opd.tiket.create') }}" class="app-nav-subitem {{ request()->routeIs('opd.tiket.create') || request()->routeIs('formulir.*') ? 'active' : '' }}">
                                <i class="fi fi-rr-plus"></i>
                                <span>Buat Permintaan Baru</span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Footer User Profile & Device ID (Tanpa Informasi Pribadi: Nama, Telepon, Jabatan) -->
            <div class="opd-sidebar-footer">
                <div class="opd-user-box">
                    <div class="opd-user-name" title="{{ Auth::user()->nama_instansi ?: Auth::user()->name }}">
                        <i class="fi fi-rr-building"></i> {{ Auth::user()->nama_instansi ?: Auth::user()->name }}
                    </div>
                    <div class="opd-user-instansi" title="{{ Auth::user()->email }}">
                        <i class="fi fi-rr-envelope"></i> {{ Auth::user()->email }}
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
