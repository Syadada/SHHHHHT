<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login WALIDATA</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Unified Master Stylesheet (Single CSS) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="form-page page-bg-slate">

    <div class="form-shell login-shell-admin">
        
        <!-- Standard White Form Card with Dark Slate Accents -->
        <div class="form-card login-card-admin">

            <div class="form-header form-group-mb-24">
                <h1 class="form-title login-title-admin">LOGIN WALIDATA</h1>
            </div>

           

            @if(isset($errors) && $errors->any())
                <div class="form-alert-error form-group-mb-16">
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.process') }}" method="POST">
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
                           placeholder="Masukkan akun walidata..." 
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

                <button type="submit" class="form-btn-submit login-btn-admin">
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
            let devId = localStorage.getItem('admin_device_id');
            if (!devId) {
                const nav = navigator.userAgent;
                let pref = 'WIN';
                if (nav.indexOf('Mac') !== -1) pref = 'MAC';
                else if (nav.indexOf('Linux') !== -1) pref = 'LNX';
                devId = pref + '-ADM-' + Math.floor(1000 + Math.random() * 9000);
                localStorage.setItem('admin_device_id', devId);
            }
            document.cookie = "app_device_id=" + devId + "; path=/; max-age=31536000";
            const el = document.getElementById('detectedLaptopId');
            if (el) el.textContent = devId;
        })();
    </script>
</body>
</html>
