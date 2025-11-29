# Sub-Shift Types API Documentation

## Overview

This API provides RESTful endpoints for managing and querying **sub-shift types** (regular vs reduce shifts) with percentage-based attendance reduction. The API is designed for external integrations and uses API key authentication.

### Key Features
- **Regular Shifts**: Work hours counted as-is (e.g., 12h = 12h)
- **Reduce Shifts**: Work hours reduced by percentage (e.g., 12h → 8h with 33.33% reduction)
- **API Key Authentication**: Secure server-to-server communication
- **Read-Only Operations**: Query shifts and attendance data
- **Zero Portal Impact**: Existing HRM portal remains unchanged

---

## Table of Contents
1. [Authentication](#authentication)
2. [API Endpoints](#api-endpoints)
   - [Shift Management](#shift-management)
   - [Attendance Queries](#attendance-queries)
3. [Response Format](#response-format)
4. [Error Codes](#error-codes)
5. [Usage Examples](#usage-examples)
6. [Implementation Guide](#implementation-guide)
7. [Database Schema](#database-schema)

---

## Authentication

All API requests require authentication via API key.

### Header
```
X-API-Key: your_api_key_here
```

### Creating API Keys

API keys must be created by administrators in the database:

```sql
INSERT INTO api_keys (
    api_key,
    name,
    description,
    permissions,
    is_active,
    created_at
) VALUES (
    'your_64_character_hex_key_here',
    'External Integration',
    'API key for payroll system integration',
    '["shifts.read", "employees.read", "attendance.read"]',
    'yes',
    NOW()
);
```

### Permissions

Available permissions:
- `shifts.read`: Access to shift endpoints
- `employees.read`: Access to employee data
- `attendance.read`: Access to attendance data
- `*`: Full access (wildcard)

### Example Request
```bash
curl -H "X-API-Key: your_api_key_here" \
     http://yourdomain.com/api/v1/shifts
```

---

## API Endpoints

### Base URL
```
http://yourdomain.com/api/v1/
```

---

## Shift Management

### 1. List All Shifts

**Endpoint**: `GET /api/v1/shifts`

**Description**: Retrieve all shifts with their types and reduction settings.

**Query Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `page` | integer | No | Page number for pagination (default: no pagination) |
| `per_page` | integer | No | Items per page (default: 50) |

**Response**:
```json
{
  "status": "success",
  "message": "Shifts retrieved successfully",
  "data": {
    "shifts": [
      {
        "id": 1,
        "shift_code": "DS",
        "shift_name": "Day Shift",
        "shift_type": "regular",
        "reduction_percentage": 0.00,
        "reduction_remarks": null,
        "weekoff": ["sunday"],
        "is_reduce_shift": false
      },
      {
        "id": 5,
        "shift_code": "NS",
        "shift_name": "Night Shift",
        "shift_type": "reduce",
        "reduction_percentage": 33.33,
        "reduction_remarks": "12 hours counted as 8 hours",
        "weekoff": ["sunday"],
        "is_reduce_shift": true
      }
    ],
    "total": 2
  },
  "timestamp": "2025-01-07 10:00:00"
}
```

**Example**:
```bash
curl -H "X-API-Key: your_api_key" \
     "http://yourdomain.com/api/v1/shifts"
```

---

### 2. Get Shift Details

**Endpoint**: `GET /api/v1/shifts/{shift_id}`

**Description**: Get detailed information about a specific shift including per-day timings and reduction calculations.

**Path Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `shift_id` | integer | Yes | Shift ID |

**Response**:
```json
{
  "status": "success",
  "message": "Shift details retrieved successfully",
  "data": {
    "shift": {
      "id": 5,
      "shift_code": "NS",
      "shift_name": "Night Shift",
      "shift_type": "reduce",
      "reduction_percentage": 33.33,
      "reduction_remarks": "12 hours counted as 8 hours",
      "weekoff": ["sunday"],
      "is_reduce_shift": true,
      "shift_duration_hours": 12,
      "reduced_duration_hours": 8,
      "timings": {
        "monday": {
          "shift_start": "20:00:00",
          "shift_end": "08:00:00",
          "lunch_start": null,
          "lunch_end": null
        },
        "tuesday": {
          "shift_start": "20:00:00",
          "shift_end": "08:00:00",
          "lunch_start": null,
          "lunch_end": null
        }
      }
    },
    "attendance_rules": {
      "late_coming_rule": [
        {
          "name": "Daily Grace",
          "hours": "00:15:00",
          "applicable": "Daily"
        }
      ],
      "attendance_rule": {
        "absent_for_work_hours": "04:00:00",
        "half_day_for_work_hours": "06:00:00"
      }
    }
  },
  "timestamp": "2025-01-07 10:00:00"
}
```

**Example**:
```bash
curl -H "X-API-Key: your_api_key" \
     "http://yourdomain.com/api/v1/shifts/5"
```

---

### 3. List Reduce Shifts Only

**Endpoint**: `GET /api/v1/shifts/reduce`

**Description**: Get only shifts with reduction applied (shift_type = 'reduce').

**Response**:
```json
{
  "status": "success",
  "message": "Reduce shifts retrieved successfully",
  "data": {
    "reduce_shifts": [
      {
        "id": 5,
        "shift_code": "NS",
        "shift_name": "Night Shift",
        "reduction_percentage": 33.33,
        "reduction_factor": 0.6667,
        "reduction_remarks": "12 hours counted as 8 hours",
        "example": {
          "actual_hours": 12,
          "counted_as_hours": 8
        }
      }
    ],
    "total": 1
  },
  "timestamp": "2025-01-07 10:00:00"
}
```

**Example**:
```bash
curl -H "X-API-Key: your_api_key" \
     "http://yourdomain.com/api/v1/shifts/reduce"
```

---

### 4. Get Employee's Assigned Shift

**Endpoint**: `GET /api/v1/employees/{employee_id}/shift`

**Description**: Get the shift assigned to a specific employee with reduction information.

**Path Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `employee_id` | integer | Yes | Employee ID |

**Response**:
```json
{
  "status": "success",
  "message": "Employee shift information retrieved successfully",
  "data": {
    "employee": {
      "id": 123,
      "emp_code": "EMP123",
      "name": "John Doe",
      "designation": "Production Staff"
    },
    "shift": {
      "id": 5,
      "shift_code": "NS",
      "shift_name": "Night Shift",
      "shift_type": "reduce",
      "reduction_percentage": 33.33,
      "reduction_remarks": "12 hours counted as 8 hours",
      "is_reduce_shift": true
    },
    "calculation_info": {
      "formula": "reduced_hours = actual_hours × 0.6667",
      "example": {
        "actual_work_hours": 12,
        "reduction_percentage": 33.33,
        "reduced_work_hours": 8,
        "explanation": "If employee works 12 hours, it will be counted as 8 hours for payroll"
      }
    }
  },
  "timestamp": "2025-01-07 10:00:00"
}
```

**Example**:
```bash
curl -H "X-API-Key: your_api_key" \
     "http://yourdomain.com/api/v1/employees/123/shift"
```

---

## Attendance Queries

### 5. Get Employee Attendance

**Endpoint**: `GET /api/v1/attendance/{employee_id}`

**Description**: Get detailed attendance records for an employee with reduction already applied.

**Path Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `employee_id` | integer | Yes | Employee ID |

**Query Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `from_date` | date | No | Start date (YYYY-MM-DD, default: current month start) |
| `to_date` | date | No | End date (YYYY-MM-DD, default: current month end) |

**Response**:
```json
{
  "status": "success",
  "message": "Attendance data retrieved successfully",
  "data": {
    "employee": {
      "id": 123,
      "emp_code": "EMP123",
      "name": "John Doe"
    },
    "date_range": {
      "from": "2025-01-01",
      "to": "2025-01-31"
    },
    "shift": {
      "shift_id": 5,
      "shift_code": "NS",
      "shift_name": "Night Shift",
      "shift_type": "reduce",
      "reduction_percentage": 33.33,
      "is_reduce_shift": true,
      "reduction_info": {
        "reduction_factor": 0.6667,
        "explanation": "Work hours are multiplied by 0.6667 for payroll calculation",
        "note": "The work_hours and paid days shown above already have the reduction applied"
      }
    },
    "summary": {
      "total_days": 31,
      "present_days": 22,
      "absent_days": 4,
      "half_days": 1,
      "total_paid_days": 22.5,
      "total_work_hours": 176,
      "average_work_hours_per_day": 5.68
    },
    "attendance": [
      {
        "date": "2025-01-01",
        "day": "Wednesday",
        "shift_start": "08:00 PM",
        "shift_end": "08:00 AM",
        "punch_in_time": "08:05 PM",
        "punch_out_time": "08:10 AM",
        "work_hours": "08:05",
        "work_hours_decimal": 8.08,
        "status": "P",
        "status_remarks": "Present",
        "paid": 1.0,
        "late_coming_minutes": 5,
        "early_going_minutes": 0,
        "leave_type": null,
        "reduction_applied": true
      },
      {
        "date": "2025-01-02",
        "day": "Thursday",
        "shift_start": "08:00 PM",
        "shift_end": "08:00 AM",
        "punch_in_time": null,
        "punch_out_time": null,
        "work_hours": "00:00",
        "work_hours_decimal": 0,
        "status": "A",
        "status_remarks": "Absent",
        "paid": 0.0,
        "late_coming_minutes": 0,
        "early_going_minutes": 0,
        "leave_type": null,
        "reduction_applied": false
      }
    ]
  },
  "timestamp": "2025-01-07 10:00:00"
}
```

**Example**:
```bash
curl -H "X-API-Key: your_api_key" \
     "http://yourdomain.com/api/v1/attendance/123?from_date=2025-01-01&to_date=2025-01-31"
```

---

### 6. Get Attendance Summary

**Endpoint**: `GET /api/v1/attendance/summary/{employee_id}`

**Description**: Get attendance summary without detailed daily records (faster query).

**Path Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `employee_id` | integer | Yes | Employee ID |

**Query Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `from_date` | date | No | Start date (YYYY-MM-DD, default: current month start) |
| `to_date` | date | No | End date (YYYY-MM-DD, default: current month end) |

**Response**:
```json
{
  "status": "success",
  "message": "Attendance summary retrieved successfully",
  "data": {
    "employee": {
      "id": 123,
      "emp_code": "EMP123",
      "name": "John Doe"
    },
    "date_range": {
      "from": "2025-01-01",
      "to": "2025-01-31"
    },
    "shift": {
      "shift_code": "NS",
      "shift_name": "Night Shift",
      "shift_type": "reduce",
      "reduction_percentage": 33.33,
      "is_reduce_shift": true
    },
    "summary": {
      "total_days": 31,
      "present_days": 22,
      "absent_days": 4,
      "half_days": 1,
      "total_paid_days": 22.5,
      "total_late_minutes": 150,
      "total_early_minutes": 45
    }
  },
  "timestamp": "2025-01-07 10:00:00"
}
```

**Example**:
```bash
curl -H "X-API-Key: your_api_key" \
     "http://yourdomain.com/api/v1/attendance/summary/123?from_date=2025-01-01&to_date=2025-01-31"
```

---

## Response Format

### Success Response Structure
```json
{
  "status": "success",
  "message": "Descriptive success message",
  "data": {
    // Response data
  },
  "timestamp": "2025-01-07 10:00:00"
}
```

### Error Response Structure
```json
{
  "status": "error",
  "message": "Descriptive error message",
  "errors": {
    // Error details (optional)
  },
  "timestamp": "2025-01-07 10:00:00"
}
```

---

## Error Codes

| HTTP Code | Status | Description |
|-----------|--------|-------------|
| 200 | Success | Request successful |
| 400 | Bad Request | Invalid parameters or request format |
| 401 | Unauthorized | Missing or invalid API key |
| 403 | Forbidden | Insufficient permissions or IP not whitelisted |
| 404 | Not Found | Resource not found (employee, shift, etc.) |
| 422 | Validation Error | Request validation failed |
| 500 | Server Error | Internal server error |

### Common Error Responses

**Missing API Key (401)**:
```json
{
  "status": "error",
  "message": "API key is required. Please provide X-API-Key header.",
  "errors": null,
  "timestamp": "2025-01-07 10:00:00"
}
```

**Invalid API Key (401)**:
```json
{
  "status": "error",
  "message": "Invalid or expired API key.",
  "errors": null,
  "timestamp": "2025-01-07 10:00:00"
}
```

**Insufficient Permissions (403)**:
```json
{
  "status": "error",
  "message": "You do not have permission to read shifts.",
  "errors": null,
  "timestamp": "2025-01-07 10:00:00"
}
```

**Resource Not Found (404)**:
```json
{
  "status": "error",
  "message": "Employee not found",
  "errors": null,
  "timestamp": "2025-01-07 10:00:00"
}
```

**Validation Error (422)**:
```json
{
  "status": "error",
  "message": "Missing required parameters: from_date, to_date",
  "errors": {
    "missing_fields": ["from_date", "to_date"]
  },
  "timestamp": "2025-01-07 10:00:00"
}
```

---

## Usage Examples

### Example 1: Check if Employee has Reduce Shift

```bash
#!/bin/bash

API_KEY="your_api_key_here"
BASE_URL="http://yourdomain.com/api/v1"
EMPLOYEE_ID=123

# Get employee's shift
response=$(curl -s -H "X-API-Key: $API_KEY" \
  "$BASE_URL/employees/$EMPLOYEE_ID/shift")

# Parse response
shift_type=$(echo $response | jq -r '.data.shift.shift_type')
reduction_percentage=$(echo $response | jq -r '.data.shift.reduction_percentage')

if [ "$shift_type" = "reduce" ]; then
  echo "Employee has reduce shift with $reduction_percentage% reduction"
else
  echo "Employee has regular shift"
fi
```

---

### Example 2: Calculate Monthly Payroll with Reduction

```python
import requests
from datetime import datetime

API_KEY = "your_api_key_here"
BASE_URL = "http://yourdomain.com/api/v1"
HEADERS = {"X-API-Key": API_KEY}

def get_employee_attendance(employee_id, year, month):
    """Get attendance summary for payroll calculation"""
    from_date = f"{year}-{month:02d}-01"
    # Simple end date calculation (assume 31 days)
    to_date = f"{year}-{month:02d}-31"

    url = f"{BASE_URL}/attendance/summary/{employee_id}"
    params = {"from_date": from_date, "to_date": to_date}

    response = requests.get(url, headers=HEADERS, params=params)
    data = response.json()

    if data['status'] == 'success':
        return data['data']
    else:
        raise Exception(data['message'])

def calculate_salary(employee_id, monthly_salary, year, month):
    """Calculate monthly salary with reduction applied"""
    attendance = get_employee_attendance(employee_id, year, month)

    # Total paid days already have reduction applied
    total_paid_days = attendance['summary']['total_paid_days']

    # Get days in month
    days_in_month = 31  # Simplified

    # Calculate per-day rate
    per_day_salary = monthly_salary / days_in_month

    # Calculate actual salary
    actual_salary = per_day_salary * total_paid_days

    return {
        "employee_id": employee_id,
        "month": f"{year}-{month:02d}",
        "monthly_salary": monthly_salary,
        "days_in_month": days_in_month,
        "total_paid_days": total_paid_days,
        "per_day_salary": round(per_day_salary, 2),
        "actual_salary": round(actual_salary, 2),
        "shift_type": attendance['shift']['shift_type'],
        "reduction_applied": attendance['shift']['is_reduce_shift']
    }

# Example usage
result = calculate_salary(
    employee_id=123,
    monthly_salary=30000,
    year=2025,
    month=1
)

print(f"Employee: {result['employee_id']}")
print(f"Shift Type: {result['shift_type']}")
print(f"Paid Days: {result['total_paid_days']}")
print(f"Actual Salary: ₹{result['actual_salary']}")
```

---

### Example 3: Generate Attendance Report with Reduction Details

```javascript
const axios = require('axios');

const API_KEY = 'your_api_key_here';
const BASE_URL = 'http://yourdomain.com/api/v1';
const headers = { 'X-API-Key': API_KEY };

async function generateAttendanceReport(employeeId, fromDate, toDate) {
  try {
    // Get detailed attendance
    const response = await axios.get(
      `${BASE_URL}/attendance/${employeeId}`,
      {
        headers,
        params: { from_date: fromDate, to_date: toDate }
      }
    );

    const data = response.data.data;
    const employee = data.employee;
    const shift = data.shift;
    const summary = data.summary;
    const attendance = data.attendance;

    console.log('='.repeat(60));
    console.log(`Attendance Report: ${employee.name} (${employee.emp_code})`);
    console.log(`Period: ${fromDate} to ${toDate}`);
    console.log('='.repeat(60));

    console.log(`\nShift: ${shift.shift_name} (${shift.shift_type})`);

    if (shift.is_reduce_shift) {
      console.log(`⚠️  REDUCTION APPLIED: ${shift.reduction_percentage}%`);
      console.log(shift.reduction_info.explanation);
      console.log(shift.reduction_info.note);
    }

    console.log(`\nSummary:`);
    console.log(`  Total Days: ${summary.total_days}`);
    console.log(`  Present: ${summary.present_days}`);
    console.log(`  Absent: ${summary.absent_days}`);
    console.log(`  Half Days: ${summary.half_days}`);
    console.log(`  Total Paid Days: ${summary.total_paid_days}`);
    console.log(`  Total Work Hours: ${summary.total_work_hours}`);

    console.log(`\nDaily Breakdown:`);
    attendance.forEach(day => {
      const symbol = day.reduction_applied ? '*' : ' ';
      console.log(
        `  ${day.date} (${day.day}): ${day.status} - ` +
        `${day.work_hours} hrs${symbol} - Paid: ${day.paid}`
      );
    });

    if (shift.is_reduce_shift) {
      console.log(`\n* Reduction applied to work hours`);
    }

  } catch (error) {
    console.error('Error:', error.response?.data?.message || error.message);
  }
}

// Example usage
generateAttendanceReport(123, '2025-01-01', '2025-01-31');
```

---

## Implementation Guide

### Step 1: Run Database Migrations

```bash
# Navigate to project directory
cd /path/to/hrm.healthgenie

# Run migrations
php spark migrate

# Verify migrations
php spark migrate:status
```

**Expected migrations**:
- `2025-01-07-100000_AddShiftTypeToShifts`
- `2025-01-07-100100_CreateApiKeysTable`
- `2025-01-07-100200_AddShiftReductionFieldsToPreFinalPaidDays`

---

### Step 2: Create API Key

```sql
-- Generate API key (use a secure random generator)
-- Example: openssl rand -hex 32

INSERT INTO api_keys (
    api_key,
    name,
    description,
    permissions,
    is_active,
    created_at
) VALUES (
    'a1b2c3d4e5f6...your_64_char_key',
    'Payroll Integration',
    'API key for external payroll system',
    '["*"]',
    'yes',
    NOW()
);
```

---

### Step 3: Configure Shift as Reduce Shift

```sql
-- Update existing shift to reduce type
UPDATE shifts
SET
    shift_type = 'reduce',
    reduction_percentage = 33.33,
    reduction_remarks = '12 hours counted as 8 hours for payroll'
WHERE shift_code = 'NS';  -- Night Shift
```

---

### Step 4: Assign Employees to Reduce Shift

```sql
-- Assign employees to reduce shift
UPDATE employees
SET shift_id = 5  -- ID of reduce shift
WHERE department_id = 3;  -- Example: Production department
```

---

### Step 5: Test API Endpoints

```bash
# Test authentication
curl -H "X-API-Key: your_key" \
     http://yourdomain.com/api/v1/shifts

# Test shift details
curl -H "X-API-Key: your_key" \
     http://yourdomain.com/api/v1/shifts/5

# Test employee shift
curl -H "X-API-Key: your_key" \
     http://yourdomain.com/api/v1/employees/123/shift

# Test attendance
curl -H "X-API-Key: your_key" \
     "http://yourdomain.com/api/v1/attendance/123?from_date=2025-01-01&to_date=2025-01-31"
```

---

### Step 6: Process Attendance

```bash
# Process attendance for employees with reduce shift
php spark attendance:process --month=2025-01

# Verify reduction was applied
# Check pre_final_paid_days table for:
# - shift_reduction_applied = 'yes'
# - shift_reduction_original_minutes (original hours)
# - work_hours (reduced hours)
```

---

## Database Schema

### shifts Table (Modified)

```sql
CREATE TABLE shifts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shift_code VARCHAR(10) NOT NULL,
    shift_name VARCHAR(100) NOT NULL,
    shift_type ENUM('regular', 'reduce') DEFAULT 'regular',
    reduction_percentage DECIMAL(5,2) DEFAULT 0.00,
    reduction_remarks VARCHAR(255) NULL,
    weekoff JSON,
    in_time TIME,
    out_time TIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

### api_keys Table (New)

```sql
CREATE TABLE api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_key VARCHAR(64) UNIQUE NOT NULL,
    api_secret VARCHAR(128),
    name VARCHAR(100) NOT NULL,
    description TEXT,
    permissions JSON,
    is_active ENUM('yes', 'no') DEFAULT 'yes',
    expires_at DATETIME,
    last_used_at DATETIME,
    ip_whitelist TEXT,
    created_by INT,
    created_at DATETIME,
    updated_at DATETIME,
    INDEX (api_key),
    INDEX (is_active)
);
```

---

### pre_final_paid_days Table (Modified)

```sql
ALTER TABLE pre_final_paid_days
ADD COLUMN shift_reduction_applied ENUM('yes', 'no') DEFAULT 'no',
ADD COLUMN shift_reduction_original_minutes INT,
ADD COLUMN shift_reduction_percentage DECIMAL(5,2),
ADD COLUMN shift_reduction_factor DECIMAL(5,4);
```

**Example Record**:
```sql
SELECT
    employee_id,
    date,
    status,
    shift_reduction_original_minutes,  -- 720 (12 hours)
    work_hours,                        -- 08:00 (8 hours)
    shift_reduction_percentage,        -- 33.33
    shift_reduction_applied,           -- yes
    paid                               -- 1.0
FROM pre_final_paid_days
WHERE employee_id = 123 AND date = '2025-01-15';
```

---

## Reduction Calculation Logic

### Formula

```
Reduction Factor = (100 - Reduction Percentage) / 100
Reduced Minutes = Original Minutes × Reduction Factor
```

### Examples

**Example 1: 33.33% Reduction (12h → 8h)**
```
Original Hours: 12 hours = 720 minutes
Reduction %: 33.33%
Reduction Factor: (100 - 33.33) / 100 = 0.6667
Reduced Minutes: 720 × 0.6667 = 480 minutes = 8 hours
```

**Example 2: 25% Reduction (12h → 9h)**
```
Original Hours: 12 hours = 720 minutes
Reduction %: 25%
Reduction Factor: (100 - 25) / 100 = 0.75
Reduced Minutes: 720 × 0.75 = 540 minutes = 9 hours
```

**Example 3: 40% Reduction (10h → 6h)**
```
Original Hours: 10 hours = 600 minutes
Reduction %: 40%
Reduction Factor: (100 - 40) / 100 = 0.60
Reduced Minutes: 600 × 0.60 = 360 minutes = 6 hours
```

---

## Best Practices

### 1. API Key Security
- Store API keys securely (environment variables, secrets manager)
- Use HTTPS in production
- Rotate keys periodically
- Set expiration dates
- Implement IP whitelisting

### 2. Error Handling
```python
try:
    response = requests.get(url, headers=headers)
    response.raise_for_status()
    data = response.json()

    if data['status'] != 'success':
        # Handle API-level errors
        logging.error(f"API Error: {data['message']}")
except requests.exceptions.RequestException as e:
    # Handle HTTP errors
    logging.error(f"HTTP Error: {str(e)}")
```

### 3. Caching
- Cache shift details (changes infrequently)
- Cache employee-shift assignments
- Invalidate cache when shifts are updated

### 4. Rate Limiting
- Implement rate limiting on client side
- Batch requests when possible
- Use summary endpoint for lighter queries

---

## Support & Troubleshooting

### Common Issues

**Issue**: API returns 401 Unauthorized
- **Solution**: Check X-API-Key header is correctly set
- **Solution**: Verify API key exists and is active in database

**Issue**: Reduction not showing in attendance
- **Solution**: Ensure shift_type = 'reduce' in shifts table
- **Solution**: Ensure reduction_percentage > 0
- **Solution**: Re-process attendance: `php spark attendance:process`

**Issue**: Work hours don't match expected reduction
- **Solution**: Check reduction_percentage value
- **Solution**: Verify `shift_reduction_applied = 'yes'` in pre_final_paid_days
- **Solution**: Check `shift_reduction_original_minutes` vs `work_hours`

---

## Changelog

### Version 1.0.0 (2025-01-07)
- Initial release
- Sub-shift types (regular/reduce) implementation
- API key authentication
- Read-only shift and attendance endpoints
- Percentage-based work hours reduction
- Zero impact on existing HRM portal

---

## API Version

**Current Version**: v1
**Base Path**: `/api/v1/`
**Last Updated**: 2025-01-07

---

**End of Documentation**
