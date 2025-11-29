# Announcement System - Quick Reference Card

## 🚀 5-Minute Setup

```bash
php spark migrate
php spark db:seed SandwichRuleAnnouncementSeeder
```

**Done!** Visit `/` to see the modal.

---

## 📍 Key Locations

| What | Where |
|------|-------|
| **Admin Panel** | `/announcements` |
| **Create New** | `/announcements/create` |
| **View Stats** | `/announcements/statistics/{id}` |
| **Modal Code** | `app/Views/User/Profile.php` lines 1343-1360, 4712-4779 |
| **Controller** | `app/Controllers/AnnouncementController.php` |
| **Models** | `app/Models/Announcement*.php` |

---

## 🎯 Quick Actions

### Create Announcement
1. Go to `/announcements/create`
2. Fill form
3. Submit
4. Done!

### Check Who Acknowledged
1. Go to `/announcements`
2. Click bar chart icon next to announcement
3. View stats

### Edit/Delete
1. Go to `/announcements`
2. Click pencil (edit) or trash (delete) icon

---

## 💻 Code Locations

### Modal HTML (17 lines)
```
File: app/Views/User/Profile.php
Lines: 1343-1360
```

### JavaScript (68 lines)
```
File: app/Views/User/Profile.php
Lines: 4712-4779
```

### Search Terms
- Search for: `System Announcement`
- Modal ID: `#sysAnnModal`

---

## 🔧 Customization

### Change Colors
**File:** `app/Views/User/Profile.php`
**Search:** `showAnnouncement(idx)`
**Lines:** 4723-4727

```javascript
if(a.type == 'warning') hdr.addClass('bg-warning text-dark');
else if(a.type == 'danger') hdr.addClass('bg-danger text-white');
else if(a.type == 'success') hdr.addClass('bg-success text-white');
else hdr.addClass('bg-primary text-white');
```

### Change Delay
**Line:** 4767
```javascript
setTimeout(function() { ... }, 2000); // Change 2000 to your value (milliseconds)
```

---

## 🐛 Troubleshooting

| Issue | Fix |
|-------|-----|
| Modal not showing | Check: `/announcement/get-pending` endpoint working? |
| "Table doesn't exist" | Run: `php spark migrate` |
| JavaScript error | Check browser console (F12) |
| Already acknowledged | Check: `announcement_acknowledgments` table |

---

## 📊 Database Quick Checks

```sql
-- Active announcements
SELECT id, title, is_active FROM announcements WHERE is_active = 1;

-- Employee acknowledgments
SELECT * FROM announcement_acknowledgments WHERE employee_id = {ID};

-- Pending count
SELECT COUNT(*) FROM announcements a
WHERE NOT EXISTS (
    SELECT 1 FROM announcement_acknowledgments aa
    WHERE aa.announcement_id = a.id AND aa.employee_id = {ID}
);
```

---

## 📋 Form Fields Reference

| Field | Options |
|-------|---------|
| **Type** | info, success, warning, danger |
| **Priority** | low, medium, high, critical |
| **Target** | all, department, designation, specific |
| **Active** | Yes/No |
| **Requires Ack** | Yes/No |
| **Show Once** | Yes/No |
| **Dates** | Optional (start/end) |

---

## 🎨 Modal Element IDs

| Element | ID |
|---------|-----|
| Modal | `#sysAnnModal` |
| Header | `#sysAnnHdr` |
| Title | `#sysAnnTitle` |
| Message | `#sysAnnMsg` |
| Counter | `#sysAnnCount` |
| Acknowledge Btn | `#sysAnnAck` |
| Previous Btn | `#sysAnnPrev` |
| Next Btn | `#sysAnnNext` |

---

## 📞 Common URLs

```
Admin List:      /announcements
Create New:      /announcements/create
Edit:            /announcements/edit/{id}
Delete:          /announcements/delete/{id}
Statistics:      /announcements/statistics/{id}

AJAX Endpoints:
Get Pending:     /announcement/get-pending
Acknowledge:     /announcement/acknowledge
```

---

## ✅ Testing Checklist

- [ ] Migration run successfully
- [ ] Seeder created announcement
- [ ] Admin can view at `/announcements`
- [ ] Admin can create new announcement
- [ ] Employee sees modal on home page
- [ ] "I Acknowledge" button works
- [ ] Modal doesn't reappear after acknowledgment
- [ ] Statistics show correct data

---

## 🎉 Quick Tips

1. **Test first** with a small group (use "specific employees" targeting)
2. **Use priority** to control display order (high priority shows first)
3. **Set end dates** for time-sensitive announcements
4. **Check stats regularly** to track acknowledgment progress
5. **All code is inline** - no external files to manage!

---

**Need More Help?**
- Full docs: `ANNOUNCEMENT_SYSTEM_README.md`
- Setup guide: `ANNOUNCEMENT_SETUP_GUIDE.md`
- Summary: `ANNOUNCEMENT_IMPLEMENTATION_SUMMARY.md`
