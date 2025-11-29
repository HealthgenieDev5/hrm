# Attendance Reduction System - Simplified Implementation Plan

## Executive Summary

This document outlines the **simplified implementation** of attendance reduction logic via a Laravel API. The system will:
- Add only `shift_type` ENUM('regular', 'reduce') to the `shifts` table (NO reduction_percentage column)
- Hardcode 66.67% reduction in Laravel API for 'reduce' shift types
- Move eTime Office sync from CodeIgniter to Laravel API
- Process attendance with reduction applied based on shift type
- Preserve raw punch data while returning adjusted work hours

---

## 1. Database Schema Changes

### 1.1 Shifts Table Modification (ONLY THIS CHANGE)

```sql
ALTER TABLE shifts
ADD COLUMN shift_type ENUM('regular', 'reduce') DEFAULT 'regular'
AFTER shift_name;
```

**Current Structure:**
```
- id
- shift_code
- shift_name
- shift_type        ← NEW FIELD (regular/reduce)
- weekoff
- in_time
- out_time
- date_time
```

**Note:** We are NOT adding `reduction_percentage` or `effective_from_date` columns. Reduction is hardcoded at 66.67% in the API.

### 1.2 CodeIgniter Model Update

**File:** `app/Models/ShiftModel.php`

```php
<?php
namespace App\Models;
use CodeIgniter\Model;

class ShiftModel extends Model {
    protected $table = 'shifts';
    protected $allowedFields = [
        'shift_code',
        'shift_name',
        'shift_type',    // NEW
        'weekoff',
        'in_time',
        'out_time'
    ];
}
```

---

## 2. Laravel API Architecture

### 2.1 System Overview

```
┌─────────────────────────────────────────────────┐
│  eTime Office API (4 Locations)                 │
│  - Delhi (del), Gurgaon (ggn)                   │
│  - Noida (hn), Bangalore (skbd)                 │
└──────────────┬──────────────────────────────────┘
               │
               │ Laravel API fetches every 10 mins
               ↓
┌─────────────────────────────────────────────────┐
│  Laravel Attendance API (Localhost:8000)        │
│                                                  │
│  Services:                                       │
│  - ETimeOfficeService (sync punching data)      │
│  - AttendanceReductionService (calculate)       │
│                                                  │
│  Reduction Logic (Hardcoded):                   │
│  if (shift_type === 'reduce')                   │
│      adjusted_minutes = original × 0.6667       │
│  else                                            │
│      adjusted_minutes = original                │
│                                                  │
└──────────────┬──────────────────────────────────┘
               │
               │ API Response (Complete Data)
               ↓
┌─────────────────────────────────────────────────┐
│  CodeIgniter HRM Portal                         │
│                                                  │
│  Pipeline:                                       │
│  - BasicDetails                                  │
│  - ShiftRulesAndDetails                         │
│  - GetAttendanceClean                           │
│  - ProcessAttendance                            │
│  - ApplyShiftReduction ← NEW PIPE (calls API)  │
│  - LateComingAdjustment                         │
│  - SandwichSecondPass                           │
│  - ApplyAttendanceOverride                      │
│  - AdjustLastWorkingDate                        │
│                                                  │
└─────────────────────────────────────────────────┘
```

### 2.2 Database Access

**Laravel API connects to the SAME database as CodeIgniter HRM:**
- Database: `hrm.healthgenie.in_bkp_2025_11_05`
- Laravel reads: `employees`, `shifts`, `shift_per_day`
- Laravel writes: `raw_attendance` (from eTime Office sync)

**No separate database or data sync needed!**

---

## 3. Laravel API Implementation

### 3.1 Project Setup

```bash
# Create Laravel project
composer create-project laravel/laravel attendance-api
cd attendance-api

# Install JWT Auth
composer require tymon/jwt-auth

# Configure .env
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### 3.2 Database Configuration

**File:** `attendance-api/.env`

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=hrm.healthgenie.in_bkp_2025_11_05
DB_USERNAME=root
DB_PASSWORD=mysql

# eTime Office - Delhi
ETIME_DEL_API_URL=https://api.etimeoffice.com/api/DownloadInOutPunchData
ETIME_DEL_CORPORATE_ID=HOOKHLA
ETIME_DEL_USERNAME=your_username
ETIME_DEL_PASSWORD=your_password

# eTime Office - Gurgaon
ETIME_GGN_API_URL=https://api.etimeoffice.com/api/DownloadInOutPunchData
ETIME_GGN_CORPORATE_ID=GGNOFFICE
ETIME_GGN_USERNAME=your_username
ETIME_GGN_PASSWORD=your_password

# eTime Office - Noida
ETIME_HN_API_URL=https://api.etimeoffice.com/api/DownloadInOutPunchData
ETIME_HN_CORPORATE_ID=HEUER
ETIME_HN_USERNAME=your_username
ETIME_HN_PASSWORD=your_password

# eTime Office - Bangalore
ETIME_SKBD_API_URL=https://api.etimeoffice.com/api/DownloadInOutPunchData
ETIME_SKBD_CORPORATE_ID=SIKANDRABAD
ETIME_SKBD_USERNAME=your_username
ETIME_SKBD_PASSWORD=your_password

# API Settings
JWT_SECRET=your_jwt_secret_here
```

### 3.3 Models

**File:** `attendance-api/app/Models/Shift.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shifts';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'shift_code',
        'shift_name',
        'shift_type',
        'weekoff',
        'in_time',
        'out_time'
    ];

    protected $casts = [
        'weekoff' => 'array'
    ];
}
```

**File:** `attendance-api/app/Models/RawAttendance.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawAttendance extends Model
{
    protected $table = 'raw_attendance';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Empcode',
        'INTime',
        'OUTTime',
        'Remark',
        'DateString',
        'DateString_2',
        'machine',
        'default_machine',
        'override_machine'
    ];

    /**
     * Get punching data for employee on specific date
     */
    public static function getPunchingData($empcode, $date)
    {
        return self::where('Empcode', $empcode)
                   ->where('DateString_2', $date)
                   ->first();
    }
}
```

### 3.4 eTime Office Sync Service

**File:** `attendance-api/app/Services/ETimeOfficeService.php`

```php
<?php

namespace App\Services;

use App\Models\RawAttendance;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ETimeOfficeService
{
    private $locations = [
        'del' => [],
        'ggn' => [],
        'hn' => [],
        'skbd' => []
    ];

    public function __construct()
    {
        // Load from .env
        foreach (['del', 'ggn', 'hn', 'skbd'] as $location) {
            $this->locations[$location] = [
                'url' => env("ETIME_{$location}_API_URL"),
                'corporate_id' => env("ETIME_{$location}_CORPORATE_ID"),
                'username' => env("ETIME_{$location}_USERNAME"),
                'password' => env("ETIME_{$location}_PASSWORD"),
            ];
        }
    }

    /**
     * Sync all locations for date range
     */
    public function syncAllLocations($employeeCode = 'ALL', $fromDate = null, $toDate = null)
    {
        $fromDate = $fromDate ?? Carbon::now()->startOfMonth()->format('d/m/Y');
        $toDate = $toDate ?? Carbon::now()->format('d/m/Y');

        $totalSynced = 0;

        foreach (['del', 'ggn', 'hn', 'skbd'] as $location) {
            Log::info("Syncing eTime Office: {$location}");

            $synced = $this->syncLocation($location, $employeeCode, $fromDate, $toDate);
            $totalSynced += $synced;

            Log::info("Synced {$synced} records from {$location}");
        }

        return $totalSynced;
    }

    /**
     * Sync specific location
     */
    private function syncLocation($location, $employeeCode, $fromDate, $toDate)
    {
        $config = $this->locations[$location];

        if (!$config['url']) {
            Log::warning("eTime Office config missing for: {$location}");
            return 0;
        }

        // Build Basic Auth token
        $authString = "{$config['corporate_id']}:{$config['username']}:{$config['password']}:true";
        $authToken = base64_encode($authString);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $authToken,
            ])->timeout(30)->get($config['url'], [
                'Empcode' => $employeeCode,
                'FromDate' => $fromDate,
                'ToDate' => $toDate,
            ]);

            if (!$response->successful()) {
                Log::error("eTime API failed for {$location}: " . $response->body());
                return 0;
            }

            $data = $response->json();

            if (empty($data)) {
                return 0;
            }

            // Save to database
            return $this->savePunchingData($data, $location);

        } catch (\Exception $e) {
            Log::error("eTime sync error for {$location}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Save punching data to raw_attendance table
     */
    private function savePunchingData($records, $machine)
    {
        $savedCount = 0;

        foreach ($records as $record) {
            try {
                // Convert DD/MM/YYYY to YYYY-MM-DD
                $dateString2 = Carbon::createFromFormat('d/m/Y', $record['DateString'])->format('Y-m-d');

                RawAttendance::updateOrCreate(
                    [
                        'Empcode' => $record['Empcode'],
                        'DateString_2' => $dateString2,
                    ],
                    [
                        'INTime' => $record['INTime'] ?? null,
                        'OUTTime' => $record['OUTTime'] ?? null,
                        'Remark' => $record['Remark'] ?? null,
                        'DateString' => $record['DateString'],
                        'machine' => $machine,
                        'default_machine' => $machine,
                        'override_machine' => null,
                    ]
                );

                $savedCount++;
            } catch (\Exception $e) {
                Log::error("Failed to save punching record: " . $e->getMessage());
            }
        }

        return $savedCount;
    }

    /**
     * Sync today's data
     */
    public function syncToday()
    {
        $today = Carbon::now()->format('d/m/Y');
        return $this->syncAllLocations('ALL', $today, $today);
    }

    /**
     * Sync current month
     */
    public function syncCurrentMonth()
    {
        $fromDate = Carbon::now()->startOfMonth()->format('d/m/Y');
        $toDate = Carbon::now()->format('d/m/Y');
        return $this->syncAllLocations('ALL', $fromDate, $toDate);
    }
}
```

### 3.5 Attendance Reduction Service (Hardcoded 66.67%)

**File:** `attendance-api/app/Services/AttendanceReductionService.php`

```php
<?php

namespace App\Services;

use App\Models\Shift;
use App\Models\RawAttendance;
use Carbon\Carbon;

class AttendanceReductionService
{
    // HARDCODED REDUCTION PERCENTAGE
    const REDUCE_SHIFT_PERCENTAGE = 66.67;

    /**
     * Process attendance for single day
     */
    public function processAttendance($employee_id, $shift_id, $date)
    {
        // 1. Fetch shift details
        $shift = Shift::find($shift_id);
        if (!$shift) {
            throw new \Exception('Shift not found');
        }

        // 2. Fetch punching data
        $punching = RawAttendance::getPunchingData($employee_id, $date);
        if (!$punching) {
            return $this->getAbsentResponse($employee_id, $shift_id, $date);
        }

        // 3. Calculate work minutes
        $workMinutes = $this->calculateWorkMinutes(
            $punching->INTime,
            $punching->OUTTime
        );

        // 4. Apply reduction if shift_type is 'reduce'
        $reductionApplied = false;
        $adjustedMinutes = $workMinutes;

        if ($shift->shift_type === 'reduce') {
            $adjustedMinutes = round($workMinutes * (self::REDUCE_SHIFT_PERCENTAGE / 100));
            $reductionApplied = true;
        }

        // 5. Determine attendance status
        $status = $this->determineStatus($adjustedMinutes);

        // 6. Build response matching HRM's expected field names
        return [
            'employee_id' => $employee_id,
            'shift_id' => $shift_id,
            'date' => $date,

            // Raw punch times
            'in_time__Raw' => $punching->INTime,
            'out_time__Raw' => $punching->OUTTime,

            // Adjusted times (for reduce shift, adjust out time)
            'in_time' => $punching->INTime,
            'out_time' => $reductionApplied
                ? $this->addMinutesToTime($punching->INTime, $adjustedMinutes)
                : $punching->OUTTime,

            // Work minutes
            'work_minutes_between_shifts_including_od' => $adjustedMinutes,
            'work_hours_between_shifts_including_od' => $this->formatMinutesToHours($adjustedMinutes),

            // Deductions (calculated elsewhere in HRM)
            'late_coming_minutes' => 0,
            'early_going_minutes' => 0,
            'deduction_minutes' => 0,

            // Status flags
            'is_present' => $status['is_present'],
            'is_absent' => $status['is_absent'],
            'half_day_because_of_work_hours' => $status['is_half_day'],
            'absent_because_of_work_hours' => $status['is_absent_hours'],

            // Reduction metadata
            'reduction_applied' => $reductionApplied,
            'reduction_percentage' => $reductionApplied ? self::REDUCE_SHIFT_PERCENTAGE : 100.00,
            'work_minutes_original' => $workMinutes,
        ];
    }

    /**
     * Calculate work minutes from punch times
     */
    private function calculateWorkMinutes($inTime, $outTime)
    {
        if (empty($inTime) || empty($outTime)) {
            return 0;
        }

        $in = Carbon::parse($inTime);
        $out = Carbon::parse($outTime);

        // Handle overnight shifts
        if ($out->lessThan($in)) {
            $out->addDay();
        }

        return $in->diffInMinutes($out);
    }

    /**
     * Add minutes to time
     */
    private function addMinutesToTime($time, $minutes)
    {
        return Carbon::parse($time)->addMinutes($minutes)->format('H:i:s');
    }

    /**
     * Format minutes to HH:MM
     */
    private function formatMinutesToHours($minutes)
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $mins);
    }

    /**
     * Determine attendance status based on work minutes
     */
    private function determineStatus($minutes)
    {
        // Thresholds (can be made configurable later)
        $absentThreshold = 240; // 4 hours
        $halfDayThreshold = 300; // 5 hours

        if ($minutes < $absentThreshold) {
            return [
                'is_present' => 'no',
                'is_absent' => 'yes',
                'is_half_day' => 'no',
                'is_absent_hours' => 'yes'
            ];
        } elseif ($minutes < $halfDayThreshold) {
            return [
                'is_present' => 'yes',
                'is_absent' => 'no',
                'is_half_day' => 'yes',
                'is_absent_hours' => 'no'
            ];
        }

        return [
            'is_present' => 'yes',
            'is_absent' => 'no',
            'is_half_day' => 'no',
            'is_absent_hours' => 'no'
        ];
    }

    /**
     * Get response for absent employee
     */
    private function getAbsentResponse($employee_id, $shift_id, $date)
    {
        return [
            'employee_id' => $employee_id,
            'shift_id' => $shift_id,
            'date' => $date,
            'in_time__Raw' => null,
            'out_time__Raw' => null,
            'in_time' => null,
            'out_time' => null,
            'work_minutes_between_shifts_including_od' => 0,
            'work_hours_between_shifts_including_od' => '00:00',
            'late_coming_minutes' => 0,
            'early_going_minutes' => 0,
            'deduction_minutes' => 0,
            'is_present' => 'no',
            'is_absent' => 'yes',
            'half_day_because_of_work_hours' => 'no',
            'absent_because_of_work_hours' => 'yes',
            'reduction_applied' => false,
            'reduction_percentage' => 100.00,
            'work_minutes_original' => 0,
        ];
    }
}
```

### 3.6 API Controller

**File:** `attendance-api/app/Http/Controllers/Api/AttendanceController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AttendanceReductionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceReductionService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Process single day attendance
     * POST /api/v1/attendance/process/single
     */
    public function processSingle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|integer',
            'shift_id' => 'required|integer',
            'date' => 'required|date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            $result = $this->attendanceService->processAttendance(
                $request->employee_id,
                $request->shift_id,
                $request->date
            );

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Processing failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Health check
     * GET /api/v1/health
     */
    public function health()
    {
        return response()->json([
            'status' => 'healthy',
            'version' => '1.0.0',
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
```

### 3.7 API Routes

**File:** `attendance-api/routes/api.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;

Route::prefix('v1')->group(function () {

    // Health check (no auth)
    Route::get('/health', [AttendanceController::class, 'health']);

    // Attendance processing (with JWT auth - configure later)
    Route::post('/attendance/process/single', [AttendanceController::class, 'processSingle']);

});
```

### 3.8 Laravel Scheduler

**File:** `attendance-api/app/Console/Kernel.php`

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\ETimeOfficeService;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Sync eTime Office data every 10 minutes
        $schedule->call(function () {
            $service = app(ETimeOfficeService::class);
            $synced = $service->syncToday();
            \Log::info("eTime Office sync completed: {$synced} records");
        })->everyTenMinutes();

        // Full month sync daily at 2 AM
        $schedule->call(function () {
            $service = app(ETimeOfficeService::class);
            $synced = $service->syncCurrentMonth();
            \Log::info("eTime Office monthly sync: {$synced} records");
        })->dailyAt('02:00');
    }
}
```

**Add to server crontab:**
```bash
* * * * * cd /path/to/attendance-api && php artisan schedule:run >> /dev/null 2>&1
```

---

## 4. CodeIgniter HRM Integration

### 4.1 API Client Service

**File:** `app/Services/AttendanceReductionApiService.php`

```php
<?php

namespace App\Services;

use CodeIgniter\HTTP\CURLRequest;
use Exception;

class AttendanceReductionApiService
{
    protected $apiBaseUrl;
    protected $client;

    public function __construct()
    {
        $this->apiBaseUrl = getenv('ATTENDANCE_API_URL') ?: 'http://localhost:8000/api/v1';

        $this->client = \Config\Services::curlrequest([
            'baseURI' => $this->apiBaseUrl,
            'timeout' => 30
        ]);
    }

    /**
     * Process single day attendance via API
     */
    public function processDay($employee_id, $shift_id, $date)
    {
        try {
            $response = $this->client->post('/attendance/process/single', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'json' => [
                    'employee_id' => $employee_id,
                    'shift_id' => $shift_id,
                    'date' => $date
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode !== 200) {
                throw new Exception("API returned status {$statusCode}");
            }

            $result = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON response from API");
            }

            return $result;

        } catch (Exception $e) {
            log_message('error', 'Attendance API call failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
```

### 4.2 New Pipeline Pipe

**File:** `app/Pipes/AttendanceProcessor/ApplyShiftReduction.php`

```php
<?php

namespace App\Pipes\AttendanceProcessor;

use App\Services\AttendanceReductionApiService;
use Closure;

class ApplyShiftReduction
{
    public function handle($punching_row, Closure $next)
    {
        // Only apply reduction if employee has punching data
        if (empty($punching_row['in_time__Raw']) || empty($punching_row['out_time__Raw'])) {
            return $next($punching_row);
        }

        try {
            $apiService = new AttendanceReductionApiService();

            $apiResult = $apiService->processDay(
                $punching_row['employee_id'],
                $punching_row['shift_id'],
                $punching_row['date']
            );

            // Override work minutes with API response
            $punching_row['work_minutes_between_shifts_including_od'] =
                $apiResult['work_minutes_between_shifts_including_od'];

            $punching_row['work_hours_between_shifts_including_od'] =
                $apiResult['work_hours_between_shifts_including_od'];

            // Add reduction metadata for reporting
            $punching_row['reduction_applied'] = $apiResult['reduction_applied'];
            $punching_row['reduction_percentage'] = $apiResult['reduction_percentage'];
            $punching_row['work_minutes_original'] = $apiResult['work_minutes_original'];

        } catch (\Exception $e) {
            log_message('error', 'Shift reduction API failed: ' . $e->getMessage());
            // Continue with original work minutes (no reduction applied)
        }

        return $next($punching_row);
    }
}
```

### 4.3 Modify Attendance Processing Pipeline

**File:** `app/Controllers/Attendance/Processor.php`

Find the pipeline around line 60-74 and add the new pipe:

```php
$result = (new Pipeline())
    ->send($data)
    ->through([
        BasicDetails::class,
        ShiftRulesAndDetails::class,
        GetAttendanceClean::class,
        ProcessAttendance::class,
        \App\Pipes\AttendanceProcessor\ApplyShiftReduction::class,  // ← ADD THIS LINE
        LateComingAdjustment::class,
        SandwichSecondPass::class,
        ApplyAttendanceOverride::class,
        AdjustLastWorkingDate::class,
    ])
    ->then(function ($data) {
        return $data;
    });
```

### 4.4 Environment Configuration

**File:** `.env`

Add to your HRM .env file:

```env
# Attendance Reduction API
ATTENDANCE_API_URL=http://localhost:8000/api/v1
```

### 4.5 Remove eTime Office Sync from HRM

**Archive these files (don't delete, just move to backup):**
- `app/Helpers/Config_defaults_helper.php` (eTime sync functions)
- `app/Controllers/Cron/ServerCron.php` (or comment out eTime methods)

**Disable these routes in** `app/Config/CustomRoutes/CronRoutes.php`:
```php
// Comment out or remove:
// $routes->match(['get', 'post'], '/cron/rawattendance/save', 'Cron\ServerCron::index');
// $routes->match(['get', 'post'], '/cron/rawattendance/update-from-last-month/save', 'Cron\ServerCron::updateFromLastMonth');
```

---

## 5. Implementation Steps

### Step 1: Database Migration
```bash
mysql -u root -pmysql -D "hrm.healthgenie.in_bkp_2025_11_05" -e "ALTER TABLE shifts ADD COLUMN shift_type ENUM('regular', 'reduce') DEFAULT 'regular' AFTER shift_name;"
```

### Step 2: Update CodeIgniter Model
Edit `app/Models/ShiftModel.php` - add `'shift_type'` to `$allowedFields`

### Step 3: Create Laravel API
```bash
composer create-project laravel/laravel attendance-api
cd attendance-api
# Copy all service files, controllers, models from this document
php artisan serve --port=8000
```

### Step 4: Configure eTime Office Credentials
Update `attendance-api/.env` with all 4 location credentials

### Step 5: Test eTime Sync
```bash
cd attendance-api
php artisan tinker
>>> app(\App\Services\ETimeOfficeService::class)->syncToday()
```

Verify data in `raw_attendance` table

### Step 6: Test API Endpoint
```bash
curl -X POST http://localhost:8000/api/v1/attendance/process/single \
  -H "Content-Type: application/json" \
  -d '{"employee_id": 123, "shift_id": 5, "date": "2025-11-08"}'
```

### Step 7: Setup Laravel Scheduler
```bash
crontab -e
# Add: * * * * * cd /path/to/attendance-api && php artisan schedule:run >> /dev/null 2>&1
```

### Step 8: Integrate with CodeIgniter
- Create `app/Services/AttendanceReductionApiService.php`
- Create `app/Pipes/AttendanceProcessor/ApplyShiftReduction.php`
- Modify `app/Controllers/Attendance/Processor.php` pipeline
- Update `.env` with API URL

### Step 9: Test End-to-End
```bash
cd /path/to/hrm
php spark attendance:process --employee=123 --month=2025-11
```

### Step 10: Mark Shifts as 'reduce'
```sql
UPDATE shifts SET shift_type = 'reduce' WHERE id IN (5, 6, 7);
```

---

## 6. Testing Checklist

- [ ] Database column added successfully
- [ ] ShiftModel updated
- [ ] Laravel API runs without errors
- [ ] eTime sync populates raw_attendance
- [ ] API endpoint returns correct data
- [ ] Reduction applied for 'reduce' shifts (66.67%)
- [ ] Regular shifts unaffected (100%)
- [ ] CodeIgniter pipeline calls API successfully
- [ ] Attendance reports show reduced hours
- [ ] No errors in logs

---

## 7. Key Differences from Original Plan

| Aspect | Original Plan | Simplified Plan |
|--------|--------------|-----------------|
| **Database Columns** | 3 columns (shift_type, reduction_percentage, effective_from_date) | 1 column (shift_type only) |
| **Reduction Logic** | Configurable per shift in database | Hardcoded 66.67% in API |
| **Data Sync** | Separate databases with sync jobs | Same database, no sync needed |
| **eTime Integration** | Both CodeIgniter + Laravel | Laravel only |
| **Complexity** | High | Medium |
| **Flexibility** | High (configurable %) | Low (fixed %) |
| **Implementation Time** | 3 weeks | 1.5 weeks |

---

## 8. Advantages of Simplified Approach

✅ **Faster Implementation** - No complex data sync logic
✅ **Single Source of Truth** - One database for all data
✅ **Easier Debugging** - All data in one place
✅ **Lower Maintenance** - No sync monitoring needed
✅ **Cost Effective** - No separate database infrastructure
✅ **Sufficient for Current Needs** - Hardcoded 66.67% is acceptable

---

## 9. Future Enhancements (if needed)

If you need configurable reduction percentages later:

```sql
-- Add column later
ALTER TABLE shifts ADD COLUMN reduction_percentage DECIMAL(5,2) DEFAULT 66.67;

-- Update API to read from database instead of constant
// Change in AttendanceReductionService.php:
$reductionPct = $shift->reduction_percentage ?? self::REDUCE_SHIFT_PERCENTAGE;
```

---

## 10. Rollback Plan

If anything goes wrong:

1. Stop Laravel API: `Ctrl+C` or `systemctl stop attendance-api`
2. Remove pipeline pipe from `Processor.php`
3. Set all shifts back to 'regular': `UPDATE shifts SET shift_type = 'regular'`
4. Re-enable eTime sync in CodeIgniter
5. System works as before

---

## Summary

This simplified plan removes unnecessary complexity while achieving the core goal: reducing work hours for specific shift types. By hardcoding the 66.67% reduction and using the same database, we significantly reduce implementation time and maintenance burden.

**Total Implementation Time: ~1.5 weeks**
- Week 1: Laravel API + eTime sync
- Week 2: CodeIgniter integration + Testing
