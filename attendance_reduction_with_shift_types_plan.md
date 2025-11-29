# Comprehensive Plan: Attendance Reduction with Sub-Shift Types

## Executive Summary

This document provides a deep analysis and implementation~ plan for adding **sub-shift types** (regular and reduce) to the HRM system. The goal is to apply percentage-based attendance reduction (from 12-hour to 8-hour equivalent) based on the shift type, rather than hardcoding employee IDs.~

---

## 1. Current System Analysis

### 1.1 Database Structure

#### Shifts Table (`shifts`)

Current fields:

- `id` (Primary Key)
- `shift_code` (VARCHAR) - Unique identifier like "A", "B", "C"
- `shift_name` (VARCHAR) - Descriptive name like "General Shift", "Night Shift"
- `weekoff` (JSON) - Array of weekoff days
- `in_time` (TIME) - Legacy field (not actively used)
- `out_time` (TIME) - Legacy field (not actively used)
- `date_time` (TIMESTAMP) - Record creation timestamp

**Note:** Actual shift timings are stored per day in the `shift_per_day` table.

#### Employees Table (`employees`)

Relevant field:

- `shift_id` (Foreign Key) - Links to `shifts.id`

#### Shift Per Day Table (`shift_per_day`)

- `shift_id` (Foreign Key)
- `day` (ENUM: monday, tuesday, wednesday, thursday, friday, saturday, sunday)
- `shift_start` (TIME)
- `shift_end` (TIME)

#### Shift Attendance Rule Table (`shift_attendance_rule`)

- `shift_id` (Foreign Key)
- `consider_early_arrival` (BOOLEAN)
- `consider_early_arrival_max_hours` (DECIMAL)
- `consider_late_departure` (BOOLEAN)
- `consider_late_departure_max_hours` (DECIMAL)
- `late_coming_rule` (TEXT/JSON)
- `attendance_rule` (TEXT/JSON)

### 1.2 Current Attendance Processing Flow

**File:** `app/Pipes/AttendanceProcessor/AddDataToPunchingRow.php`

**Key Processing Steps:**

1. **Lines 127-140:** Calculate `work_minutes_between_shifts_including_od`
   - Handles cross-day shifts (when out_time < in_time)
   - Deducts deduction minutes
2. **Lines 142-154:** Determine attendance status based on work minutes
   - `absent_because_of_work_hours` - if work minutes < threshold
   - `half_day_because_of_work_hours` - if work minutes < half-day threshold
3. **Line 156:** Format work minutes into `work_hours_between_shifts_including_od`

**Critical Point:** The reduction logic must be applied **AFTER** line 140 (work minutes calculation) but **BEFORE** line 144 (attendance status determination).

---

## 2. Proposed Solution: Sub-Shift Types

### 2.1 Concept

Add a new field `shift_type` to the `shifts` table with two possible values:

- **`regular`** - Standard shift (12-hour or normal duration) - no reduction applied
- **`reduce`** - Reduced shift (8-hour equivalent) - apply 66.67% reduction (8/12 ratio)

### 2.2 Benefits

1. **Scalability:** Easy to add more shift types in the future (e.g., "part-time", "flexible")
2. **Maintainability:** No hardcoded employee IDs - shift type is a property of the shift itself
3. **Flexibility:** Employees can switch between shift types by changing their assigned shift
4. **Audit Trail:** Clear indication in the database of which shift type each employee is on
5. **Reporting:** Easy to generate reports based on shift types

---

## 3. Detailed Implementation Plan

### 3.1 Database Changes

#### Step 1: Create Migration File

**File:** `app/Database/Migrations/YYYY-MM-DD-HHMMSS_AddShiftTypeToShifts.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShiftTypeToShifts extends Migration
{
    public function up()
    {
        $fields = [
            'shift_type' => [
                'type'       => 'ENUM',
                'constraint' => ['regular', 'reduce'],
                'default'    => 'regular',
                'null'       => false,
                'after'      => 'shift_name'
            ],
            'reduction_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => '100.00',
                'null'       => false,
                'comment'    => 'Percentage of work hours to count (100.00 = no reduction, 66.67 = 8/12 reduction)',
                'after'      => 'shift_type'
            ],
            'effective_from_date' => [
                'type'       => 'DATE',
                'null'       => true,
                'comment'    => 'Date from which this shift type becomes effective',
                'after'      => 'reduction_percentage'
            ]
        ];

        $this->forge->addColumn('shifts', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('shifts', ['shift_type', 'reduction_percentage', 'effective_from_date']);
    }
}
```

**Rationale for Additional Fields:**

- `reduction_percentage`: Makes the system flexible - not hardcoded to 66.67%
- `effective_from_date`: Allows gradual rollout (e.g., "Apply reduction from 2025-11-01 onwards")

#### Step 2: Update Seed Data (Optional)

Create seed data to populate existing shifts with 'regular' type:

```php
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateShiftTypesSeeder extends Seeder
{
    public function run()
    {
        // Update all existing shifts to 'regular' by default
        $this->db->table('shifts')
            ->update(['shift_type' => 'regular', 'reduction_percentage' => 100.00]);
    }
}
```

### 3.2 Model Updates

#### File: `app/Models/ShiftModel.php`

**Current Code:**

```php
<?php
namespace App\Models;
use CodeIgniter\Model;

class ShiftModel extends Model{
	protected $table = 'shifts';
	protected $allowedFields = ['shift_code', 'shift_name', 'weekoff', 'in_time', 'out_time'];
}
?>
```

**Updated Code:**

```php
<?php
namespace App\Models;
use CodeIgniter\Model;

class ShiftModel extends Model{
	protected $table = 'shifts';
	protected $allowedFields = [
		'shift_code',
		'shift_name',
		'shift_type',              // NEW
		'reduction_percentage',    // NEW
		'effective_from_date',     // NEW
		'weekoff',
		'in_time',
		'out_time'
	];

	// Validation rules
	protected $validationRules = [
		'shift_code' => 'required|is_unique[shifts.shift_code,id,{id}]',
		'shift_name' => 'required',
		'shift_type' => 'required|in_list[regular,reduce]',
		'reduction_percentage' => 'permit_empty|decimal|greater_than[0]|less_than_equal_to[100]',
	];

	protected $validationMessages = [
		'shift_type' => [
			'required' => 'Shift type is required',
			'in_list' => 'Shift type must be either regular or reduce'
		]
	];
}
?>
```

### 3.3 Controller Updates

#### File: `app/Controllers/Master/Shift.php`

**Update the `addShift()` method** (around line 83-150):

Add validation rules for new fields:

```php
$rules = [
    'shift_name'  =>  [
        'rules'         =>  'required|is_unique[shifts.shift_name]',
        'errors'        =>  [
            'required'  => 'Shift Name is required',
            'is_unique' => 'This Shift Name already exists'
        ]
    ],
    'shift_code'  =>  [
        'rules'         =>  'required|is_unique[shifts.shift_code]',
        'errors'        =>  [
            'required'  => 'Shift Code is required',
            'is_unique' => 'This Shift Code already exists'
        ]
    ],
    'shift_type'  =>  [  // NEW
        'rules'         =>  'required|in_list[regular,reduce]',
        'errors'        =>  [
            'required'  => 'Shift Type is required',
            'in_list'   => 'Invalid shift type selected'
        ]
    ],
    'reduction_percentage'  =>  [  // NEW
        'rules'         =>  'permit_empty|decimal|greater_than[0]|less_than_equal_to[100]',
        'errors'        =>  [
            'decimal'   => 'Reduction percentage must be a valid number',
            'greater_than' => 'Reduction percentage must be greater than 0',
            'less_than_equal_to' => 'Reduction percentage cannot exceed 100'
        ]
    ],
    'weekoff'  =>  [
        'rules'         =>  'required',
        'errors'        =>  [
            'required'  => 'Please select weekoff day or days',
        ]
    ],
];
```

Update the insert values:

```php
$values = [
    'shift_name'            => $this->request->getPost('shift_name'),
    'shift_code'            => $this->request->getPost('shift_code'),
    'shift_type'            => $this->request->getPost('shift_type'),              // NEW
    'reduction_percentage'  => $this->request->getPost('reduction_percentage') ?: 100.00,  // NEW
    'effective_from_date'   => $this->request->getPost('effective_from_date') ?: null,     // NEW
    'weekoff'               => json_encode($this->request->getPost('weekoff')),
];
```

**Update the `updateShift()` method similarly.**

**Update the `getAllShifts()` method** to include shift_type in the response:

```php
public function getAllShifts()
{
    $ShiftModel = new ShiftModel();
    $ShiftPerDayModel = new ShiftPerDayModel();
    $EmployeeModel = new EmployeeModel();
    $all_shifts = $ShiftModel->findAll();

    if (!empty($all_shifts)) {
        foreach ($all_shifts as $index => $shift_row) {
            $shift_id = $shift_row['id'];
            $all_shifts[$index]['shift_id'] = $shift_id;
            $all_shifts[$index]['employee_count'] = $EmployeeModel->where('shift_id =', $shift_id)->countAllResults();
            $all_shifts[$index]['date_time'] = !empty($shift_row['date_time']) ? date('d-M-Y h:i A', strtotime($shift_row['date_time'])) : '';

            // Add shift type badge/label
            $shift_type_badge = '';
            if ($shift_row['shift_type'] == 'reduce') {
                $shift_type_badge = '<span class="badge badge-warning">Reduce (' . $shift_row['reduction_percentage'] . '%)</span>';
            } else {
                $shift_type_badge = '<span class="badge badge-primary">Regular</span>';
            }
            $all_shifts[$index]['shift_type_badge'] = $shift_type_badge;

            $all_shifts[$index]['actions'] = '';

            // ... rest of the code for days
        }
    }

    echo json_encode($all_shifts);
}
```

### 3.4 View Updates

#### File: `app/Views/Master/ShiftMaster.php`

**Add shift type fields in the "Add Shift" modal** (after line 34):

```html
<div class="row">
  <div class="col-lg-6 mb-3">
    <label class="required form-label">Shift Type</label>
    <select
      name="shift_type"
      id="shift_type"
      class="form-select form-select-solid"
      onchange="toggleReductionFields(this.value); $(this).next().html('')"
    >
      <option value="">Select Shift Type</option>
      <option value="regular" selected>Regular (Standard Hours)</option>
      <option value="reduce">Reduce (8-hour equivalent from 12-hour)</option>
    </select>
    <span class="text-danger error-text d-block" id="shift_type_error"></span>
  </div>
  <div
    class="col-lg-6 mb-3"
    id="reduction_percentage_container"
    style="display: none;"
  >
    <label class="form-label">Reduction Percentage</label>
    <div class="input-group">
      <input
        type="number"
        name="reduction_percentage"
        id="reduction_percentage"
        class="form-control form-control-solid"
        placeholder="66.67"
        value="66.67"
        step="0.01"
        min="0"
        max="100"
      />
      <span class="input-group-text">%</span>
    </div>
    <small class="form-text text-muted"
      >Percentage of work hours to count (e.g., 66.67 for 8/12 reduction)</small
    >
    <span
      class="text-danger error-text d-block"
      id="reduction_percentage_error"
    ></span>
  </div>
</div>
<div class="row" id="effective_from_container" style="display: none;">
  <div class="col-lg-12 mb-3">
    <label class="form-label">Effective From Date</label>
    <input
      type="date"
      name="effective_from_date"
      id="effective_from_date"
      class="form-control form-control-solid"
      placeholder="Leave empty for immediate effect"
    />
    <small class="form-text text-muted"
      >Optional: Reduction will only apply from this date onwards</small
    >
    <span
      class="text-danger error-text d-block"
      id="effective_from_date_error"
    ></span>
  </div>
</div>
```

**Add JavaScript to toggle reduction fields:**

```javascript
<script>
function toggleReductionFields(shiftType) {
    const reductionContainer = document.getElementById('reduction_percentage_container');
    const effectiveFromContainer = document.getElementById('effective_from_container');

    if (shiftType === 'reduce') {
        reductionContainer.style.display = 'block';
        effectiveFromContainer.style.display = 'block';
        document.getElementById('reduction_percentage').value = '66.67'; // Default to 8/12
    } else {
        reductionContainer.style.display = 'none';
        effectiveFromContainer.style.display = 'none';
        document.getElementById('reduction_percentage').value = '100.00';
        document.getElementById('effective_from_date').value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const shiftTypeSelect = document.getElementById('shift_type');
    if (shiftTypeSelect) {
        toggleReductionFields(shiftTypeSelect.value);
    }
});
</script>
```

**Update the DataTable configuration to show shift type:**

Add a column definition for shift_type_badge in the JavaScript DataTable initialization.

### 3.5 ProcessorHelper Updates

#### File: `app/Pipes/AttendanceProcessor/ProcessorHelper.php`

**Add new helper functions** (add after existing functions, around line 1520):

```php
/**
 * Check if employee's shift has reduction applied
 *
 * @param int $employee_id Employee ID
 * @param int $shift_id Current shift ID
 * @param string $date Current date being processed
 * @return array|false Returns array with shift type info or false if no reduction
 */
public static function get_shift_reduction_info($employee_id, $shift_id, $date)
{
    $ShiftModel = new ShiftModel();

    // Get shift information
    $shift = $ShiftModel->find($shift_id);

    if (empty($shift)) {
        return false;
    }

    // Check if shift type is 'reduce'
    if ($shift['shift_type'] !== 'reduce') {
        return false;
    }

    // Check effective date (if set)
    if (!empty($shift['effective_from_date'])) {
        if (strtotime($date) < strtotime($shift['effective_from_date'])) {
            // Reduction not yet effective
            return false;
        }
    }

    // Return reduction info
    return [
        'shift_type' => $shift['shift_type'],
        'reduction_percentage' => floatval($shift['reduction_percentage']),
        'effective_from_date' => $shift['effective_from_date'],
        'shift_code' => $shift['shift_code'],
        'shift_name' => $shift['shift_name']
    ];
}

/**
 * Apply work hours reduction based on shift type
 *
 * @param int $employee_id Employee ID
 * @param int $shift_id Current shift ID
 * @param float $work_minutes Original work minutes
 * @param string $date Current date being processed
 * @return array Returns [adjusted_minutes, reduction_applied, reduction_info]
 */
public static function get_adjusted_work_minutes($employee_id, $shift_id, $work_minutes, $date)
{
    // Get reduction info for this shift
    $reduction_info = self::get_shift_reduction_info($employee_id, $shift_id, $date);

    // If no reduction applies, return original minutes
    if ($reduction_info === false) {
        return [
            'adjusted_minutes' => $work_minutes,
            'reduction_applied' => false,
            'reduction_info' => null,
            'original_minutes' => $work_minutes
        ];
    }

    // Calculate adjusted minutes
    $reduction_percentage = $reduction_info['reduction_percentage'];
    $adjusted_minutes = $work_minutes * ($reduction_percentage / 100);

    return [
        'adjusted_minutes' => $adjusted_minutes,
        'reduction_applied' => true,
        'reduction_info' => $reduction_info,
        'original_minutes' => $work_minutes,
        'reduction_percentage' => $reduction_percentage,
        'minutes_reduced' => $work_minutes - $adjusted_minutes
    ];
}
```

### 3.6 Attendance Processor Integration

#### File: `app/Pipes/AttendanceProcessor/AddDataToPunchingRow.php`

**Modify the work minutes calculation** (around lines 136-156):

**BEFORE (Current Code):**

```php
} else {
    $punching_row['work_minutes_between_shifts_including_od'] = ProcessorHelper::get_time_difference($punching_row['in_time'], $punching_row['out_time'], 'minutes') - $punching_row['deduction_minutes'];
}

$punching_row['half_day_because_of_work_hours'] = "no";
$punching_row['absent_because_of_work_hours'] = "no";
if ($punching_row['work_minutes_between_shifts_including_od'] < $punching_row['absent_for_work_hours_minutes']) {
    $punching_row['absent_because_of_work_hours'] = "yes";
    // ... rest of logic
}

$punching_row['work_hours_between_shifts_including_od'] = str_pad(floor($punching_row['work_minutes_between_shifts_including_od'] / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($punching_row['work_minutes_between_shifts_including_od'] - floor($punching_row['work_minutes_between_shifts_including_od'] / 60) * 60), 2, '0', STR_PAD_LEFT);
```

**AFTER (Updated Code with Reduction Logic):**

```php
} else {
    $punching_row['work_minutes_between_shifts_including_od'] = ProcessorHelper::get_time_difference($punching_row['in_time'], $punching_row['out_time'], 'minutes') - $punching_row['deduction_minutes'];
}

// ============================================================================
// NEW: Apply shift-based reduction logic
// ============================================================================
$reduction_result = ProcessorHelper::get_adjusted_work_minutes(
    $punching_row['employee_id'],
    $punching_row['shift_id'],
    $punching_row['work_minutes_between_shifts_including_od'],
    $punching_row['date']
);

// Store original minutes for reference/debugging
$punching_row['work_minutes_original'] = $reduction_result['original_minutes'];

// Apply the adjusted minutes
$punching_row['work_minutes_between_shifts_including_od'] = $reduction_result['adjusted_minutes'];

// Store reduction metadata for reporting/debugging
$punching_row['reduction_applied'] = $reduction_result['reduction_applied'];
if ($reduction_result['reduction_applied']) {
    $punching_row['reduction_percentage'] = $reduction_result['reduction_percentage'];
    $punching_row['minutes_reduced'] = $reduction_result['minutes_reduced'];
    $punching_row['shift_type'] = $reduction_result['reduction_info']['shift_type'];
} else {
    $punching_row['reduction_percentage'] = 100.00;
    $punching_row['minutes_reduced'] = 0;
    $punching_row['shift_type'] = 'regular';
}
// ============================================================================
// END: Reduction logic
// ============================================================================

$punching_row['half_day_because_of_work_hours'] = "no";
$punching_row['absent_because_of_work_hours'] = "no";
if ($punching_row['work_minutes_between_shifts_including_od'] < $punching_row['absent_for_work_hours_minutes']) {
    $punching_row['absent_because_of_work_hours'] = "yes";
    // ... rest of logic (unchanged - now uses adjusted minutes)
}

$punching_row['work_hours_between_shifts_including_od'] = str_pad(floor($punching_row['work_minutes_between_shifts_including_od'] / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($punching_row['work_minutes_between_shifts_including_od'] - floor($punching_row['work_minutes_between_shifts_including_od'] / 60) * 60), 2, '0', STR_PAD_LEFT);
```

**Key Points:**

1. The reduction is applied **in-place** to `work_minutes_between_shifts_including_od`
2. Original minutes are preserved in `work_minutes_original` for auditing
3. All subsequent logic (half-day, absent checks) automatically uses the adjusted value
4. Metadata is stored for reporting purposes

---

## 4. Testing Strategy

### 4.1 Unit Testing Scenarios

#### Test Case 1: Regular Shift (No Reduction)

- Employee assigned to shift with `shift_type = 'regular'`
- Works 10 hours (600 minutes)
- Expected: `work_minutes_between_shifts_including_od` = 600
- Expected: `reduction_applied` = false

#### Test Case 2: Reduce Shift (With Reduction)

- Employee assigned to shift with `shift_type = 'reduce'`, `reduction_percentage = 66.67`
- Works 10 hours (600 minutes)
- Expected: `work_minutes_between_shifts_including_od` = 400 (66.67% of 600)
- Expected: `reduction_applied` = true
- Expected: `minutes_reduced` = 200

#### Test Case 3: Effective Date Not Reached

- Employee assigned to reduce shift with `effective_from_date = '2025-12-01'`
- Processing date = '2025-11-15'
- Works 10 hours (600 minutes)
- Expected: No reduction applied (works as regular shift)
- Expected: `work_minutes_between_shifts_including_od` = 600

#### Test Case 4: Effective Date Reached

- Employee assigned to reduce shift with `effective_from_date = '2025-11-01'`
- Processing date = '2025-11-15'
- Works 10 hours (600 minutes)
- Expected: Reduction applied
- Expected: `work_minutes_between_shifts_including_od` = 400

#### Test Case 5: Half-Day Threshold with Reduction

- Employee on reduce shift (66.67% reduction)
- Half-day threshold = 240 minutes (4 hours)
- Works 7 hours (420 minutes)
- After reduction: 280 minutes
- Expected: `half_day_because_of_work_hours` = "no" (280 > 240)

#### Test Case 6: Absent Threshold with Reduction

- Employee on reduce shift (66.67% reduction)
- Absent threshold = 180 minutes (3 hours)
- Works 4 hours (240 minutes)
- After reduction: 160 minutes
- Expected: `absent_because_of_work_hours` = "yes" (160 < 180)

### 4.2 Integration Testing

1. **Shift Creation Test**

   - Create new shift with type 'reduce'
   - Verify shift appears in shift master with correct badge
   - Verify reduction percentage is saved

2. **Employee Assignment Test**

   - Assign employee to reduce shift
   - Process attendance for that employee
   - Verify reduction is applied in attendance report

3. **Shift Type Change Test**

   - Change existing shift from 'regular' to 'reduce'
   - Reprocess attendance for affected employees
   - Verify reduction is applied retroactively (or from effective date)

4. **Report Generation Test**
   - Generate attendance report for employees on different shift types
   - Verify work hours are correctly displayed
   - Verify reduction metadata is available for reporting

### 4.3 Backward Compatibility Testing

1. **Existing Shifts**

   - After migration, all existing shifts should default to `shift_type = 'regular'`
   - No changes to existing attendance calculations

2. **Missing Shift Data**

   - If shift record is not found, system should handle gracefully
   - Fall back to no reduction (regular processing)

3. **Null/Empty Values**
   - If `reduction_percentage` is null, default to 100.00
   - If `effective_from_date` is null, reduction applies immediately

---

## 5. Rollout Strategy

### Phase 1: Database and Model Updates (Week 1)

1. Create and run migration to add `shift_type`, `reduction_percentage`, `effective_from_date`
2. Update `ShiftModel` with new fields
3. Run seed to set all existing shifts to 'regular'
4. Verify database changes in staging environment

### Phase 2: UI Updates (Week 1-2)

1. Update shift master view to include shift type selection
2. Update shift master controller to handle new fields
3. Test shift creation and editing with new fields
4. Deploy to staging for user acceptance testing

### Phase 3: Attendance Processor Updates (Week 2)

1. Add helper functions to `ProcessorHelper.php`
2. Update `AddDataToPunchingRow.php` to call reduction logic
3. Test with sample data in staging
4. Verify attendance reports show correct calculations

### Phase 4: Testing and Validation (Week 3)

1. Comprehensive testing with various scenarios
2. User acceptance testing with HR team
3. Performance testing (ensure no significant slowdown)
4. Fix any bugs or issues discovered

### Phase 5: Production Deployment (Week 4)

1. Create specific "reduce" shifts (e.g., "Shift A - Reduce", "Shift B - Reduce")
2. Set `effective_from_date` for gradual rollout
3. Assign employees to reduce shifts
4. Monitor first few days of attendance processing
5. Generate comparison reports (original vs. reduced hours)

### Phase 6: Monitoring and Optimization (Ongoing)

1. Monitor system performance
2. Gather feedback from HR and employees
3. Adjust reduction percentages if needed
4. Add additional shift types if required

---

## 6. Additional Considerations

### 6.1 Reporting Enhancements

Consider adding the following to attendance reports:

1. **Dual Display:** Show both original and adjusted work hours

   - Example: "10:00 (Original) → 06:40 (Adjusted)"

2. **Reduction Indicator:** Visual indicator for reduced shifts

   - Badge or icon next to adjusted hours

3. **Summary Statistics:**
   - Total minutes reduced per employee per month
   - Total salary impact (if applicable)

### 6.2 Permissions and Access Control

- Restrict shift type changes to specific roles (superuser, admin, hr)
- Log all shift type changes with timestamp and user
- Add approval workflow for changing shift types (optional)

### 6.3 Documentation

Create user documentation covering:

- How to create a reduce shift
- How to assign employees to reduce shifts
- How to interpret attendance reports with reductions
- FAQ for common questions

### 6.4 Future Enhancements

1. **Employee-Specific Overrides:**

   - Allow individual employees to have custom reduction percentages
   - Useful for part-time or special arrangements

2. **Date Range Reductions:**

   - Apply reduction only within specific date ranges
   - Example: "Reduce shift for Q4 2025 only"

3. **Dynamic Reduction Rules:**

   - Different reduction percentages for weekdays vs. weekends
   - Different reductions based on department or role

4. **Audit Trail:**
   - Track when reduction was applied
   - Allow HR to review historical reductions

---

## 7. Code Files Summary

### Files to Create:

1. `app/Database/Migrations/YYYY-MM-DD-HHMMSS_AddShiftTypeToShifts.php`
2. `app/Database/Seeds/UpdateShiftTypesSeeder.php` (optional)

### Files to Modify:

1. `app/Models/ShiftModel.php` - Add new fields to allowedFields
2. `app/Controllers/Master/Shift.php` - Update addShift() and updateShift() methods
3. `app/Views/Master/ShiftMaster.php` - Add shift type UI elements
4. `app/Pipes/AttendanceProcessor/ProcessorHelper.php` - Add reduction helper functions
5. `app/Pipes/AttendanceProcessor/AddDataToPunchingRow.php` - Integrate reduction logic

### Files to Review (No Changes Required):

1. `app/Models/EmployeeModel.php` - Already has shift_id field
2. `app/Models/ShiftAttendanceRuleModel.php` - Rules remain the same
3. `app/Models/ShiftPerDayModel.php` - Day-wise timings remain the same

---

## 8. Conclusion

This comprehensive plan provides a robust, scalable solution for implementing sub-shift types with attendance reduction. The approach:

✅ **Avoids hardcoding** - No employee IDs in code
✅ **Database-driven** - All configuration in the database
✅ **Flexible** - Supports custom reduction percentages and effective dates
✅ **Backward compatible** - Existing shifts default to 'regular'
✅ **Auditable** - Tracks original and adjusted hours
✅ **Maintainable** - Clean separation of concerns
✅ **Testable** - Clear test scenarios and expected outcomes

By following this plan, the attendance reduction feature can be implemented in a professional, maintainable manner that scales with future business needs.
