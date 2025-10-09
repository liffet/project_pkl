@echo off
cd C:\laragon\www\infrastruktur_jaringan
REM Jalankan scheduler dan simpan hasilnya ke log
"C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan schedule:run >> scheduler_log.txt 2>&1
echo.
echo === Manual maintenance check === >> scheduler_log.txt
"C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan maintenance:check >> scheduler_log.txt 2>&1
