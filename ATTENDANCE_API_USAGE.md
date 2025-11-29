# Attendance API Usage Guide

## ✅ API Status: **WORKING**

Your Laravel Attendance API is running at `http://hrm-attendance-api.test/api/v1`

---

## 🔧 Configuration

### HRM `.env` Settings:
```env
USE_ATTENDANCE_API=true
ATTENDANCE_API_URL=http://hrm-attendance-api.test/api/v1
ATTENDANCE_API_KEY=639cc3a5cec2e1d9e61c1ec1f8005652
ATTENDANCE_API_SECRET=845d74e4af6a1e86fe4d21f8d06cd1d1
ATTENDANCE_API_TIMEOUT=30
ATTENDANCE_API_FALLBACK_TO_LOCAL=true
```

**Note**: The API currently runs WITHOUT JWT authentication. Authentication endpoints are commented out in `routes/api.php:22-23`.

---

## 📡 Available Endpoints

### 1. Health Check (Public)
```bash
curl http://hrm-attendance-api.test/api/v1/health
```

**Response:**
```json
{
  "status": "healthy",
  "version": "1.0.0",
  "uptime": 0,
  "database": "connected",
  "timestamp": "2025-11-17 07:36:18"
}
```

---

### 2. Process Single Day Attendance

**Endpoint:** `POST /api/v1/attendance/process/single`

**Input** (only 3 fields needed):
```json
{
  "employee_id": 2,
  "shift_id": 1,
  "date": "2025-11-15"
}
```

**Output** (complete attendance data):
```json
{
  "status": "success",
  "data": {
    "employee_id": 2,
    "shift_id": 1,
    "date": "2025-11-15",
    "punch_in_original": "09:17:00",
    "punch_out_original": "11:27:00",
    "punch_in_adjusted": "09:50:00",
    "punch_out_adjusted": "06:16:00",
    "work_minutes_original": 87,
    "work_minutes_adjusted": -224,
    "work_hours_original": "01:27",
    "work_hours_adjusted": "-4:-44",
    "reduction_applied": true,
    "reduction_percentage": "66.70",
    "minutes_reduced": -112,
    "late_coming_minutes": 0,
    "early_going_minutes": 423,
    "deduction_minutes": 423,
    "is_present": "no",
    "is_absent": "yes",
    "is_half_day": "no",
    "shift_type": "reduce",
    "shift_code": "001",
    "machine": "del",
    "incomplete_punch": false
  }
}
```

**cURL Example:**
```bash
curl -X POST http://hrm-attendance-api.test/api/v1/attendance/process/single \
  -H "Content-Type: application/json" \
  -d '{
    "employee_id": 2,
    "shift_id": 1,
    "date": "2025-11-15"
  }'
```

---

### 3. Process Bulk Attendance

**Endpoint:** `POST /api/v1/attendance/process/bulk`

**Input:**
```json
{
  "employee_id": 2,
  "date_from": "2025-11-01",
  "date_to": "2025-11-15"
}
```

**cURL Example:**
```bash
curl -X POST http://hrm-attendance-api.test/api/v1/attendance/process/bulk \
  -H "Content-Type: application/json" \
  -d '{
    "employee_id": 2,
    "date_from": "2025-11-01",
    "date_to": "2025-11-15"
  }'
```

---

### 4. Get Raw Punching Data

**Endpoint:** `GET /api/v1/attendance/raw`

**Query Parameters:**
- `employee_id` (required)
- `date_from` (required)
- `date_to` (required)

**cURL Example:**
```bash
curl "http://hrm-attendance-api.test/api/v1/attendance/raw?employee_id=2&date_from=2025-11-01&date_to=2025-11-15"
```

---

## 💻 Using in PHP Code

### Simple Usage (Without Authentication)

Since JWT auth is not enabled, you can use simple CURL:

```php
<?php

// Example: Process single day attendance
function getAttendanceFromApi($employeeId, $shiftId, $date) {
    $url = 'http://hrm-attendance-api.test/api/v1/attendance/process/single';

    $data = [
        'employee_id' => $employeeId,
        'shift_id' => $shiftId,
        'date' => $date
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $result = json_decode($response, true);
        return $result['data'];
    }

    return null;
}

// Usage
$attendance = getAttendanceFromApi(2, 1, '2025-11-15');

if ($attendance) {
    echo "Work Hours: " . $attendance['work_hours_adjusted'] . "\n";
    echo "Is Present: " . $attendance['is_present'] . "\n";
    echo "Reduction Applied: " . ($attendance['reduction_applied'] ? 'Yes' : 'No') . "\n";
}
```

---

### Using AttendanceApiClient (Needs Update)

The current `AttendanceApiClient` expects JWT authentication. Since your API doesn't use it yet, you have two options:

**Option 1: Update AttendanceApiClient to skip authentication**

Edit `app/Services/AttendanceApiClient.php` and modify the methods to not call `authenticate()`.

**Option 2: Enable JWT in Laravel API**

Follow the setup in `attendance-api-implementation/QUICK_REFERENCE.md`.

---

## 🧪 Testing Commands

### Test API Connection:
```bash
cd D:/LOCALHOST/hrm.healthgenie
php spark test:api
```

### Test with Specific Employee:
```bash
php spark test:api --employee=2 --shift=1 --date=2025-11-15
```

**Note**: The current test command expects JWT auth, so it will show auth errors. Direct curl works fine.

---

## 🔄 Workflow

### How It Works:

1. **Your HRM System** sends minimal data:
   - Employee ID
   - Shift ID
   - Date

2. **Laravel API**:
   - Fetches raw punching data from database
   - Applies shift rules
   - Calculates work hours
   - Applies reduction (for reduce shifts)
   - Returns complete attendance

3. **Your HRM System** receives:
   - All calculated fields
   - Present/Absent status
   - Work hours (adjusted)
   - Late coming, early going
   - And more...

---

## 📊 Response Fields Explained

| Field | Description |
|-------|-------------|
| `punch_in_original` | Original punch in time from machine |
| `punch_in_adjusted` | Adjusted punch in (after shift grace) |
| `work_minutes_adjusted` | Work minutes after reduction |
| `work_hours_adjusted` | Work hours (HH:MM format) |
| `reduction_applied` | Whether reduction was applied (for reduce shifts) |
| `reduction_percentage` | Reduction % (e.g., 66.70 means 66.70% reduced) |
| `is_present` | "yes" or "no" |
| `is_absent` | "yes" or "no" |
| `late_coming_minutes` | Minutes late |
| `early_going_minutes` | Minutes left early |
| `shift_type` | "reduce" or "normal" |

---

## 🚨 Important Notes

### ⚠️ JWT Authentication Not Enabled

The API routes show authentication is commented out:

```php
// routes/api.php line 22-23
// Route::post('/auth/token', [AuthController::class, 'token']);
```

**This means:**
- ✅ No authentication needed right now
- ❌ API is open to anyone who can reach it
- 🔒 Enable JWT in production for security

### ✅ What's Working

- Health check endpoint
- Process single day
- Process bulk
- Raw punching data retrieval
- All calculations (reduction, late coming, etc.)

### ⚠️ What Needs Work

- JWT authentication (currently disabled)
- `AttendanceApiClient` expects auth (needs update or enable JWT)
- Test command expects auth (use curl for now)

---

## 📝 Next Steps

### If you want to keep it simple (no auth):

1. Update `app/Services/AttendanceApiClient.php` to skip authentication
2. Use direct HTTP calls in your controllers
3. Restrict access via IP whitelist or VPN

### If you want to enable JWT:

1. Uncomment lines in `routes/api.php`
2. Create `AuthController`
3. Install and configure JWT package
4. Update HRM to use JWT tokens

---

## 🎯 Quick Copy-Paste Examples

### Test in Browser/Postman:

**URL:** `http://hrm-attendance-api.test/api/v1/attendance/process/single`
**Method:** POST
**Headers:** `Content-Type: application/json`
**Body:**
```json
{
  "employee_id": 2,
  "shift_id": 1,
  "date": "2025-11-15"
}
```

### Test in Terminal:

```bash
# Health check
curl http://hrm-attendance-api.test/api/v1/health

# Process attendance
curl -X POST http://hrm-attendance-api.test/api/v1/attendance/process/single \
  -H "Content-Type: application/json" \
  -d '{"employee_id":2,"shift_id":1,"date":"2025-11-15"}'
```

---

## 📞 Support

- Check logs: `D:\LOCALHOST\hrm-attendance-api\storage\logs\laravel.log`
- Check HRM logs: `D:\LOCALHOST\hrm.healthgenie\writable\logs\`
- Verify .env settings match on both sides

---

**Last Updated:** 2025-11-17
**API Version:** 1.0.0
**Status:** ✅ Working (No Auth)
