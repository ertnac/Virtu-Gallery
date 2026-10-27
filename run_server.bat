@echo OFF
echo ========================================================
echo Starting VirtuGallery Web Application on http://localhost:8000
echo ========================================================
set PHP_BIN=C:\Users\carlc\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe
if not exist "%PHP_BIN%" (
    set PHP_BIN=php
)
"%PHP_BIN%" -S 127.0.0.1:8000 -c "%~dp0php.ini" -t "%~dp0"
pause
