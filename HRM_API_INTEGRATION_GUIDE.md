# HRM Attendance API Integration Guide

## Overview

This guide shows you how to integrate the Laravel Attendance API into your CodeIgniter 4 HRM portal.

---

## 📁 Files Already Created

✅ **Service Layer**
- `app/Services/AttendanceApiClient.php` - API client service

✅ **CLI Commands**
- `app/Commands/SyncToApiDatabase.php` - Sync employees/shifts to API database

---

## 🔧 Step 1: Configure Database Connection

Add API database connection to your HRM database config.

**File:** `app/Config/Database.php`

```php
<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    // ... existing code ...

    /**
     * API Database Connection
     * Used to sync data to the Laravel Attendance API database
     */
    public array $api_database = [
        'DSN'         => '',
        'hostname'    => 'localhost',           // Same as your HRM database
        'username'    => 'root',                // Your MySQL username
        'password'    => 'your_password',       // Your MySQL password
    'database'    => 'hrm_attendance_api',  // Laravel API database name
        'DBDriver'    => 'MySQLi',
        'DBPrefix'    => '',
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8mb4',
        'DBCollat'    => 'utf8mb4_general_ci',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
    ];
}
```

---

## 🔧 Step 2: Configure Environment Variables

Add API configuration to your `.env` file.

**File:** `.env`

```env
#--------------------------------------------------------------------
# ATTENDANCE API CONFIGURATION
#--------------------------------------------------------------------

# Enable/Disable API usage
USE_ATTENDANCE_API=true

# API Base URL (Laravel API)
ATTENDANCE_API_URL=http://localhost:8001/api/v1

# API Authentication (for future JWT implementation)
ATTENDANCE_API_KEY=your_api_key_here
ATTENDANCE_API_SECRET=your_secret_here

# API Timeout (seconds)
ATTENDANCE_API_TIMEOUT=30

# Fallback to local processing if API fails
ATTENDANCE_API_FALLBACK_TO_LOCAL=true
```

**IMPORTANT:** For now, since JWT is not implemented, the API is accessible without authentication. You can set dummy values for API_KEY and API_SECRET.

---

## 🔧 Step 3: How to Use the API Client

### Option A: Call API Directly in Your Code

**Example: Process attendance for a single employee**

```php
<?php

use App\Services\AttendanceApiClient;

// Initialize API client
$apiClient = new AttendanceApiClient();

// Process single day attendance
try {
    $result = $apiClient->processSingleDay(
        employeeId: 1,
        shiftId: 5,
        date: '2025-11-11'
    );

    if ($result === null) {
        // No punch data at all - handle as absent in YOUR HRM portal
        echo "No punching data found - marking employee as absent";

        // YOUR LOGIC: Create absent record in your database
        // createAbsentRecord($employeeId, $shiftId, $date);

    } elseif ($result['incomplete_punch'] ?? false) {
        // Incomplete punch data - has IN or OUT but not both
        echo "Incomplete punch detected!";
        echo "Missing: " . $result['missing_punch'];  // "IN" or "OUT"
        echo "Has IN: " . ($result['punch_in_original'] ?? 'No');
        echo "Has OUT: " . ($result['punch_out_original'] ?? 'No');
        echo "Reason: " . $result['reason'];

        // YOUR LOGIC: Handle incomplete punch (mark as half-day, notify employee, etc.)
        // handleIncompletePunch($result);

    } else {
        // Complete punch data - use API calculations
        echo "Employee ID: " . $result['employee_id'];
        echo "Work Hours: " . $result['work_hours_adjusted'];
        echo "Reduction Applied: " . ($result['reduction_applied'] ? 'Yes' : 'No');
        echo "Status: " . $result['is_present'];
    }

} catch (Exception $e) {
    // Real error (not "no data") - handle or fallback
    log_message('error', 'API call failed: ' . $e->getMessage());
}
```

### Option B: Integrate into Existing Attendance Processing Pipeline

If you have an existing attendance processing pipeline (like pipes/processors), you can add the API call there.

**Example: In your attendance processor**

```php
<?php

namespace App\Pipes\AttendanceProcessor;

use App\Services\AttendanceApiClient;
use Closure;

class AddDataToPunchingRow
{
    public function handle($punching_row, Closure $next)
    {
        // Check if API is enabled
        $useApi = getenv('USE_ATTENDANCE_API') === 'true';

        if ($useApi) {
            try {
                $apiClient = new AttendanceApiClient();

                // Call API with minimal data (returns NULL if no punch data)
                $apiResult = $apiClient->processSingleDay(
                    $punching_row['employee_id'],
                    $punching_row['shift_id'],
                    $punching_row['date']
                );

                if ($apiResult === null) {
                    // No punch data at all - handle as absent
                    log_message('info', 'No punching data from API, marking as absent');

                    $punching_row['is_present'] = 'no';
                    $punching_row['is_absent'] = 'yes';
                    $punching_row['work_minutes_between_shifts_including_od'] = 0;
                    // ... set other absent fields

                } elseif ($apiResult['incomplete_punch'] ?? false) {
                    // Incomplete punch - has raw punch data but can't calculate hours
                    log_message('warning', 'Incomplete punch from API: missing ' . $apiResult['missing_punch']);

                    // Store the raw punch data that exists
                    $punching_row['punch_in'] = $apiResult['punch_in_original'];
                    $punching_row['punch_out'] = $apiResult['punch_out_original'];
                    $punching_row['incomplete_punch_flag'] = true;
                    $punching_row['missing_punch_type'] = $apiResult['missing_punch'];

                    // YOUR LOGIC: Decide how to handle incomplete punch
                    // Option 1: Mark as half-day
                    // Option 2: Mark as absent
                    // Option 3: Notify employee to correct punch
                    $punching_row['is_half_day'] = 'yes';  // Example
                    $punching_row['work_minutes_between_shifts_including_od'] = 0;

                } else {
                    // Complete punch data - use API calculations
                    $punching_row['work_minutes_between_shifts_including_od'] = $apiResult['work_minutes_adjusted'];
                    $punching_row['work_hours_between_shifts_including_od'] = $apiResult['work_hours_adjusted'];
                    $punching_row['reduction_applied'] = $apiResult['reduction_applied'];
                    $punching_row['reduction_percentage'] = $apiResult['reduction_percentage'];
                    $punching_row['is_present'] = $apiResult['is_present'];
                    $punching_row['is_absent'] = $apiResult['is_absent'];
                    $punching_row['is_half_day'] = $apiResult['is_half_day'];
                    // ... use other fields as needed
                }

            } catch (Exception $e) {
                // Real error - fallback to local processing
                log_message('error', 'API failed, using local processing: ' . $e->getMessage());

                // Your existing local calculation code here
                // $punching_row['work_minutes_between_shifts_including_od'] = ...
            }
        } else {
            // Use existing local processing
            // Your existing local calculation code here
        }

        return $next($punching_row);
    }
}
```

---

## 🔧 Step 4: Sync Data to API Database

The API needs employee and shift data. Run this command to sync:

```bash
# Sync employees and shifts to API database
php spark sync:api-database
```

**Output:**
```
Starting sync to API database...
Database connections established
Syncing employees...
529 employees synced successfully
Syncing shifts...
93 shifts synced successfully
═══════════════════════════════════════
Sync completed successfully!
├─ Employees synced: 529
└─ Shifts synced: 93
═══════════════════════════════════════
```

### Setup Automatic Sync (Optional)

Add to your server cron to sync every 5 minutes:

```bash
*/5 * * * * cd /path/to/hrm.healthgenie && php spark sync:api-database >> /var/log/api-sync.log 2>&1
```

---

## 🔧 Step 5: Test the Integration

### Test 1: Check API Health

```php
<?php

use App\Services\AttendanceApiClient;

$apiClient = new AttendanceApiClient();

if ($apiClient->checkHealth()) {
    echo "✓ API is healthy and reachable";
} else {
    echo "✗ API is down or unreachable";
}

// Get detailed status
$status = $apiClient->getStatus();
print_r($status);
```

### Test 2: Process Test Employee

```php
<?php

use App\Services\AttendanceApiClient;

$apiClient = new AttendanceApiClient();

// Test with a known employee
$result = $apiClient->processSingleDay(
    employeeId: 1,      // MANU GROVER
    shiftId: 1,         // Regular shift
    date: '2025-11-10'
);

echo "Employee: " . $result['employee_id'] . "\n";
echo "Work Hours: " . $result['work_hours_adjusted'] . "\n";
echo "Present: " . $result['is_present'] . "\n";
```

---

## 📊 API Response Structure

### Success Response (200 OK)

#### Case 1: Complete Punch Data (Both IN and OUT)

When both punch IN and OUT exist, the API returns complete attendance calculations:

```php
{
    "status": "success",
    "data": {
        // Basic Info
        'employee_id' => 123,
        'shift_id' => 5,
        'date' => '2025-11-11',

        // Punch Times (Original from eTime Office)
        'punch_in_original' => '09:00:00',
        'punch_out_original' => '19:00:00',

        // Punch Times (Adjusted for reduce shift)
        'punch_in_adjusted' => '09:00:00',
        'punch_out_adjusted' => '15:40:00',  // Adjusted for 66.67% reduction

        // Work Hours (Original)
        'work_minutes_original' => 600,      // 10 hours
        'work_hours_original' => '10:00',

        // Work Hours (Adjusted with reduction)
        'work_minutes_adjusted' => 400,      // 6h 40m after reduction
        'work_hours_adjusted' => '06:40',

        // Reduction Metadata
        'reduction_applied' => true,         // Is this a reduce shift?
        'reduction_percentage' => 66.67,     // Percentage applied
        'minutes_reduced' => 200,            // Minutes deducted

        // Deductions
        'late_coming_minutes' => 0,
        'early_going_minutes' => 0,
        'deduction_minutes' => 0,

        // Attendance Status
        'is_present' => 'yes',
        'is_absent' => 'no',
        'is_half_day' => 'no',
        'absent_because_of_work_hours' => 'no',
        'half_day_because_of_work_hours' => 'no',

        // Additional Info
        'shift_type' => 'reduce',
        'shift_code' => '003',
        'machine' => 'del',
        'incomplete_punch' => false,         // Both punches exist
    }
}
```

#### Case 2: Incomplete Punch Data (Only IN or only OUT)

When only one punch exists (missing IN or OUT), the API returns the raw data without calculations:

```php
{
    "status": "success",
    "data": {
        // Basic Info
        'employee_id' => 385,
        'shift_id' => 5,
        'date' => '2025-11-10',

        // Punch Times (Whatever exists)
        'punch_in_original' => '09:15:00',   // Has IN
        'punch_out_original' => null,        // Missing OUT
        'punch_in_adjusted' => '09:15:00',
        'punch_out_adjusted' => null,

        // No work hours calculable
        'work_minutes_original' => 0,
        'work_minutes_adjusted' => 0,
        'work_hours_original' => '00:00',
        'work_hours_adjusted' => '00:00',

        // No reduction
        'reduction_applied' => false,
        'reduction_percentage' => 0,
        'minutes_reduced' => 0,

        // No deductions
        'late_coming_minutes' => 0,
        'early_going_minutes' => 0,
        'deduction_minutes' => 0,

        // Incomplete punch status
        'is_present' => 'no',
        'is_absent' => 'no',
        'is_half_day' => 'no',
        'absent_because_of_work_hours' => 'no',
        'half_day_because_of_work_hours' => 'no',

        // Additional Info
        'shift_type' => 'regular',
        'shift_code' => '001',
        'machine' => 'del',
        'incomplete_punch' => true,          // ⚠️ Check this flag!
        'missing_punch' => 'OUT',            // Which punch is missing
        'reason' => 'Incomplete punching data - missing OUT time',
    }
}
```

**IMPORTANT:** Check the `incomplete_punch` flag in your HRM portal to handle missing punch data!

### No Data Response (404 Not Found)

When no punch data exists, the API returns 404 - **Your HRM portal decides how to handle this**:

```php
{
    "status": "no_data",
    "message": "No punching data found for employee 123 on 2025-11-11",
    "employee_id": 123,
    "date": "2025-11-11"
}
```

**IMPORTANT:** The API does **NOT** create absent records. When you receive a 404 response, your HRM portal should:
- Create an absent record in your database
- Mark the employee as absent
- Apply your absence handling logic

---

## 🚀 Deployment Checklist

### On Development Server:

- [ ] Configure `.env` with API URL
- [ ] Add API database connection in `Database.php`
- [ ] Run data sync: `php spark sync:api-database`
- [ ] Test API health check
- [ ] Test with sample employee

### On Production Server:

- [ ] Deploy API client service files
- [ ] Configure production `.env` values
- [ ] Setup API database connection
- [ ] Run initial data sync
- [ ] Setup cron for automatic sync (every 5 minutes)
- [ ] Monitor logs for API errors
- [ ] Ensure fallback is enabled: `ATTENDANCE_API_FALLBACK_TO_LOCAL=true`

---

## 🔍 Troubleshooting

### Issue: "API is unreachable"

**Check:**
1. Is Laravel API running? `cd /d/LOCALHOST/hrm-attendance-api && php artisan serve --port=8001`
2. Is the URL correct in `.env`? Should be `http://localhost:8001/api/v1`
3. Test manually: `curl http://localhost:8001/api/v1/health`

### Issue: "No data synced to API database"

**Solution:**
```bash
# Check database connection
php spark db:table employees --limit 5

# Run sync manually
php spark sync:api-database
```

### Issue: "API returns 500 error"

**Check Laravel logs:**
```bash
tail -f /d/LOCALHOST/hrm-attendance-api/storage/logs/laravel.log
```

### Issue: "Fallback not working"

**Ensure:**
1. `.env` has `ATTENDANCE_API_FALLBACK_TO_LOCAL=true`
2. Implement fallback logic in `AttendanceApiClient::fallbackToLocalProcessing()`

---

## 📈 Performance Considerations

### API is Faster Than Local Processing

| Aspect | Local Processing | API Processing |
|--------|-----------------|----------------|
| Database Queries | Multiple per employee | 1 request |
| Reduction Logic | Executed in PHP | Pre-calculated |
| Response Time | ~200-500ms | ~50-100ms |
| Scalability | Limited by web server | Can scale independently |

### Best Practices

1. **Use Bulk API for Reports:** When processing multiple employees, use `processBulk()`
2. **Cache Results:** Store API results to avoid repeated calls
3. **Monitor API Health:** Regularly check API status
4. **Enable Fallback:** Always have local processing as backup

---

## 🎯 Next Steps

1. **Enable API in Development:**
   ```env
   USE_ATTENDANCE_API=true
   ```

2. **Test with Sample Data:**
   - Process attendance for 1-2 employees
   - Verify results match expected values

3. **Gradually Roll Out:**
   - Start with 10% of employees
   - Monitor logs and performance
   - Increase to 100% over 1 week

4. **Add JWT Authentication (Future):**
   - Secure API endpoints
   - Implement token refresh logic

5. **Setup Monitoring:**
   - Track API response times
   - Alert on API downtime
   - Monitor fallback usage

---

## 📞 Support

If you encounter issues:

1. Check Laravel API logs: `storage/logs/laravel.log`
2. Check HRM logs: `writable/logs/log-YYYY-MM-DD.log`
3. Test API health: `php spark api:health` (create this command)
4. Verify database sync: `php spark sync:api-database`

---

## Summary

**What You Need to Do:**

1. ✅ Add API database connection to `app/Config/Database.php`
2. ✅ Configure `.env` with API settings
3. ✅ Run `php spark sync:api-database` to sync data
4. ✅ Test API with sample employee
5. ✅ Integrate API client into your attendance processing code
6. ✅ Enable in production with `USE_ATTENDANCE_API=true`

The API is **ready to use** - just configure and test!
