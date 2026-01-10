# Probation "Ask Me Later" Feature - Implementation Plan

## Overview
Add an "Ask me later" option to the probation popup modal that allows HODs/Reporting Managers to defer their decision. When selected, the popup will disappear and reappear the next day (after 24 hours).

## Current Implementation Analysis

### How the Popup Currently Works

1. **Data Retrieval** (`app/Controllers/User/Profile.php:1040`)
   - Method: `getDataForUnderProbationPopUp()`
   - Fetches employees under the current user's reporting who are:
     - Not confirmed (probation != 'confirmed')
     - Active status
     - Joined at least 1 month ago
     - Not in certain designations (161, 75)

2. **Response Filtering Logic** (Lines 1082-1094)
   - Employees are EXCLUDED from popup if:
     - Latest response is "Confirmed", OR
     - Any response was given within the last 7 days
   - Employees are INCLUDED if:
     - No response exists, OR
     - Latest response is older than 7 days and not "Confirmed"

3. **Available Actions** (Lines 1138-1159)
   - Current options:
     - "Too Early to Decide" (conditional)
     - "To be Extended" (conditional)
     - "Not to Confirm" (always available)
     - "Confirmed" (always available)

4. **Frontend Display** (`app/Views/User/Profile.php:2325-2412`)
   - Uses SweetAlert2 to show popup
   - Validates all selections before saving
   - Sends responses to backend endpoint

5. **Backend Save** (`app/Controllers/User/Profile.php:1178`)
   - Method: `saveProbationResponseOfHod()`
   - Saves response to `probation_hod_response` table
   - Sends email notification to HR team

## Database Structure

### Current Table: `probation_hod_response`
```
Columns (as seen in ProbationHodResponseModel):
- employee_id
- hod_id
- response
- date_time (auto-generated timestamp)
```

## Implementation Plan

### Phase 1: Add "Ask me later" Option

#### 1.1 Backend Changes - Controller Logic

**File:** `app/Controllers/User/Profile.php`

**Change 1:** Add "Ask me later" to available actions (Line ~1157)
```php
// Current code:
$available_actions[] = 'Not to Confirm';
$available_actions[] = 'Confirmed';

// Modified code:
$available_actions[] = 'Not to Confirm';
$available_actions[] = 'Confirmed';
$available_actions[] = 'Ask me later';
```

**Change 2:** Update filtering logic to handle "Ask me later" (Line ~1082-1094)
```php
// Current logic excludes if response within 7 days
// New logic should:
// - For "Ask me later": exclude for 1 day (24 hours) only
// - For other responses: keep current 7-day exclusion

if (!empty($ProbationHodResponse)) {
    $hod_response = $ProbationHodResponse['response'];
    $response_date = $ProbationHodResponse['response_date'];

    // Exclude if response is 'Confirmed'
    if ($hod_response == 'Confirmed') {
        $should_include = false;
    }
    // For "Ask me later", exclude for 1 day only
    elseif ($hod_response == 'Ask me later') {
        $one_day_ago = date('Y-m-d', strtotime('-1 day'));
        if (strtotime($response_date) >= strtotime($one_day_ago)) {
            $should_include = false;
        }
    }
    // For other responses, exclude for 7 days
    elseif (strtotime($response_date) >= strtotime($SevenDaysBefore)) {
        $should_include = false;
    }
}
```

**Change 3:** Update save method to handle "Ask me later" (Line ~1178)
```php
// In saveProbationResponseOfHod() method
// Add conditional logic to skip email sending for "Ask me later"

foreach ($hod_responses as $employee_id => $hod_response) {

    // Save response to database first
    $data = [
        'employee_id' => $employee_id,
        'hod_id' => $this->session->get('current_user')['employee_id'],
        'response' => $hod_response
    ];
    $ProbationHodResponseModel = new ProbationHodResponseModel();
    $ProbationHodResponseModel->save($data);

    // Only send email if NOT "Ask me later"
    if ($hod_response != 'Ask me later') {
        // Existing email sending logic here
        $EmployeeModel = new EmployeeModel();
        // ... rest of email code
    }
}
```

### Phase 2: Frontend Changes

**File:** `app/Views/User/Profile.php`

**Change:** Update success message (Line ~2399)
```javascript
// Current code shows generic success for all responses
// Update to show different message for "Ask me later"

}).then(async (result) => {
    if (result.isConfirmed) {
        const selectedData = {
            'reponses': result.value
        };

        // Check if any response is "Ask me later"
        const hasAskLater = Object.values(result.value).some(
            response => response === 'Ask me later'
        );

        try {
            const response = await $.ajax({
                method: "POST",
                url: "<?php echo base_url('backend/master/employee/save-probation-response-of-hod'); ?>",
                data: selectedData,
                success: function(response) {
                    console.log(response);
                    const message = hasAskLater
                        ? 'Response saved. You will be reminded tomorrow.'
                        : 'Actions have been saved successfully.';
                    Swal.fire('Saved!', message, 'success');
                },
                // ... error handling
            });
        } catch (error) {
            Swal.fire('Error', 'Failed to save actions. Please try again.', 'error');
        }
    }
});
```

## Testing Plan

### Test Case 1: "Ask me later" Appears in Dropdown
- **Scenario:** Open probation popup
- **Expected:** "Ask me later" option is visible in all employee dropdowns
- **Verification:** Check dropdown options in browser

### Test Case 2: Save "Ask me later" Response
- **Scenario:** Select "Ask me later" for an employee and save
- **Expected:**
  - Response saved to database
  - Success message shows "You will be reminded tomorrow"
  - NO email sent to HR team
- **Verification:**
  - Check database `probation_hod_response` table
  - Check email logs (should be empty)

### Test Case 3: Popup Reappears After 24 Hours
- **Scenario:** After selecting "Ask me later", wait 24+ hours
- **Expected:** Employee appears in popup again
- **Verification:** Check popup on next day

### Test Case 4: Popup Does NOT Reappear Within 24 Hours
- **Scenario:** After selecting "Ask me later", check same day
- **Expected:** Employee does NOT appear in popup
- **Verification:**
  - Login multiple times same day
  - Popup should not show the employee

### Test Case 5: Other Responses Still Work
- **Scenario:** Select "Confirmed", "Not to Confirm", etc.
- **Expected:**
  - Response saved
  - Email sent to HR
  - Employee excluded from popup for 7 days
- **Verification:** Check database and email logs

### Test Case 6: Mixed Responses
- **Scenario:** Select "Ask me later" for Employee A and "Confirmed" for Employee B
- **Expected:**
  - Both responses saved
  - Email sent only for Employee B
  - Employee A reappears tomorrow, Employee B excluded for 7 days
- **Verification:** Database, email logs, next-day popup

## Database Impact

**No database migration needed** - The existing `probation_hod_response` table already supports storing the "Ask me later" response value. The `date_time` column (auto-generated) will track when the response was saved.

## Email Notification Impact

### Current Behavior
- Email sent to: developer3@healthgenie.in, hrd@gstc.com, careers@gstc.com, developer2@healthgenie.in
- Email subject: "Probation Response by [HOD Name] for [Employee Name]"
- Email content: Shows HOD's response

### New Behavior
- **"Ask me later" response:** NO email sent (silent save)
- **All other responses:** Email sent as before

## Rollback Plan

If issues arise, the changes can be easily rolled back:

1. **Remove "Ask me later" from available actions**
   - Line ~1157-1159 in Profile.php controller

2. **Revert filtering logic**
   - Lines ~1082-1094 in Profile.php controller

3. **Revert save method**
   - Lines ~1178-1263 in Profile.php controller

4. **Clean up database** (optional)
   - Delete "Ask me later" responses:
     ```sql
     DELETE FROM probation_hod_response WHERE response = 'Ask me later';
     ```

## Files to be Modified

1. `app/Controllers/User/Profile.php`
   - Method: `getDataForUnderProbationPopUp()` (Lines 1040-1176)
   - Method: `saveProbationResponseOfHod()` (Lines 1178-1263)

2. `app/Views/User/Profile.php`
   - JavaScript section (Lines 2325-2412)

## Summary of Changes

| Component | Current Behavior | New Behavior |
|-----------|-----------------|--------------|
| Available Actions | 4 options (conditional) | 5 options (+ "Ask me later") |
| Popup Reappearance | 7 days for all responses | 1 day for "Ask me later", 7 days for others |
| Email Notification | Sent for all responses | NOT sent for "Ask me later" |
| User Message | Generic success | Different message for "Ask me later" |

## Timeline Estimate

- Backend changes: 30-45 minutes
- Frontend changes: 15-20 minutes
- Testing: 1-2 hours (including 24-hour wait test)
- **Total: ~2-3 hours + 24-hour verification**

## Notes

1. The 24-hour exclusion is based on date comparison (`date('Y-m-d')`), which means:
   - If HOD clicks "Ask me later" at 11:59 PM on Day 1
   - Employee will reappear at 12:01 AM on Day 2 (technically next calendar day)
   - This is simpler than exact 24-hour timestamp comparison

2. Alternative: Use exact timestamp comparison for true 24-hour delay:
   ```php
   $yesterday_timestamp = date('Y-m-d H:i:s', strtotime('-24 hours'));
   if ($response_datetime >= $yesterday_timestamp) {
       $should_include = false;
   }
   ```
   This would require modifying the query to select full datetime instead of just date.

3. The feature is non-destructive - it only adds a new option and doesn't change existing functionality.
