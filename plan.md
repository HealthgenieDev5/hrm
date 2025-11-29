# Plan: Logic for Reducing 12-Hour Shifts to 8-Hour Shifts

## 1. Objective

To adjust the calculation of work hours for employees moving from a 12-hour shift schedule to an 8-hour shift schedule without altering the raw clock-in/clock-out data. The system should display the adjusted hours (proportionally reduced) when users fetch attendance records.

## 2. Core Principle

The fundamental principle is to maintain the integrity of the original attendance data. All clock-in and clock-out timestamps will be recorded as they actually occur. The adjustment logic will only be applied at the point of calculation and display.

- **DO NOT** modify the stored `time_in` or `time_out` values in the database.
- **DO** apply a transformation when calculating the `total_hours_worked` for display or reporting purposes.

## 3. Calculation Logic

The adjustment will be a percentage-based reduction. Since the shift is moving from 12 hours to 8 hours, the new effective work duration is 8/12 (or 2/3) of the original.

**Formula:**

`Displayed Work Hours = Actual Hours Worked * (8 / 12)`

Where:
- **Actual Hours Worked** is the duration between the employee's `time_out` and `time_in`.
- **(8 / 12)** is the reduction factor, which simplifies to `0.6667` or `2/3`.

## 4. Implementation Strategy

1.  **Identify Calculation Point:** Locate the specific function or method in the codebase where `total_hours_worked` is calculated from the raw attendance records. This is typically where reports are generated or where attendance is displayed to managers or HR.

2.  **Introduce Conditional Logic:** The reduction should not apply to everyone. Create a condition to check if an employee is part of the group that has been moved to the 8-hour shift calculation. This could be based on:
    - A new flag in the `employees` table (e.g., `is_8_hour_shift_calculated`).
    - The employee's department.
    - A specific date range.
    - A separate configuration table that maps employees to shift calculation rules.

3.  **Apply the Formula:** If the condition is met, apply the reduction formula to the calculated duration.

    ```php
    // Example Pseudocode
    function ~calculateDisplayHours~(employeeId, timeIn, timeOut) {
        // Calculate the actual duration in hours
        let actualHours = (timeOut - timeIn) / 3600; // Assuming timestamps

        // Check if the employee is on the new 8-hour calculation scheme
        if (isEmployeeOnReducedShift(employeeId)) {
            // Apply the percentage-based reduction
            let displayHours = actualHours * (8 / 12);
            return displayHours;
        } else {
            // Return the actual hours for standard employees
            return actualHours;
        }
    }
    ```

## 5. Example Scenarios

| Actual Hours Worked (Clock-In to Clock-Out) | Calculation             | Displayed Work Hours |
| ------------------------------------------- | ----------------------- | -------------------- |
| 12 hours                                    | `12 * (8 / 12)`         | 8 hours              |
| 9 hours                                     | `9 * (8 / 12)`          | 6 hours              |
| 6 hours                                     | `6 * (8 / 12)`          | 4 hours              |
| 1 hour                                      | `1 * (8 / 12)`          | 0.67 hours (40 mins) |

## 6. Impact on Overtime and Other Policies

- **Overtime:** Overtime calculations must be adjusted to use the **displayed work hours** as the baseline. For example, if the new standard is 8 hours, overtime should be calculated for any displayed time exceeding 8 hours.
- **Lateness/Undertime:** Similar to overtime, any policies regarding lateness or undertime should be based on the new 8-hour standard and the displayed hours.
- **Reporting:** All reports that show "Total Hours" will need to use the new calculation logic to ensure consistency.
