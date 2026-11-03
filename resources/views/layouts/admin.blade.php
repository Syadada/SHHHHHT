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

            <!-- Right Controls (Subtle system badge; Controls moved to sidebar) -->
            <div class="app-header-right">
                <span class="dtsen-chip" style="font-family: var(--font-mono); font-size: 11px; background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;">
                    <i class="fi fi-rr-shield-check" style="color: var(--blue-primary);"></i> Portal Walidata SPBE
                </span>
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

                    @php
                        $isLayananActive = request()->routeIs('admin.bagi-pakai-dtsen.*');
                    @endphp
                    <!-- 2. Layanan (Row D & 1: SOP 555/07-SOP/DISKOMINFO SP/2026 - Bagi Pakai DTSEN) -->
                    <li class="app-nav-dropdown {{ $isLayananActive ? 'active' : '' }}">
                        <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="app-nav-dropdown-toggle" title="Layanan Bagi Pakai DTSEN (SOP: 555/07-SOP/DISKOMINFO SP/2026)">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-layers app-nav-icon"></i>
                                <span>Layanan</span>
                            </div>
                            <i class="fi fi-rr-angle-small-right app-nav-caret"></i>
                        </a>
                        <ul class="app-nav-submenu">
                            <li>
                                <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="app-nav-subitem {{ request()->routeIs('admin.bagi-pakai-dtsen.index') ? 'active' : '' }}">
                                    <i class="fi fi-rr-share"></i>
                                    <span>Bagi Pakai DTSEN</span>
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
                        $pendingResetCount = \Illuminate\Support\Facades\Schema::hasTable('password_reset_requests') ? \App\Models\PasswordResetRequest::where('status', 'MENUNGGU')->count() : 0;
                    @endphp
                    <!-- 4. User (Dropdown Flyout WordPress Style) -->
                    <li class="app-nav-dropdown {{ $isUserActive ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="app-nav-dropdown-toggle">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-users app-nav-icon"></i>
                                <span>User</span>
                                @if($pendingResetCount > 0 && auth()->user()->isSuperAdminOnly())
                                    <span style="background: #f59e0b; color: #ffffff; font-size: 10px; font-weight: 800; border-radius: 9999px; padding: 1px 6px; margin-left: 6px;">{{ $pendingResetCount }}</span>
                                @endif
                            </div>
                            <i class="fi fi-rr-angle-small-right app-nav-caret"></i>
                        </a>
                        <ul class="app-nav-submenu">
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="app-nav-subitem {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.show') ? 'active' : '' }}">
                                    <i class="fi fi-rr-users-alt"></i>
                                    <span>User Management</span>
                                    @if($pendingResetCount > 0 && auth()->user()->isSuperAdminOnly())
                                        <span style="background: #f59e0b; color: #ffffff; font-size: 9.5px; font-weight: 800; border-radius: 9999px; padding: 1px 5px; margin-left: auto;">{{ $pendingResetCount }}</span>
                                    @endif
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
                    <!-- 5. Ekspor (Dropdown Flyout WordPress Style) -->
                    <li class="app-nav-dropdown {{ $isEksporActive ? 'active' : '' }}">
                        <a href="{{ route('admin.riwayat.export') }}" class="app-nav-dropdown-toggle">
                            <div class="app-nav-item-left">
                                <i class="fi fi-rr-download app-nav-icon"></i>
                                <span>Ekspor</span>
                            </div>
                            <i class="fi fi-rr-angle-small-right app-nav-caret"></i>
                        </a>
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

                <!-- Sidebar Footer (User Profile & Logout) -->
                <div class="app-sidebar-footer">
                    <!-- Preview Portal OPD (Image 6) -->
                    <a href="{{ route('opd.dashboard') }}" class="sidebar-preview-btn" target="_blank" title="Lihat Tampilan Portal OPD">
                        <i class="fi fi-rr-browser"></i>
                        <span>Preview Portal OPD</span>
                        <i class="fi fi-rr-arrow-up-right sidebar-preview-ext"></i>
                    </a>

                    <!-- User Profile Box & Logout -->
                    <div class="sidebar-user-box">
                        <div class="sidebar-user-header">
                            <div class="app-user-avatar">
                                {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="sidebar-user-meta">
                                <span class="sidebar-user-name" title="{{ Auth::user()->name ?? 'Super Administrator' }}">
                                    {{ Auth::user()->name ?? 'Super Administrator' }}
                                </span>
                                <span class="sidebar-user-role">
                                    {{ Auth::user()->role_title ?? 'Super Administrator' }}
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="sidebar-logout-form">
                            @csrf
                            <button type="submit" class="sidebar-logout-btn" title="Keluar dari akun">
                                <i class="fi fi-rr-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.app-nav-dropdown').forEach(function(item) {
                item.classList.remove('open');
                item.addEventListener('mouseleave', function() {
                    item.classList.remove('open');
                    const sub = item.querySelector('.app-nav-submenu');
                    if (sub) {
                        sub.style.display = 'none';
                        setTimeout(() => { sub.style.display = ''; }, 30);
                    }
                });
            });
        });
    </script>
    @yield('scripts')
    @include('partials.idle_session_timer')
</body>
</html>
