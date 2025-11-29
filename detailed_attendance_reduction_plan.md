### Detailed Plan: Percentage-Based Attendance Reduction

This document outlines the detailed plan to implement a percentage-based reduction for employees moving from a 12-hour to an 8-hour shift schedule. The core principle is to apply this change only to the final calculated work hours, without altering the raw punch-in and punch-out data.

### 1. Deep Analysis Summary

- **Core Logic Location**: The primary calculation for work duration occurs in the file `app/Pipes/AttendanceProcessor/AddDataToPunchingRow.php`.

- **Key Variable**: Inside this file, the variable `$punching_row['work_minutes_between_shifts_including_od']` is calculated. This variable holds the total number of minutes an employee has worked.

- **Subsequent Calculations**: This `work_minutes` variable is then used by other logic within the same file to determine attendance statuses like "half-day" (`half_day_because_of_work_hours`) or "absent" (`absent_because_of_work_hours`).

- **Helper Functions**: Reusable logic and calculations are stored as static functions in `app/Pipes/AttendanceProcessor/ProcessorHelper.php`. This is the ideal place to add our new, reusable reduction logic.

### 2. Detailed Implementation Plan

The implementation involves two main steps to ensure the change is safe, effective, and easy to manage in the future.

**Step 1: Create a Control Mechanism in `ProcessorHelper.php`**

Two new functions will be added to `app/Pipes/AttendanceProcessor/ProcessorHelper.php`.

- **Function A: `is_employee_on_reduced_shift($employee_id)`**
    - **Purpose**: This function will act as the master switch. It will check if 
    a specific employee is on the reduced 8-hour shift schedule.
    - **Initial Logic**: To prevent affecting any users right away, this function will be a placeholder that returns `false`. You can later add your own logic to it (e.g., check a list of employee IDs or a new field in your `employees` database table).

    ```php
    private static function is_employee_on_reduced_shift($employee_id)
    {
        // TODO: Implement the actual logic to check if the employee is on a reduced shift.
        // This could involve checking a flag in the employees table, a specific department, or a date range.
        return false;
    }
    ```

- **Function B: `get_adjusted_work_minutes($employee_id, $work_minutes)`**
    - **Purpose**: This function will use the function above to decide whether to apply the reduction.
    - **Logic**:
        - It will call `is_employee_on_reduced_shift()` with the employee's ID.
        - If it returns `true`, the function will apply the reduction: `$work_minutes * (8 / 12)`.
        - If it returns `false`, it will return the original, unaltered `$work_minutes`.

    ```php
    public static function get_adjusted_work_minutes($employee_id, $work_minutes)
    {
        if (self::is_employee_on_reduced_shift($employee_id)) {
            return $work_minutes * (8 / 12);
        }

        return $work_minutes;
    }
    ```

**Step 2: Integrate the Logic into the Main Calculation Pipe**

- **File to Modify**: `app/Pipes/AttendanceProcessor/AddDataToPunchingRow.php`.
- **Action**: Intercept the work minutes calculation. Right after the `$punching_row['work_minutes_between_shifts_including_od']` variable is calculated, pass it through the new `get_adjusted_work_minutes()` helper function.
- **Impact**: The value of `work_minutes_between_shifts_including_od` will be updated in place. Because this happens before the "half-day" or "absent" status checks, all subsequent attendance logic will automatically use the correctly adjusted (or un-adjusted) value, ensuring the entire system remains consistent.

    **Code Modification in `AddDataToPunchingRow.php`**

    The following line:
    ```php
    $punching_row['work_hours_between_shifts_including_od'] = str_pad(floor($punching_row['work_minutes_between_shifts_including_od'] / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($punching_row['work_minutes_between_shifts_including_od'] - floor($punching_row['work_minutes_between_shifts_including_od'] / 60) * 60), 2, '0', STR_PAD_LEFT);
    ```

    Will be replaced with:
    ```php
    // Apply the percentage-based reduction for designated employees
    $punching_row['work_minutes_between_shifts_including_od'] = ProcessorHelper::get_adjusted_work_minutes($punching_row['employee_id'], $punching_row['work_minutes_between_shifts_including_od']);

    // Format the (potentially adjusted) minutes into an H:i string
    $punching_row['work_hours_between_shifts_including_od'] = str_pad(floor($punching_row['work_minutes_between_shifts_including_od'] / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($punching_row['work_minutes_between_shifts_including_od'] - floor($punching_row['work_minutes_between_shifts_including_od'] / 60) * 60), 2, '0', STR_PAD_LEFT);
    ```
