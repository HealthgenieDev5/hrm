# How to Fetch Raw Attendance Data into Database

## 📋 Overview

You have **3 ways** to fetch raw attendance data from the API and save it to your `raw_attendance` database table:

1. ✅ **Using the Command** (NEW - Best for cron jobs)
2. ✅ **Using the Helper Function** (Existing)
3. ✅ **Automatic via Cron** (Recommended for production)

---

## Method 1: Using the Command (NEW)

### ✅ **Command Created:**

`php spark fetch:raw-attendance`

### **Basic Usage:**

```bash
# Fetch for current month (all employees)
php spark fetch:raw-attendance

# Fetch for specific employee
php spark fetch:raw-attendance --employee=HG001

# Fetch for specific date range
php spark fetch:raw-attendance --from=2025-11-01 --to=2025-11-30

# Fetch for specific month
php spark fetch:raw-attendance --month=2025-11

# Combine options
php spark fetch:raw-attendance --employee=HG001 --month=2025-10
```

### **What it Does:**

1. Fetches data from API at `http://hrm-attendance-api.test/api/v1/attendance/raw`
2. Saves each record to `raw_attendance` table
3. Updates existing records if already present (based on Empcode + DateString_2)
4. Shows detailed output of what was saved

### **Example Output:**

```
═══════════════════════════════════════════════════════
  Fetching Raw Attendance from API
═══════════════════════════════════════════════════════

Parameters:
─────────────────────────────────────────────────────
  Employee: HG001
  From Date: 2025-11-01
  To Date: 2025-11-30

Fetching data from API...
✓ Received 30 records from API

Saving to database...
─────────────────────────────────────────────────────
  Saved: HG001 - 2025-11-01 | In: 09:17:00 Out: 18:25:00
  Saved: HG001 - 2025-11-02 | In: 09:20:00 Out: 18:30:00
  ...

═══════════════════════════════════════════════════════
  Summary
═══════════════════════════════════════════════════════
  Total Records: 30
  New Records Saved: 25
  Records Updated: 5
  Errors: 0
```

---

## Method 2: Using Helper Function (Existing)

### **Function:** `save_raw_punching_data()`

Location: `app/Helpers/Config_defaults_helper.php:163`

### **Usage in PHP:**

```php
<?php

// Save current month data for all employees
save_raw_punching_data('ALL');

// Save specific employee data
save_raw_punching_data('HG001');

// Save with date range
save_raw_punching_data('HG001', '2025-11-01', '2025-11-30');

// Use in controller
public function syncRawData()
{
    save_raw_punching_data('ALL', first_date_of_month(), current_date_of_month());
    return redirect()->back()->with('success', 'Data synced successfully');
}
```

### **How it Works:**

```php
function save_raw_punching_data($employee_id = 'ALL', $from_date = '', $to_date = '', $return = false)
{
    // 1. Fetch data from API (via get_raw_punching_data)
    $RawPunchingData = json_decode(get_raw_punching_data($employee_id, $from_date, $to_date), true)['InOutPunchData'];

    // 2. Loop through each record
    foreach ($RawPunchingData as $dataRow) {
        // 3. Check if exists (by Empcode + DateString_2)
        $existing = $RawPunchingDataModel->where('Empcode =', $dataRow['Empcode'])->where('DateString_2 =', $dataRow['DateString_2'])->first();

        // 4. Update if exists, insert if new
        if (!empty($existing)) {
            $dataRow['id'] = $existing['id'];
        }
        $RawPunchingDataModel->save($dataRow);
    }
}
```

---

## Method 3: Automatic Cron Job (Recommended)

### **Option A: Using Spark Command (Recommended)**

Create a cron job to run the command automatically:

#### **Linux/Mac Cron:**

```bash
# Edit crontab
crontab -e

# Add these lines:

# Fetch yesterday's data every day at 6 AM
0 6 * * * cd /path/to/hrm.healthgenie && php spark fetch:raw-attendance --from=$(date -d "yesterday" +\%Y-\%m-\%d) --to=$(date -d "yesterday" +\%Y-\%m-\%d) >> /var/log/fetch-attendance.log 2>&1

# Fetch current month data every day at 7 AM
0 7 * * * cd /path/to/hrm.healthgenie && php spark fetch:raw-attendance >> /var/log/fetch-attendance.log 2>&1

# Fetch previous month on 1st of every month at 8 AM
0 8 1 * * cd /path/to/hrm.healthgenie && php spark fetch:raw-attendance --month=$(date -d "last month" +\%Y-\%m) >> /var/log/fetch-attendance.log 2>&1
```

#### **Windows Task Scheduler:**

1. Open Task Scheduler
2. Create New Task
3. **Trigger:** Daily at 6:00 AM
4. **Action:** Start a program
   - Program: `php`
   - Arguments: `spark fetch:raw-attendance`
   - Start in: `D:\LOCALHOST\hrm.healthgenie`

### **Option B: Using URL Cron (via Controller)**

Create a controller endpoint:

```php
// app/Controllers/Cron/SyncRawAttendance.php
<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;

class SyncRawAttendance extends BaseController
{
    public function sync()
    {
        helper('config_defaults');

        // Fetch current month
        save_raw_punching_data('ALL', first_date_of_month(), current_date_of_month());

        return 'Raw attendance data synced successfully';
    }

    public function syncYesterday()
    {
        helper('config_defaults');

        $yesterday = date('Y-m-d', strtotime('-1 day'));
        save_raw_punching_data('ALL', $yesterday, $yesterday);

        return 'Yesterday\'s data synced successfully';
    }
}
```

Add route:
```php
// app/Config/CustomRoutes/CronRoutes.php
$routes->get('cron/sync-raw-attendance', 'Cron\SyncRawAttendance::sync');
$routes->get('cron/sync-yesterday-attendance', 'Cron\SyncRawAttendance::syncYesterday');
```

Then use URL cron service:
```
http://your-domain.com/cron/sync-raw-attendance
http://your-domain.com/cron/sync-yesterday-attendance
```

---

## 🔍 Verifying Data is Saved

### **Check Database:**

```sql
-- Check recent records
SELECT * FROM raw_attendance ORDER BY DateString_2 DESC LIMIT 10;

-- Count records for a date
SELECT COUNT(*) FROM raw_attendance WHERE DateString_2 = '2025-11-17';

-- Check specific employee
SELECT * FROM raw_attendance
WHERE Empcode = 'HG001'
AND DateString_2 BETWEEN '2025-11-01' AND '2025-11-30'
ORDER BY DateString_2;

-- Check what was saved today
SELECT * FROM raw_attendance
WHERE created_at >= CURDATE()
ORDER BY created_at DESC;
```

### **Using PHP:**

```php
use App\Models\RawPunchingDataModel;

$model = new RawPunchingDataModel();

// Get today's records
$records = $model->where('DateString_2', date('Y-m-d'))->findAll();
echo count($records) . " records for today";

// Get specific employee
$records = $model->where('Empcode', 'HG001')
                 ->where('DateString_2 >=', '2025-11-01')
                 ->where('DateString_2 <=', '2025-11-30')
                 ->findAll();
```

---

## 📊 Database Table Structure

**Table:** `raw_attendance`

```sql
CREATE TABLE `raw_attendance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Empcode` varchar(50) DEFAULT NULL,
  `INTime` varchar(20) DEFAULT NULL,
  `OUTTime` varchar(20) DEFAULT NULL,
  `Remark` varchar(255) DEFAULT NULL,
  `DateString` varchar(20) DEFAULT NULL,
  `DateString_2` date DEFAULT NULL,
  `machine` varchar(20) DEFAULT NULL,
  `default_machine` varchar(20) DEFAULT NULL,
  `override_machine` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Empcode` (`Empcode`),
  KEY `DateString_2` (`DateString_2`)
);
```

---

## 🎯 Recommended Setup

### **For Production:**

1. **Daily Sync:** Run command every morning at 6 AM to fetch yesterday's data
2. **Monthly Sync:** On 1st of month, fetch previous complete month
3. **Manual Sync:** Use command for ad-hoc fetching

### **Cron Schedule:**

```bash
# Daily at 6 AM - fetch yesterday
0 6 * * * cd /d/LOCALHOST/hrm.healthgenie && php spark fetch:raw-attendance --from=$(date -d "yesterday" +\%Y-\%m-\%d) --to=$(date -d "yesterday" +\%Y-\%m-\%d)

# Daily at 7 AM - fetch current month (to catch late updates)
0 7 * * * cd /d/LOCALHOST/hrm.healthgenie && php spark fetch:raw-attendance

# Every Monday at 8 AM - fetch last week
0 8 * * 1 cd /d/LOCALHOST/hrm.healthgenie && php spark fetch:raw-attendance --from=$(date -d "last monday" +\%Y-\%m-\%d) --to=$(date -d "last sunday" +\%Y-\%m-\%d)
```

---

## 🔧 Troubleshooting

### **Issue: No data received from API**

**Check:**
```bash
# 1. Is API running?
curl http://hrm-attendance-api.test/api/v1/health

# 2. Does API have data?
curl "http://hrm-attendance-api.test/api/v1/attendance/raw?employee_id=2&date_from=2025-11-15&date_to=2025-11-15"

# 3. Is .env configured?
cat .env | grep ATTENDANCE_API
```

### **Issue: Command not found**

```bash
# List commands
php spark list

# If not showing, check file exists
ls -la app/Commands/FetchRawAttendance.php

# Clear cache
php spark cache:clear
```

### **Issue: Permission denied**

```bash
# Check permissions
chmod +x spark
chmod -R 777 writable/
```

### **Issue: Data not saving**

Check logs:
```bash
tail -f writable/logs/log-$(date +%Y-%m-%d).log
```

---

## 📝 Summary

| Method | Use Case | Command/Code |
|--------|----------|--------------|
| **Command** | Manual fetch, cron jobs | `php spark fetch:raw-attendance` |
| **Helper** | Inside controllers/code | `save_raw_punching_data('ALL')` |
| **Cron** | Automatic daily sync | Set up crontab/Task Scheduler |

---

## ✅ Quick Start

**1. Test the command:**
```bash
cd D:\LOCALHOST\hrm.healthgenie
php spark fetch:raw-attendance --from=2025-11-15 --to=2025-11-15
```

**2. Check database:**
```sql
SELECT COUNT(*) FROM raw_attendance WHERE DateString_2 = '2025-11-15';
```

**3. Set up cron (choose one):**
- Linux: Add to crontab
- Windows: Use Task Scheduler
- Web: Create controller + use cron URL service

**Done!** 🎉

---

**Last Updated:** 2025-11-17
**Command File:** `app/Commands/FetchRawAttendance.php`
**Helper File:** `app/Helpers/Config_defaults_helper.php:163`
