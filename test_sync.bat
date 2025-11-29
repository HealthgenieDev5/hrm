@echo off
echo ========================================
echo  Attendance Data Sync - Manual Test
echo ========================================
echo.

echo [1] Checking if Laravel API is running...
curl -s http://localhost:8001/api/v1/health
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Laravel API is not running!
    echo Please start it with: cd D:\LOCALHOST\hrm-attendance-api ^&^& php artisan serve --port=8001
    pause
    exit /b 1
)

echo.
echo [2] API is running. Starting sync...
echo.

php sync_attendance_data.php

echo.
echo ========================================
echo  Sync Test Complete
echo ========================================
pause
