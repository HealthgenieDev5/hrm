# Quick Setup Guide - Announcement System

## 🚀 Quick Start (5 Minutes)

### Step 1: Run Database Migration
```bash
cd D:\LOCALHOST\hrm.healthgenie
php spark migrate
```

### Step 2: Create the Sandwich Rule Announcement
```bash
php spark db:seed SandwichRuleAnnouncementSeeder
```

### Step 3: Test the System

**As Admin:**
1. Navigate to: `https://hrm.healthgenie.test/announcements`
2. You should see the "Sandwich Rule" announcement
3. Click the bar chart icon to view statistics

**As Employee:**
1. Log in as any employee
2. Navigate to home page: `https://hrm.healthgenie.test/`
3. A modal should appear with the sandwich rule announcement
4. Click "I Acknowledge" button
5. Refresh the page - modal should not appear again

---

## ✅ What Was Created

### Files Created:

**Backend (PHP):**
- `app/Database/Migrations/2025-11-27-000001_CreateAnnouncementsTable.php`
- `app/Models/AnnouncementModel.php`
- `app/Models/AnnouncementAcknowledgmentModel.php`
- `app/Controllers/AnnouncementController.php`
- `app/Config/CustomRoutes/AnnouncementRoutes.php`
- `app/Database/Seeds/SandwichRuleAnnouncementSeeder.php`

**Frontend (Views):**
- `app/Views/Announcements/Index.php`
- `app/Views/Announcements/Create.php`
- `app/Views/Announcements/Edit.php`
- `app/Views/Announcements/Statistics.php`

**Modified:**
- `app/Views/User/Profile.php` (added announcement modal HTML & inline JavaScript - optimized, no external files)

**Documentation:**
- `ANNOUNCEMENT_SYSTEM_README.md`
- `ANNOUNCEMENT_SETUP_GUIDE.md`

---

## 🎯 How It Works

1. **Admin creates announcement** at `/announcements/create`
2. **System targets employees** based on criteria (all, department, designation, or specific)
3. **Employee logs in** and visits home page (`/`)
4. **Modal appears automatically** if employee has pending announcements
5. **Employee acknowledges** by clicking button
6. **System records acknowledgment** with timestamp, IP, and user agent
7. **Admin tracks progress** via statistics page

---

## 📊 Database Tables

### `announcements`
Stores all announcements with targeting, dates, and settings.

### `announcement_acknowledgments`
Tracks which employees acknowledged which announcements.

---

## 🔗 Admin Routes

| URL | Purpose |
|-----|---------|
| `/announcements` | List all announcements |
| `/announcements/create` | Create new announcement |
| `/announcements/edit/{id}` | Edit announcement |
| `/announcements/delete/{id}` | Delete announcement |
| `/announcements/statistics/{id}` | View acknowledgment stats |

---

## 🔧 Configuration Options

### Announcement Settings:

**Type:** (Visual color)
- Info (Blue)
- Success (Green)
- Warning (Yellow)
- Danger (Red)

**Priority:** (Display order)
- Low
- Medium
- High
- Critical

**Target Audience:**
- All Employees
- Specific Departments (select multiple)
- Specific Designations (select multiple)
- Specific Employees (comma-separated IDs)

**Options:**
- Active/Inactive
- Requires Acknowledgment (Yes/No)
- Show Only Once (Yes/No)
- Start Date (optional)
- End Date (optional)

---

## 🎨 Customization

### Change Where Modal Appears

Currently shows on: `/` (home/profile page)

**Note:** All JavaScript is inline in `Profile.php` (optimized, no external files).
Modal HTML IDs: `#sysAnnModal`, `#sysAnnTitle`, `#sysAnnMsg`, etc.

### Change Modal Colors

Edit: `app/Views/User/Profile.php` (search for "System Announcement")
Look for the `showAnnouncement()` function and modify the color classes.

---

## 🐛 Troubleshooting

### Modal Not Showing?

**Check 1:** Is announcement active?
```sql
SELECT id, title, is_active FROM announcements WHERE is_active = 1;
```

**Check 2:** Has employee already acknowledged?
```sql
SELECT * FROM announcement_acknowledgments
WHERE announcement_id = 1 AND employee_id = {your_employee_id};
```

**Check 3:** Browser console errors?
Open Developer Tools (F12) > Console tab > Look for errors

**Check 4:** JavaScript code present?
View page source > Search for "System Announcement"

### "Table doesn't exist" Error?

Run migration:
```bash
php spark migrate
```

### Admin Page 404 Error?

Clear route cache:
```bash
php spark cache:clear
```

---

## 💡 Tips

1. **Test with multiple employees** to see targeting work
2. **Use start/end dates** for time-sensitive announcements
3. **Check statistics regularly** to see who hasn't acknowledged
4. **Use priority levels** to control display order
5. **All code is inline** in Profile.php - no external JavaScript files needed

---

## 📝 Create Your First Custom Announcement

1. Go to `/announcements/create`
2. Title: "Welcome to New Features"
3. Message: "Check out our new dashboard improvements!"
4. Type: Info
5. Priority: Medium
6. Target: All Employees
7. Active: ✓
8. Requires Acknowledgment: ✓
9. Show Once: ✓
10. Click "Create Announcement"

Test by logging in as an employee!

---

## 🎉 Success Checklist

- [ ] Migration completed without errors
- [ ] Seed data inserted successfully
- [ ] Admin page loads at `/announcements`
- [ ] Can create new announcement
- [ ] Modal appears on home page for employees
- [ ] Acknowledgment button works
- [ ] Statistics page shows data
- [ ] Modal doesn't reappear after acknowledgment

---

## 📞 Need Help?

1. Check `ANNOUNCEMENT_SYSTEM_README.md` for detailed documentation
2. Enable debug mode: `debug: true` in JavaScript
3. Check PHP error logs: `writable/logs/`
4. Check browser console for JavaScript errors

---

**System Ready!** 🚀
The announcement system is fully functional and reusable for future announcements.
