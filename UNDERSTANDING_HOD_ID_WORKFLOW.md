# Understanding HOD ID Workflow

## ✅ Your System is Working Correctly!

Based on the debug output, the system is functioning as designed. Let me explain the workflow:

---

## How HOD ID and Manager ID Work

### When HR Creates a Resignation:

```
1. HR selects Employee: Sunny Kumar (ID: 385)
2. System looks up: Employee 385's reporting_manager_id = 40
3. System creates record with:
   - hod_id = 40 (Employee's reporting manager) ✓ CORRECT
   - manager_id = NULL ✓ CORRECT (set later)
```

### The Workflow Steps:

#### Step 1: HR Creates Resignation
```sql
INSERT INTO resignation_hod_response (
    resignation_id,
    employee_id,
    hod_id,              -- Set to employee's reporting_manager_id
    hod_response,        -- 'pending'
    manager_id           -- NULL (not set yet)
)
```

**Result:**
- `hod_id` = Employee's `reporting_manager_id`
- `manager_id` = NULL initially

#### Step 2: HOD Responds (Accept/Reject)
```php
// When HOD clicks Accept or Reject
$ResignationHodResponseModel->update($recordId, [
    'hod_response' => 'accept' (or 'rejected'),
    'hod_response_date' => NOW()
]);

// Then set manager notification
$ResignationHodResponseModel->setManagerPending($recordId, $hrManagerId);
```

**Result:**
- `hod_response` = 'accept' or 'rejected'
- `manager_id` = Gets set to HR manager ID from config
- `manager_viewed` = 'pending'

#### Step 3: HR Manager Acknowledges
- HR Manager sees notification modal
- Clicks "Acknowledge"
- `manager_viewed` = 'viewed'

---

## Example from Your Database

**Resignation ID 13:**
- **Employee:** Sunny Kumar (ID: 385)
- **Employee's Reporting Manager:** MD NAZRUL ISLAM (ID: 40)
- **Recorded HOD ID:** 40 ✓ **CORRECT!**
- **Manager ID:** NULL ✓ **Expected initially**

### Why is HOD ID = 40?

Because **Sunny Kumar (385) reports to MD NAZRUL ISLAM (40)** in the database.

Check with this query:
```sql
SELECT
    id,
    CONCAT(first_name, ' ', last_name) as name,
    reporting_manager_id
FROM employees
WHERE id = 385;
```

Result: `reporting_manager_id = 40`

---

## If You Think HOD ID is Wrong

### Scenario 1: Wrong Reporting Manager in Database
If employee 385 should NOT report to employee 40, then update the employee record:

```sql
UPDATE employees
SET reporting_manager_id = [correct_manager_id]
WHERE id = 385;
```

### Scenario 2: Created Resignation for Wrong Employee
If you meant to create a resignation for a different employee, just create a new one for the correct employee.

---

## Common Confusion Points

### ❓ "Why is manager_id NULL?"
**Answer:** This is correct! `manager_id` only gets populated AFTER the HOD responds (accept/reject).

**Workflow:**
1. Resignation created → `manager_id = NULL` ✓
2. HOD responds → `manager_id = [HR manager ID]` ✓
3. HR acknowledges → `manager_viewed = 'viewed'` ✓

### ❓ "The HOD ID is the current user's ID!"
**Answer:** Check if:
- The employee you selected actually reports to you
- You might have selected an employee from your team by mistake

**Use the lookup tool:**
```
https://hrm.healthgenie.test/check_employee.php?id=[employee_id]
```

---

## Verification Tools

### 1. Check Employee Before Creating Resignation
```
URL: https://hrm.healthgenie.test/check_employee.php?id=385
```

This shows:
- Employee details
- Current reporting manager
- What HOD ID will be set when resignation is created

### 2. Debug Existing Resignations
```
php debug_resignation_create.php
```

Shows:
- Recent resignations
- Actual vs recorded HOD IDs
- Highlights mismatches

### 3. Check Database Directly
```sql
SELECT
    r.id as resignation_id,
    r.employee_id,
    CONCAT(emp.first_name, ' ', emp.last_name) as employee_name,
    emp.reporting_manager_id as should_be_hod_id,
    rhr.hod_id as recorded_hod_id,
    CONCAT(hod.first_name, ' ', hod.last_name) as hod_name
FROM resignations r
LEFT JOIN employees emp ON emp.id = r.employee_id
LEFT JOIN resignation_hod_response rhr ON rhr.resignation_id = r.id
LEFT JOIN employees hod ON hod.id = rhr.hod_id
WHERE r.id = [resignation_id];
```

---

## The Code (For Reference)

### ResignationController.php - store() method
```php
// Create HOD response record if employee has reporting manager
$employee = $EmployeeModel->find($data['employee_id']);

if ($employee && !empty($employee['reporting_manager_id'])) {
    $ResignationHodResponseModel->insert([
        'resignation_id' => $resignation_id,
        'employee_id' => $data['employee_id'],
        'hod_id' => $employee['reporting_manager_id'],  // ← Uses employee's RM
        'hod_response' => 'pending'
        // manager_id is NOT set here (NULL)
    ]);
}
```

### Profile.php - saveResignationResponseOfHod() method
```php
if ($action === 'accept' || $action === 'reject') {
    // Update HOD response
    $ResignationHodResponseModel->update($recordId, $updateData);

    // NOW set manager_id for HR notification
    $hrManagerIds = array_map('intval',
        explode(',', env('app.resignationHrManagerIds', '52,40,93')));
    $managerId = $hrManagerIds[0];

    $ResignationHodResponseModel->setManagerPending($recordId, $managerId);
    // ← This is when manager_id gets set!
}
```

---

## Summary

✅ **HOD ID = Employee's reporting_manager_id** (set when resignation created)
✅ **Manager ID = NULL initially** (set when HOD responds)
✅ **Manager ID = HR manager ID** (from config, after HOD responds)

Your system is working **exactly as designed**!

---

## Quick Test

1. Open: https://hrm.healthgenie.test/check_employee.php
2. Enter employee ID you want to create resignation for
3. Check the "Reporting Manager ID" shown
4. That ID will become the `hod_id` when you create the resignation
5. If that's not the right person, update the employee's `reporting_manager_id` in the database first

---

## Still Have Issues?

If after checking everything above, you still believe there's an issue:

1. **Provide specific details:**
   - Employee ID you selected: ?
   - Expected HOD ID: ?
   - Actual HOD ID recorded: ?
   - Employee's reporting_manager_id in database: ?

2. **Run these checks:**
   ```bash
   php debug_resignation_create.php
   ```

3. **Check the lookup tool:**
   ```
   https://hrm.healthgenie.test/check_employee.php?id=[your_employee_id]
   ```
