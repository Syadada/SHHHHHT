@echo off
setlocal enabledelayedexpansion

REM Memastikan system PATH standar Windows tersedia
set "PATH=%SystemRoot%\system32;%SystemRoot%;%SystemRoot%\System32\Wbem;%SystemRoot%\System32\WindowsPowerShell\v1.0\;%PATH%"

title BAST DTSEN - Server Launcher and Auto Installer
color 0A

echo =======================================================================
echo          LAYANAN BAGI PAKAI DTSEN DAN PENGESAHAN BAST 2026
echo             ALL-IN-ONE AUTOMATIC SETUP DAN SERVER LAUNCHER
echo =======================================================================
echo.

set "PROJECT_DIR=%~dp0"
cd /d "%PROJECT_DIR%"

set PHP_EXE=
set COMPOSER_PHAR=
set PYTHON_EXE=

rem =======================================================================
rem 1. OTOMASI DETEKSI DAN INSTALASI PYTHON BACKEND RUNTIME
rem =======================================================================
echo [1/8] Memeriksa Runtime Python Backend...

if exist "%PROJECT_DIR%python\python.exe" (
    set "PYTHON_EXE=%PROJECT_DIR%python\python.exe"
    echo [OK] Menggunakan Local Portable Python: %PROJECT_DIR%python\python.exe
    goto :check_python_pip
)

where python >nul 2>&1
if %errorlevel% equ 0 (
    set "PYTHON_EXE=python"
    echo [OK] Menggunakan Python dari System PATH.
    goto :check_python_pip
)

where py >nul 2>&1
if %errorlevel% equ 0 (
    set "PYTHON_EXE=py"
    echo [OK] Menggunakan Python Launcher.
    goto :check_python_pip
)

echo.
echo [PERINGATAN] Python belum terpasang di komputer ini!
echo [SETUP] Mengunduh dan memasang Python 3.11 otomatis via Windows Winget...
winget install Python.Python.3.11 --accept-package-agreements --accept-source-agreements --silent
set "PATH=%LOCALAPPDATA%\Microsoft\WindowsApps;%PATH%"
where python >nul 2>&1
if %errorlevel% equ 0 (
    set "PYTHON_EXE=python"
    echo [OK] Instalasi Python berhasil!
) else (
    echo [WARNING] Python tidak ditemukan. Backend akan menggunakan kalkulator internal.
)

:check_python_pip
if not defined PYTHON_EXE goto :check_php_runtime

"%PYTHON_EXE%" -m pip --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [INFO] Menyiapkan modul pip untuk Python...
    "%PYTHON_EXE%" -m ensurepip --default-pip >nul 2>&1
)

if exist "%PROJECT_DIR%requirements.txt" (
    echo [SETUP] Memeriksa dan mengunduh paket dependensi Python requirements.txt...
    "%PYTHON_EXE%" -m pip install -r "%PROJECT_DIR%requirements.txt" --no-warn-script-location --quiet
    echo [OK] Seluruh pustaka Python Backend siap digunakan.
)

rem =======================================================================
rem 2. OTOMASI DETEKSI DAN INSTALASI PHP RUNTIME (FRONTEND LARAVEL)
rem =======================================================================
:check_php_runtime
echo.
echo [2/8] Memeriksa Runtime PHP (Frontend Presentation Layer)...

rem Refresh PATH dari Registry & WindowsApps jika ada instalasi baru (misal via winget)
set "PATH=%LOCALAPPDATA%\Microsoft\WindowsApps;%ProgramFiles%\PHP;%ProgramFiles%\PHP\v8.2;%PATH%"
for /f "tokens=2*" %%a in ('reg query "HKLM\System\CurrentControlSet\Control\Session Manager\Environment" /v Path 2^>nul') do set "SYS_PATH=%%b"
for /f "tokens=2*" %%a in ('reg query "HKCU\Environment" /v Path 2^>nul') do set "USER_PATH=%%b"
if defined SYS_PATH if defined USER_PATH set "PATH=%LOCALAPPDATA%\Microsoft\WindowsApps;!USER_PATH!;!SYS_PATH!;!PATH!"

if exist "%PROJECT_DIR%php\php.exe" (
    set "PHP_EXE=%PROJECT_DIR%php\php.exe"
    set "COMPOSER_PHAR=%PROJECT_DIR%php\composer.phar"
    set "PATH=%PROJECT_DIR%php;!PATH!"
    echo [OK] Menggunakan Local Portable PHP: %PROJECT_DIR%php\php.exe
    goto :configure_php_ini
)

rem Deteksi PHP di Laragon (C:\ atau D:\)
for /d %%d in ("C:\laragon\bin\php\php*" "D:\laragon\bin\php\php*") do (
    if not defined PHP_EXE (
        if exist "%%~d\php.exe" (
            set "PHP_EXE=%%~d\php.exe"
            set "PATH=%%~d;!PATH!"
            echo [OK] Menggunakan PHP dari Laragon: %%~d\php.exe
            goto :configure_php_ini
        )
    )
)

rem Deteksi PHP di XAMPP (C:\ atau D:\)
for %%d in ("C:\xampp\php" "D:\xampp\php") do (
    if not defined PHP_EXE (
        if exist "%%~d\php.exe" (
            set "PHP_EXE=%%~d\php.exe"
            set "COMPOSER_PHAR=%%~d\composer.phar"
            set "PATH=%%~d;!PATH!"
            echo [OK] Menggunakan PHP dari XAMPP: %%~d\php.exe
            goto :configure_php_ini
        )
    )
)

where php >nul 2>&1
if %errorlevel% equ 0 (
    set "PHP_EXE=php"
    echo [OK] Menggunakan PHP dari System PATH.
    goto :configure_php_ini
)

echo.
echo [PERINGATAN] PHP belum terpasang di komputer ini!
echo [SETUP] Mengunduh dan memasang PHP 8.2 otomatis via Windows Winget...
winget install PHP.PHP.8.2 --accept-package-agreements --accept-source-agreements --silent

rem Refresh PATH setelah winget install selesai
set "PATH=%LOCALAPPDATA%\Microsoft\WindowsApps;%ProgramFiles%\PHP;%ProgramFiles%\PHP\v8.2;%PATH%"
for /f "tokens=2*" %%a in ('reg query "HKLM\System\CurrentControlSet\Control\Session Manager\Environment" /v Path 2^>nul') do set "SYS_PATH=%%b"
for /f "tokens=2*" %%a in ('reg query "HKCU\Environment" /v Path 2^>nul') do set "USER_PATH=%%b"
if defined SYS_PATH if defined USER_PATH set "PATH=%LOCALAPPDATA%\Microsoft\WindowsApps;!USER_PATH!;!SYS_PATH!;!PATH!"

where php >nul 2>&1
if %errorlevel% equ 0 (
    set "PHP_EXE=php"
    echo [OK] Instalasi PHP berhasil!
    goto :configure_php_ini
) else (
    echo [ERROR] PHP gagal dipasang otomatis. Sediakan folder 'php' portable atau pasang Laragon/XAMPP.
    pause
    exit /b 1
)

:configure_php_ini
echo [INFO] Memverifikasi ekstensi php.ini (SQLite, cURL, Fileinfo, variables_order)...
powershell -NoProfile -Command "$cmd = '%PHP_EXE%'; try { $p = & $cmd --ini 2>$null; $ini = ($p | Select-String 'Loaded Configuration File:\s*(.+)').Matches.Groups[1].Value.Trim(); if (-not $ini -or $ini -eq '(none)') { $base = Split-Path (Get-Command $cmd -ErrorAction SilentlyContinue).Source; if (-not $base -and (Test-Path $cmd)) { $base = Split-Path $cmd }; if ($base) { $sample = Get-ChildItem -Path $base -Filter 'php.ini-*' | Select-Object -First 1 -ExpandProperty FullName; if ($sample) { Copy-Item $sample (Join-Path $base 'php.ini'); $ini = Join-Path $base 'php.ini'; } } }; if ($ini -and (Test-Path $ini)) { $c = [System.IO.File]::ReadAllText($ini); $exts = @('pdo_sqlite','sqlite3','fileinfo','curl','gd','zip','intl','mbstring','openssl'); foreach($e in $exts){ $c = $c -replace ';extension='+$e, ('extension='+$e) }; $c = $c -replace ';extension_dir = \"ext\"', 'extension_dir = \"ext\"'; $c = $c -replace 'variables_order = \"GPCS\"', 'variables_order = \"EGPCS\"'; [System.IO.File]::WriteAllText($ini, $c) } } catch {}" >nul 2>&1

:php_ready

rem =======================================================================
rem 3. OTOMASI COMPOSER DAN DEPENDENSI VENDOR LARAVEL
rem =======================================================================
if exist "%PROJECT_DIR%vendor\autoload.php" (
    echo [OK] Folder vendor dependensi sudah terpasang lengkap.
    goto :vendor_ready
)

echo.
echo [3/8] Folder vendor belum ada. Mengunduh seluruh dependensi Composer otomatis...

if exist "%PROJECT_DIR%php\composer.phar" (
    echo [INFO] Menjalankan Composer phar folder php...
    "%PHP_EXE%" "%PROJECT_DIR%php\composer.phar" install --no-interaction --prefer-dist --ignore-platform-reqs
    goto :check_vendor_result
)

if defined COMPOSER_PHAR if exist "%COMPOSER_PHAR%" (
    echo [INFO] Menjalankan Composer phar terdeteksi...
    "%PHP_EXE%" "%COMPOSER_PHAR%" install --no-interaction --prefer-dist --ignore-platform-reqs
    goto :check_vendor_result
)

if exist "%PROJECT_DIR%composer.phar" (
    echo [INFO] Menjalankan composer.phar direktori root...
    "%PHP_EXE%" "%PROJECT_DIR%composer.phar" install --no-interaction --prefer-dist --ignore-platform-reqs
    goto :check_vendor_result
)

echo [INFO] Mengunduh composer.phar resmi dari getcomposer.org...
powershell -NoProfile -Command "[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; (New-Object System.Net.WebClient).DownloadFile('https://getcomposer.org/composer-stable.phar', '%PROJECT_DIR%php\composer.phar')" >nul 2>&1

if exist "%PROJECT_DIR%php\composer.phar" (
    "%PHP_EXE%" "%PROJECT_DIR%php\composer.phar" install --no-interaction --prefer-dist --ignore-platform-reqs
    goto :check_vendor_result
)

powershell -NoProfile -Command "[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; (New-Object System.Net.WebClient).DownloadFile('https://getcomposer.org/composer-stable.phar', '%PROJECT_DIR%composer.phar')" >nul 2>&1

if exist "%PROJECT_DIR%composer.phar" (
    "%PHP_EXE%" "%PROJECT_DIR%composer.phar" install --no-interaction --prefer-dist --ignore-platform-reqs
    goto :check_vendor_result
)

where composer >nul 2>&1
if %errorlevel% equ 0 (
    echo [INFO] Menjalankan Composer dari System PATH...
    composer install --no-interaction --prefer-dist --ignore-platform-reqs
)

:check_vendor_result
if exist "%PROJECT_DIR%vendor\autoload.php" (
    echo [OK] Seluruh dependensi Composer vendor berhasil terpasang!
) else (
    echo [ERROR] Gagal memasang dependensi vendor! Pastikan koneksi internet aktif dan ekstensi openssl aktif di php.ini.
    pause
    exit /b 1
)

:vendor_ready

rem =======================================================================
rem 4. OTOMASI ENVIRONMENT DAN APPLICATION KEY (.env)
rem =======================================================================
echo.
echo [4/8] Memeriksa konfigurasi environment (.env)...
if not exist "%PROJECT_DIR%.env" (
    echo [INFO] Membuat file .env dari template...
    copy "%PROJECT_DIR%.env.example" "%PROJECT_DIR%.env" >nul
    "%PHP_EXE%" artisan key:generate --force >nul 2>&1
)

findstr /C:"APP_KEY=base64:" "%PROJECT_DIR%.env" >nul 2>&1
if %errorlevel% neq 0 (
    echo [INFO] Menghasilkan APP_KEY baru...
    "%PHP_EXE%" artisan key:generate --force >nul 2>&1
)
echo [OK] Konfigurasi environment siap.

rem =======================================================================
rem 5. MEMASTIKAN DIREKTORI STORAGE DAN STORAGE LINK
rem =======================================================================
if not exist "%PROJECT_DIR%storage\logs" mkdir "%PROJECT_DIR%storage\logs"
if not exist "%PROJECT_DIR%storage\framework\views" mkdir "%PROJECT_DIR%storage\framework\views"
if not exist "%PROJECT_DIR%storage\framework\sessions" mkdir "%PROJECT_DIR%storage\framework\sessions"
if not exist "%PROJECT_DIR%storage\framework\cache\data" mkdir "%PROJECT_DIR%storage\framework\cache\data"
if not exist "%PROJECT_DIR%storage\app\public" mkdir "%PROJECT_DIR%storage\app\public"
if not exist "%PROJECT_DIR%bootstrap\cache" mkdir "%PROJECT_DIR%bootstrap\cache"
if not exist "%PROJECT_DIR%public\storage" (
    "%PHP_EXE%" artisan storage:link >nul 2>&1
)

rem =======================================================================
rem 6. BASIS DATA SQLITE (WAL MODE) DAN MIGRATIONS SERTA SEEDER
rem =======================================================================
echo.
echo [5/8] Menyiapkan database SQLite dan migrasi tabel...
if not exist "%PROJECT_DIR%database\database.sqlite" (
    type nul > "%PROJECT_DIR%database\database.sqlite"
    echo [OK] File database SQLite baru dibuat.
)
"%PHP_EXE%" artisan migrate --force >nul 2>&1
"%PHP_EXE%" artisan db:seed --force >nul 2>&1
echo [OK] Struktur tabel database dan akun bawaan siap.

rem =======================================================================
rem 7. OPTIMIZE DAN CLEAR CACHE
rem =======================================================================
"%PHP_EXE%" artisan optimize:clear >nul 2>&1

rem =======================================================================
rem 8. MENJALANKAN SERVER PYTHON BACKEND DAN LARAVEL FRONTEND
rem =======================================================================
echo.
rem =======================================================================
rem Deteksi Otomatis Port Bebas (Fallback Port jika 8080/8000 Sedang Dipakai)
rem =======================================================================
set "PYTHON_PORT=8000"
for %%q in (8000 8001 8002 8003 8004 8005) do (
    netstat -ano 2>nul | findstr /C:":%%q " | findstr "LISTENING" >nul 2>&1
    if errorlevel 1 (
        set "PYTHON_PORT=%%q"
        goto :py_port_found
    )
)
:py_port_found

set "LARAVEL_PORT=8080"
for %%p in (8080 8081 8082 8083 8084 8085 8086 8087 8088 8089 8888 8008) do (
    netstat -ano 2>nul | findstr /C:":%%p " | findstr "LISTENING" >nul 2>&1
    if errorlevel 1 (
        set "LARAVEL_PORT=%%p"
        goto :laravel_port_found
    )
)
:laravel_port_found

echo [6/8] Menjalankan Python FastAPI Backend Engine pada port %PYTHON_PORT%...
if defined PYTHON_EXE (
    start "Python Backend Engine" /b "%PYTHON_EXE%" -m uvicorn python_backend.server:app --host 127.0.0.1 --port %PYTHON_PORT% >nul 2>&1
    echo [OK] Python Backend aktif di http://127.0.0.1:%PYTHON_PORT%/
)

rem Deteksi IP LAN Server untuk akses laptop lain
set "SERVER_IP="
for /f "delims=" %%i in ('powershell -Command "(Get-CimInstance Win32_NetworkAdapterConfiguration | Where-Object IPEnabled | Select-Object -ExpandProperty IPAddress)[0]" 2^>nul') do set "SERVER_IP=%%i"

echo.
echo =======================================================================
echo [SISTEM SUKSES AKTIF]
echo  - Backend Engine : Python FastAPI (Port %PYTHON_PORT%)
echo  - Frontend Client: Laravel 12 Blade (Port %LARAVEL_PORT%)
echo  - Database       : SQLite WAL Mode (database/database.sqlite)
echo.
echo [AKSES DARI LAPTOP INI]
echo  - Portal OPD     : http://127.0.0.1:%LARAVEL_PORT%/login
echo.
if defined SERVER_IP (
echo [AKSES DARI LAPTOP LAIN DALAM JARINGAN WIFI ATAU LAN]
echo  - Buka di Browser : http://%SERVER_IP%:%LARAVEL_PORT%/
echo  - Portal OPD      : http://%SERVER_IP%:%LARAVEL_PORT%/login
echo.
)
echo [KREDENSIAL AKUN OPD]
echo  - OPD Dinsos     : dinsos / dinsos123
echo  - OPD Bappeda    : bappeda / bappeda123
echo =======================================================================
echo.

echo [7/8] Membuka peramban (browser) otomatis ke Portal OPD...
if defined PYTHON_EXE (
    start "" /b "%PYTHON_EXE%" -c "import time, webbrowser; time.sleep(2); webbrowser.open('http://127.0.0.1:%LARAVEL_PORT%/login')" >nul 2>&1
) else (
    start "" http://127.0.0.1:%LARAVEL_PORT%/login
)

echo [8/8] SERVER SEDANG AKTIF DI PORT %LARAVEL_PORT% (0.0.0.0)...
echo.
echo =======================================================================
echo  [PENTING] JANGAN TUTUP JENDELA CMD INI SELAMA SISTEM DIGUNAKAN!
echo  Jendela CMD ini adalah mesin web server lokal. Cukup MINIMIZE jendela
echo  ini ke taskbar. Jika jendela ini ditutup (X), maka website akan mati.
echo  Tekan CTRL+C jika memang ingin menghentikan server secara manual.
echo =======================================================================
echo.
"%PHP_EXE%" -d variables_order=EGPCS artisan serve --host=0.0.0.0 --port=%LARAVEL_PORT% --no-reload
if %errorlevel% neq 0 (
    echo.
    echo [INFO] artisan serve gagal binding 0.0.0.0. Menjalankan fallback web server langsung [server.php]...
    "%PHP_EXE%" -d variables_order=EGPCS -S 0.0.0.0:%LARAVEL_PORT% "%PROJECT_DIR%server.php"
    if %errorlevel% neq 0 (
        echo [INFO] Menjalankan fallback ke localhost 127.0.0.1...
        "%PHP_EXE%" -d variables_order=EGPCS -S 127.0.0.1:%LARAVEL_PORT% "%PROJECT_DIR%server.php"
    )
)

echo.
echo [INFO] Server Laravel telah dihentikan.
pause
