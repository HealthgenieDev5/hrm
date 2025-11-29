# Attendance Data Sync Setup Instructions

## Overview

This setup allows you to sync attendance data from `hrm-attendance-api` to your local HRM database every 5 minutes.

**Flow:**
```
eTime Office APIs → hrm-attendance-api (Laravel) → Sync Script → HRM Database → HRM Portal (CodeIgniter)
```

## Prerequisites

1. ✅ `hrm-attendance-api` is installed at `D:\LOCALHOST\hrm-attendance-api`
2. ✅ Laravel API is syncing data from eTime Office
3. ✅ Sync script created at `D:\LOCALHOST\hrm.healthgenie\sync_attendance_data.php`

## Step 1: Start the Laravel API

Navigate to the API directory and start the server:

```bash
cd D:\LOCALHOST\hrm-attendance-api
php artisan serve --port=8001
```

**Keep this running in a separate terminal/command prompt.**

## Step 2: Test the Sync Script

Run the sync script manually to verify it works:

```bash
cd D:\LOCALHOST\hrm.healthgenie
php sync_attendance_data.php
```

**Expected Output:**
```
[2025-11-15 10:30:00] Starting attendance data sync...
[2025-11-15 10:30:00] Fetching data from API: http://localhost:8001/api/v1/attendance/raw?from_date=2025-10-16&to_date=2025-11-15
[2025-11-15 10:30:01] Fetched 4942 records from API
[2025-11-15 10:30:05] Sync completed:
  - Inserted: 4942 records
  - Updated: 0 records
  - Errors: 0 records
  - Total processed: 4942 records
```

## Step 3: Verify Data in Database

Check if data was synced correctly:

```bash
mysql -u root -pmysql -D "hrm.healthgenie.in_bkp_2025_11_05" -e "SELECT COUNT(*) as total, MIN(DateString_2) as from_date, MAX(DateString_2) as to_date FROM raw_attendance"
```

**Sample records:**
```bash
mysql -u root -pmysql -D "hrm.healthgenie.in_bkp_2025_11_05" -e "SELECT Empcode, INTime, OUTTime, DateString_2, machine FROM raw_attendance LIMIT 5"
```

## Step 4: Setup CRON Job (Windows Task Scheduler)

### Option A: Using Task Scheduler GUI

1. Open **Task Scheduler** (Search in Windows Start Menu)
2. Click **Create Basic Task**
3. Configure:
   - **Name:** Attendance Data Sync
   - **Description:** Syncs attendance data from API every 5 minutes
   - **Trigger:** Daily, repeat every 5 minutes for duration of 1 day
   - **Action:** Start a program
     - **Program:** `C:\php\php.exe` (or your PHP path)
     - **Arguments:** `D:\LOCALHOST\hrm.healthgenie\sync_attendance_data.php`
     - **Start in:** `D:\LOCALHOST\hrm.healthgenie`

4. After creating, edit the task:
   - Go to **Triggers** tab → Edit
   - Check **Repeat task every:** `5 minutes`
   - **For a duration of:** `Indefinitely`
   - Click OK

### Option B: Using Command Line

Create a batch file to add the scheduled task:

**File: `setup_cron.bat`**
```batch
@echo off
schtasks /create /tn "AttendanceDataSync" /tr "C:\php\php.exe D:\LOCALHOST\hrm.healthgenie\sync_attendance_data.php" /sc minute /mo 5 /f
echo Task created successfully!
pause
```

Run this batch file as Administrator.

## Step 5: Monitor Sync Logs

Create a logging version of the script to track sync activity:

Add this to the end of `sync_attendance_data.php` (optional):

```php
// Log to file
$logFile = __DIR__ . '/writable/logs/sync_attendance_' . date('Y-m-d') . '.log';
file_put_contents($logFile, ob_get_contents(), FILE_APPEND);
```

Then check logs daily:
```bash
tail -f D:\LOCALHOST\hrm.healthgenie\writable\logs\sync_attendance_*.log
```

## Verification Checklist

- [ ] Laravel API is running on http://localhost:8001
- [ ] API health check returns: `{"status":"healthy"}`
- [ ] Sync script runs without errors
- [ ] Data appears in `raw_attendance` table
- [ ] NULL values converted to `--:--`
- [ ] CRON job scheduled in Task Scheduler
- [ ] HRM Portal displays attendance data correctly

## Troubleshooting

### Issue: "API request failed with HTTP code: 0"

**Solution:** Start the Laravel API server
```bash
cd D:\LOCALHOST\hrm-attendance-api
php artisan serve --port=8001
```

### Issue: "Database connection failed"

**Solution:** Check database credentials in script:
- Database: `hrm.healthgenie.in_bkp_2025_11_05`
- Username: `root`
- Password: `mysql`

### Issue: Duplicate records

**Solution:** The script uses `UPDATE` if record exists (based on `Empcode` + `DateString_2`), so duplicates shouldn't occur.

### Issue: Task Scheduler not running script

**Solution:**
1. Check if task is enabled in Task Scheduler
2. Verify PHP path is correct: `where php` in CMD
3. Check task history in Task Scheduler
4. Run script manually to verify it works

## Configuration

### Change Sync Frequency

Edit the scheduled task trigger:
- **Every 5 minutes** (default)
- **Every 10 minutes** (less frequent)
- **Every 1 minute** (real-time, not recommended)

### Change Date Range

Edit `sync_attendance_data.php`:

```php
// Current: Last 30 days
$fromDate = date('Y-m-d', strtotime('-30 days'));

// Change to: Last 7 days
$fromDate = date('Y-m-d', strtotime('-7 days'));

// Or: Specific date
$fromDate = '2025-11-01';
```

## Production Deployment

For production, ensure:

1. **API runs as a service** (not `php artisan serve`)
   - Use Apache/Nginx to serve the Laravel API
   - Configure virtual host on port 8001

2. **Error logging enabled**
   - Script errors go to `writable/logs/`
   - Monitor logs daily

3. **Backup before first sync**
   ```bash
   mysqldump -u root -pmysql "hrm.healthgenie.in_bkp_2025_11_05" raw_attendance > raw_attendance_backup_$(date +%Y%m%d).sql
   ```

4. **Test in staging first**
   - Run manual sync
   - Verify data integrity
   - Check HRM portal attendance reports

## Summary

✅ **Setup complete!** Your HRM system now fetches attendance data from the Laravel API every 5 minutes.

**No code changes needed in HRM Portal** - it continues to use `get_punching_data()` function which reads from the local `raw_attendance` table that gets updated by this sync script.
