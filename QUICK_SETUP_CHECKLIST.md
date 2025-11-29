# Quick Setup Checklist - Attendance API Integration

## ✅ Pre-Setup (Already Done)

- [x] Laravel Attendance API created (`/d/LOCALHOST/hrm-attendance-api`)
- [x] API database migrated (employees, shifts, raw_attendance tables)
- [x] Data already in API database (529 employees, 93 shifts)
- [x] API tested and working
- [x] `AttendanceApiClient.php` service created in HRM
- [x] `SyncToApiDatabase.php` command created in HRM

---

## 🔧 What You Need To Do Now

### Step 1: Configure HRM Database Connection (2 minutes)

**File:** `app/Config/Database.php`

Add this at the end of the Database class:

```php
public array $api_database = [
    'DSN'         => '',
    'hostname'    => 'localhost',
    'username'    => 'root',
    'password'    => '',  // Your MySQL password
    'database'    => 'hrm_attendance_api',
    'DBDriver'    => 'MySQLi',
    'DBPrefix'    => '',
    'pConnect'    => false,
    'DBDebug'     => true,
    'charset'     => 'utf8mb4',
    'DBCollat'    => 'utf8mb4_general_ci',
    'port'        => 3306,
];
```

### Step 2: Configure Environment Variables (1 minute)

**File:** `.env`

Add these lines:

```env
# Attendance API
USE_ATTENDANCE_API=false
ATTENDANCE_API_URL=http://localhost:8001/api/v1
ATTENDANCE_API_KEY=dummy
ATTENDANCE_API_SECRET=dummy
ATTENDANCE_API_TIMEOUT=30
ATTENDANCE_API_FALLBACK_TO_LOCAL=true
```

**Note:** Set `USE_ATTENDANCE_API=false` initially for testing. Change to `true` when ready.

### Step 3: Start Laravel API (1 command)

```bash
cd /d/LOCALHOST/hrm-attendance-api
php artisan serve --port=8001
```

Keep this running in a separate terminal.

### Step 4: Test API Health (1 command)

Open browser or curl:

```bash
curl http://localhost:8001/api/v1/health
```

Expected response:
```json
{
    "status": "healthy",
    "version": "1.0.0",
    "database": "connected",
    "timestamp": "2025-11-11 12:00:00"
}
```

### Step 5: Sync Data to API (1 command)

```bash
cd /d/LOCALHOST/hrm.healthgenie
php spark sync:api-database
```

Expected output:
```
Syncing employees...
529 employees synced successfully
Syncing shifts...
93 shifts synced successfully
Sync completed successfully!
```

### Step 6: Test API Integration (Create Test File)

**File:** `app/Controllers/TestApiController.php`

```php
<?php

namespace App\Controllers;

use App\Services\AttendanceApiClient;

class TestApiController extends BaseController
{
    public function testApi()
    {
        $apiClient = new AttendanceApiClient();

        // Test 1: Health check
        echo "<h2>Test 1: API Health</h2>";
        if ($apiClient->checkHealth()) {
            echo "✓ API is healthy<br>";
        } else {
            echo "✗ API is down<br>";
        }

        // Test 2: Get status
        echo "<h2>Test 2: API Status</h2>";
        $status = $apiClient->getStatus();
        echo "<pre>" . print_r($status, true) . "</pre>";

        // Test 3: Process attendance
        echo "<h2>Test 3: Process Attendance</h2>";
        try {
            $result = $apiClient->processSingleDay(
                employeeId: 1,
                shiftId: 1,
                date: '2025-11-10'
            );

            echo "✓ API call successful<br>";
            echo "<pre>" . print_r($result, true) . "</pre>";

        } catch (\Exception $e) {
            echo "✗ API call failed: " . $e->getMessage() . "<br>";
        }
    }
}
```

**Add Route:** `app/Config/Routes.php`

```php
$routes->get('test-api', 'TestApiController::testApi');
```

**Visit:** `http://localhost:8080/test-api`

---

## 🎯 Integration Options

### Option A: Simple Test Integration

Add this to any controller:

```php
use App\Services\AttendanceApiClient;

$apiClient = new AttendanceApiClient();
$result = $apiClient->processSingleDay(1, 1, '2025-11-10');
echo "Work Hours: " . $result['work_hours_adjusted'];
```

### Option B: Replace Existing Attendance Calculation

Find where you currently calculate attendance (likely in a pipe/processor):

**Before (Your existing code):**
```php
// Your existing calculation
$work_minutes = calculateWorkHours($employee, $shift, $date);
```

**After (With API):**
```php
use App\Services\AttendanceApiClient;

if (getenv('USE_ATTENDANCE_API') === 'true') {
    // Use API
    $apiClient = new AttendanceApiClient();
    try {
        $result = $apiClient->processSingleDay($employee_id, $shift_id, $date);
        $work_minutes = $result['work_minutes_adjusted'];
        // Use other fields as needed
    } catch (\Exception $e) {
        // Fallback to existing calculation
        $work_minutes = calculateWorkHours($employee, $shift, $date);
    }
} else {
    // Use existing calculation
    $work_minutes = calculateWorkHours($employee, $shift, $date);
}
```

---

## 📋 Testing Checklist

Before enabling in production:

- [ ] API health check passes
- [ ] Data sync successful (employees & shifts)
- [ ] Test with 1 employee - regular shift
- [ ] Test with 1 employee - reduce shift (if available)
- [ ] Test with invalid employee ID (should return error)
- [ ] Test with invalid shift ID (should return error)
- [ ] Test fallback (stop API, ensure local processing works)
- [ ] Check logs for errors

---

## 🚀 Go Live Checklist

When ready to use API in production:

1. **Enable API:**
   ```env
   USE_ATTENDANCE_API=true
   ```

2. **Setup Auto-Sync Cron:**
   ```bash
   # Add to crontab
   */5 * * * * cd /path/to/hrm.healthgenie && php spark sync:api-database
   ```

3. **Keep API Running:**
   - Use process manager like `supervisor` or `pm2`
   - Or configure as systemd service

4. **Monitor:**
   - Watch logs: `tail -f writable/logs/log-*.log`
   - Check API health periodically

---

## 🔥 Quick Commands Reference

```bash
# Start Laravel API
cd /d/LOCALHOST/hrm-attendance-api
php artisan serve --port=8001

# Sync data from HRM to API
cd /d/LOCALHOST/hrm.healthgenie
php spark sync:api-database

# Test API health
curl http://localhost:8001/api/v1/health

# Run API tests
cd /d/LOCALHOST/hrm-attendance-api
php artisan test --filter=AttendanceApiSimpleTest
```

---

## 🎓 Summary

**Minimum Steps to Get Started:**

1. ✅ Add API database config to `Database.php`
2. ✅ Add settings to `.env`
3. ✅ Start Laravel API: `php artisan serve --port=8001`
4. ✅ Sync data: `php spark sync:api-database`
5. ✅ Test in browser: Create test controller and visit `/test-api`

**That's it! Your API is ready to use.**

For gradual rollout:
- Keep `USE_ATTENDANCE_API=false` initially
- Test thoroughly
- Enable for specific employees/routes first
- Monitor performance
- Enable globally when confident

---

## 📞 Need Help?

Check these files:
- `HRM_API_INTEGRATION_GUIDE.md` - Detailed integration guide
- `TESTING_GUIDE.md` (in API folder) - API testing documentation
- Logs: `writable/logs/log-*.log` (HRM) and `storage/logs/laravel.log` (API)
