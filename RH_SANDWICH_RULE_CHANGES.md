# RH Sandwich Rule Implementation - Change Documentation

## Date: 2025-11-26

## Purpose
Implement Sandwich Rule for Restricted Holidays (RH) to match the behavior of regular holidays (HL). When an RH falls between two work days where the employee is absent, it should be marked as a sandwich holiday with status "S/W" and unpaid (paid = '0').

---

## Change Summary

**Total Files to Modify:** 1
**Total Lines Changed:** 1
**Risk Level:** Low (Single line change with easy rollback)

---

## Detailed Changes

### File 1: ProcessorHelper.php
**Full Path:** `D:\LOCALHOST\hrm.healthgenie\app\Pipes\AttendanceProcessor\ProcessorHelper.php`

#### Change 1.1: Update isSandwichCandidate() Function

**Location:** Line 1159
**Function:** `isSandwichCandidate()`
**Change Type:** Add 'RH' to status array

**BEFORE:**
```php
private static function isSandwichCandidate($data)
{
    return in_array($data['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'NH']) &&
        $data['is_absent'] === 'yes' &&
        $data['is_present'] === 'no' &&
        $data['is_onOD'] === 'no';
}
```

**AFTER:**
```php
private static function isSandwichCandidate($data)
{
    return in_array($data['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'NH', 'RH']) &&
        $data['is_absent'] === 'yes' &&
        $data['is_present'] === 'no' &&
        $data['is_onOD'] === 'no';
}
```

**Specific Change:**
- **Line 1159:** Add `'RH'` at the end of the status array
- **Old:** `['W/O', 'F/O', 'HL', 'SPL HL', 'NH']`
- **New:** `['W/O', 'F/O', 'HL', 'SPL HL', 'NH', 'RH']`

**Rationale:**
- This allows RH days to be evaluated as sandwich candidates in the second pass
- Matches the existing behavior of HL (regular holidays)
- The second-pass logic is more reliable than first-pass (which was disabled on Nov 18, 2024)

---

## Files That Do NOT Require Changes

### File: ApplyStatusCodeAndRemarks.php
**Path:** `D:\LOCALHOST\hrm.healthgenie\app\Pipes\AttendanceProcessor\ApplyStatusCodeAndRemarks.php`
**Lines:** 279-293
**Action:** NO CHANGE - Keep commented code as-is

**Reason:**
- The commented sandwich logic (lines 280-293) was intentionally disabled on Nov 18, 2024
- First-pass sandwich detection was unreliable for RH
- Second-pass logic will handle sandwich detection after all statuses are assigned
- The SandwichSecondPass pipe runs AFTER this pipe and will overwrite RH status when appropriate

**Existing Code (Keep as-is):**
```php
} elseif ($data['is_RH'] == 'yes') {
    #Begin::commented on 18 Nov 2024 to correct 03 Nov Sandwich over RH
    // if( $data['is_sandwitch'] == 'yes' ){
    //     $data['status']          = "S/W";
    //     $data['status_remarks']  = "Sandwitch33";
    //     $data['paid']            = '0';
    //     $data['grace']           = '0';
    // }else{
    $RH_DATA = $data['RH_DATA'];
    $data['status']         = $RH_DATA['holiday_code'];
    $data['status_remarks'] = $RH_DATA['holiday_name'] . " (" . $RH_DATA['holiday_type'] . ")";
    $data['paid']           = '1';
    $data['grace']          = '0';
    // }
    #Begin::commented on 18 Nov 2024 to correct 03 Nov Sandwich over RH
}
```

### File: SandwichSecondPass.php
**Path:** `D:\LOCALHOST\hrm.healthgenie\app\Pipes\SandwichSecondPass.php`
**Action:** NO CHANGE

**Reason:**
- This pipe calls `ProcessorHelper::find_sandwich_second_pass()`
- Once we update `isSandwichCandidate()`, this pipe automatically processes RH
- No code modification needed

---

## Verification Points

### Existing Code That Already Supports RH (No Changes Needed)

#### 1. findPreviousWorkDay() - Line 1168
**Current Code:**
```php
while ($key >= 0 && in_array($data[$key]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    $key--;
}
```
**Status:** ✅ Already includes 'RH' - Correct

#### 2. findNextWorkDay() - Line 1177
**Current Code:**
```php
while ($key < count($data) && in_array($data[$key]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    $key++;
}
```
**Status:** ✅ Already includes 'RH' - Correct

#### 3. checkPrevMonthSandwich() - Line 1220
**Current Code:**
```php
while ($prevKey >= 0 && in_array($prevMonthData[$prevKey]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    $prevKey--;
}
```
**Status:** ✅ Already includes 'RH' - Correct

#### 4. checkNextMonthSandwich() - Line 1258
**Current Code:**
```php
while ($nextKey < count($nextMonthData) && in_array($nextMonthData[$nextKey]['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'RH', 'NH'])) {
    $nextKey++;
}
```
**Status:** ✅ Already includes 'RH' - Correct

#### 5. isWorkDay() - Line 1185
**Current Code:**
```php
private static function isWorkDay($status)
{
    return in_array($status, ['A', 'S/W', 'M/P', 'CL', 'EL', 'COMP OFF']);
}
```
**Status:** ✅ Correctly excludes RH from work days - Correct

#### 6. markSandwich() - Line 1192
**Current Code:**
```php
private static function markSandwich(&$entry, $remark)
{
    $entry['status'] = 'S/W';
    $entry['status_remarks'] = $remark;
    $entry['paid'] = '0';
}
```
**Status:** ✅ Correctly sets paid = '0' - Correct

---

## Expected Behavior After Implementation

### Scenario 1: RH Sandwiched Between Absences
**Input:**
- Day 1: Employee absent (status = 'A', is_absent = 'yes', is_present = 'no')
- Day 2: RH (is_RH = 'yes', RH_DATA populated)
- Day 3: Employee absent (status = 'A', is_absent = 'yes', is_present = 'no')

**Output for Day 2:**
```php
status = 'S/W'
status_remarks = 'Sandwich5'
paid = '0'
grace = '0'
```

### Scenario 2: RH Not Sandwiched (Employee Present)
**Input:**
- Day 1: Employee present (status = 'P', punches exist)
- Day 2: RH (is_RH = 'yes', RH_DATA populated)
- Day 3: Employee present (status = 'P', punches exist)

**Output for Day 2:**
```php
status = 'RH' (or specific holiday_code)
status_remarks = 'Restricted Holiday (Holiday Name)'
paid = '1'
grace = '0'
```

### Scenario 3: RH Sandwiched with Leaves
**Input:**
- Day 1: Employee on leave (status = 'CL' or 'EL')
- Day 2: RH (is_RH = 'yes', RH_DATA populated)
- Day 3: Employee on leave (status = 'CL' or 'EL')

**Output for Day 2:**
```php
status = 'S/W'
status_remarks = 'Sandwich5'
paid = '0'
grace = '0'
```
**Note:** Leaves (CL, EL) are considered work days per `isWorkDay()` definition

### Scenario 4: RH Adjacent to Holiday (Not Sandwiched)
**Input:**
- Day 1: Regular Holiday (status = 'HL')
- Day 2: RH (is_RH = 'yes', RH_DATA populated)
- Day 3: Employee absent (status = 'A')

**Output for Day 2:**
```php
status = 'RH'
status_remarks = 'Restricted Holiday (Holiday Name)'
paid = '1'
grace = '0'
```
**Note:** Not sandwiched because Day 1 is HL (holiday), not a work day

### Scenario 5: RH at Month Boundary (Sandwiched)
**Input:**
- Previous Month Day 30: Employee absent (status = 'A')
- Current Month Day 1: RH (is_RH = 'yes', RH_DATA populated)
- Current Month Day 2: Employee absent (status = 'A')

**Output for Current Month Day 1:**
```php
status = 'S/W'
status_remarks = 'Sandwich1' (or 'Sandwich3' depending on which boundary)
paid = '0'
grace = '0'
```

---

## Processing Pipeline Order

### Pipeline Execution Sequence:
1. **BasicDetails** - Load employee data
2. **ShiftRulesAndDetails** - Load shift rules
3. **GetAttendanceClean** - Fetch raw punching data
4. **ProcessAttendance** (Internal Pipeline):
   - ApplyShiftOverride
   - RefactorPunchingRow
   - AdjustNightShiftAndSwitchToDay
   - AdjustDayShiftAfterNightShift
   - ApplyManualPunching
   - PunchTimeCleanup
   - CheckFraudPunchesAndOverride
   - **AddDataToPunchingRow** ← Sets `is_RH = 'yes'` and `RH_DATA`
   - **ApplyStatusCodeAndRemarks** ← Initially sets RH status with `paid = '1'`
5. **LateComingAdjustment** - Adjusts late/early minutes
6. **SandwichSecondPass** ← **Overwrites RH to S/W if sandwiched** (This is where our change takes effect)
7. **ApplyAttendanceOverride** - Applies manual overrides
8. **AdjustLastWorkingDate** - Adjusts for resignees

### Key Point:
The `SandwichSecondPass` pipe runs AFTER `ApplyStatusCodeAndRemarks`, which means:
1. RH initially gets status = 'RH', paid = '1'
2. If RH is sandwiched, SandwichSecondPass overwrites it to status = 'S/W', paid = '0'
3. This prevents the timing issues that caused the Nov 18, 2024 bug

---

## Database Impact

### Tables Affected (Updates Only):
1. **pre_final_paid_days**
   - Columns affected: `status`, `status_remarks`, `paid`
   - Change: RH records will have different values when sandwiched

### Tables Referenced (Read-Only):
1. **fixed_rh** - Employee RH assignments
2. **holidays** - Holiday master data including RH
3. **shift_per_day** - Shift configurations
4. **shift_attendance_rule** - Work hour rules

### No Schema Changes Required:
- All existing columns support the new logic
- No new tables or columns needed
- No indexes need to be added

---

## Testing Checklist

### Basic Tests:
- [ ] RH sandwiched between two absences → status = 'S/W', paid = '0'
- [ ] RH between two work days (employee present) → status = 'RH', paid = '1'
- [ ] RH adjacent to regular holiday → status = 'RH', paid = '1'
- [ ] RH sandwiched with leaves (CL/EL) → status = 'S/W', paid = '0'

### Edge Cases:
- [ ] RH at start of month with previous month absence
- [ ] RH at end of month with next month absence
- [ ] Employee with multiple RH in same month
- [ ] Employee with no RH assigned
- [ ] RH on first day of employment
- [ ] RH on last working day before resignation

### Comparison Tests:
- [ ] Verify RH sandwich behaves identically to HL sandwich
- [ ] Verify W/O, F/O, HL, SPL HL, NH sandwich behavior unchanged

### Regression Tests:
- [ ] Verify Nov 3, 2024 scenario doesn't reoccur (the bug that was fixed on Nov 18)
- [ ] Verify existing sandwich logic for other holidays still works
- [ ] Verify attendance processing pipeline completes successfully

---

## Rollback Plan

### If Issues Occur:

**Step 1: Revert the Change**
Edit `ProcessorHelper.php` line 1159:

```php
// Remove 'RH' from the array
return in_array($data['status'], ['W/O', 'F/O', 'HL', 'SPL HL', 'NH']) &&
```

**Step 2: Clear Cache (if applicable)**
```bash
php spark cache:clear
```

**Step 3: Reprocess Affected Attendance**
- Identify date range with RH sandwiches
- Reprocess attendance for affected employees
- Verify status reverted to 'RH' with paid = '1'

### Rollback Risk: **Very Low**
- Single line change
- No database schema changes
- No new dependencies
- Existing code paths remain intact

---

## Risk Assessment

### Risk Level: **LOW**

#### Reasons:
1. **Minimal Code Change:** Only 1 line modified
2. **Well-Tested Pattern:** Using existing sandwich logic that works for HL, W/O, etc.
3. **Easy Rollback:** Single line revert
4. **No Schema Changes:** No database migrations needed
5. **Isolated Impact:** Only affects RH sandwich scenarios
6. **Existing Infrastructure:** All supporting code already in place

#### Potential Issues:
1. **Performance:** Minimal impact (same logic as existing holidays)
2. **Data Integrity:** No risk (uses existing columns and validation)
3. **User Impact:** Only affects payroll calculation for sandwiched RH

---

## Dependencies

### Required Tables:
- ✅ `fixed_rh` - Must exist with employee RH assignments
- ✅ `holidays` - Must contain RH entries with holiday_code = 'RH'
- ✅ `pre_final_paid_days` - Must have status and paid columns

### Required Functions:
- ✅ `ProcessorHelper::is_RH()` - Already implemented (line 581-609)
- ✅ `ProcessorHelper::find_sandwich_second_pass()` - Already implemented (line 1130-1155)
- ✅ `ProcessorHelper::isSandwichCandidate()` - To be modified (line 1157-1163)
- ✅ `ProcessorHelper::isWorkDay()` - Already implemented (line 1183-1186)
- ✅ `ProcessorHelper::markSandwich()` - Already implemented (line 1188-1193)

### Required Pipes:
- ✅ `AddDataToPunchingRow` - Sets is_RH flag
- ✅ `ApplyStatusCodeAndRemarks` - Initial RH status assignment
- ✅ `SandwichSecondPass` - Overwrites status when sandwiched

---

## Implementation Steps

### Step 1: Backup
```bash
# Create backup of the file before modification
cp D:\LOCALHOST\hrm.healthgenie\app\Pipes\AttendanceProcessor\ProcessorHelper.php D:\LOCALHOST\hrm.healthgenie\app\Pipes\AttendanceProcessor\ProcessorHelper.php.backup
```

### Step 2: Make the Change
- Open `ProcessorHelper.php`
- Navigate to line 1159
- Change: `['W/O', 'F/O', 'HL', 'SPL HL', 'NH']`
- To: `['W/O', 'F/O', 'HL', 'SPL HL', 'NH', 'RH']`
- Save file

### Step 3: Verify Syntax
```bash
php -l D:\LOCALHOST\hrm.healthgenie\app\Pipes\AttendanceProcessor\ProcessorHelper.php
```
Expected output: `No syntax errors detected`

### Step 4: Test in Development
- Process test employee attendance with RH dates
- Verify sandwich detection works correctly
- Check all test scenarios from checklist

### Step 5: Deploy to Production
- Deploy modified file
- Monitor logs for errors
- Verify payroll calculations

---

## Post-Implementation Validation

### Immediate Checks:
1. Run attendance processor for test employees with RH
2. Verify no PHP errors in logs
3. Check database records for correct status and paid values
4. Verify UI displays correct status

### Weekly Monitoring:
1. Review payroll reports for RH sandwiches
2. Check for any user complaints or tickets
3. Verify sandwich counts match expectations

### Monthly Review:
1. Compare RH sandwich counts month-over-month
2. Analyze impact on payroll calculations
3. Gather user feedback

---

## Change History

| Date | Version | Author | Description |
|------|---------|--------|-------------|
| 2025-11-26 | 1.0 | Claude Code | Initial implementation plan for RH sandwich rule |

---

## Approval Sign-off

- [ ] Technical Review Complete
- [ ] Testing Plan Approved
- [ ] Rollback Plan Verified
- [ ] Ready for Implementation

---

## Notes

### Why This Approach?
1. **Minimal Risk:** Single line change reduces chance of bugs
2. **Proven Pattern:** Uses same logic as HL, W/O, F/O, SPL HL
3. **Avoids Previous Bug:** Uses second-pass logic instead of first-pass (which was disabled Nov 18, 2024)
4. **Easy to Maintain:** No new files or complex logic
5. **Easy to Rollback:** One line revert if issues occur

### Alternative Approaches Considered (and Rejected):
1. ❌ **Uncomment first-pass logic** - Rejected because it was buggy (disabled Nov 18, 2024)
2. ❌ **Create new pipe** - Rejected because unnecessary complexity
3. ❌ **Modify multiple files** - Rejected because existing infrastructure sufficient

### Related Issues:
- Nov 18, 2024: RH sandwich first-pass logic disabled due to "03 Nov Sandwich over RH" bug
- This implementation uses second-pass logic to avoid similar issues

---

**END OF DOCUMENT**
