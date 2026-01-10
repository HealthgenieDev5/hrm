# Attendance Grace Deduction Bug - Leave Application Issue

## Issue Description

When an employee applies for half CL (Casual Leave) or any leave and has punch in/out times, the system is incorrectly deducting late arrival and early departure minutes from their grace period, even though they have an approved leave application.

### Example Scenario

- **Employee Shift**: 10:00 AM - 6:30 PM
- **Punch In**: 11:00 AM (late by 1 hour = 60 minutes)
- **Punch Out**: 02:30 PM (early departure by 4 hours)
- **Leave Applied**: Half CL (Approved)
- **Expected Behavior**: No grace deduction since employee has approved leave
- **Actual Behavior**: Grace is being deducted for late coming and early going

## Root Cause Analysis

### Location of Bug
**File**: `app/Pipes/AttendanceProcessor/AddDataToPunchingRow.php`
**Line**: 169-173

### Current Code Logic

The attendance processing pipeline has two key files:

1. **AddDataToPunchingRow.php** (Lines 29-173)
   - Calculates `late_coming_minutes` (line 30)
   - Calculates `early_going_minutes` (line 37)
   - Calculates `late_coming_plus_early_going_minutes` (line 64)
   - Has a condition to reset these to 0 for certain cases (line 169)

2. **ApplyStatusCodeAndRemarks.php** (Lines 235-247)
   - Handles leave status and sets `grace = '0'` for leave cases
   - But by this time, late/early minutes are already calculated

### The Problem

At **line 169** in `AddDataToPunchingRow.php`, the system resets late/early minutes to 0 for:
- Week off (`is_weekoff`)
- Holiday (`is_holiday`)
- Special Holiday (`is_special_holiday`)
- Fixed Off (`is_fixed_off`)
- Restricted Holiday (`is_RH`)

**BUT it does NOT include Leave cases (`is_onLeave`)**

```php
// Current Code (Line 169-173)
if ($punching_row['is_weekoff'] == 'yes' ||
    $punching_row['is_holiday'] == 'yes' ||
    $punching_row['is_special_holiday'] == 'yes' ||
    $punching_row['is_fixed_off'] == 'yes' ||
    $punching_row['is_RH'] == 'yes') {

    $punching_row['late_coming_minutes'] = 0;
    $punching_row['early_going_minutes'] = 0;
    $punching_row['late_coming_plus_early_going_minutes'] = 0;
    $punching_row['late_coming_plus_early_going_minutes_adjustable'] = 0;
    $punching_row['minutes_required_for_half_day'] = 0;
    $punching_row['minutes_required_for_full_day'] = 0;
    $punching_row['half_day_because_of_work_hours'] = "no";
    $punching_row['absent_because_of_work_hours'] = "no";
}
```

### Why This Causes the Issue

1. Employee punches in at 11:00 (late by 60 minutes)
2. Employee punches out at 02:30 (early by ~240 minutes)
3. `AddDataToPunchingRow.php` calculates:
   - `late_coming_minutes = 60`
   - `early_going_minutes = 240`
   - `late_coming_plus_early_going_minutes = 300`
4. The condition at line 169 doesn't check for `is_onLeave`, so these values remain
5. These minutes get stored in the database
6. Later, `ApplyStatusCodeAndRemarks.php` sets the status to "CL/2" with `grace = 0`
7. But the late/early minutes are already calculated and stored, affecting grace calculations

## Proposed Solution

### Change Required

**File**: `app/Pipes/AttendanceProcessor/AddDataToPunchingRow.php`
**Line**: 169

### Modification

Add `|| $punching_row['is_onLeave'] == 'yes'` to the condition:

```php
// Proposed Code (Line 169-173)
if ($punching_row['is_weekoff'] == 'yes' ||
    $punching_row['is_holiday'] == 'yes' ||
    $punching_row['is_special_holiday'] == 'yes' ||
    $punching_row['is_fixed_off'] == 'yes' ||
    $punching_row['is_RH'] == 'yes' ||
    $punching_row['is_onLeave'] == 'yes') {  // ← ADD THIS CONDITION

    $punching_row['late_coming_minutes'] = 0;
    $punching_row['early_going_minutes'] = 0;
    $punching_row['late_coming_plus_early_going_minutes'] = 0;
    $punching_row['late_coming_plus_early_going_minutes_adjustable'] = 0;
    $punching_row['minutes_required_for_half_day'] = 0;
    $punching_row['minutes_required_for_full_day'] = 0;
    $punching_row['half_day_because_of_work_hours'] = "no";
    $punching_row['absent_because_of_work_hours'] = "no";
}
```

## Expected Outcome After Fix

After implementing this fix:

1. When an employee has an approved leave (full or half CL, SL, etc.)
2. The late_coming_minutes and early_going_minutes will be set to 0
3. No grace will be deducted for that day
4. The employee's leave status will be properly reflected without penalty

### Test Scenarios

After applying the fix, test with:

1. **Full Day Leave**: Employee with full CL and no punches → Should show CL, paid = 1, grace = 0
2. **Half Day Leave**: Employee with half CL, punched in late and left early → Should show CL/2, paid = 0.5, late/early minutes = 0
3. **Regular Day**: Employee with no leave, punched late → Should calculate late minutes and apply grace rules normally

## Files to be Modified

1. `app/Pipes/AttendanceProcessor/AddDataToPunchingRow.php` - Line 169

## Impact Analysis

- **Scope**: All employees with leave applications
- **Risk**: Low - This is a conservative fix that aligns leave handling with how weekoffs and holidays are handled
- **Testing Required**: Yes - Test with various leave types (CL, SL, half day, full day)
- **Database Changes**: None - Only logic change
- **Backward Compatibility**: Should not affect historical data, only new attendance processing

## Additional Notes

The system already has a mechanism for "wave off half day who did not work for half day" which might be a manual override. This fix ensures that approved leaves are automatically handled correctly without requiring manual intervention.

## Recommendation

**Approve this fix** to ensure employees on approved leave are not penalized with grace deductions for late arrival or early departure.
