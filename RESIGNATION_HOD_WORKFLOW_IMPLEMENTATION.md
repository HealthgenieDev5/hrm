# Resignation HOD Acknowledgment Workflow - Implementation Summary

## Overview
Successfully implemented a comprehensive HOD acknowledgment workflow for resignations, mirroring the existing probation notification system.

## Files Created/Modified

### 1. Database Migration
**Created:** `app/Database/Migrations/2026-02-07-094442_CreateResignationHodResponseTable.php`
- Creates `resignation_hod_response` table to track HOD responses and manager notifications
- Fields: id, resignation_id, employee_id, hod_id, hod_response, hod_response_date, hod_rejection_reason, manager_id, manager_viewed, manager_viewed_date, timestamps

**Manual SQL File:** `create_resignation_hod_response_table.sql`
- Can be run manually if migration fails due to other migration issues
- Creates the same table structure

### 2. Model
**Created:** `app/Models/ResignationHodResponseModel.php`
- `getPendingHodNotifications($hodId)` - Get resignations requiring HOD acknowledgment
- `getPendingManagerNotifications($managerId)` - Get HOD responses pending manager review
- `markManagerViewed($recordId)` - Mark notification as viewed by manager
- `setManagerPending($recordId, $managerId)` - Trigger manager notification after HOD responds
- `hasUnviewedManagerNotification($resignationId, $hodId)` - Check for existing notifications

### 3. Controller Updates
**Modified:** `app/Controllers/User/Profile.php`

Added 4 new methods:
- `getDataForResignationHodPopUp()` - Fetches resignations requiring HOD acknowledgment
- `saveResignationResponseOfHod()` - Saves HOD responses (too_early, accept, reject)
- `getManagerResignationNotifications()` - Gets notifications for HR managers
- `handleManagerResignationNotificationAction()` - Handles manager acknowledgment
- `sendResignationHodResponseEmail()` - Private method to send email notifications

Updated `index()` method:
- Added `'resignationHodAcknowledgments' => $this->getDataForResignationHodPopUp()` to $data array (line ~129)

**Modified:** `app/Controllers/ResignationController.php`

Updated `store()` method:
- After creating resignation, checks if employee has reporting_manager_id
- Creates record in resignation_hod_response table with hod_response='pending'
- Updates success message to indicate HOD will be notified

### 4. Routes
**Modified:** `app/Config/CustomRoutes/ProfileRoutes.php`

Added 3 new AJAX routes (after probation routes):
```php
$routes->match(['get', 'post'], '/ajax/resignation/save-hod-response', [Profile::class, 'saveResignationResponseOfHod']);
$routes->match(['get', 'post'], '/ajax/resignation/manager-notifications', [Profile::class, 'getManagerResignationNotifications']);
$routes->post('/ajax/resignation/manager-notification-action', [Profile::class, 'handleManagerResignationNotificationAction']);
```

### 5. Frontend Views
**Modified:** `app/Views/User/Profile.php`

Added two modal systems after line 1567 (after probation confirmation modal):

**A. HOD Modal (SweetAlert2)**
- Displays list of resignations requiring acknowledgment
- For each resignation shows: employee name, dates, reason
- Three action options per resignation:
  - "Remind Me" - Dismisses until next day (sets hod_response='too_early')
  - "Accept" - HOD accepts the resignation
  - "Reject" - Shows textarea for rejection reason
- Validates all selections before submission
- AJAX POST to `/ajax/resignation/save-hod-response`
- Not dismissible (must make selections)
- Shows urgent resignations (≤7 days) with red styling

**B. Manager Notification Modal (Bootstrap)**
- Only visible to HR managers (env: app.resignationHrManagerIds)
- Auto-checks every 2.5 seconds for pending notifications
- Shows three information cards:
  1. Employee Information (name, ID, department, designation, company)
  2. Resignation Details (resignation date, last working date)
  3. HOD Response (HOD name, response status badge, rejection reason if applicable)
- "Acknowledge" button to mark as viewed
- Auto-loops through multiple notifications
- AJAX POST to `/ajax/resignation/manager-notification-action`

## Workflow Sequence

### 1. HR Creates Resignation
1. HR navigates to `/resignation/create`
2. Fills form (employee, dates, reason, buyout days)
3. Submit → `ResignationController->store()`
4. Creates record in `resignations` table
5. If employee has reporting_manager_id:
   - Creates record in `resignation_hod_response` (hod_response='pending')
6. Success message: "Resignation recorded. HOD will be notified."

### 2. HOD Acknowledges
1. HOD logs in → Profile page loads
2. `Profile->index()` → `getDataForResignationHodPopUp()`
3. Queries resignation_hod_response for pending items
4. SweetAlert2 modal auto-appears with list
5. HOD selects action for each resignation:
   - **Remind Me**: Updates hod_response='too_early', hod_response_date=NOW()
     - Will reappear tomorrow (query filters: DATE(hod_response_date) < CURDATE())
   - **Accept**: Updates hod_response='accept', hod_response_date=NOW()
     - Calls `setManagerPending()` to notify HR
     - Sends email to HR team
   - **Reject**: Updates hod_response='rejected', hod_rejection_reason, hod_response_date=NOW()
     - Calls `setManagerPending()` to notify HR
     - Sends email with rejection reason
6. AJAX POST to `/ajax/resignation/save-hod-response`
7. Resignation status remains 'active' (no auto-withdrawal)

### 3. Manager Views HOD Response
1. HR Manager logs in → Profile page loads
2. After 2.5 seconds, checks `/ajax/resignation/manager-notifications`
3. If notifications exist, Bootstrap modal appears
4. Shows employee info, resignation details, HOD response
5. Response badge: green for "accept", red for "rejected"
6. HR clicks "Acknowledge"
7. AJAX POST to `/ajax/resignation/manager-notification-action`
8. Updates manager_viewed='viewed', manager_viewed_date=NOW()
9. Modal closes, auto-checks for next notification

## Environment Configuration

Add to `.env` file:
```env
app.resignationHrManagerIds = 52,40,93
```

These employee IDs receive HR manager notifications about HOD responses.

## Database Schema

```sql
CREATE TABLE resignation_hod_response (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resignation_id INT(11) UNSIGNED NOT NULL,
    employee_id INT(11) UNSIGNED NOT NULL,
    hod_id INT(11) UNSIGNED NOT NULL,
    hod_response ENUM('pending','too_early','accept','rejected') DEFAULT 'pending',
    hod_response_date DATETIME NULL,
    hod_rejection_reason TEXT NULL,
    manager_id INT(11) UNSIGNED NULL,
    manager_viewed ENUM('pending','viewed') DEFAULT 'pending' NULL,
    manager_viewed_date DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    KEY resignation_id (resignation_id),
    KEY employee_id (employee_id),
    KEY hod_id (hod_id),
    KEY manager_id (manager_id)
);
```

## Email Notifications

**From:** app.hrm@healthgenie.in
**To:** developer3@healthgenie.in, hrd@gstc.com, careers@gstc.com
**Subject:** "Resignation Response - [Employee Name] (Accepted/Rejected)"
**Content:** Employee details, HOD name, response status, rejection reason (if applicable)

## Key Features

1. **Auto-Popup on Login**: HOD sees modal immediately if pending resignations
2. **Remind Me Functionality**: HOD can defer decision until tomorrow
3. **Rejection Reason Required**: Must provide reason when rejecting
4. **Email Notifications**: HR team notified of all HOD responses
5. **Manager Notification Loop**: HR modal auto-loops through multiple pending items
6. **No Auto-Withdrawal**: Rejected resignations stay active for HR to handle
7. **Urgent Flagging**: Resignations with ≤7 days highlighted in red
8. **Date Formatting**: All dates displayed in DD/MM/YYYY format
9. **Response Status Badges**: Color-coded (green=accept, red=reject)
10. **Non-Dismissible Modals**: HOD must make selections, cannot close without action

## Testing Checklist

### Database Setup
- [ ] Run `create_resignation_hod_response_table.sql` to create table
- [ ] Verify table structure matches schema
- [ ] Check indexes are created

### Resignation Creation Flow
- [ ] Login as HR user
- [ ] Create resignation for employee with reporting_manager_id
- [ ] Verify record in resignations table
- [ ] Verify record in resignation_hod_response (hod_response='pending')

### HOD Acknowledgment Flow
- [ ] Login as HOD (reporting manager)
- [ ] Verify SweetAlert2 modal appears on profile page
- [ ] Test "Remind Me" - verify hod_response='too_early', won't show again today
- [ ] Test "Accept" - verify hod_response='accept', email sent
- [ ] Test "Reject" - verify rejection reason required, email sent with reason
- [ ] Verify resignation status remains 'active'

### Manager Notification Flow
- [ ] Login as HR manager (in resignationHrManagerIds)
- [ ] Wait 2.5 seconds for auto-check
- [ ] Verify Bootstrap modal appears if notifications pending
- [ ] Verify employee info, resignation details displayed correctly
- [ ] Verify HOD response badge color (green/red)
- [ ] Click "Acknowledge" - verify modal closes
- [ ] Verify manager_viewed='viewed' in database
- [ ] Verify auto-check for next notification

### Edge Cases
- [ ] Employee without reporting_manager_id: no HOD response record created
- [ ] Multiple resignations: all shown in HOD modal
- [ ] Unauthorized access to AJAX endpoints: verify 403/error
- [ ] Invalid actions: verify error handling

## Troubleshooting

### Modal Not Appearing
1. Check if `resignationHodAcknowledgments` is being passed to view
2. Check browser console for JavaScript errors
3. Verify SweetAlert2 library is loaded

### Database Issues
1. If migration fails, run `create_resignation_hod_response_table.sql` manually
2. Verify table exists: `SHOW TABLES LIKE 'resignation_hod_response';`
3. Check indexes: `SHOW INDEXES FROM resignation_hod_response;`

### Email Not Sending
1. Check email service configuration in `app/Config/Email.php`
2. Verify `from` email is configured
3. Check email logs in `writable/logs/`

### "Remind Me" Not Working
1. Verify hod_response_date is being set to NOW()
2. Check query logic: `DATE(hod_response_date) < CURDATE()`
3. Test with different dates to ensure logic works

## Future Enhancements (Not in Scope)

- [ ] Multi-level approval (HOD → Department Head → HR)
- [ ] Dashboard widget showing pending acknowledgment counts
- [ ] Notification bell icon integration
- [ ] SMS notifications for urgent resignations
- [ ] Resignation withdrawal approval workflow
- [ ] Email notifications when resignation is created (not just on response)

## Implementation Complete

All components have been successfully implemented following the probation notification pattern. The workflow creates visibility and accountability in the resignation process by ensuring HODs are aware of and can respond to their team members' resignations.
