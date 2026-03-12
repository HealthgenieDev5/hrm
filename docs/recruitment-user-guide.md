# Recruitment Module — User Reference Guide

## 1. Overview

The Recruitment module manages the full lifecycle of job vacancies at Healthgenie/GSTC — from creating a job listing through a 3-stage approval chain, to tracking comments/issues, delegating recruitment tasks to HR team members, and formally closing the position once a candidate is selected.

### Who Can Do What

| Role | Create Listing | Approve | Assign Tasks | Close Job | View |
|------|:-:|:-:|:-:|:-:|------|
| HR / HR Executive (role = `hr`) | ✅ | Stage 1 (emp 52 only) | ✅ | Initiate (Phase 1) | **All** |
| HOD | ✅ | Stage 2 | — | Finalize (Phase 2) | Own dept |
| HR Manager (emp 293) | ✅ | Stage 3 | — | — | **All** |
| Manager / TL | ✅ | — | — | Finalize (Phase 2) | Own listings |
| superuser / admin / emp 40 | ✅ | — | — | — | **All** |

> **Note on view scope:** Access is determined by the `role` column in the database.
> - Roles `hr`, `admin`, `superuser`, or employee ID 40 → see **all** listings.
> - HODs → see listings belonging to their department.
> - Everyone else → see only listings they personally created.

---

## 2. Creating a Job Listing

Go to **Recruitment → Job Listings → New Job Listing**.

Fill in all required fields. Below is a complete example:

```
Job Title:                Senior Software Engineer
Listing Title:            SSE – Backend (March 2026)
Company:                  GSTC
Department:               IT
Job Type:                 Full Time
Job Location:             Mumbai HO
Interview Location:       Mumbai HO
Office Seating Location:  Floor 3 – IT Bay
Min Experience:           3 years
Max Experience:           7 years
Min Budget:               ₹8,00,000
Max Budget:               ₹14,00,000
Shift Timing:             09:30 AM – 06:30 PM (General)   ← loaded from database
Reporting To:             Rajesh Sharma (IT Manager)       ← filtered by company + dept
Educational Qualification: B.E. / B.Tech (CS / IT)
Specific Industry:        IT / Software
IQ Test Required:         No
English Test Required:    Yes
Operation Test Required:  No
Other Test Required:      Yes → "Spring Boot + SQL assessment"
Candidate Review 3M:      Priya Mehta (HOD IT)
Candidate Review 6M:      Priya Mehta (HOD IT)
Candidate Review 12M:     Rajesh Sharma (IT Manager)
Target Closure Date:      2026-04-30
KRA File:                 SSE_KRA_2026.pdf  (upload)
Job Description:          [rich-text editor — paste full JD here]
```

**Multiple vacancies:** Set **No. of Vacancies** (hidden field, default 1). If set to 3, the system creates 3 separate listing rows — each goes through the full approval workflow and is tracked independently.

**Shift Timing dropdown** is populated dynamically from the `shifts` table. Values display as `HH:MM – HH:MM (Shift Name)`.

**Reporting To dropdown** is filtered by the selected Company and Department — only active employees in that department (or managers/HODs) are shown.

---

## 3. Approval Workflow (3 Stages)

A new listing starts in **draft** status and must pass three approvals before it goes live.

### Stage 1 — HR Executive (employee ID 52)

- **Who:** Only employee ID 52 can perform this step.
- **Action:** Open the job listing → click **Approve (HR Exec)**.
- **Result:** `approved_by_hr_executive` is set; a notification is sent to the department's HOD.
- **Auto-skip HOD:** If the listing was created by the HOD themselves, Stage 2 is auto-approved and the notification jumps directly to the HR Manager.

### Stage 2 — HOD

- **Who:** The HOD of the job's department.
- **Action:** Click the bell icon (header) → open the notification → click **Approve (HOD)**.
- **Result:** `approved_by_hod` is set; a notification is sent to the HR Manager (emp 293).

### Stage 3 — HR Manager (employee ID 293)

- **Who:** Only employee ID 293 can perform this step.
- **Action:** Open the job → click **Approve (HR Manager)**.
- **Result:** `approved_by_hr_manager` set, `job_opening_date` = today's date, `status` → **open**. The listing is now live.

### Example Timeline

```
Mar 10 10:00  Ravi (manager) creates SSE listing          → status: draft
Mar 10 10:05  HR Exec (emp 52) approves Stage 1           → HOD Priya notified
Mar 11 09:30  HOD Priya approves Stage 2                  → HR Manager (293) notified
Mar 11 11:00  HR Manager (293) approves Stage 3           → status: open, opening date: 2026-03-11
```

---

## 4. Viewing & Filtering Listings

URL: `/recruitment/job-listing/all`

**What you see** depends on your role (see table in §1).

**Filter bar options:**
- Company
- Department
- Job Type
- Status
- Date range (created at)

### Status Values

| Status | Meaning |
|--------|---------|
| `draft` | Created, not yet fully approved |
| `open` | All 3 approvals done — actively hiring |
| `in progress` | Interviews are ongoing |
| `partially closed` | HR Executive has initiated closure (Phase 1 done) |
| `closed` | Fully closed — candidate selected, manager finalized |
| `on hold` | Temporarily paused |

---

## 5. Comments & Issues System

The job detail page has a threaded comment panel with three types:

| Type | Purpose | When to use |
|------|---------|-------------|
| **Comment** | General note visible to all | Progress updates, portal activity |
| **Issue** | Flag a problem that needs resolution | Budget concern, conflicting requirements |
| **Resolution** | Reply to close an open issue | Confirming the fix or decision made |

**Example thread:**

```
Issue #1 — HOD Priya (Mar 11):
  "Shift timing conflicts with client site visits."

  Resolution — HR Exec (Mar 12):
  "Shift changed to flexible 09:00–18:00. Updated in listing."
  → Issue #1 auto-closed ✅

Comment — HR Exec (Mar 13):
  "JD shared on Naukri, LinkedIn, and internal portal."
```

### Notification Routing

- **Before Stage 1 approval:** notifications route between the creator and HR Executive.
- **After Stage 1 approval:** notifications route between the creator and the HOD (via HR Executive).
- The bell icon in the header shows the unread count; clicking it opens a grouped panel.

---

## 6. Job Closure (2 Phases)

Closing a listing is a 2-phase process requiring both HR and management input.

### Phase 1 — HR Executive Initiates

HR Executive fills in:
- Selected candidate name / employee ID
- Which employee is being replaced (if this is a backfill)
- HR assessment notes

After submission, status → **partially closed**.

### Phase 2 — Manager or HOD Finalizes

The hiring manager or HOD fills in:
- Candidate strengths and weaknesses
- Current team size
- Best and worst performer in the team
- Keep posting open? (Yes / No + reason)
- Need a replacement hire? (Yes / No + details)
- Notice period compliance notes

After submission, status → **closed**, `job_closing_date` = today.

### Example

```
Phase 1 — HR Exec (Apr 28):
  Selected Candidate: Amit Verma (applied via Naukri)
  Replacing:          N/A (new headcount)
  HR Notes:           "Strong backend skills, cleared all 3 rounds"
  → status: partially closed

Phase 2 — HOD Priya (Apr 29):
  Strengths:          "Problem-solving, clear communication"
  Weaknesses:         "Limited cloud exposure"
  Team size:          8
  Keep posting open:  No
  → status: closed, closure date: Apr 29
```

### PDFs Generated Automatically

| PDF | Contents |
|-----|---------|
| **Job Opening PDF** | Full job card + approver signatures |
| **Job Closure PDF** | Closure summary + manager/HOD notes |

---

## 7. Task Assignment (HR Executive only)

HR Executives can delegate recruitment activities to HR team members from the job detail page.

### 11 Task Types

1. Source Candidates
2. Screen Resumes / CVs
3. Schedule Interviews
4. Conduct Telephonic Screening
5. Send Job Offer Letter
6. Background Verification
7. Collect Interview Feedback
8. Job Portal Posting / Update
9. Follow-up with Candidates
10. Reference Check
11. Coordinate with Department HOD

### How to Assign

1. Open the job listing detail page.
2. Click **Assign Task**.
3. Select task type, one or more assignees, due date, and remarks.
4. Submit.

**Multiple assignees:** One task-assignee row is created per person — each tracks their own status independently.

### Example

```
Job Listing:  SSE – Backend (March 2026)
Task Type:    Screen Resumes / CVs
Assigned To:  Neha Joshi, Sandeep More
Remarks:      "Filter for 4+ yrs Spring Boot; ignore profiles without SQL"
Due Date:     2026-03-20
```
→ Creates 2 rows, one for Neha and one for Sandeep.

### Status Flow

```
pending → in_progress → completed
```

Assigned HR members update their own task status from the Task Dashboard.

---

## 8. Task Dashboard

URL: `/recruitment/task-dashboard`

### What You See

| Role | Visible Tasks |
|------|--------------|
| HR Executive | All tasks across all jobs; can filter by assignee |
| HR team member | Only tasks assigned to them |

### Filters

- Status (`pending`, `in_progress`, `completed`)
- Task Type
- Due Date range
- Assigned To (HR Executive only)

### Task Cards Show

- Job listing name
- Task type
- Assigned-to name with their current status
- Due date (highlighted red if overdue)
- Remarks
- Revision history popover (change log)

### Excel Export

Use the **Download** button to export the currently filtered task list as `.xlsx`.
