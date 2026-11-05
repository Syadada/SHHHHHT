<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Layanan Permintaan Bagi Pakai DTSEN') — Sistem DTSEN</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Product+Sans:wght@400;700&family=Roboto:wght@300;400;500;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Unified Master Stylesheet (Single CSS) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon" style="font-size: 13px; font-weight: 800; background: #0b57d0; color: #fff; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">DTSEN</div>
            <div class="brand-text">
                <h1 style="font-size: 15px; font-weight: 700; color: #1f1f1f;">Layanan DTSEN</h1>
                <span style="font-size: 11px; color: #5e5e5e;">Sistem Bagi Pakai Data</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-category">Modul Layanan</div>
            <a href="{{ route('formulir.index') }}" class="nav-item {{ request()->routeIs('formulir.*') ? 'active' : '' }}">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                <span>Tiket Bagi Pakai DTSEN</span>
            </a>

            @auth
                <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Profil & Riwayat Data</span>
                </a>

                @if(Auth::user()->isSuperAdmin())
                    <div class="nav-category" style="margin-top: 14px;">Kendali Walidata</div>
                    <a href="{{ route('admin.riwayat') }}" class="nav-item {{ request()->routeIs('admin.riwayat*') ? 'active' : '' }}">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>Dashboard & Proses</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>User Management & Log</span>
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>Login Petugas / Instansi</span>
                </a>
            @endauth
        </nav>

        <div class="sidebar-footer">
            <div class="python-status">
                <span class="python-dot"></span>
                <span>Layanan DTSEN: Aktif</span>
            </div>
            <div style="margin-top: 8px; font-size: 11px;">
                Hak Akses: <strong>{{ Auth::check() ? (Auth::user()->isSuperAdmin() ? 'Walidata' : 'Perangkat Daerah') : 'Tamu (Guest)' }}</strong>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-title">
                <h2>@yield('header_title', 'Layanan Permintaan Bagi Pakai DTSEN')</h2>
            </div>
            <div class="topbar-actions" style="display: flex; align-items: center; gap: 12px;">
                @auth
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 12px; font-weight: 600; color: #1f1f1f;">
                            {{ Auth::user()->name }} ({{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'OPD' }})
                        </span>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn-header-logout" style="padding: 6px 12px; font-size: 12px;">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-pill-primary" style="font-size: 12px; padding: 6px 14px; text-decoration: none;">
                        Login Masuk
                    </a>
                @endauth
            </div>
        </header>

        <main class="content-container">
            @if(session('success'))
                <div class="alert alert-success">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
    @include('partials.idle_session_timer')
</body>
</html>
