<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $targetUrl = route('login');
    @endphp
    <meta http-equiv="refresh" content="0;url={{ $targetUrl }}">
    <title>Sesi Berakhir — Mengalihkan...</title>
    <script>
        window.location.replace("{{ $targetUrl }}");
    </script>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background: #f8fafc;">
    <div style="text-align: center; padding: 24px;">
        <div style="font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Sesi Anda telah berakhir.</div>
        <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">Mengalihkan ke menu login...</p>
        <a href="{{ $targetUrl }}" style="display: inline-block; padding: 8px 18px; background: #0b57d0; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 500;">
            Masuk Kembali
        </a>
    </div>
</body>
</html>

