<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - Layanan Bagi Pakai DTSEN</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Unified Master Stylesheet (Single CSS) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="form-page">

    <div class="form-shell login-shell-opd">
        
        <!-- Standard White Form Card (24px Border Radius) -->
        <div class="form-card login-card-opd">

            <div class="form-header form-group-mb-24">
                <h1 class="form-title login-title-opd">LOGIN</h1>
            </div>

          


            @if(session('success'))
                <div class="form-group-mb-16" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 10px 14px; border-radius: 8px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                    <i class="fi fi-rr-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="form-alert-error form-group-mb-16">
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('show_forgot_password'))
                <div style="margin-bottom: 20px; padding: 16px 18px; background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px;">
                    <div style="font-size: 13.5px; font-weight: 700; color: #92400e; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                        <i class="fi fi-rr-key"></i> Lupa Kata Sandi?
                    </div>
                    <div style="font-size: 12.5px; color: #78350f; line-height: 1.5; margin-bottom: 12px;">
                        Anda telah salah memasukkan kata sandi 3 kali. Klik tombol di bawah untuk meminta bantuan reset kata sandi ke Superadmin.
                    </div>
                    <!-- Tombol Kirim Permintaan ke Superadmin -->
                    <form action="{{ route('password.request-reset') }}" method="POST" style="margin: 0;">
                        @csrf
                        <input type="hidden" name="username" value="{{ session('failed_username', old('login')) }}">
                        <button type="submit" class="btn-pill-primary" style="background: #b45309; border-color: #b45309; font-size: 12.5px; padding: 8px 16px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fi fi-rr-bell"></i> Minta Reset Kata Sandi ke Superadmin
                        </button>
                    </form>
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="form-group form-group-mb-16">
                    <label for="input_login" class="form-label">
                        <span>Username atau Email</span> <span class="form-required">*</span>
                    </label>
                    <input type="text" 
                           name="login" 
                           id="input_login" 
                           class="form-control" 
                           value="{{ old('login') }}" 
                           placeholder="Contoh: dinsos atau bappeda" 
                           required 
                           autofocus
                           autocomplete="username">
                </div>

                <div class="form-group form-group-mb-24">
                    <label for="input_password" class="form-label">
                        <span>Kata Sandi</span> <span class="form-required">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           id="input_password" 
                           class="form-control" 
                           placeholder="••••••••" 
                           required
                           autocomplete="current-password">
                </div>

                <button type="submit" class="form-btn-submit login-btn-opd">
                    <span>Masuk ke Dashboard</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer Notice -->
        <footer class="form-footer">
            <p>Kebijakan Privasi</p>
        </footer>

    </div>

    <!-- Unified Master JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        (function() {
            let devId = localStorage.getItem('opd_device_id');
            if (!devId) {
                const nav = navigator.userAgent;
                let pref = 'WIN';
                if (nav.indexOf('Mac') !== -1) pref = 'MAC';
                else if (nav.indexOf('Linux') !== -1) pref = 'LNX';
                devId = pref + '-OPD-' + Math.floor(1000 + Math.random() * 9000);
                localStorage.setItem('opd_device_id', devId);
            }
            document.cookie = "app_device_id=" + devId + "; path=/; max-age=31536000";
            const el = document.getElementById('detectedLaptopId');
            if (el) el.textContent = devId;
        })();
    </script>
</body>
</html>
