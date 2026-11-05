@auth
@php
    $idleLifetimeMinutes = (int) \App\Models\AppSetting::get('session_lifetime', config('session.lifetime', 5));
@endphp
<!-- Pengatur Waktu Sesi Aktif Otomatis (Auto Logout ke Menu Login jika Idle) -->
<script>
(function() {
    var maxIdleMs = {{ $idleLifetimeMinutes * 60 }} * 1000;
    var lastActivityTime = Date.now();
    var logoutUrl = "{{ url('/logout') }}";
    var isLoggingOut = false;

    function resetIdleTimer() {
        lastActivityTime = Date.now();
    }

    function checkIdleTimeout() {
        if (!isLoggingOut && (Date.now() - lastActivityTime >= maxIdleMs)) {
            isLoggingOut = true;
            window.location.replace(logoutUrl);
        }
    }

    // Pantau setiap aktivitas interaksi pengguna
    var events = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'];
    events.forEach(function(evt) {
        window.addEventListener(evt, resetIdleTimer, { passive: true });
    });

    // Periksa saat tab kembali aktif (mencegah timer beku di background tab browser)
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            checkIdleTimeout();
        }
    });

    // Pengecekan berkala setiap 2 detik
    setInterval(checkIdleTimeout, 2000);
})();
</script>
@endauth
