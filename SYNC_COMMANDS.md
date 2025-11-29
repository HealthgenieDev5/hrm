# Attendance Sync Commands Reference

## Basic Commands

### 1. Default Sync (Last 30 Days)
```bash
php sync_attendance_data.php
```
Syncs attendance data for the last 30 days for all employees.

---

### 2. Sync Specific Date
```bash
php sync_attendance_data.php --date=2025-11-15
```
Syncs attendance data for a single date only.

**Example:**
```bash
php sync_attendance_data.php --date=2025-11-01
```

---

### 3. Sync Date Range
```bash
php sync_attendance_data.php --from=2025-11-01 --to=2025-11-15
```
Syncs attendance data between two dates (inclusive).

**Example:**
```bash
php sync_attendance_data.php --from=2025-11-01 --to=2025-11-14
```

---

### 4. Sync Last N Days
```bash
php sync_attendance_data.php --days=7
```
Syncs attendance data for the last N days.

**Examples:**
```bash
php sync_attendance_data.php --days=7    # Last 7 days
php sync_attendance_data.php --days=15   # Last 15 days
php sync_attendance_data.php --days=1    # Yesterday + today
```

---

### 5. Sync Specific Employee
```bash
php sync_attendance_data.php --employee=123
```
Syncs attendance data for a specific employee code (last 30 days).

**Example:**
```bash
php sync_attendance_data.php --employee=1
php sync_attendance_data.php --employee=EMP001
```

---

### 6. Combine Parameters
```bash
php sync_attendance_data.php --employee=123 --date=2025-11-15
```
Syncs a specific employee for a specific date.

**More examples:**
```bash
# Specific employee, last 7 days
php sync_attendance_data.php --employee=1 --days=7

# Specific employee, date range
php sync_attendance_data.php --employee=1 --from=2025-11-01 --to=2025-11-15
```

---

## Quick Commands for Common Tasks

### Today's Attendance Only
```bash
php sync_attendance_data.php --date=%date:~0,4%-%date:~5,2%-%date:~8,2%
```
Or simply:
```bash
php sync_attendance_data.php --days=0
```

### This Week (Last 7 Days)
```bash
php sync_attendance_data.php --days=7
```

### This Month
```bash
php sync_attendance_data.php --from=2025-11-01 --to=2025-11-30
```

### Full Re-sync (Last 90 Days)
```bash
php sync_attendance_data.php --days=90
```

---

## Using the Batch File

Instead of typing the full command, use:

```bash
test_sync.bat
```

This will:
- Check if API is running
- Run the default sync (last 30 days)
- Show results

---

## Schedule Automatic Sync

### Setup CRON (Every 5 Minutes)
```bash
setup_cron.bat
```
Run this as **Administrator** to create a Windows scheduled task.

### Manual Schedule Setup
1. Open **Task Scheduler**
2. Create Basic Task
3. Set trigger: Every 5 minutes
4. Action: `C:\php\php.exe D:\LOCALHOST\hrm.healthgenie\sync_attendance_data.php`

---

## Verify Sync Results

### Check Record Count
```bash
mysql -u root -pmysql -D "hrm.healthgenie.in_bkp_2025_11_05" -e "SELECT COUNT(*) FROM raw_attendance"
```

### Check Latest Records
```bash
mysql -u root -pmysql -D "hrm.healthgenie.in_bkp_2025_11_05" -e "SELECT * FROM raw_attendance ORDER BY DateString_2 DESC LIMIT 10"
```

### Check Specific Date
```bash
mysql -u root -pmysql -D "hrm.healthgenie.in_bkp_2025_11_05" -e "SELECT COUNT(*) FROM raw_attendance WHERE DateString_2='2025-11-15'"
```

### Check Specific Employee
```bash
mysql -u root -pmysql -D "hrm.healthgenie.in_bkp_2025_11_05" -e "SELECT * FROM raw_attendance WHERE Empcode='1' ORDER BY DateString_2 DESC LIMIT 10"
```

---

## Troubleshooting Commands

### Test API Connection
```bash
curl http://localhost:8001/api/v1/health
```
Should return: `{"status":"healthy",...}`

### Test API Data Fetch
```bash
curl "http://localhost:8001/api/v1/attendance/raw?from_date=2025-11-15&to_date=2025-11-15"
```

### Check if Laravel API is Running
```bash
netstat -ano | findstr :8001
```

### Start Laravel API
```bash
cd D:\LOCALHOST\hrm-attendance-api
php artisan serve --port=8001
```

---

## Output Examples

### Successful Sync
```
[2025-11-15 10:30:00] Starting attendance data sync...
[2025-11-15 10:30:00] Mode: Date range sync
[2025-11-15 10:30:00] Fetching data from API: http://localhost:8001/api/v1/attendance/raw?from_date=2025-10-16&to_date=2025-11-15
[2025-11-15 10:30:01] Fetched 4942 records from API
[2025-11-15 10:30:05] Sync completed:
  - Inserted: 4942 records
  - Updated: 0 records
  - Errors: 0 records
  - Total processed: 4942 records
```

### No New Records
```
[2025-11-15 10:35:00] Starting attendance data sync...
[2025-11-15 10:35:00] Mode: Date range sync
[2025-11-15 10:35:00] Fetching data from API: http://localhost:8001/api/v1/attendance/raw?from_date=2025-10-16&to_date=2025-11-15
[2025-11-15 10:35:01] Fetched 4942 records from API
[2025-11-15 10:35:05] Sync completed:
  - Inserted: 0 records
  - Updated: 4942 records
  - Errors: 0 records
  - Total processed: 4942 records
```

### API Error
```
[2025-11-15 10:40:00] Starting attendance data sync...
[2025-11-15 10:40:00] Mode: Date range sync
[CRITICAL ERROR] API request failed with HTTP code: 0
```
**Solution:** Start the Laravel API server.

---

## Best Practices

1. **Initial Sync:** Sync a large date range first
   ```bash
   php sync_attendance_data.php --days=90
   ```

2. **Regular Sync:** Let CRON handle it (every 5 minutes)
   ```bash
   setup_cron.bat
   ```

3. **Manual Re-sync:** Only when needed
   ```bash
   php sync_attendance_data.php --date=2025-11-15
   ```

4. **Specific Employee:** When testing or debugging
   ```bash
   php sync_attendance_data.php --employee=1 --days=7
   ```

---

## Summary

| Task | Command |
|------|---------|
| Default sync | `php sync_attendance_data.php` |
| Today only | `php sync_attendance_data.php --date=2025-11-15` |
| Last 7 days | `php sync_attendance_data.php --days=7` |
| Date range | `php sync_attendance_data.php --from=2025-11-01 --to=2025-11-15` |
| One employee | `php sync_attendance_data.php --employee=123` |
| Setup CRON | `setup_cron.bat` (as Admin) |
| Test | `test_sync.bat` |

---

**Need Help?** Check `SYNC_SETUP_INSTRUCTIONS.md` for detailed setup and troubleshooting guide.
