# HRM Portal - Announcement System Documentation

## Overview

A complete, reusable announcement/notification system for the HRM portal that allows administrators to create announcements and track employee acknowledgments. The system automatically displays pending announcements to employees on the home page and records their acknowledgments.

## Features

- ✅ **Employee Acknowledgment Tracking** - Tracks which employees have acknowledged announcements
- ✅ **Targeted Announcements** - Send to all employees, specific departments, designations, or individuals
- ✅ **Priority Levels** - Low, Medium, High, Critical
- ✅ **Type Classifications** - Info, Success, Warning, Danger (with color coding)
- ✅ **Automatic Modal Display** - Shows on home page login for pending announcements
- ✅ **One-time Display** - Option to show only once per employee
- ✅ **Date Range Control** - Schedule announcements with start/end dates
- ✅ **Admin Dashboard** - Full CRUD interface for managing announcements
- ✅ **Statistics & Reporting** - View acknowledgment rates and pending employees
- ✅ **Reusable Components** - Easy to integrate into any page

---

## Installation & Setup

### Step 1: Run Database Migration

Create the required database tables:

```bash
php spark migrate
```

This will create two tables:
- `announcements` - Stores announcement data
- `announcement_acknowledgments` - Tracks employee acknowledgments

### Step 2: Seed Sample Announcement (Optional)

To add the Sandwich Rule announcement:

```bash
php spark db:seed SandwichRuleAnnouncementSeeder
```

### Step 3: Verify Routes

Routes are automatically loaded from `app/Config/CustomRoutes/AnnouncementRoutes.php`

**Available Routes:**
- `GET /announcements` - Admin: List all announcements
- `GET /announcements/create` - Admin: Create announcement form
- `POST /announcements/store` - Admin: Store new announcement
- `GET /announcements/edit/{id}` - Admin: Edit announcement
- `POST /announcements/update/{id}` - Admin: Update announcement
- `GET /announcements/delete/{id}` - Admin: Delete announcement
- `GET /announcements/statistics/{id}` - Admin: View statistics
- `GET /announcement/get-pending` - AJAX: Get pending announcements for logged-in user
- `POST /announcement/acknowledge` - AJAX: Acknowledge announcement

---

## Usage

### For Administrators

#### Creating a New Announcement

1. Navigate to `/announcements` in your browser
2. Click **"Create Announcement"**
3. Fill in the form:
   - **Title**: Short descriptive title
   - **Message**: Full announcement text (supports HTML)
   - **Type**: Info/Success/Warning/Danger (affects modal color)
   - **Priority**: Low/Medium/High/Critical (affects display order)
   - **Target Audience**:
     - All Employees
     - Specific Departments
     - Specific Designations
     - Specific Employees (comma-separated IDs)
   - **Start Date**: When to start showing (optional)
   - **End Date**: When to stop showing (optional)
   - **Active**: Enable/disable announcement
   - **Requires Acknowledgment**: Must user click "I Acknowledge"?
   - **Show Only Once**: Don't show again after acknowledgment

4. Click **"Create Announcement"**

#### Viewing Statistics

1. Go to `/announcements`
2. Click the **bar chart icon** next to any announcement
3. View:
   - Total target employees
   - Acknowledged count
   - Pending count
   - Completion percentage
   - List of acknowledged employees with timestamps
   - List of pending employees

#### Editing/Deleting Announcements

- Click the **pencil icon** to edit
- Click the **trash icon** to delete (requires confirmation)

### For Employees

Employees will automatically see pending announcements when they visit the home page (`/` or `/profile`).

**Modal Behavior:**
1. Modal appears automatically on page load if there are pending announcements
2. Employee reads the announcement
3. Employee clicks **"I Acknowledge"** button
4. If multiple announcements exist, they can navigate using Previous/Next buttons
5. Once acknowledged, the announcement won't show again (if "Show Only Once" is enabled)

---

## Architecture

### Database Schema

#### `announcements` Table

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| title | VARCHAR(255) | Announcement title |
| message | TEXT | Full announcement message (HTML supported) |
| type | ENUM | info, warning, success, danger |
| priority | ENUM | low, medium, high, critical |
| target_type | ENUM | all, department, designation, specific |
| target_ids | TEXT | Comma-separated IDs for targeted announcements |
| start_date | DATETIME | When to start showing (nullable) |
| end_date | DATETIME | When to stop showing (nullable) |
| is_active | TINYINT | Active status (1/0) |
| requires_acknowledgment | TINYINT | Requires acknowledgment (1/0) |
| show_once | TINYINT | Show only once per user (1/0) |
| created_by | INT | User ID who created |
| created_at | DATETIME | Creation timestamp |
| updated_at | DATETIME | Update timestamp |

#### `announcement_acknowledgments` Table

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| announcement_id | INT | Foreign key to announcements |
| employee_id | INT | Employee who acknowledged |
| acknowledged_at | DATETIME | Acknowledgment timestamp |
| ip_address | VARCHAR(45) | IP address of acknowledgment |
| user_agent | TEXT | Browser user agent |

### File Structure

```
app/
├── Config/CustomRoutes/
│   └── AnnouncementRoutes.php          # Route definitions
├── Controllers/
│   └── AnnouncementController.php      # Admin & AJAX endpoints
├── Models/
│   ├── AnnouncementModel.php           # Announcement data model
│   └── AnnouncementAcknowledgmentModel.php  # Acknowledgment tracking
├── Views/Announcements/
│   ├── Index.php                       # List all announcements
│   ├── Create.php                      # Create form
│   ├── Edit.php                        # Edit form
│   └── Statistics.php                  # Statistics view
├── Database/
│   ├── Migrations/
│   │   └── 2025-11-27-000001_CreateAnnouncementsTable.php
│   └── Seeds/
│       └── SandwichRuleAnnouncementSeeder.php
└── Views/User/
    └── Profile.php                     # Home page (modal integrated here)

public/assets/js/
└── announcement-modal.js               # Reusable JavaScript component
```

---

## JavaScript Component API

### AnnouncementModal Class

```javascript
new AnnouncementModal(options)
```

**Options:**

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| checkInterval | Number | 60000 | How often to check for new announcements (ms), 0 = once only |
| autoCheck | Boolean | true | Auto-check on initialization |
| apiEndpoint | String | '/announcement/get-pending' | API endpoint for fetching |
| acknowledgeEndpoint | String | '/announcement/acknowledge' | API endpoint for acknowledgment |
| onAcknowledge | Function | null | Callback after successful acknowledgment |
| debug | Boolean | false | Enable debug logging |

**Methods:**

- `refresh()` - Manually trigger announcement check
- `destroy()` - Stop periodic checking
- `closeModal()` - Close the modal programmatically

**Example Usage:**

```javascript
// Initialize on home page only, check once
window.announcementModal = new AnnouncementModal({
    debug: false,
    checkInterval: 0,
    autoCheck: true,
    onAcknowledge: function(announcement) {
        console.log('User acknowledged:', announcement.title);
    }
});

// Manually refresh
window.announcementModal.refresh();
```

---

## How to Add Modal to Other Pages

If you want the announcement modal on additional pages:

```html
<!-- Add this in your view's scripts section -->
<script src="<?= base_url('assets/js/announcement-modal.js') ?>"></script>
<script>
    $(document).ready(function() {
        window.announcementModal = new AnnouncementModal({
            debug: false,
            checkInterval: 60000, // Check every 60 seconds
            autoCheck: true
        });
    });
</script>
```

---

## Customization

### Changing Modal Appearance

Edit `public/assets/js/announcement-modal.js` in the `createModalHTML()` method to customize:
- Modal size (change `modal-lg` to `modal-xl`, `modal-sm`, etc.)
- Button text and icons
- Header/footer styles

### Adding Custom Fields

1. Add column to migration
2. Update `$allowedFields` in `AnnouncementModel.php`
3. Add input field to `Create.php` and `Edit.php` views
4. Update controller's `store()` and `update()` methods

### Custom Targeting Logic

Modify `AnnouncementModel::getPendingAnnouncementsForEmployee()` to add custom targeting rules.

---

## Testing

### Test the System

1. **Create Announcement:**
   ```
   Navigate to: /announcements/create
   Fill form and submit
   ```

2. **Test Employee View:**
   ```
   Log in as an employee
   Visit home page (/)
   Modal should appear automatically
   ```

3. **Test Acknowledgment:**
   ```
   Click "I Acknowledge"
   Refresh page - modal should not appear again
   ```

4. **Check Statistics:**
   ```
   Navigate to: /announcements/statistics/{announcement_id}
   Verify acknowledgment is recorded
   ```

### Debug Mode

Enable debug mode in JavaScript:

```javascript
window.announcementModal = new AnnouncementModal({
    debug: true  // Logs all actions to console
});
```

---

## Security Considerations

- ✅ CSRF protection on all POST requests
- ✅ Input validation in models
- ✅ XSS protection with `esc()` in views
- ✅ Authentication filter on admin routes
- ✅ IP address and user agent tracking for audit trail
- ✅ Foreign key constraints for data integrity

---

## Troubleshooting

### Modal Not Appearing

1. Check browser console for JavaScript errors
2. Verify `announcement-modal.js` is loaded: View Source > Search for "announcement-modal.js"
3. Check if announcements exist and are active in database
4. Ensure employee hasn't already acknowledged

### No Pending Announcements

Check in database:
```sql
SELECT * FROM announcements WHERE is_active = 1;
```

### Statistics Not Showing

Verify:
- Employee belongs to target audience
- Announcement is within date range (if specified)
- Announcement is active

### AJAX Errors

Check:
- Routes are registered: `php spark routes | grep announcement`
- Session is active (user is logged in)
- Database tables exist: `php spark migrate:status`

---

## Future Enhancements

Possible improvements:
- Email notifications for critical announcements
- Rich text editor for message formatting
- File attachments
- Scheduled announcements (publish in future)
- Announcement categories/tags
- Multi-language support
- Push notifications
- Read receipts without acknowledgment requirement

---

## Support

For issues or questions:
1. Check this documentation
2. Review code comments in source files
3. Enable debug mode for troubleshooting
4. Contact system administrator

---

## Changelog

### Version 1.0 (2025-11-27)
- Initial release
- Core announcement system
- Employee acknowledgment tracking
- Admin dashboard
- Statistics and reporting
- Reusable JavaScript component
- Sandwich Rule announcement seeder

---

## License

Internal use only - Healthgenie HRM Portal
