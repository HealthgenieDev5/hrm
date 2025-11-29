# Notifications Module - Deployment Guide

## Overview
This document outlines all changes and requirements for deploying the Employee Notifications module to the production server.

---

## 🗂️ Database Changes

### 1. Run Migrations
Execute the following migrations in order:

```bash
php spark migrate
```

**Migration Files (in order):**
1. `app/Database/Migrations/2025-10-11-000000_CreateEmployeeNotificationsTable.php`
2. `app/Database/Migrations/2025-10-11-000001_CreateNotificationReadsTable.php`
3. `app/Database/Migrations/2025-10-11-000002_CreateNotificationEmailLogsTable.php`

### 2. Database Tables Created

#### `employee_notifications`
- `id` (INT, Primary Key, Auto Increment)
- `title` (VARCHAR 255)
- `description` (TEXT)
- `notification_type` (ENUM: 'event', 'reminder', 'alert', 'announcement', 'policy', 'other')
- `event_date` (DATE)
- `reminder_1_date` (DATE, nullable)
- `reminder_2_date` (DATE, nullable)
- `reminder_3_date` (DATE, nullable)
- `target_employees` (JSON, nullable)
- `is_active` (TINYINT, default 1)
- `created_by` (INT, nullable)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

#### `notification_reads`
- `id` (INT, Primary Key, Auto Increment)
- `notification_id` (INT)
- `employee_id` (INT)
- `read_at` (DATETIME)
- `created_at` (DATETIME)
- **Unique Key:** `notification_id` + `employee_id`

#### `notification_email_logs`
- `id` (INT, Primary Key, Auto Increment)
- `notification_id` (INT)
- `employee_id` (INT)
- `email_address` (VARCHAR 255)
- `email_type` (ENUM: 'notification', 'reminder_1', 'reminder_2', 'reminder_3')
- `sent_at` (DATETIME)
- `status` (ENUM: 'sent', 'failed', 'pending')
- `error_message` (TEXT, nullable)
- `created_at` (DATETIME)

---

## 📁 New Files Added

### Controllers
- `app/Controllers/EmployeeNotificationController.php`

### Models
- `app/Models/Notification/EmployeeNotificationModel.php`
- `app/Models/Notification/NotificationReadModel.php`
- `app/Models/Notification/NotificationEmailLogModel.php`

### Views
- `app/Views/Notifications/Index.php` - List all notifications with filters
- `app/Views/Notifications/Create.php` - Create new notification form
- `app/Views/Notifications/Edit.php` - Edit existing notification form

### Routes
- `app/Config/CustomRoutes/NotificationRoutes.php`

### Migrations
- `app/Database/Migrations/2025-10-11-000000_CreateEmployeeNotificationsTable.php`
- `app/Database/Migrations/2025-10-11-000001_CreateNotificationReadsTable.php`
- `app/Database/Migrations/2025-10-11-000002_CreateNotificationEmailLogsTable.php`

---

## 🔧 Modified Files

### 1. **app/Views/Templates/AsideMenu.php**
**Location:** After Appraisals menu item (Lines 273-284)

**Changes:**
- Added "Notifications" menu item in Master Panel
- Visible only to: superuser, hr roles, and employee IDs: 40, 93

```php
<?php if (in_array(session()->get('current_user')['role'], ['superuser', 'hr']) || in_array(session()->get('current_user')['employee_id'], ['40', '93'])): ?>
<div class="menu-item">
    <a class="menu-link <?php if (isset($current_controller) && $current_controller == 'notifications') { echo 'active'; } ?>"
       href="<?php echo base_url('backend/notifications'); ?>">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
        <span class="menu-title">Notifications</span>
    </a>
</div>
<?php endif; ?>
```

### 2. **app/Views/User/Profile.php**
**Location:** Lines 1342-1366

**Changes:**
- Updated `employeeNotificationModal` design to match `probationNotificationModal`
- Added centered layout with emoji display
- Dynamic emoji and icons based on notification type
- Changed button from "Mark as Read" to "Acknowledge"

**JavaScript Function Updates:**
- Enhanced `showEmployeeNotificationModal()` function (Lines 1671-1727)
- Added notification type configuration with emojis and badge colors
- Dynamic content rendering based on notification type

### 3. **app/Helpers/Config_defaults_helper.php**
**Status:** ✅ Already exists (No changes needed)
- Contains `first_date_of_last_month()` function required by sidebar menu

---

## 🔑 Key Features

### Admin Features (HR/Superuser)
1. **Create Notifications**
   - Title, Description, Notification Type
   - Event Date with 3 optional reminder dates
   - Target specific employees or broadcast to all
   - Form UI matches recruitment/job-listing design

2. **List & Manage Notifications**
   - DataTables with advanced filtering
   - Filter by: Type, Status, Date Range
   - Fixed columns for better UX
   - Edit and Delete capabilities
   - Read count tracking per notification

3. **Edit Notifications**
   - Update all notification details
   - Maintain target employee selections
   - Consistent UI with Create form

### Employee Features
1. **Dashboard Popup Notifications**
   - Auto-popup on login for unread notifications
   - Celebratory design with emojis
   - Different icons/colors per notification type:
     - 🎉 Event (Info - Blue)
     - ⏰ Reminder (Warning - Yellow)
     - ⚠️ Alert (Danger - Red)
     - 📢 Announcement (Success - Green)
     - 📋 Policy (Secondary - Gray)
     - 💬 Other (Primary - Blue)

2. **Notification Acknowledgment**
   - Single "Acknowledge" button
   - Tracks read status per employee
   - Sequential display of unread notifications

---

## 🛣️ Routes

### Backend Routes (Admin)
```php
GET  /backend/notifications              - List all notifications
GET  /backend/notifications/create       - Create form
POST /backend/notifications/store        - Store new notification
GET  /backend/notifications/edit/:id     - Edit form
POST /backend/notifications/update/:id   - Update notification
POST /backend/notifications/delete/:id   - Delete notification
```

### AJAX Routes
```php
POST /ajax/notifications/table              - DataTables data
POST /ajax/notifications/dashboard          - Dashboard notifications
POST /ajax/notifications/mark-as-read       - Mark as read
```

---

## 🎨 UI Design Patterns

### Index Page (List View)
- Follows `recruitment/job-listing/all` design
- Filter card with dropdowns and date range picker
- DataTables with fixed columns (ID left, Actions right)
- Badge styling for notification types
- Export to Excel button

### Create/Edit Forms
- Follows `recruitment/job-listing` form design
- Floating labels with clean animations
- Card-based layout with header and toolbar
- Consistent color scheme (#f5f8fa background)
- Select2 for employee multi-select
- Flatpickr for date selection

### Notification Modal
- Follows `probationNotificationModal` design
- Centered layout without background color
- Large emoji display (3rem)
- Single centered "Acknowledge" button
- Clean, celebration-style design

---

## 🔐 Permissions

### Access Control
**Admin Access (Create/Edit/Delete):**
- Role: `superuser`
- Role: `hr`
- Employee IDs: `40`, `93`

**View Access (Dashboard Popups):**
- All employees can view notifications targeted to them
- System checks `target_employees` JSON field
- Empty `target_employees` = broadcast to all

---

## 📦 Dependencies

### PHP Packages (Already installed)
- CodeIgniter 4
- Standard CI4 libraries

### Frontend Libraries (CDN)
- **DataTables:** 1.13.6
  - `dataTables.bootstrap5.min.js`
  - `dataTables.bootstrap5.min.css`
- **Fixed Columns Plugin:** 4.3.0
  - `dataTables.fixedColumns.min.js`
  - `fixedColumns.bootstrap5.min.css`
- **Flatpickr:** Latest
  - `flatpickr.min.js`
  - `flatpickr.min.css`
- **Select2:** (Already in use)
- **SweetAlert2:** (Already in use)

---

## 🚀 Deployment Steps

### 1. Backup Production Database
```bash
# Create backup before deployment
mysqldump -u username -p database_name > backup_before_notifications_$(date +%Y%m%d).sql
```

### 2. Upload Files
Upload the following directories/files to production:
```
app/Controllers/EmployeeNotificationController.php
app/Models/Notification/
app/Views/Notifications/
app/Config/CustomRoutes/NotificationRoutes.php
app/Database/Migrations/2025-10-11-000000_CreateEmployeeNotificationsTable.php
app/Database/Migrations/2025-10-11-000001_CreateNotificationReadsTable.php
app/Database/Migrations/2025-10-11-000002_CreateNotificationEmailLogsTable.php
```

### 3. Update Modified Files
Replace these existing files:
```
app/Views/Templates/AsideMenu.php (Lines 273-284)
app/Views/User/Profile.php (Lines 1342-1366, 1671-1727)
```

### 4. Run Migrations
```bash
cd /path/to/production
php spark migrate
```

### 5. Set Permissions
```bash
chmod 755 app/Controllers/EmployeeNotificationController.php
chmod 755 app/Models/Notification/*.php
chmod 755 app/Views/Notifications/*.php
```

### 6. Clear Cache
```bash
# Clear application cache
php spark cache:clear

# Clear route cache if exists
php spark route:clear
```

### 7. Test Routes
Access these URLs to verify:
```
https://yourdomain.com/backend/notifications
https://yourdomain.com/backend/notifications/create
```

### 8. Verify Database Tables
```sql
SHOW TABLES LIKE '%notification%';
DESCRIBE employee_notifications;
DESCRIBE notification_reads;
DESCRIBE notification_email_logs;
```

---

## ✅ Post-Deployment Testing

### Test Checklist

#### Admin Tests
- [ ] Login as HR/Superuser
- [ ] Access Notifications menu from Master Panel
- [ ] Create a new notification with all fields
- [ ] Create notification targeting specific employees
- [ ] Edit an existing notification
- [ ] Delete a notification
- [ ] Test filters (Type, Status, Date Range)
- [ ] Test Excel export
- [ ] Verify read count updates

#### Employee Tests
- [ ] Login as regular employee
- [ ] Verify notification popup appears on dashboard
- [ ] Click "Acknowledge" button
- [ ] Verify notification doesn't reappear after acknowledgment
- [ ] Test with employee targeted in notification
- [ ] Test with employee NOT targeted in notification
- [ ] Verify different notification types show correct emojis/colors

#### Edge Cases
- [ ] Create notification with no target (broadcast)
- [ ] Create notification with all 3 reminders
- [ ] Create notification with empty reminders
- [ ] Test concurrent users acknowledging same notification
- [ ] Test special characters in title/description
- [ ] Test very long descriptions

---

## 🐛 Troubleshooting

### Common Issues

#### 1. 404 Error on /backend/notifications
**Solution:** Check routes are loaded
```bash
php spark routes | grep notifications
```

#### 2. 500 Error on Mark as Read
**Solution:** Already fixed in `NotificationReadModel.php`
- Ensure `useTimestamps = false`
- Ensure `created_at` in `allowedFields`

#### 3. Function not found: first_date_of_last_month()
**Solution:** Load helper in controller
```php
helper(['Config_defaults_helper']);
```

#### 4. Modal not appearing
**Solution:** Check JavaScript console for errors
- Verify jQuery is loaded
- Verify Bootstrap modal is initialized
- Check AJAX endpoint returns valid JSON

#### 5. Permissions denied
**Solution:** Verify user role/employee_id
```php
// Check in EmployeeNotificationController
if (!in_array(session()->get('current_user')['role'], ['superuser', 'hr'])
    && !in_array(session()->get('current_user')['employee_id'], ['40', '93'])) {
    return redirect()->to(base_url('/unauthorised'));
}
```

---

## 📊 Database Indexes

The following indexes are automatically created:
- `employee_notifications`: PRIMARY KEY on `id`
- `notification_reads`: PRIMARY KEY on `id`, UNIQUE KEY on (`notification_id`, `employee_id`)
- `notification_email_logs`: PRIMARY KEY on `id`

---

## 🔄 Future Enhancements (Not in this deployment)

### Planned Features
1. **Email Integration**
   - Send email notifications on creation
   - Send reminder emails on scheduled dates
   - Use `notification_email_logs` table

2. **Notification Categories**
   - Group notifications by categories
   - Department-specific notifications

3. **Notification Templates**
   - Pre-defined templates for common notifications
   - Template variables for dynamic content

4. **Analytics Dashboard**
   - Notification engagement metrics
   - Read/unread statistics
   - Popular notification types

5. **Bulk Operations**
   - Bulk delete notifications
   - Bulk archive old notifications
   - Export notification history

---

## 📝 Notes

### Important Considerations
1. **Target Employees JSON Format:**
   ```json
   ["123", "456", "789"]
   ```
   Stored as JSON array of employee IDs (strings)

2. **Notification Types:**
   - Must be one of: event, reminder, alert, announcement, policy, other
   - Each type has specific emoji and badge color

3. **Read Tracking:**
   - One record per employee per notification
   - Unique constraint prevents duplicates
   - Read status is permanent (no "mark as unread")

4. **Active/Inactive Status:**
   - Inactive notifications don't show in dashboard popups
   - Still visible in admin list for management

5. **Created By:**
   - Automatically set to current logged-in user
   - Used for audit trail

---

## 📞 Support

For issues or questions during deployment, contact:
- **Developer:** [Your Name]
- **Date Created:** October 11, 2025
- **Version:** 1.0.0

---

## 📜 Change Log

### Version 1.0.0 (October 11, 2025)
- ✅ Initial module creation
- ✅ Database schema design
- ✅ CRUD operations for notifications
- ✅ Dashboard popup integration
- ✅ Read tracking system
- ✅ UI design matching existing patterns
- ✅ Permission-based access control
- ✅ DataTables with advanced filtering
- ✅ Modal redesign to match probation notification
- ✅ Menu integration in Master Panel

---

**End of Deployment Guide**
