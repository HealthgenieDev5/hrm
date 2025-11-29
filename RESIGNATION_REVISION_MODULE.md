# Resignation Revision Module

## Overview
Complete revision tracking system for resignations with JSON data storage and right sidebar history view.

## Features Implemented

### 1. Database Table: `resignations_revision`
Stores complete revision history with JSON data format.

**Fields:**
- `id` - Primary key
- `resignation_id` - Reference to original resignation
- `revision_data` - Complete resignation data as JSON string
- `action` - Type of action (created, updated, completed, withdrawn)
- `action_by` - Employee who performed the action
- `action_note` - Additional notes about the change
- `created_at` - Timestamp of revision

**JSON Data Structure Example:**
```json
{
  "employee_id": 123,
  "resignation_date": "2025-01-15",
  "resignation_reason": "Better opportunity",
  "buyout_days": 5,
  "status": "active",
  "submitted_by_hr": 40
}
```

### 2. ResignationRevisionModel
Full-featured model with helper methods:

- `saveRevision()` - Save a new revision with JSON data
- `getRevisionHistory()` - Get all revisions for a resignation with employee details
- `getLatestRevision()` - Get the most recent revision
- `compareRevisions()` - Compare two revisions to find differences

### 3. Automatic Revision Tracking
Revisions are automatically saved on:

**Create:**
- Action: `created`
- Saves initial resignation data
- Note: "Initial resignation record created"

**Update:**
- Action: `updated`
- Saves updated resignation data
- Note: "Resignation details updated"

**Complete:**
- Action: `completed`
- Saves final state with date_of_leaving
- Note: "Resignation marked as completed. Last working day: {date}"

**Withdraw:**
- Action: `withdrawn`
- Saves withdrawn state
- Note: "Resignation withdrawn by HR"

### 4. Right Sidebar History View
Beautiful offcanvas sidebar on `/resignation` dashboard:

**Features:**
- **History Button** - Info-colored button in actions column on all tables
- **Offcanvas Sidebar** - 600px wide right-side panel
- **Timeline View** - Chronological display of all revisions
- **Color-Coded Badges:**
  - Created: Green
  - Updated: Warning/Orange
  - Completed: Info/Blue
  - Withdrawn: Secondary/Gray
- **Employee Info** - Shows who made each change
- **Data Snapshot** - Displays key fields from each revision
- **Timestamps** - Full date and time of each action

### 5. Files Created/Modified

**New Files:**
- `app/Database/Migrations/2025-10-07-103747_CreateResignationsRevisionTable.php`
- `app/Models/ResignationRevisionModel.php`
- `create_resignations_revision.sql` (manual SQL for table creation)
- `RESIGNATION_REVISION_MODULE.md` (this documentation)

**Modified Files:**
- `app/Controllers/ResignationController.php`
  - Added ResignationRevisionModel import
  - Updated store() to save "created" revision
  - Updated update() to save "updated" revision
  - Updated withdraw() to save "withdrawn" revision
  - Updated complete() to save "completed" revision
  - Added getRevisionHistory() method for AJAX

- `app/Views/Resignation/Dashboard.php`
  - Added offcanvas sidebar HTML
  - Added "History" button to actions column (all tables)
  - Added JavaScript to load and display revisions
  - Added timeline styling and formatting

- `app/Config/CustomRoutes/ResignationRoutes.php`
  - Added `/ajax/resignation/history/(:num)` route

## Usage

### Viewing Revision History
1. Navigate to `/resignation` dashboard
2. Find any resignation in any tab
3. Click the **History** button (info icon)
4. Right sidebar slides in showing complete revision history

### What Gets Tracked
Every time a resignation is:
- **Created** - Initial data captured
- **Edited** - Updated data saved
- **Completed** - Final state with last working day
- **Withdrawn** - Withdrawn state saved

Each revision includes:
- Complete resignation data (JSON)
- Who made the change
- When it was changed
- Why it was changed (action note)

## Database Setup

### Option 1: Run Migration (if migration system is clean)
```bash
php spark migrate
```

### Option 2: Manual SQL (recommended due to conflicting migrations)
```sql
-- Run the SQL file
mysql -u username -p database_name < create_resignations_revision.sql
```

Or execute `create_resignations_revision.sql` directly in phpMyAdmin/MySQL Workbench.

## API Endpoints

### Get Revision History (AJAX)
```
GET/POST /ajax/resignation/history/{resignation_id}
```

**Response:**
```json
[
  {
    "id": 1,
    "resignation_id": 5,
    "revision_data": {
      "employee_id": 123,
      "resignation_date": "2025-01-15",
      "status": "active"
    },
    "action": "created",
    "action_note": "Initial resignation record created",
    "created_at": "2025-10-07 10:30:00",
    "action_by_name": "John Doe",
    "action_by_emp_id": "EMP001"
  }
]
```

## Benefits

✅ **Complete Audit Trail** - Every change is tracked with full data
✅ **JSON Storage** - Flexible, easy to query and display
✅ **Who/When/What** - Know exactly who changed what and when
✅ **Beautiful UI** - Right sidebar with timeline view
✅ **No Manual Work** - Automatic revision saving on all actions
✅ **Easy to Extend** - Add more fields to JSON without schema changes
✅ **Accessible** - History button on every resignation record

## Future Enhancements (Optional)

1. **Compare Revisions**
   - Side-by-side comparison of any two revisions
   - Highlight differences

2. **Restore Functionality**
   - Restore resignation to a previous state
   - Requires additional permissions

3. **Export History**
   - Export revision history to PDF/Excel
   - Useful for audits

4. **Email Notifications**
   - Send email when important changes occur
   - Notify managers of status changes

5. **Advanced Search**
   - Search revisions by date range
   - Filter by action type or employee

## Testing

### Test Revision Creation
1. Create a new resignation
2. Click History button
3. Should see one "CREATED" revision with initial data

### Test Revision Updates
1. Edit a resignation (change buyout days)
2. Click History button
3. Should see "UPDATED" revision with new data

### Test Completion
1. Mark resignation as completed
2. Click History button
3. Should see "COMPLETED" revision with date_of_leaving note

### Test Withdrawal
1. Withdraw a resignation
2. Click History button
3. Should see "WITHDRAWN" revision

## Notes

- All revisions are stored as TEXT (JSON string) for maximum compatibility
- The `revision_data` field can store any JSON structure
- Revisions are never deleted - permanent audit trail
- History button appears on all tabs: Active, Urgent Alerts, and Completed
- Sidebar width is 600px for comfortable viewing
- Bootstrap 5 offcanvas component used for sidebar
