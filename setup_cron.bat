@echo off
echo ========================================
echo  Setup Attendance Sync CRON Job
echo ========================================
echo.

REM Get PHP path
for /f "tokens=*" %%i in ('where php') do set PHP_PATH=%%i

if "%PHP_PATH%"=="" (
    echo [ERROR] PHP not found in PATH!
    echo Please add PHP to your system PATH or edit this script to specify the full path.
    pause
    exit /b 1
)

echo Found PHP at: %PHP_PATH%
echo.

echo Creating scheduled task to run every 5 minutes...
echo.

REM Delete existing task if it exists
schtasks /delete /tn "AttendanceDataSync" /f >nul 2>&1

REM Create new task
schtasks /create /tn "AttendanceDataSync" /tr "\"%PHP_PATH%\" \"D:\LOCALHOST\hrm.healthgenie\sync_attendance_data.php\"" /sc minute /mo 5 /f

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo  SUCCESS!
    echo ========================================
    echo.
    echo Scheduled task created successfully!
    echo.
    echo Task Name: AttendanceDataSync
    echo Frequency: Every 5 minutes
    echo Script: D:\LOCALHOST\hrm.healthgenie\sync_attendance_data.php
    echo.
    echo You can view/manage this task in Windows Task Scheduler.
    echo.
) else (
    echo.
    echo [ERROR] Failed to create scheduled task!
    echo Please run this script as Administrator.
    echo.
)

pause
