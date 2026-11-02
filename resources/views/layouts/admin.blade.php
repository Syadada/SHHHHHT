<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — Layanan Bagi Pakai DTSEN</title>
    
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
    <div class="app-shell">
        
        <!-- 1. TOP APP BAR ADMIN -->
        <header class="app-header">
            <div class="app-header-left">
                <a href="{{ route('admin.dashboard') }}" class="app-brand">
                    <div class="app-brand-icon app-brand-icon-dtsen">
                        DTSEN
                    </div>
                    <div class="app-brand-title">
                        <span>Layanan Bagi Pakai DTSEN</span>
                        <span class="app-brand-badge">SUPER ADMIN</span>
                    </div>
                </a>
            </div>

            <!-- Right Controls -->
            <div class="app-header-right">
                <a href="{{ route('opd.dashboard') }}" class="btn-header-link" target="_blank" title="Lihat Portal OPD">
                    <i class="fi fi-rr-browser"></i> <span>Preview Portal OPD</span>
                </a>

                <div class="app-user-chip">
                    <div class="app-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span>{{ Auth::user()->name ?? 'Administrator' }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="form-inline">
                    @csrf
                    <button type="submit" class="btn-header-logout" title="Keluar dari akun">
                        <i class="fi fi-rr-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- 2. BODY LAYOUT (SIDEBAR + MAIN CONTENT) -->
        <div class="app-body">
            
            <!-- Left Sidebar Navigation Admin -->
            <aside class="app-sidebar">
                <ul class="app-nav-list">
                    <!-- 1. Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="app-nav-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.riwayat') ? 'active' : '' }}">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-apps app-nav-icon"></i>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>

                    <!-- 2. Ticket -->
                    <li>
                        <a href="{{ route('admin.tiket.index') }}" class="app-nav-item {{ request()->routeIs('admin.tiket.*') ? 'active' : '' }}">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-ticket app-nav-icon"></i>
                                <span>Ticket</span>
                            </div>
                            <span class="app-nav-badge app-nav-badge-tiket">Tiket</span>
                        </a>
                    </li>

                    @php
                        $isBagiPakaiActive = request()->routeIs('admin.bagi-pakai-dtsen.*');
                    @endphp
                    <!-- 3. Bagi Pakai DTSEN (SOP: 555/07-SOP/DISKOMINFO SP/2026 - Case OPD gak bisa bikin tiket) -->
                    <li class="app-nav-dropdown {{ $isBagiPakaiActive ? 'active open' : '' }}">
                        <button type="button" class="app-nav-dropdown-toggle" onclick="toggleNavDropdown(this)" title="Layanan Bagi Pakai DTSEN (SOP: 555/07-SOP/DISKOMINFO SP/2026)">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-share app-nav-icon"></i>
                                <span>Bagi Pakai DTSEN</span>
                            </div>
                            <i class="fi fi-rr-angle-small-right app-nav-caret"></i>
                        </button>
                        <ul class="app-nav-submenu">
                            <li>
                                <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="app-nav-subitem {{ request()->routeIs('admin.bagi-pakai-dtsen.index') ? 'active' : '' }}">
                                    <i class="fi fi-rr-list-check"></i>
                                    <span>Daftar Permintaan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.bagi-pakai-dtsen.create') }}" class="app-nav-subitem {{ request()->routeIs('admin.bagi-pakai-dtsen.create') ? 'active' : '' }}">
                                    <i class="fi fi-rr-plus"></i>
                                    <span>Buat Permintaan Baru</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @php
                        $isUserActive = request()->routeIs('admin.users.*') || request()->routeIs('admin.logs.*') || request()->routeIs('admin.profile');
                    @endphp
                    <!-- 3. User (Dropdown Flyout WordPress Style) -->
                    <li class="app-nav-dropdown {{ $isUserActive ? 'active open' : '' }}">
                        <button type="button" class="app-nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-users app-nav-icon"></i>
                                <span>User</span>
                            </div>
                            <i class="fi fi-rr-angle-small-right app-nav-caret"></i>
                        </button>
                        <ul class="app-nav-submenu">
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="app-nav-subitem {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.show') ? 'active' : '' }}">
                                    <i class="fi fi-rr-users-alt"></i>
                                    <span>User Management</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.create') }}" class="app-nav-subitem {{ request()->routeIs('admin.users.create') || request()->routeIs('admin.users.edit-access') ? 'active' : '' }}">
                                    <i class="fi fi-rr-user-add"></i>
                                    <span>Buat Akun</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.logs.index') }}" class="app-nav-subitem {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                                    <i class="fi fi-rr-laptop"></i>
                                    <span>Log</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.profile') }}" class="app-nav-subitem {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                                    <i class="fi fi-rr-user"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @php
                        $isEksporActive = request()->routeIs('admin.riwayat.export*');
                    @endphp
                    <!-- 4. Ekspor (Dropdown Flyout WordPress Style) -->
                    <li class="app-nav-dropdown {{ $isEksporActive ? 'active open' : '' }}">
                        <button type="button" class="app-nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-download app-nav-icon"></i>
                                <span>Ekspor</span>
                            </div>
                            <i class="fi fi-rr-angle-small-right app-nav-caret"></i>
                        </button>
                        <ul class="app-nav-submenu">
                            <li>
                                <a href="{{ route('admin.riwayat.export') }}" class="app-nav-subitem {{ request()->routeIs('admin.riwayat.export') ? 'active' : '' }}">
                                    <i class="fi fi-rr-file-chart-line"></i>
                                    <span>Ekspor Tiket</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.riwayat.export-logs', ['format' => 'csv']) }}" class="app-nav-subitem {{ request()->routeIs('admin.riwayat.export-logs') ? 'active' : '' }}">
                                    <i class="fi fi-rr-document"></i>
                                    <span>Ekspor Log</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </aside>

            <!-- Main Content Area Admin (Scrollable) -->
            <main class="app-main-content">
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="form-alert-success flash-margin-bottom">
                        <i class="fi fi-rr-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="form-alert-error flash-margin-bottom">
                        <i class="fi fi-rr-cross-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    <script>
        function toggleNavDropdown(btn) {
            const parent = btn.closest('.app-nav-dropdown');
            if (parent) {
                parent.classList.toggle('open');
            }
        }
    </script>
    @yield('scripts')
    @include('partials.idle_session_timer')
</body>
</html>
