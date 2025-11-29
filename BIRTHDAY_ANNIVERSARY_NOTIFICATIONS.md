# Birthday & Work Anniversary Notifications System

## Overview
Automated notification system that creates notifications for employee birthdays and work anniversaries. These notifications are displayed to **all employees** on their dashboard/profile page.

**Two modes available:**
1. **Daily Mode** - Creates notifications only for today (run daily)
2. **Monthly Mode** - Creates notifications for the entire current month (run once per month)

---

## How It Works

### Monthly Automation Flow (Recommended)
1. **Cron job runs monthly** on the 1st day of each month
2. System loops through all days in the current month (1-28/29/30/31)
3. For each day, checks employees with birthdays or work anniversaries
4. Creates **one notification per event type per day** for the entire month
5. Each notification displays on its respective date (via `reminder_1_date`)
6. All employees see these notifications when the date arrives
7. Prevents duplicates - won't recreate if already exists

### Daily Automation Flow (Alternative)
1. **Cron job runs daily** at midnight (00:00)
2. System checks for employees with birthdays or work anniversaries **today**
3. Creates **one notification per event type** (birthday/anniversary)
4. All employees see these notifications when they log in
5. Employees can mark notifications as read

### Notification Logic
- **Birthday Check**: Matches `date_of_birth` month-day (MM-DD) with target date
- **Anniversary Check**: Matches `date_of_anniversary` or `joining_date` month-day with target date
- **Single Notification**: Multiple employees celebrating on same day = one combined notification
- **Target**: All employees (not targeted to specific employees)
- **Display Date**: `reminder_1_date` = event date (shows on that date only)
- **Duplicate Prevention**: Skips creation if notification already exists for that date

---

## Files Created

### 1. CLI Command
**Location:** `app/Commands/BirthdayAnniversaryCron.php`

**Purpose:** Command-line interface for running the cron job

**Command:**
```bash
php spark cron:birthday-anniversary
```

### 2. Cron Controller
**Location:** `app/Controllers/Cron/BirthdayAnniversaryNotifications.php`

**Purpose:** Contains all business logic for creating notifications

**Methods:**
- `createDailyNotifications()` - Main method that creates notifications
- `getEmployeesWithBirthdayToday()` - Finds employees with birthdays
- `getEmployeesWithAnniversaryToday()` - Finds employees with anniversaries
- `createBirthdayNotification()` - Creates birthday notification
- `createAnniversaryNotification()` - Creates anniversary notification
- `createTestNotifications()` - Manual testing route (requires superuser/hr)

### 3. Routes
**Location:** `app/Config/CustomRoutes/CronRoutes.php`

**Added:**
- Test route: `/cron/birthday-anniversary/test` (superuser/hr only)

---

## CLI Command Usage

### Monthly Mode (Recommended)
Creates notifications for **all days** in the current month.

```bash
php spark cron:birthday-anniversary --monthly
```

**Expected Output:**
```
Starting Birthday & Anniversary notification creation (MONTHLY)...
This will create notifications for ALL days in the current month

Process completed successfully!
Month: 2025-10
Days processed: 31

🎂 BIRTHDAYS:
   Total employees celebrating this month: 15
   Notifications created: 10

🎉 WORK ANNIVERSARIES:
   Total employees celebrating this month: 8
   Notifications created: 6

✓ 16 notification(s) created for the entire month
Employees will see these notifications on their respective dates
```

### Daily Mode
Creates notifications for **today only**.

```bash
php spark cron:birthday-anniversary
```

**Expected Output:**
```
Starting Birthday & Anniversary notification creation (DAILY)...

Process completed successfully!
Date: 2025-10-25

🎂 BIRTHDAYS:
   Employees celebrating: 3
   Notifications created: 1

🎉 WORK ANNIVERSARIES:
   Employees celebrating: 2
   Notifications created: 1

✓ 2 notification(s) created successfully
All employees will see these notifications on their dashboard
```

### If No Events
**Monthly Mode:**
```
🎂 BIRTHDAYS: None this month
🎉 WORK ANNIVERSARIES: None this month
```

**Daily Mode:**
```
🎂 BIRTHDAYS: None today
🎉 WORK ANNIVERSARIES: None today
```

### If Already Run
```
⚠ Notifications for today already exist
```
(System automatically skips dates that already have notifications)

---

## Cron Job Setup

### Windows (Task Scheduler)

1. **Open Task Scheduler**
   - Press `Win + R`, type `taskschd.msc`, press Enter

2. **Create Basic Task**
   - Click "Create Basic Task" in right panel
   - Name: `HRM Birthday Anniversary Notifications`
   - Description: `Daily notification creation for birthdays and work anniversaries`

3. **Trigger**
   - Select "Daily"
   - Start time: `00:00:00` (midnight)
   - Recur every: `1` days

4. **Action**
   - Select "Start a program"
   - Program/script: `php`
   - Add arguments: `spark cron:birthday-anniversary`
   - Start in: `D:\LOCALHOST\hrm.healthgenie`

5. **Finish**
   - Check "Open the Properties dialog"
   - In Properties:
     - Check "Run whether user is logged on or not"
     - Check "Run with highest privileges"
     - Click OK

### Linux/Mac (Crontab)

1. **Edit crontab**
```bash
crontab -e
```

2. **Add cron entry** (runs daily at midnight)
```bash
0 0 * * * cd /path/to/hrm.healthgenie && php spark cron:birthday-anniversary >> /path/to/logs/birthday-cron.log 2>&1
```

**Cron Format Explanation:**
```
0 0 * * *
│ │ │ │ │
│ │ │ │ └─── Day of week (0-7, Sunday = 0 or 7)
│ │ │ └───── Month (1-12)
│ │ └─────── Day of month (1-31)
│ └───────── Hour (0-23)
└─────────── Minute (0-59)
```

### Alternative Times
```bash
# Run at 1:00 AM
0 1 * * * cd /path/to/hrm.healthgenie && php spark cron:birthday-anniversary

# Run at 6:00 AM
0 6 * * * cd /path/to/hrm.healthgenie && php spark cron:birthday-anniversary

# Run at 11:59 PM
59 23 * * * cd /path/to/hrm.healthgenie && php spark cron:birthday-anniversary
```

---

## Testing

### Option 1: Command Line (Recommended)
```bash
cd D:\LOCALHOST\hrm.healthgenie
php spark cron:birthday-anniversary
```

### Option 2: Web Browser (Requires Login)
1. Login as `superuser` or `hr` role
2. Navigate to: `http://localhost:8080/cron/birthday-anniversary/test`
3. View JSON response

**Expected Response:**
```json
{
    "success": true,
    "message": "Test birthday/anniversary notifications created",
    "data": {
        "date": "2025-10-25",
        "birthdays_created": 1,
        "anniversaries_created": 1,
        "birthday_employees": 3,
        "anniversary_employees": 2
    }
}
```

### Testing with Specific Dates

To test with employees who have birthdays/anniversaries on specific dates:

1. **Temporarily update employee records** in database:
```sql
-- Set employee date_of_birth to today for testing
UPDATE employees
SET date_of_birth = CONCAT(YEAR(CURDATE()) - 30, '-', MONTH(CURDATE()), '-', DAY(CURDATE()))
WHERE id = 5;

-- Set employee date_of_anniversary to today for testing
UPDATE employees
SET date_of_anniversary = CONCAT(YEAR(CURDATE()) - 3, '-', MONTH(CURDATE()), '-', DAY(CURDATE()))
WHERE id = 10;
```

2. **Run the command:**
```bash
php spark cron:birthday-anniversary
```

3. **Check notifications table:**
```sql
SELECT * FROM employee_notifications
WHERE event_date = CURDATE()
AND notification_type = 'event'
ORDER BY id DESC
LIMIT 5;
```

4. **Login as any employee** and visit profile page to see notifications

---

## Notification Display

### Where Notifications Appear
- **Profile Page:** `http://localhost:8080/profile`
- Auto-displays as modal popup on page load
- Only shows unread notifications

### Notification Content

#### Birthday Notification (Single Employee)
```
Title: Birthday: John Doe
Description: Wishing John Doe a very Happy Birthday! May this special day bring joy, success, and wonderful memories.
```

#### Birthday Notification (Multiple Employees)
```
Title: Birthdays Today: 3 employees
Description: Please join us in wishing a Happy Birthday to:

• John Doe
• Jane Smith
• Bob Johnson

May their day be filled with joy and happiness!
```

#### Anniversary Notification (Single Employee)
```
Title: Work Anniversary: Jane Smith (5 years)
Description: Congratulations to Jane Smith (5 years) on completing another successful year with us! Thank you for your dedication and contributions.
```

#### Anniversary Notification (Multiple Employees)
```
Title: Work Anniversaries Today: 2 employees
Description: Please join us in celebrating work anniversaries of:

• Jane Smith (5 years)
• Bob Johnson (3 years)

Thank you for your continued dedication and contribution to our organization!
```

---

## Database Schema

### employee_notifications table
```sql
INSERT INTO employee_notifications (
    title,
    description,
    notification_type,
    event_date,
    reminder_1_date,
    target_employees,
    is_active,
    created_by
) VALUES (
    'Birthday: John Doe',
    'Wishing John Doe a very Happy Birthday!...',
    'event',
    '2025-10-25',
    '2025-10-25',
    NULL,  -- NULL means all employees
    1,
    1      -- System user
);
```

---

## Important Notes

### Duplicate Prevention
- System checks if notifications for today already exist
- Will **NOT** create duplicates if run multiple times on same day
- Safe to run multiple times

### Employee Filtering
- Only **active employees** (`status = 'active'`)
- Only employees with valid birth/anniversary dates
- Date comparison uses month-day (MM-DD) only, ignoring year

### Anniversary Date Priority
1. Uses `date_of_anniversary` if available
2. Falls back to `joining_date` if `date_of_anniversary` is empty
3. Calculates years of service from anniversary date

### Target Audience
- `target_employees = NULL` → Shows to **all employees**
- Notifications appear in auto-display modal on profile page
- All logged-in employees see the same notifications

---

## Troubleshooting

### Command Not Found
```bash
# Make sure you're in the project directory
cd D:\LOCALHOST\hrm.healthgenie

# Run with full path to php
C:\path\to\php.exe spark cron:birthday-anniversary
```

### No Notifications Created
**Check:**
1. Are there employees with birthdays/anniversaries today?
2. Are employee records marked as `status = 'active'`?
3. Do employees have valid `date_of_birth` or `date_of_anniversary`/`joining_date`?

**Debug SQL:**
```sql
-- Check employees with birthdays today
SELECT id, first_name, last_name, date_of_birth
FROM employees
WHERE status = 'active'
AND MONTH(date_of_birth) = MONTH(CURDATE())
AND DAY(date_of_birth) = DAY(CURDATE());

-- Check employees with anniversaries today
SELECT id, first_name, last_name, date_of_anniversary, joining_date
FROM employees
WHERE status = 'active'
AND (
    (MONTH(date_of_anniversary) = MONTH(CURDATE()) AND DAY(date_of_anniversary) = DAY(CURDATE()))
    OR
    (MONTH(joining_date) = MONTH(CURDATE()) AND DAY(joining_date) = DAY(CURDATE()))
);
```

### Notifications Not Appearing
1. Check `employee_notifications` table for today's entries
2. Verify `reminder_1_date = today`
3. Verify `is_active = 1`
4. Check if notification already marked as read in `notification_reads` table

### Permission Denied on Web Route
- Web test route requires `superuser` or `hr` role
- Use command line for testing instead

---

## Log Files

### Check Logs
**Location:** `writable/logs/log-YYYY-MM-DD.log`

**Look for:**
```
INFO - 2025-10-25 00:00:01 --> Starting birthday/anniversary notification creation for 2025-10-25
INFO - 2025-10-25 00:00:02 --> Created birthday notification for 3 employees
INFO - 2025-10-25 00:00:03 --> Created anniversary notification for 2 employees
INFO - 2025-10-25 00:00:04 --> Created 1 birthday notification(s) and 1 anniversary notification(s) for 2025-10-25
```

**Errors:**
```
ERROR - 2025-10-25 00:00:05 --> Failed to create birthday notification: [error message]
```

---

## Integration with Existing Notification System

This system integrates seamlessly with the existing notification infrastructure:

- **Uses existing tables:** `employee_notifications`, `notification_reads`, `notification_email_logs`
- **Uses existing models:** `EmployeeNotificationModel`, `NotificationReadModel`
- **Uses existing display logic:** Auto-display modal on profile page
- **Compatible with:** Backend CRUD operations at `/backend/notifications`

---

## Future Enhancements (Optional)

- [ ] Email notifications in addition to dashboard notifications
- [ ] Configurable message templates
- [ ] Custom greetings for milestone years (5, 10, 15, 20+ years)
- [ ] Birthday/anniversary calendar view
- [ ] Notification preview before creation
- [ ] Configure notification time (instead of midnight)
- [ ] Slack/Teams integration
- [ ] Birthday/anniversary reports

---

## Quick Reference Commands

### Run Cron Manually
```bash
php spark cron:birthday-anniversary
```

### Test via Web (superuser/hr only)
```
http://localhost:8080/cron/birthday-anniversary/test
```

### Check Today's Notifications
```sql
SELECT * FROM employee_notifications
WHERE event_date = CURDATE()
AND notification_type = 'event';
```

### List All Cron Commands
```bash
php spark list
```

---

## Support

### Files to Check
1. `app/Commands/BirthdayAnniversaryCron.php` - CLI command
2. `app/Controllers/Cron/BirthdayAnniversaryNotifications.php` - Business logic
3. `app/Config/CustomRoutes/CronRoutes.php` - Routes
4. `writable/logs/log-YYYY-MM-DD.log` - Log files

### Common Issues
- **Database connection:** Check `.env` file
- **Permissions:** Ensure web server can write to `writable/logs/`
- **Timezone:** Check PHP timezone matches your location

---

**Created:** October 25, 2025
**Status:** ✅ Ready for Production
**Dependencies:** Existing notification system (NOTIFICATION_SYSTEM_SUMMARY.md)
