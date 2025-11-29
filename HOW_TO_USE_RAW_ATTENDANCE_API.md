# How to Fetch Raw Attendance Data from API in HRM

## 📍 Current Implementation

Your HRM already has API integration in `app/Helpers/Config_defaults_helper.php` at **lines 367-401**.

---

## ✅ **How It Works**

The function `get_raw_punching_data()` automatically checks your `.env` and if API is configured, it fetches data from:

```
http://hrm-attendance-api.test/api/v1/attendance/raw
```

---

## 📝 **Usage Examples**

### **Example 1: Get Raw Data for One Employee**

```php
<?php

// In any controller or view
$empCode = 'HG001';  // Internal employee ID
$fromDate = '2025-11-01';
$toDate = '2025-11-30';

// This function automatically uses API if configured
$rawDataJson = get_raw_punching_data($empCode, $fromDate, $toDate);

// Decode JSON to array
$data = json_decode($rawDataJson, true);
$punchingData = $data['InOutPunchData'];

// Use the data
foreach ($punchingData as $row) {
    echo "Date: " . $row['DateString_2'] . "<br>";
    echo "In: " . $row['INTime'] . " | Out: " . $row['OUTTime'] . "<br>";
    echo "Machine: " . $row['machine'] . "<br><br>";
}
```

**Output Data Structure:**
```php
[
    [
        'Empcode' => 'HG001',
        'INTime' => '09:17:00',
        'OUTTime' => '18:25:00',
        'Remark' => '',
        'DateString' => '01-11-2025',
        'DateString_2' => '2025-11-01',
        'machine' => 'del',
        'default_machine' => 'del',
        'override_machine' => ''
    ],
    // ... more days
]
```

---

### **Example 2: Get Data for All Employees**

```php
<?php

// Get all employees' data
$rawDataJson = get_raw_punching_data('ALL', '2025-11-15', '2025-11-15');
$data = json_decode($rawDataJson, true);
$punchingData = $data['InOutPunchData'];

// Process all employees
$summary = [];
foreach ($punchingData as $row) {
    $empCode = $row['Empcode'];

    if (!isset($summary[$empCode])) {
        $summary[$empCode] = [
            'present_days' => 0,
            'total_days' => 0
        ];
    }

    $summary[$empCode]['total_days']++;
    if ($row['INTime'] !== '--:--') {
        $summary[$empCode]['present_days']++;
    }
}

print_r($summary);
```

---

### **Example 3: Use in a Controller**

```php
<?php

namespace App\Controllers;

class AttendanceController extends BaseController
{
    public function viewRawData($employeeId)
    {
        $EmployeeModel = new \App\Models\EmployeeModel();
        $employee = $EmployeeModel->find($employeeId);

        // Get raw data for current month
        $fromDate = date('Y-m-01');
        $toDate = date('Y-m-d');

        $rawDataJson = get_raw_punching_data(
            $employee['internal_employee_id'],
            $fromDate,
            $toDate
        );

        $data = json_decode($rawDataJson, true);

        return view('attendance/raw_data', [
            'employee' => $employee,
            'punching_data' => $data['InOutPunchData'],
            'from_date' => $fromDate,
            'to_date' => $toDate
        ]);
    }
}
```

---

## ⚠️ **Current Issue in Your Code**

### **Line 403 - Variable Name Mismatch**

```php
// Line 398-400: Data is fetched into $InOutPunchData
if (isset($data['data']) && is_array($data['data'])) {
    $InOutPunchData = $data['data'];
}

// Line 403: But then it checks wrong variable name!
if (!empty($InOutPunchData__Del__GGN_Noida)) {  // ❌ WRONG VARIABLE
    // ... processing code
}
```

**This means:** Your API is being called, but the data is being ignored!

---

## 🔧 **How to Fix**

You need to update line 403 to use the correct variable. Here's what it should look like:

**Current Code (Line 403):**
```php
if (!empty($InOutPunchData__Del__GGN_Noida)) {
```

**Should be:**
```php
if (!empty($InOutPunchData)) {
```

**And line 467:**
```php
$data['InOutPunchData'] = !empty($InOutPunchData__Del__GGN_Noida) ? $InOutPunchData__Del__GGN_Noida : [];
```

**Should be:**
```php
$data['InOutPunchData'] = !empty($InOutPunchData) ? $InOutPunchData : [];
```

---

## 🎯 **Recommended Approach**

Create a new helper function specifically for API raw data:

```php
<?php

/**
 * Fetch raw attendance from API (simplified, no machine override logic)
 */
if (!function_exists('get_raw_attendance_api_simple')) {
    function get_raw_attendance_api_simple($empCode, $fromDate, $toDate)
    {
        $apiUrl = env('ATTENDANCE_API_URL');

        if (empty($apiUrl)) {
            return ['InOutPunchData' => []];
        }

        $url = $apiUrl . '/attendance/raw';
        $queryParams = [
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ];

        if ($empCode !== 'ALL') {
            $queryParams['employee_id'] = $empCode;  // Note: API expects employee_id
        }

        $fullUrl = $url . '?' . http_build_query($queryParams);

        $ch = curl_init($fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);
            return $result['data'] ?? [];
        }

        log_message('error', "API failed: HTTP {$httpCode}");
        return [];
    }
}
```

---

## 📊 **API Response Format**

When you call the API, you get:

```json
{
  "status": "success",
  "data": [
    {
      "Empcode": "HG001",
      "INTime": "09:17:00",
      "OUTTime": "18:25:00",
      "Remark": "",
      "DateString": "15-11-2025",
      "DateString_2": "2025-11-15",
      "machine": "del",
      "default_machine": "del",
      "override_machine": ""
    }
  ]
}
```

---

## 🔄 **How Data Flow Works**

### **Current Flow:**

1. Your HRM calls `get_raw_punching_data()`
2. Function checks if API is configured in `.env`
3. If configured, makes CURL request to API
4. API returns raw punching data
5. Data is stored in `$InOutPunchData` variable
6. ⚠️ **BUT** code then checks wrong variable name
7. So API data is lost and old eTime logic runs instead

### **Fixed Flow:**

1. Your HRM calls `get_raw_punching_data()`
2. Function checks if API is configured in `.env`
3. If configured, makes CURL request to API
4. API returns raw punching data
5. Data is stored in `$InOutPunchData` variable
6. ✅ Code uses `$InOutPunchData` correctly
7. API data is processed and returned

---

## 🧪 **Test Your API Integration**

### **Test 1: Check if API is being called**

```php
// Add this temporarily to line 400 in Config_defaults_helper.php
if (isset($data['data']) && is_array($data['data'])) {
    $InOutPunchData = $data['data'];
    log_message('info', 'API returned ' . count($InOutPunchData) . ' records');
}
```

Then check your logs at `writable/logs/log-2025-11-17.log`

### **Test 2: Direct API test**

```bash
curl "http://hrm-attendance-api.test/api/v1/attendance/raw?employee_id=2&date_from=2025-11-15&date_to=2025-11-15"
```

---

## 📋 **Summary**

### ✅ **What's Working:**

1. Your `.env` is configured correctly
2. API is running at `http://hrm-attendance-api.test`
3. The `get_raw_punching_data()` function calls the API
4. API returns data successfully

### ⚠️ **What Needs Fixing:**

1. **Line 403**: Change `$InOutPunchData__Del__GGN_Noida` to `$InOutPunchData`
2. **Line 467**: Same fix needed

### 💡 **Best Practice:**

Create a simple wrapper function (shown above) that:
- Only calls the API
- Returns clean data
- No complex machine override logic
- Easy to test and debug

---

## 🎯 **Quick Fix Guide**

**Option 1: Fix existing code (2 lines to change)**
- Line 403: Change variable name
- Line 467: Change variable name

**Option 2: Create new simple function**
- Add the `get_raw_attendance_api_simple()` function above
- Use it in new code
- Keep old function for backward compatibility

---

## 📞 **Next Steps**

Would you like me to:

1. ✏️ Fix the two lines in `Config_defaults_helper.php`
2. 📝 Create a new simple API helper function
3. 🧪 Create a test page to verify API is working
4. 📚 Show you how to integrate this in attendance processing

Let me know what you'd like to do!

---

**Last Updated:** 2025-11-17
**File:** `app/Helpers/Config_defaults_helper.php`
**Lines:** 367-401 (API call), 403 (needs fix), 467 (needs fix)
