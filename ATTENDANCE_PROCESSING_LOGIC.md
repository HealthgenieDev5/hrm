# Attendance Processing Logic Documentation

## Table of Contents
1. [System Overview](#system-overview)
2. [Controllers](#controllers)
3. [Models & Database Schema](#models--database-schema)
4. [Processing Pipeline](#processing-pipeline)
5. [Core Business Logic](#core-business-logic)
6. [Attendance Status Codes](#attendance-status-codes)
7. [Helper Functions](#helper-functions)
8. [CLI Commands](#cli-commands)
9. [Integrations](#integrations)
10. [Configuration & Customization](#configuration--customization)
11. [Complete Processing Workflow](#complete-processing-workflow)

---

## System Overview

The HRM Attendance Processing System is a sophisticated multi-layered pipeline that transforms raw biometric punch data into finalized attendance records for payroll processing. The system integrates with:

- **eTimeOffice** (biometric system)
- **Leave Management System**
- **On-Duty (OD) Request System**
- **Payroll Processing System**

### Architecture Components

```
Raw Biometric Data → Processing Pipeline → Pre-Final Attendance → Salary Lock → Final Attendance
```

**Key Features:**
- Automatic late coming detection with grace adjustment
- Night shift support with cross-midnight calculations
- Sandwich leave detection
- Manual override capabilities
- Comp-off and wave-off integration
- OD (On-Duty) time integration
- Configurable shift-based rules
- Batch processing for scalability

---

## Controllers

### 1. Attendance\Processor.php
**Location**: `app/Controllers/Attendance/Processor.php`

**Purpose**: Core processor for attendance data transformation and calculation

**Key Methods**:

#### `getProcessedPunchingData($employee_id, $dateFrom, $dateTo)`
Main entry point for processing attendance data for a specific employee and date range.

**Returns**: Processed attendance array with all calculated fields

#### `ProcessPunchingData()`
Orchestrates the complete pipeline processing flow.

**Pipeline Stages**:
1. `BasicDetails` - Load employee and shift information
2. `ShiftRulesAndDetails` - Load shift rules and schedules
3. `GetAttendanceClean` - Fetch and clean raw punching data
4. `ProcessAttendance` - Apply business logic per punch row
5. `LateComingAdjustment` - Apply grace adjustment logic
6. `SandwichSecondPass` - Detect sandwich leaves
7. `ApplyAttendanceOverride` - Apply manual overrides
8. `AdjustLastWorkingDate` - Mark dates after employee leaving

#### `getProcessedDashboardData()`
Real-time dashboard attendance processing for current month.

---

### 2. Override\Attendance.php
**Location**: `app/Controllers/Override/Attendance.php`

**Purpose**: Manual attendance override management (Admin only)

**Key Methods**:

#### `index()`
Display override interface with employee search and date filters.

**Access Control**: Restricted to administrators only

#### `overrideAttendance()`
Create manual attendance corrections.

**Parameters**:
- `employee_id`: Target employee
- `attendance`: New status code (P, A, H/D, etc.)
- `attendance_date`: Date to override
- `remarks`: Reason for override

**Validation**: Prevents overriding salary-locked months

#### `getAttendanceOverrideAll()`
List all overrides with employee details.

#### `deleteAttendanceOverride()`
Delete overrides with revision tracking for audit trail.

---

### 3. Reports\FinalAttendance.php
**Location**: `app/Controllers/Reports/FinalAttendance.php`

**Purpose**: Final attendance report generation

**Key Methods**:

#### `index()`
Attendance report interface with filters:
- Employee selection
- Date range
- Department/Unit filters

#### `generateAttendance($employee_id)`
Generate processed attendance for single employee.

**Workflow**:
1. Check salary lock status
2. Fetch raw attendance from eTimeOffice
3. Process through pipeline
4. Save to `pre_final_paid_days`

#### `attendanceSheet()`
Bulk attendance sheet generation for multiple employees.

---

### 4. User\AttendanceHistory.php
**Location**: `app/Controllers/User/AttendanceHistory.php`

**Purpose**: User-facing attendance history viewer

**Features**:
- Monthly attendance calendar view
- Daily punch details
- Status code explanations
- Leave/OD/Override annotations

---

## Models & Database Schema

### Core Attendance Models

#### 1. PreFinalPaidDaysModel
**Table**: `pre_final_paid_days`
**Location**: `app/Models/PreFinalPaidDaysModel.php`

**Purpose**: Stores processed attendance data before salary finalization

**Key Fields**:

| Field | Type | Description |
|-------|------|-------------|
| `employee_id` | int | Employee identifier |
| `date` | date | Attendance date |
| `day` | varchar | Day name (Mon, Tue, etc.) |
| `machine` | varchar | Biometric machine name |
| `shift_start` | time | Shift start time |
| `shift_end` | time | Shift end time |
| `punch_in_time` | datetime | Actual punch-in time |
| `punch_out_time` | datetime | Actual punch-out time |
| `in_time_between_shift_with_od` | datetime | Capped in-time within shift (with OD) |
| `out_time_between_shift_with_od` | datetime | Capped out-time within shift (with OD) |
| `in_time_including_od` | datetime | In-time including OD extension |
| `out_time_including_od` | datetime | Out-time including OD extension |
| `late_coming_minutes` | int | Minutes late after grace |
| `early_going_minutes` | int | Minutes early departure |
| `late_coming_plus_early_going_minutes` | int | Total shortage minutes |
| `late_coming_grace` | int | Grace minutes used |
| `comp_off_minutes` | int | Comp-off credits used |
| `wave_off_minutes` | int | Wave-off grace used |
| `deduction_minutes` | int | Penalty deductions |
| `ExtraWorkMinutes` | int | Work beyond shift end |
| `LateSittingMinutes` | int | Calculated late sitting credits |
| `OverTimeMinutes` | int | Overtime for eligible employees |
| `work_hours` | time | Total work hours |
| `status` | varchar(10) | Attendance status code |
| `status_remarks` | varchar(255) | Status explanation |
| `paid` | decimal(3,1) | Paid days (1.0, 0.5, 0.0) |
| `leave_request_type` | varchar(10) | Leave type if on leave |
| `leave_request_amount` | decimal(3,1) | Leave days consumed |
| `leave_request_status` | varchar(20) | Leave approval status |
| `od_hours` | time | OD hours |
| `settlement_type` | varchar(50) | Grace type used |
| `settlement` | int | Settlement minutes |
| `settlement_remarks` | text | Settlement details |
| `settled_by` | varchar(50) | Settlement authority |
| `final_paid` | decimal(3,1) | Final paid days after settlement |

---

#### 2. RawPunchingDataModel
**Table**: `raw_attendance`
**Location**: `app/Models/RawPunchingDataModel.php`

**Purpose**: Stores raw punch data from biometric machines

**Key Fields**:
- `Empcode`: Employee code from biometric system
- `INTime`: Raw check-in timestamp
- `OUTTime`: Raw check-out timestamp
- `DateString`: Date in YYYY-MM-DD format
- `DateString_2`: Alternate date format
- `machine`: Machine identifier

---

#### 3. AttendanceOverrideModel
**Table**: `attendance_override`
**Location**: `app/Models/AttendanceOverrideModel.php`

**Purpose**: Manual attendance corrections

**Fields**:
- `employee_id`: Target employee
- `attendance`: Override status code
- `attendance_date`: Date to override
- `remarks`: Override reason
- `created_at`: Timestamp

---

#### 4. AttendanceOverrideRevisionModel
**Table**: `attendance_override_revision`
**Location**: `app/Models/AttendanceOverrideRevisionModel.php`

**Purpose**: Audit trail for attendance overrides

**Fields**: Same as override + revision metadata

---

### Shift & Schedule Models

#### 5. ShiftModel
**Table**: `shifts`
**Location**: `app/Models/ShiftModel.php`

**Fields**:
- `shift_code`: Unique shift code
- `shift_name`: Display name
- `weekoff`: Default weekoff day (0-6, 0=Sunday)
- `in_time`: Default shift start
- `out_time`: Default shift end

---

#### 6. ShiftPerDayModel
**Table**: `shift_per_day`
**Location**: `app/Models/ShiftPerDayModel.php`

**Purpose**: Day-wise shift timings (Mon-Sun)

**Fields**:
- `shift_id`: Reference to shifts table
- `day`: Day name (Monday, Tuesday, etc.)
- `shift_start`: Start time for this day
- `shift_end`: End time for this day
- `lunch_start`: Lunch break start
- `lunch_end`: Lunch break end
- `break_1_start`, `break_1_end`: Break 1
- `break_2_start`, `break_2_end`: Break 2

---

#### 7. ShiftAttendanceRuleModel
**Table**: `shift_attendance_rule`
**Location**: `app/Models/ShiftAttendanceRuleModel.php`

**Purpose**: Stores attendance calculation rules per shift

**Key Fields**:

**`late_coming_rule`** (JSON):
```json
[{
  "name": "Daily Grace",
  "hours": "00:15:00",
  "applicable": "Daily",
  "count": "Half Day Present"
}]
```

**`attendance_rule`** (JSON):
```json
{
  "absent_for_work_hours": "04:00:00",
  "half_day_for_work_hours": "06:00:00"
}
```

**Other Fields**:
- `early_arrival_considered`: yes/no
- `early_arrival_minutes`: Minutes before shift
- `early_departure_considered`: yes/no
- `early_departure_minutes`: Minutes before shift end

---

#### 8. ShiftOverrideModel
**Table**: `shift_override`
**Location**: `app/Models/ShiftOverrideModel.php`

**Purpose**: Temporary shift changes for employees

**Fields**:
- `employee_id`: Target employee
- `shift_id`: New shift
- `from_date`: Start date
- `to_date`: End date
- `remarks`: Reason for override

---

#### 9. ManualPunchModel
**Table**: `manual_punches`
**Location**: `app/Models/ManualPunchModel.php`

**Purpose**: Manual punch entries for missing/incorrect biometric punches

**Fields**:
- `employee_id`: Target employee
- `punch_date`: Date of punch
- `punch_in_time`: Manual in-time
- `punch_out_time`: Manual out-time
- `remarks`: Reason
- `approved_by`: Approver

---

## Processing Pipeline

### Main Pipeline Architecture

**Entry Point**: `app/Libraries/AttendanceProcessor.php`

**Pipeline Flow**:
```
1. BasicDetails
2. ShiftRulesAndDetails
3. GetAttendanceClean
4. ProcessAttendance
   ├─ ApplyShiftOverride
   ├─ RefactorPunchingRow
   ├─ AdjustNightShiftAndSwitchToDay
   ├─ AdjustDayShiftAfterNightShift
   ├─ ApplyManualPunching
   ├─ PunchTimeCleanup
   ├─ CheckFraudPunchesAndOverride
   ├─ AddDataToPunchingRow
   └─ ApplyStatusCodeAndRemarks
5. LateComingAdjustment
6. SandwichSecondPass
7. ApplyAttendanceOverride
8. AdjustLastWorkingDate
```

---

### Pipeline Stage Details

#### Stage 1: BasicDetails
**Location**: `app/Pipes/BasicDetails.php`

**Purpose**: Load employee and company information

**Operations**:
- Fetch employee record with company details
- Load employee-specific settings (OT allowed, late sitting allowed)
- Load late sitting formula

---

#### Stage 2: ShiftRulesAndDetails
**Location**: `app/Pipes/ShiftRulesAndDetails.php`

**Purpose**: Load shift configuration and rules

**Operations**:
- Fetch shift master record
- Load shift per day (Mon-Sun timings)
- Load shift attendance rules (JSON)
- Parse late coming rules
- Parse attendance rules (absent/half-day thresholds)

---

#### Stage 3: GetAttendanceClean
**Location**: `app/Pipes/GetAttendanceClean.php`

**Purpose**: Fetch and clean raw punching data

**Operations**:
- Query `raw_attendance` table
- Group by date
- Handle multiple punches (first IN, last OUT)
- Remove duplicate entries

---

#### Stage 4: ProcessAttendance
**Location**: `app/Pipes/ProcessAttendance.php`

**Purpose**: Apply business logic to each punch row

**Sub-Pipeline**:

##### 4.1: ApplyShiftOverride
Check if employee has shift override for the date and apply temporary shift.

##### 4.2: RefactorPunchingRow
Transform raw data into standardized structure:
- Parse times
- Extract date components
- Initialize calculation fields

##### 4.3: AdjustNightShiftAndSwitchToDay
Handle shifts crossing midnight (e.g., 20:00 to 06:00):
- Detect: `shift_start > shift_end`
- Switch processing to next day
- Adjust punch times accordingly

##### 4.4: AdjustDayShiftAfterNightShift
Prevent overlap between consecutive night and day shifts.

##### 4.5: ApplyManualPunching
Override biometric punches with manual entries if approved.

##### 4.6: PunchTimeCleanup
Clean and validate punch times:
- Remove invalid timestamps
- Handle missing punches
- Normalize formats

##### 4.7: CheckFraudPunchesAndOverride
Detect and flag suspicious punch patterns:
- Multiple punches in short duration
- Out-of-shift punches without OD
- Inconsistent patterns

##### 4.8: AddDataToPunchingRow
**CORE CALCULATION ENGINE** - See [Core Business Logic](#core-business-logic)

##### 4.9: ApplyStatusCodeAndRemarks
**STATUS DETERMINATION** - See [Attendance Status Codes](#attendance-status-codes)

---

#### Stage 5: LateComingAdjustment
**Location**: `app/Pipes/LateComingAdjustment.php`

**Purpose**: Apply grace adjustment to convert absent/half-day to present

**See**: [Grace Adjustment Logic](#grace-adjustment-logic)

---

#### Stage 6: SandwichSecondPass
**Location**: `app/Pipes/SandwichSecondPass.php`

**Purpose**: Iterative sandwich leave detection

**See**: [Sandwich Leave Detection](#sandwich-leave-detection)

---

#### Stage 7: ApplyAttendanceOverride
**Location**: `app/Pipes/ApplyAttendanceOverride.php`

**Purpose**: Apply manual overrides

**Operations**:
- Fetch all overrides for employee
- Override `status`, `status_remarks`, `paid`
- Reset `late_coming_minutes` and `early_going_minutes` to 0
- Append original remarks for audit trail

---

#### Stage 8: AdjustLastWorkingDate
**Location**: `app/Pipes/AdjustLastWorkingDate.php`

**Purpose**: Mark all dates after employee leaving date

**Operations**:
- Check employee leaving date
- Mark all subsequent dates as "Left" with paid = 0

---

## Core Business Logic

### AddDataToPunchingRow - Core Calculations
**Location**: `app/Pipes/AttendanceProcessor/AddDataToPunchingRow.php`

This is the heart of attendance calculation logic.

#### 1. Late Coming Calculation

```php
late_coming_minutes = max(0, actual_in_time - shift_start) - grace_period
```

**Rules**:
- Only counted if employee is present
- Weekoff/Holiday: No late coming penalty (set to 0)
- Grace period deducted from late coming
- Negative values set to 0

**Example**:
- Shift start: 09:00
- Actual in: 09:45
- Grace: 15 minutes
- Late coming: 45 - 15 = 30 minutes

---

#### 2. Early Going Calculation

```php
early_going_minutes = max(0, shift_end - actual_out_time)
```

**Rules**:
- Only counted if employee is present
- Weekoff/Holiday: No penalty
- OD time extends shift end

**Example**:
- Shift end: 18:00
- Actual out: 17:30
- Early going: 30 minutes

---

#### 3. Work Hours Calculation

```php
work_minutes = time_difference(in_time_between_shift_with_od, out_time_between_shift_with_od)
```

**Special Cases**:

**Night Shift**:
```php
if (shift_start > shift_end) {
    // Shift crosses midnight
    work_minutes = calculate_across_midnight(in_time, out_time)
}
```

**With OD**:
```php
in_time_including_od = min(in_time, od_start_time)
out_time_including_od = max(out_time, od_end_time)
work_minutes = time_difference(in_time_including_od, out_time_including_od)
```

**With Deductions**:
```php
work_minutes = work_minutes - deduction_minutes
```

---

#### 4. Absence/Half-Day Detection

```php
absent_threshold = shift_attendance_rule.attendance_rule.absent_for_work_hours
half_day_threshold = shift_attendance_rule.attendance_rule.half_day_for_work_hours

if (work_minutes < absent_threshold) {
    status = "A" (Absent)
} else if (work_minutes < half_day_threshold) {
    status = "H/D" (Half Day)
} else {
    status = "P" (Present)
}
```

**Default Thresholds**:
- Absent: < 4 hours (240 minutes)
- Half Day: < 6 hours (360 minutes)
- Full Day: >= 6 hours

---

#### 5. Extra Work & Late Sitting Calculation

```php
ExtraWorkMinutes = max(0, actual_out_time - shift_end)
```

**Late Sitting Formula** (Employee-specific):
```php
// Example: 1 grace minute per 5 extra minutes
LateSittingMinutes = ExtraWorkMinutes / 5
```

**Formula Evaluation**:
- Stored as string in database
- Evaluated using `eval()` at runtime
- Effective from specific date
- Variables available: `$ExtraWorkMinutes`

**Example Formulas**:
- `$ExtraWorkMinutes / 5` - 20% conversion
- `$ExtraWorkMinutes / 4` - 25% conversion
- `min($ExtraWorkMinutes / 5, 60)` - Capped at 60 minutes

---

#### 6. Overtime Calculation

```php
if (employee.over_time_allowed == 'yes') {
    OverTimeMinutes = ExtraWorkMinutes
} else {
    OverTimeMinutes = 0
}
```

---

#### 7. OD Hours Integration

```php
od_hours = get_od_hours_between_shifts(employee_id, date, shift_start, shift_end)
```

**OD Time Extension**:
- Extends shift boundaries
- Includes in work hours calculation
- Prevents late coming/early going penalties during OD
- Supports estimated vs actual OD times

**Night Shift OD**:
```php
// OD spanning midnight for night shift
if (shift_start > shift_end) {
    od_start_date = date
    od_end_date = date + 1 day
}
```

---

#### 8. Comp-off & Wave-off Integration

```php
comp_off_minutes = get_comp_off_minutes(employee_id, date)
wave_off_minutes = get_wave_off_minutes(employee_id, date)
deduction_minutes = get_deduction_minutes(employee_id, date)
```

**Comp-off**:
- Earned for working on holidays/weekoffs
- Stored in `comp_off_credits` table
- Deducted when used for adjustment

**Wave-off**:
- HR-granted grace minutes
- Stored in `wave_off_credits` table
- One-time use

**Deductions**:
- Penalty minutes for violations
- Subtracted from work hours

---

### ApplyStatusCodeAndRemarks - Status Determination
**Location**: `app/Pipes/AttendanceProcessor/ApplyStatusCodeAndRemarks.php`

#### Status Priority Logic

**Priority 1: Manual Override**
If attendance override exists, skip all logic and apply override.

**Priority 2: Leaving Date Check**
If date > employee leaving date, mark as "Left".

**Priority 3: Present with Punch**

```php
if (is_present(punch_in, punch_out)) {
    if (is_missed_punch(punch_in, punch_out)) {
        status = "M/P"
        paid = 0.5
    } else if (is_weekoff(shift_id, date)) {
        status = "W/O"
        paid = 0
    } else if (is_fixed_off(date, employee_id)) {
        status = "F/O"
        paid = 0
    } else if (is_holiday(date)) {
        status = holiday_code
        paid = 0
    } else {
        // Apply work hours logic
        if (work_minutes < absent_threshold) {
            status = "A"
            paid = 0
        } else if (work_minutes < half_day_threshold) {
            status = "H/D"
            paid = 0.5
        } else {
            status = "P"
            paid = 1
        }
    }
}
```

**Priority 4: On OD**

```php
if (is_onOD(date, employee_id)) {
    if (work_minutes < half_day_threshold) {
        status = "OD/2"
        paid = 0.5
    } else {
        status = "OD"
        paid = 1
    }
}
```

**Priority 5: On Leave**

```php
leave = is_onLeave(date, employee_id)
if (leave) {
    if (leave.leave_request_amount == 0.5) {
        status = leave.leave_request_type + "/2"
        paid = 0.5
    } else {
        status = leave.leave_request_type
        paid = 0
    }
}
```

**Priority 6: Absent**

```php
if (is_sandwitch(shift_id, date)) {
    status = "S/W"
    paid = 0
} else if (is_holiday(date)) {
    status = holiday_code
    paid = 0
} else {
    status = "A"
    paid = 0
}
```

---

### Grace Adjustment Logic
**Location**: `app/Pipes/LateComingAdjustment.php`

This is the most complex part of the attendance system.

#### Step 1: Calculate Total Grace Pool

```php
totalGrace = Sum(LateSittingMinutes)
           + Sum(WaveOffMinutes)
           + Sum(CompOffMinutes)
           + Sum(accumulated_late_sitting_from_previous_months)
```

---

#### Step 2: Sort Punching Data

```php
// Sort by late coming (descending) to prioritize worst cases
sort_by(late_coming_plus_early_going_minutes, DESC)
```

---

#### Step 3: Apply Adjustment

For each day in sorted order:

**Case 1: Half Day (due to work hours)**

```php
if (status == "H/D" && is_due_to_work_hours) {
    minutes_required_for_full_day = half_day_threshold - work_minutes

    if (totalGrace >= minutes_required_for_full_day) {
        // Upgrade to full day
        status = "P" (or "OD" if on OD)
        paid = 1
        settlement = minutes_required_for_full_day
        totalGrace -= minutes_required_for_full_day
    } else {
        // Remains half day
        status = "H/D"
        paid = 0.5
    }
}
```

---

**Case 2: Absent (due to work hours)**

```php
if (status == "A" && is_due_to_work_hours) {
    minutes_required_for_full_day = half_day_threshold - work_minutes
    minutes_required_for_half_day = absent_threshold - work_minutes

    if (totalGrace >= minutes_required_for_full_day) {
        // Upgrade to full day
        status = "P"
        paid = 1
        settlement = minutes_required_for_full_day
        totalGrace -= minutes_required_for_full_day
    } else if (wave_off_half_day && totalGrace >= minutes_required_for_half_day) {
        // Upgrade to half day
        status = "H/D"
        paid = 0.5
        settlement = minutes_required_for_half_day
        totalGrace -= minutes_required_for_half_day
    } else {
        // Remains absent
        status = "A"
        paid = 0
    }
}
```

---

**Case 3: Full Day Leave**

```php
if (is_on_full_day_leave) {
    // Override work status with leave
    status = leave_type
    paid = 0 (deducted from leave balance)
}
```

---

**Case 4: Half Day Leave**

```php
if (is_on_half_day_leave) {
    if (work_status == "P") {
        // Half day work + half day leave
        status = "H/D+" + leave_type + "/2"
        paid = 0.5 (from work) + 0 (half day deducted from leave)
    } else if (work_status == "H/D") {
        // Half day work + half day leave
        status = "H/D+" + leave_type + "/2"
        paid = 0.5
    } else {
        // No work + half day leave
        status = leave_type + "/2"
        paid = 0.5 (half day deducted from leave)
    }
}
```

---

**Case 5: Absent without Leave**

```php
if (status == "A" && !is_on_leave) {
    if (totalGrace >= late_coming_minutes) {
        // Mark as incomplete (adjusted absent)
        status = "INC"
        paid = 0
        settlement = late_coming_minutes
        totalGrace -= late_coming_minutes
    } else {
        // Remains absent
        status = "A"
        paid = 0
    }
}
```

---

#### Step 4: Record Settlement

```php
settlement_type = grace_type_used (Late Sitting / Comp-off / Wave-off)
settlement = minutes_used
settlement_remarks = detailed_explanation
settled_by = grace_adjustment_engine
final_paid = paid (after adjustment)
```

---

### Sandwich Leave Detection
**Location**: `app/Pipes/SandwichSecondPass.php`

#### First Pass (Helper Function)

```php
is_sandwitch(shift_id, date) {
    prev_day = date - 1
    next_day = date + 1

    if ((is_weekoff(shift_id, prev_day) || is_holiday(prev_day)) &&
        (is_weekoff(shift_id, next_day) || is_holiday(next_day)) &&
        !is_present(date)) {
        return true
    }
    return false
}
```

---

#### Second Pass (Iterative Detection)

**Purpose**: Detect sandwich leaves between two holidays/weekoffs separated by multiple absent days

**Algorithm**:
```php
find_sandwich_second_pass(punching_data) {
    for each day in punching_data {
        if (day.status == "A" || day.status == "UL") {
            // Look backward for weekoff/holiday
            backward_found = false
            for (i = day - 1; i >= 0; i--) {
                if (is_weekoff || is_holiday) {
                    backward_found = true
                    break
                }
                if (is_present) break
            }

            // Look forward for weekoff/holiday
            forward_found = false
            for (i = day + 1; i < punching_data.length; i++) {
                if (is_weekoff || is_holiday) {
                    forward_found = true
                    break
                }
                if (is_present) break
            }

            if (backward_found && forward_found) {
                day.status = "S/W"
                day.paid = 0
            }
        }
    }
}
```

**Example**:
```
Mon: Holiday
Tue: Absent → S/W
Wed: Absent → S/W
Thu: Absent → S/W
Fri: Weekoff
```

All Tue-Wed-Thu marked as sandwich (S/W).

---

## Attendance Status Codes

| Code | Description | Paid | Usage |
|------|-------------|------|-------|
| **P** | Present | 1.0 | Full day attendance |
| **A** | Absent | 0.0 | Not present, no leave |
| **H/D** | Half Day | 0.5 | Present but < 6 hours work |
| **M/P** | Missed Punch | 0.5 | Either IN or OUT missing |
| **W/O** | Week Off | 0.0 | Weekly off day |
| **F/O** | Fixed Off | 0.0 | Employee-specific off day |
| **OD** | On Duty | 1.0 | Full day on official duty |
| **OD/2** | Half Day OD | 0.5 | Half day on official duty |
| **CL** | Casual Leave | 0.0 | Full day casual leave |
| **CL/2** | Half Day CL | 0.5 | Half day casual leave |
| **ML** | Medical Leave | 0.0 | Medical/sick leave |
| **SL** | Sick Leave | 0.0 | Sick leave |
| **UL** | Unpaid Leave | 0.0 | Leave without pay |
| **S/W** | Sandwich Leave | 0.0 | Sandwich between holidays |
| **INC** | Incomplete | 0.0 | Absent but adjusted with grace |
| **HL** | Holiday | 0.0 | Public holiday |
| **RH** | Restricted Holiday | 0.0 | Restricted holiday |
| **H/D+CL/2** | Half Work + Half Leave | 0.5 | Combination status |

### Status Remarks Examples

| Status | Typical Remarks |
|--------|----------------|
| P | Present |
| A | Absent without leave |
| H/D | Half day - insufficient work hours (< 6 hours) |
| M/P | Missed OUT punch |
| W/O | Weekly off (Sunday) |
| OD | On official duty - Client meeting |
| CL | Casual leave approved |
| S/W | Sandwich leave between holiday and weekoff |
| INC | Absent adjusted with late sitting credits |

---

## Helper Functions

### ProcessorHelper.php
**Location**: `app/Pipes/AttendanceProcessor/ProcessorHelper.php`

#### Holiday & Weekoff Checks

```php
is_holiday($date)
// Returns: holiday_code or false
// Checks: company-wide holidays table

is_special_holiday($date, $employee_id)
// Returns: true/false
// Checks: employee-specific holidays

is_RH($date, $employee_id)
// Returns: true/false
// Checks: restricted holidays

is_weekoff($shift_id, $date)
// Returns: true/false
// Checks: shift weekoff configuration

is_fixed_off($date, $employee_id)
// Returns: true/false
// Checks: employee-specific fixed off days
```

---

#### Leave & OD Checks

```php
is_onLeave($date, $employee_id)
// Returns: leave object or false
// Fields: leave_request_type, leave_request_amount, leave_request_status

is_onOD($date, $employee_id)
// Returns: OD object or false
// Fields: estimated_from_date_time, estimated_to_date_time, actual_from_date_time, actual_to_date_time

is_on_InternationOD($date, $employee_id)
// Returns: true/false
// Special handling for international OD
```

---

#### Presence Checks

```php
is_present($in_time, $out_time)
// Returns: true/false
// Logic: Both IN and OUT must exist

is_absent($in_time, $out_time)
// Returns: true/false
// Logic: Neither IN nor OUT exists

is_missed_punch($in_time, $out_time)
// Returns: true/false
// Logic: Only one of IN or OUT exists
```

---

#### Time Calculations

```php
get_late_coming_minutes($shift_start, $in_time)
// Returns: minutes late (integer)
// Logic: max(0, in_time - shift_start)

get_early_going_minutes($shift_end, $out_time)
// Returns: minutes early (integer)
// Logic: max(0, shift_end - out_time)

get_time_difference($time1, $time2, $unit = 'minutes')
// Returns: difference in specified unit
// Units: minutes, hours
```

---

#### OD Integration

```php
get_punch_time_including_od($employee_id, $date, $punch_in, $punch_out, $shift_start, $shift_end)
// Returns: [in_time_including_od, out_time_including_od]
// Logic: Extends punch times with OD boundaries

get_punch_time_between_shift_including_od($employee_id, $date, $punch_in, $punch_out, $shift_start, $shift_end)
// Returns: [in_time_capped, out_time_capped]
// Logic: Caps punch times within shift + OD boundaries

get_od_hours_between_shifts($employee_id, $date, $shift_start, $shift_end)
// Returns: OD hours (time format)
// Logic: Calculates OD duration within shift boundaries
```

**Night Shift OD Logic**:
```php
if (shift_start > shift_end) {
    // Night shift spanning midnight
    od_start_date = date
    od_end_date = date + 1 day

    // Adjust OD times accordingly
}
```

---

#### Credits & Adjustments

```php
get_comp_off_minutes($employee_id, $date)
// Returns: available comp-off minutes
// Source: comp_off_credits table

get_wave_off_minutes($employee_id, $date)
// Returns: wave-off grace minutes
// Source: wave_off_credits table

get_deduction_minutes($employee_id, $date)
// Returns: penalty deduction minutes
// Source: deductions table
```

---

#### Sandwich Detection

```php
is_sandwitch($shift_id, $date)
// Returns: true/false
// Logic: Check if date is between weekoff/holiday on both sides

find_sandwich_second_pass($punching_data)
// Returns: modified punching_data with sandwich flags
// Logic: Iterative detection across multiple absent days
```

---

### Config Defaults Helper
**Location**: `app/Helpers/Config_defaults_helper.php`

#### Raw Data Fetching

```php
save_raw_punching_data($employee_id, $from_date, $to_date)
// Purpose: Fetch raw attendance from eTimeOffice API and save to database
// API: External biometric system integration
// Storage: raw_attendance table

get_punching_data($employee_id, $from_date, $to_date)
// Purpose: Retrieve stored raw punching data
// Returns: Array of punch records
```

---

#### Date Utilities

```php
first_date_of_month($date = null)
// Returns: YYYY-MM-01

last_date_of_month($date = null)
// Returns: YYYY-MM-DD (last day)

get_days_in_month($date = null)
// Returns: Number of days in month (28-31)
```

---

## CLI Commands

### ProcessAttendance Command
**Location**: `app/Commands/ProcessAttendance.php`

#### Usage

```bash
php spark attendance:process [options]
```

#### Options

| Option | Description | Default |
|--------|-------------|---------|
| `--chunk` | Batch size for processing | 25 |
| `--month` | Target month (YYYY-MM) | Last month |
| `--employee` | Specific employee ID or comma-separated list | All employees |

#### Examples

```bash
# Process all employees for last month in chunks of 25
php spark attendance:process

# Process specific month with custom chunk size
php spark attendance:process --month=2024-05 --chunk=50

# Process specific employee
php spark attendance:process --employee=40

# Process multiple employees
php spark attendance:process --employee=40,41,42

# Process current month for single employee
php spark attendance:process --month=2024-06 --employee=40
```

---

#### Command Workflow

1. **Parse Arguments**
   ```php
   chunk_size = CLI::getOption('chunk') ?? 25
   month = CLI::getOption('month') ?? last_month
   employee_ids = CLI::getOption('employee') ?? null
   ```

2. **Fetch Fresh Attendance**
   ```php
   save_raw_punching_data(null, first_date_of_month, last_date_of_month)
   // Bulk API call to eTimeOffice
   ```

3. **Query Employees**
   ```php
   employees = EmployeeModel::with('shift', 'company')
       ->where('is_active', 1)
       ->if(employee_ids) {
           ->whereIn('id', employee_ids)
       }
       ->get()
   ```

4. **Process in Chunks**
   ```php
   foreach (array_chunk(employees, chunk_size) as $chunk) {
       AttendanceProcessor::processAll($chunk, $month)
   }
   ```

5. **Log Progress**
   ```php
   // Execution time per employee
   // Total processed count
   // Errors/warnings
   ```

---

### Batch Processing Library
**Location**: `app/Libraries/AttendanceProcessor.php`

#### `processAll($employees, $month, $chunk_size)`

**Workflow**:
```php
foreach ($employees as $employee) {
    $start_time = microtime(true)

    // 1. Check salary lock
    if (is_salary_locked($employee, $month)) {
        log("Skipped - Salary locked")
        continue
    }

    // 2. Fetch raw attendance
    $raw_data = get_punching_data($employee->id, $from_date, $to_date)

    // 3. Process through pipeline
    $processor = new Processor()
    $processed_data = $processor->getProcessedPunchingData(
        $employee->id,
        $from_date,
        $to_date
    )

    // 4. Save to database
    insertOrUpdateBatch($processed_data, 'pre_final_paid_days')

    $end_time = microtime(true)
    log("Processed in " . ($end_time - $start_time) . " seconds")
}
```

---

#### `insertOrUpdateBatch($data, $table)`

**Purpose**: Efficient batch insert/update using single query

**Logic**:
```sql
INSERT INTO pre_final_paid_days
    (employee_id, date, status, paid, ...)
VALUES
    (40, '2024-05-01', 'P', 1.0, ...),
    (40, '2024-05-02', 'P', 1.0, ...),
    ...
ON DUPLICATE KEY UPDATE
    status = VALUES(status),
    paid = VALUES(paid),
    ...
```

**Benefits**:
- Single database round-trip
- ~10x faster than individual updates
- Transaction-safe

---

## Integrations

### 1. eTimeOffice Integration

**Purpose**: Fetch raw biometric punch data from external system

#### API Configuration
**Location**: `app/Config/Custom.php` or `.env`

```php
eTimeOffice_API_URL = "https://api.etimeofficepro.com/..."
eTimeOffice_API_KEY = "your_api_key"
```

---

#### Data Fetch Function
**Location**: `app/Helpers/Config_defaults_helper.php`

```php
save_raw_punching_data($employee_id, $from_date, $to_date) {
    // 1. Build API request
    $params = [
        'employee_code' => $employee->emp_code,
        'from_date' => $from_date,
        'to_date' => $to_date
    ]

    // 2. Call API
    $response = http_get(eTimeOffice_API_URL, $params, [
        'Authorization' => 'Bearer ' . eTimeOffice_API_KEY
    ])

    // 3. Parse response
    $punches = json_decode($response)

    // 4. Save to raw_attendance table
    foreach ($punches as $punch) {
        RawPunchingDataModel::updateOrCreate([
            'Empcode' => $punch->emp_code,
            'DateString' => $punch->date
        ], [
            'INTime' => $punch->in_time,
            'OUTTime' => $punch->out_time,
            'machine' => $punch->machine
        ])
    }
}
```

---

#### Fetch Schedule
- **Manual**: Via Reports → Final Attendance → Generate button
- **Automated**: Daily cron job at 00:30
  ```bash
  30 0 * * * cd /path/to/project && php spark attendance:fetch-raw
  ```

---

### 2. Leave Management Integration

#### Leave Request Model
**Table**: `leave_requests`

**Key Fields**:
- `employee_id`: Leave applicant
- `leave_type`: CL, ML, SL, UL
- `from_date`: Start date
- `to_date`: End date
- `days`: Total days (supports 0.5 for half day)
- `status`: pending, approved, rejected

---

#### Integration in Attendance

```php
is_onLeave($date, $employee_id) {
    return LeaveRequestModel::where('employee_id', $employee_id)
        ->where('from_date', '<=', $date)
        ->where('to_date', '>=', $date)
        ->where('status', 'approved')
        ->first()
}
```

**Status Override**:
- If on full day leave: Status = leave type (CL, ML, etc.), Paid = 0
- If on half day leave: Status = leave type + "/2", Paid = 0.5
- Leave deducted from employee leave balance

---

### 3. On-Duty (OD) Integration

#### OD Request Model
**Table**: `od_requests`

**Key Fields**:
- `employee_id`: OD requester
- `estimated_from_date_time`: Planned start
- `estimated_to_date_time`: Planned end
- `actual_from_date_time`: Actual start (filled after completion)
- `actual_to_date_time`: Actual end (filled after completion)
- `od_type`: local, international
- `purpose`: Description
- `status`: pending, approved, rejected

---

#### Integration in Attendance

```php
is_onOD($date, $employee_id) {
    return OdRequestModel::where('employee_id', $employee_id)
        ->where('DATE(estimated_from_date_time)', '<=', $date)
        ->where('DATE(estimated_to_date_time)', '>=', $date)
        ->where('status', 'approved')
        ->first()
}
```

**Punch Time Extension**:
```php
// Use actual times if available, else estimated
$od_start = $od->actual_from_date_time ?? $od->estimated_from_date_time
$od_end = $od->actual_to_date_time ?? $od->estimated_to_date_time

// Extend shift boundaries
$in_time_including_od = min($punch_in, $od_start)
$out_time_including_od = max($punch_out, $od_end)
```

**Benefits**:
- No late coming penalty during OD
- OD time included in work hours
- Status marked as "OD" instead of "P"

---

### 4. Payroll Integration

#### Pre-Final to Final Flow

**Pre-Final Table**: `pre_final_paid_days`
- Editable until salary lock
- Used for attendance reports
- Allows overrides and corrections

**Final Table**: `final_paid_days`
- Created when salary processed
- Locked and immutable
- Used for salary calculation

---

#### Salary Lock Mechanism

```php
SalaryLockModel::where('employee_id', $employee_id)
    ->where('month', $month)
    ->exists()
```

**Effects**:
- Prevents attendance re-processing
- Blocks manual overrides
- Ensures salary consistency

---

#### Paid Days Calculation

```php
total_paid_days = SUM(final_paid) for month

// Example:
// 20 days P (1.0 each) = 20.0
// 5 days H/D (0.5 each) = 2.5
// 5 days A (0.0 each) = 0.0
// Total = 22.5 paid days
```

**Salary Calculation**:
```php
per_day_salary = monthly_salary / days_in_month
actual_salary = per_day_salary * total_paid_days
```

---

## Configuration & Customization

### 1. Shift Attendance Rules

**Location**: Edit in Admin → Shifts → Shift Attendance Rules

#### Late Coming Rule (JSON)

```json
[
  {
    "name": "Daily Grace",
    "hours": "00:15:00",
    "applicable": "Daily",
    "count": "Half Day Present"
  },
  {
    "name": "Monthly Grace",
    "hours": "02:00:00",
    "applicable": "Monthly",
    "count": "Full Day Present"
  }
]
```

**Fields**:
- `name`: Rule identifier
- `hours`: Grace duration (HH:MM:SS)
- `applicable`: Daily, Weekly, Monthly
- `count`: When to count as late (Half Day Present, Full Day Present)

---

#### Attendance Rule (JSON)

```json
{
  "absent_for_work_hours": "04:00:00",
  "half_day_for_work_hours": "06:00:00"
}
```

**Customization**:
- Change thresholds per shift
- Example for 6-hour shift:
  ```json
  {
    "absent_for_work_hours": "02:00:00",
    "half_day_for_work_hours": "04:00:00"
  }
  ```

---

### 2. Late Sitting Formula

**Location**: Edit in Admin → Employees → Employee Details

#### Formula Examples

**1:5 Ratio** (1 grace per 5 extra minutes):
```php
$ExtraWorkMinutes / 5
```

**1:4 Ratio**:
```php
$ExtraWorkMinutes / 4
```

**Capped at 60 minutes**:
```php
min($ExtraWorkMinutes / 5, 60)
```

**Progressive**:
```php
$ExtraWorkMinutes <= 60 ? $ExtraWorkMinutes / 5 : (60/5 + ($ExtraWorkMinutes - 60) / 4)
```

**Effective Date**:
```
late_sitting_formula_effective_from = 2024-06-01
```

---

### 3. Employee-Specific Settings

**Table**: `employees`

#### Key Settings

| Field | Values | Purpose |
|-------|--------|---------|
| `late_sitting_allowed` | yes, no | Enable/disable late sitting credits |
| `over_time_allowed` | yes, no | Enable/disable OT calculation |
| `late_sitting_formula` | PHP expression | Custom formula for LateSitting |
| `late_sitting_formula_effective_from` | Date | When formula starts applying |

---

### 4. Shift Override

**Purpose**: Temporary shift change for employee

**Example**:
```php
ShiftOverrideModel::create([
    'employee_id' => 40,
    'shift_id' => 5, // Night shift
    'from_date' => '2024-06-01',
    'to_date' => '2024-06-15',
    'remarks' => 'Temporary night shift for project deployment'
])
```

**Effects**:
- Employee follows new shift timings during period
- New shift rules applied
- Automatic revert after end date

---

### 5. Manual Punching

**Purpose**: Correct missing/incorrect biometric punches

**Example**:
```php
ManualPunchModel::create([
    'employee_id' => 40,
    'punch_date' => '2024-06-05',
    'punch_in_time' => '2024-06-05 09:00:00',
    'punch_out_time' => '2024-06-05 18:00:00',
    'remarks' => 'Biometric machine was down',
    'approved_by' => 'HR Manager'
])
```

**Priority**: Manual punches override biometric punches

---

### 6. Weekoff Configuration

**Location**: `shifts` table and `shift_per_day` table

#### Default Weekoff
```php
shift.weekoff = 0 // 0=Sunday, 1=Monday, ..., 6=Saturday
```

#### Custom Per-Day Timings
```php
// Sunday: Holiday
ShiftPerDayModel::create([
    'shift_id' => 1,
    'day' => 'Sunday',
    'shift_start' => null,
    'shift_end' => null
])

// Saturday: Half day
ShiftPerDayModel::create([
    'shift_id' => 1,
    'day' => 'Saturday',
    'shift_start' => '09:00:00',
    'shift_end' => '13:00:00'
])
```

---

## Complete Processing Workflow

### End-to-End Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    1. RAW DATA FETCHING                      │
├─────────────────────────────────────────────────────────────┤
│ → eTimeOffice API Call (Biometric Machines)                 │
│ → Store in raw_attendance table                             │
│ → Fields: Empcode, INTime, OUTTime, DateString, machine     │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                  2. EMPLOYEE DATA LOADING                    │
├─────────────────────────────────────────────────────────────┤
│ → Query employee with shift details                         │
│ → Load shift per day (Mon-Sun timings)                      │
│ → Load shift attendance rules (JSON)                        │
│ → Load employee settings (OT, late sitting)                 │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│              3. PIPELINE PROCESSING (Per Day)                │
├─────────────────────────────────────────────────────────────┤
│ Step 1: Apply shift override (if exists)                    │
│ Step 2: Refactor punch row data structure                   │
│ Step 3: Adjust night shift times (cross midnight)           │
│ Step 4: Apply manual punching corrections                   │
│ Step 5: Clean punch times                                   │
│ Step 6: Detect fraud punches                                │
│ Step 7: CORE CALCULATIONS                                   │
│    ├─ Check weekoff/holiday/leave/OD                        │
│    ├─ Calculate late coming minutes                         │
│    ├─ Calculate early going minutes                         │
│    ├─ Calculate work hours (with OD integration)            │
│    ├─ Fetch comp-off, wave-off, deduction minutes           │
│    ├─ Calculate extra work, late sitting, overtime          │
│    └─ Determine initial status (P/A/H/D based on work hrs)  │
│ Step 8: Apply status code and remarks                       │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                   4. GRACE ADJUSTMENT                        │
├─────────────────────────────────────────────────────────────┤
│ → Calculate total grace pool:                               │
│   Sum(LateSitting + WaveOff + CompOff)                      │
│ → Sort days by late coming (descending)                     │
│ → For each day:                                             │
│   ├─ H/D → Check if can upgrade to P                        │
│   ├─ A → Check if can upgrade to H/D or P                   │
│   └─ Deduct used grace from pool                            │
│ → Integrate with leave requests                             │
│ → Record settlement details                                 │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                  5. SANDWICH DETECTION                       │
├─────────────────────────────────────────────────────────────┤
│ → First pass: Basic sandwich (A between two W/O or HL)      │
│ → Second pass: Iterative sandwich detection                 │
│   (multiple consecutive absent days between holidays)       │
│ → Mark sandwich days: Status = "S/W", Paid = 0              │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                   6. MANUAL OVERRIDES                        │
├─────────────────────────────────────────────────────────────┤
│ → Fetch attendance overrides for employee                   │
│ → Override status, remarks, paid fields                     │
│ → Reset late coming/early going to 0                        │
│ → Append original remarks for audit                         │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│               7. LAST WORKING DATE ADJUSTMENT                │
├─────────────────────────────────────────────────────────────┤
│ → Check employee leaving date                               │
│ → Mark all dates after leaving as "Left", Paid = 0          │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                     8. DATA STORAGE                          │
├─────────────────────────────────────────────────────────────┤
│ → Batch insert/update to pre_final_paid_days               │
│ → Use ON DUPLICATE KEY UPDATE for efficiency                │
│ → Store all calculated fields and metadata                  │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                   9. SALARY PROCESSING                       │
├─────────────────────────────────────────────────────────────┤
│ → Generate final reports                                    │
│ → Review and approve attendance                             │
│ → Lock salary for month                                     │
│ → Copy pre_final_paid_days → final_paid_days               │
│ → Calculate: total_paid = SUM(final_paid)                   │
│ → Salary = (monthly_salary / days) × total_paid            │
└─────────────────────────────────────────────────────────────┘
```

---

### Step-by-Step Example

**Scenario**: Process attendance for Employee #40 for June 2024

#### Step 1: Fetch Raw Data
```bash
php spark attendance:process --employee=40 --month=2024-06
```

#### Step 2: Raw Data Retrieved
```
Date: 2024-06-03 (Monday)
IN: 09:30:00
OUT: 18:45:00
Machine: Main Gate
```

#### Step 3: Employee & Shift Data
```
Employee: John Doe (#40)
Shift: General (ID: 1)
Shift Timings (Monday): 09:00 - 18:00
Late Coming Rule: 15 min grace
Absent Threshold: < 4 hours
Half Day Threshold: < 6 hours
```

#### Step 4: Core Calculations
```php
// Late Coming
shift_start = 09:00
actual_in = 09:30
grace = 15 min
late_coming = 30 - 15 = 15 minutes

// Work Hours
shift_end = 18:00
actual_out = 18:45
work_time = 18:45 - 09:30 = 9 hours 15 minutes (555 min)

// Extra Work
extra_work = 18:45 - 18:00 = 45 minutes

// Late Sitting (Formula: ExtraWork / 5)
late_sitting = 45 / 5 = 9 minutes

// Initial Status
work_time (555 min) >= half_day_threshold (360 min)
status = "P" (Present)
paid = 1.0
```

#### Step 5: Grace Adjustment
```php
// Total Grace Pool
total_grace = 0 (start of month)

// No adjustment needed (already full day)
status = "P"
paid = 1.0
final_paid = 1.0
```

#### Step 6: Final Record
```php
PreFinalPaidDaysModel::updateOrCreate([
    'employee_id' => 40,
    'date' => '2024-06-03'
], [
    'day' => 'Monday',
    'shift_start' => '09:00:00',
    'shift_end' => '18:00:00',
    'punch_in_time' => '2024-06-03 09:30:00',
    'punch_out_time' => '2024-06-03 18:45:00',
    'late_coming_minutes' => 15,
    'early_going_minutes' => 0,
    'work_hours' => '09:15:00',
    'ExtraWorkMinutes' => 45,
    'LateSittingMinutes' => 9,
    'status' => 'P',
    'status_remarks' => 'Present',
    'paid' => 1.0,
    'final_paid' => 1.0
])
```

---

## Summary

The HRM Attendance Processing System is a comprehensive, rule-based engine that:

1. **Fetches** raw biometric data from external systems
2. **Processes** through multi-stage pipeline with complex business logic
3. **Calculates** work hours, late coming, early going, and various adjustments
4. **Integrates** with leave, OD, and payroll systems
5. **Adjusts** attendance using grace pools (late sitting, comp-off, wave-off)
6. **Detects** sandwich leaves and fraud patterns
7. **Supports** manual overrides and corrections
8. **Handles** night shifts, OD extensions, and custom formulas
9. **Generates** finalized attendance for salary processing
10. **Maintains** audit trail with revision tracking

### Key Strengths
- **Flexible**: Configurable rules per shift
- **Accurate**: Multi-layered validation and calculation
- **Scalable**: Batch processing for thousands of employees
- **Auditable**: Complete revision history and settlement tracking
- **Integrated**: Seamless with leave, OD, and payroll systems

### Technical Highlights
- Pipeline architecture for maintainability
- JSON-based rule configuration
- Night shift cross-midnight handling
- Grace adjustment priority algorithm
- Efficient batch insert/update operations
- CLI support for automation

---

**Document Version**: 1.0
**Last Updated**: 2024-06-07
**Maintained By**: HRM Development Team
