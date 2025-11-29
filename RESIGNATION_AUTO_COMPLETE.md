# Resignation Auto-Complete System

## Overview
Automated system that handles resignation completion and date_of_leaving synchronization to prevent manual completion errors.

## Features Implemented

### 1. Automatic Completion on Dashboard Load
- When HR visits `/resignation` dashboard, overdue resignations are automatically completed
- Runs silently in background via `ResignationAutoCompleteService`
- No manual intervention needed

### 2. Date of Leaving Synchronization
- When resignation is **completed** (manually or automatically):
  - Calculates: `last_working_day = resignation_date + notice_period - buyout_days`
  - Updates `employees.date_of_leaving` field
  - Uses database transactions for data integrity

- When resignation is **withdrawn**:
  - Clears `employees.date_of_leaving` (sets to NULL)
  - Employee becomes fully active again

### 3. Dashboard Enhancements
- Added **Overdue** status card showing resignations past their last working day
- Status badges:
  - **OVERDUE** (Black) - Past last working day, should have been completed
  - **URGENT** (Red) - 1-7 days remaining
  - **WARNING** (Orange) - 8-14 days remaining
  - **NORMAL** (Green) - More than 14 days remaining

### 4. Manual Command (Optional)
```bash
php spark resignation:auto-complete
```
- Manually trigger resignation completion process
- Use `--dry-run` flag to preview without making changes
- Useful for testing or manual batch processing

## How It Works

### Automatic Process
1. HR accesses `/resignation` dashboard
2. System checks for active resignations where `last_working_day < TODAY`
3. For each overdue resignation:
   - Updates resignation status to "completed"
   - Sets `employees.date_of_leaving` to calculated last working day
   - Logs any failures

### Manual Completion by HR
1. HR clicks "Complete" button on resignation
2. System calculates last working day
3. Updates both:
   - `resignations.status = 'completed'`
   - `employees.date_of_leaving = calculated_date`

### Withdrawal Process
1. HR clicks "Withdraw" button
2. System updates:
   - `resignations.status = 'withdrawn'`
   - `employees.date_of_leaving = NULL`

## Files Modified/Created

### New Files
- `app/Services/ResignationAutoCompleteService.php` - Auto-completion service
- `app/Commands/AutoCompleteResignations.php` - CLI command
- `RESIGNATION_AUTO_COMPLETE.md` - This documentation

### Modified Files
- `app/Controllers/ResignationController.php`
  - Added auto-complete call in dashboard()
  - Updated complete() to sync date_of_leaving
  - Updated withdraw() to clear date_of_leaving

- `app/Models/ResignationModel.php`
  - Added overdue count to statistics
  - Updated urgent alerts query (1-7 days only)

- `app/Views/Resignation/Dashboard.php`
  - Added overdue statistics card
  - Updated JavaScript to display overdue count

- `app/Config/CustomRoutes/ResignationRoutes.php`
  - Fixed route conflicts

- `app/Views/Templates/AsideMenu.php`
  - Added Resignation Dashboard menu item (HR and user ID 40 only)

## Benefits

✅ **Zero Manual Effort** - Resignations complete automatically
✅ **Data Accuracy** - date_of_leaving always synced
✅ **Attendance/Salary** - Processing works correctly with accurate leaving dates
✅ **Visual Alerts** - Dashboard shows overdue resignations prominently
✅ **Transaction Safety** - Database transactions prevent partial updates
✅ **Error Logging** - Failures logged for troubleshooting
✅ **Manual Override** - HR can still complete manually if needed

## Testing

### Test Auto-Completion
1. Create a resignation with past last working day
2. Visit `/resignation` dashboard
3. Verify resignation auto-completes and date_of_leaving is set

### Test Manual Completion
1. Create a resignation
2. Click "Complete" button
3. Verify both resignation and employee record updated

### Test Withdrawal
1. Create and complete a resignation
2. Click "Withdraw" button
3. Verify date_of_leaving is cleared

### Test CLI Command
```bash
# Dry run to see what would be done
php spark resignation:auto-  --dry-run

# Actually complete resignations
php spark resignation:auto-complete
```

## Future Enhancements (Optional)

1. **Email Notifications**
   - Send email when resignation is auto-completed
   - Weekly summary of overdue resignations

2. **Employee Status Update**
   - Automatically set `employees.status = 'left'` on completion
   - Requires business logic confirmation

3. **Schedule as CRON**
   - Add to server crontab to run daily at midnight
   - Example: `0 0 * * * cd /path/to/project && php spark resignation:auto-complete`

4. **Audit Trail**
   - Track who/what completed each resignation
   - Add `completed_by` and `auto_completed` fields

## Notes

- Auto-completion runs on EVERY dashboard page load (by HR)
- This is lightweight and completes in milliseconds
- If no overdue resignations exist, it exits immediately
- All operations use database transactions for safety
- Failures are logged to `writable/logs/`
