# Resignation HOD Workflow - Installation Steps

## Step 1: Create Database Table

Since the migration system has conflicts with existing migrations, you'll need to create the table manually:

### Option A: Using phpMyAdmin or MySQL Workbench
1. Open phpMyAdmin or MySQL Workbench
2. Select the `hrm_healthgenie` database
3. Go to SQL tab
4. Copy and paste the contents of `create_resignation_hod_response_table.sql`
5. Execute the query

### Option B: Using Command Line (with password)
```bash
mysql -h 127.0.0.1 -u root -p hrm_healthgenie < create_resignation_hod_response_table.sql
```
Enter your MySQL password when prompted.

### Option C: Using CodeIgniter Migration (if migrations work)
```bash
php spark migrate
```

## Step 2: Verify Table Creation

Run this query to verify the table was created successfully:
```sql
SHOW TABLES LIKE 'resignation_hod_response';
DESCRIBE resignation_hod_response;
```

You should see a table with these columns:
- id
- resignation_id
- employee_id
- hod_id
- hod_response
- hod_response_date
- hod_rejection_reason
- manager_id
- manager_viewed
- manager_viewed_date
- created_at
- updated_at

## Step 3: Configure Environment Variables

Add this line to your `.env` file (or create it from `env` template):

```env
app.resignationHrManagerIds = 52,40,93
```

**Note:** Replace with actual employee IDs of HR managers who should receive notifications.

## Step 4: Clear Cache (Optional but Recommended)

```bash
php spark cache:clear
```

## Step 5: Test the Implementation

### Test 1: Create a Resignation
1. Login as HR user
2. Navigate to `/resignation/create` or Resignation Dashboard
3. Create a new resignation for an employee who has a `reporting_manager_id`
4. Submit the form
5. Check database: `SELECT * FROM resignation_hod_response ORDER BY id DESC LIMIT 1;`
6. Verify a record was created with `hod_response = 'pending'`

### Test 2: HOD Acknowledgment
1. Logout and login as the employee's reporting manager (HOD)
2. Navigate to Profile page (`/profile`)
3. You should see a SweetAlert2 modal with resignation acknowledgment request
4. Try each action:
   - **Remind Me**: Modal closes, check DB for `hod_response='too_early'`
   - **Accept**: Modal closes, check DB for `hod_response='accept'`, check email inbox
   - **Reject**: Enter reason, submit, check DB for rejection reason, check email

### Test 3: Manager Notification
1. Logout and login as HR Manager (one of the IDs in `resignationHrManagerIds`)
2. Navigate to Profile page
3. Wait 2.5 seconds
4. If HOD has responded (accept/reject), you should see a Bootstrap modal
5. Verify employee details, resignation info, and HOD response are displayed
6. Click "Acknowledge"
7. Modal should close and check for next notification
8. Verify in DB: `manager_viewed = 'viewed'`

## Troubleshooting

### Modal Not Appearing for HOD
**Check:**
- Browser console for JavaScript errors
- View page source and search for `resignationHodAcknowledgments`
- Verify the resignation record has `status='active'`
- Verify `hod_response` is 'pending' or 'too_early' with old date

**Debug:**
```php
// Add to Profile.php index() method temporarily
dd($this->getDataForResignationHodPopUp());
```

### Manager Modal Not Appearing
**Check:**
- Logged-in user ID is in `app.resignationHrManagerIds` (in .env)
- HOD has responded (hod_response = 'accept' or 'rejected')
- manager_viewed is 'pending'
- Browser console for JavaScript errors

**Debug:**
Navigate to: `/ajax/resignation/manager-notifications`
You should see JSON response with notifications array.

### Email Not Sending
**Check:**
- Email configuration in `app/Config/Email.php`
- SMTP settings are correct
- Check `writable/logs/log-YYYY-MM-DD.log` for email errors

### Database Connection Issues
**Check:**
- Database credentials in `.env` file
- Database server is running
- User has proper permissions

## Verification Queries

```sql
-- Check all resignation HOD responses
SELECT
    rhr.*,
    r.resignation_date,
    e.first_name,
    e.last_name,
    hod.first_name as hod_first_name,
    hod.last_name as hod_last_name
FROM resignation_hod_response rhr
LEFT JOIN resignations r ON r.id = rhr.resignation_id
LEFT JOIN employees e ON e.id = rhr.employee_id
LEFT JOIN employees hod ON hod.id = rhr.hod_id
ORDER BY rhr.id DESC;

-- Check pending HOD acknowledgments
SELECT * FROM resignation_hod_response
WHERE hod_response = 'pending'
OR (hod_response = 'too_early' AND DATE(hod_response_date) < CURDATE());

-- Check pending manager notifications
SELECT * FROM resignation_hod_response
WHERE hod_response IN ('accept', 'rejected')
AND manager_viewed = 'pending';
```

## Post-Installation

Once verified working:
1. Remove temporary debug code (if added)
2. Commit changes to git
3. Update team documentation
4. Train HR staff on new workflow

## Rollback (if needed)

If you need to rollback the changes:

```sql
-- Drop the table
DROP TABLE IF EXISTS resignation_hod_response;
```

Then revert the code changes:
```bash
git checkout -- app/Controllers/User/Profile.php
git checkout -- app/Controllers/ResignationController.php
git checkout -- app/Config/CustomRoutes/ProfileRoutes.php
git checkout -- app/Views/User/Profile.php
rm app/Models/ResignationHodResponseModel.php
```

## Support

For issues or questions:
- Check `writable/logs/` for error logs
- Review `RESIGNATION_HOD_WORKFLOW_IMPLEMENTATION.md` for detailed documentation
- Test each component individually using the verification queries
