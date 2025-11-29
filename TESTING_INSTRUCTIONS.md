# Job Closure System Testing Instructions

## Prerequisites
1. **Database Setup**: Run the SQL file `create_closure_table.sql` in your database to create the required table and update status enum
2. **Development Server**: Server is already running on http://localhost:8080

## Testing Steps

### Step 1: Create/Verify Test Data
1. Make sure you have at least one active job listing in the system
2. Ensure you have employee records in the `employees` table for testing dropdowns

### Step 2: Test HR Executive Workflow
1. **Login as HR Executive** (employee_id: 52)
2. **Navigate to**: http://localhost:8080/recruitment/job-listing/all
3. **Look for**: Jobs with status "Active" should show a red "Start Closure Process" button (stop-circle icon)
4. **Click the button** → Should show confirmation dialog
5. **Confirm** → Should redirect to HR Approval Form
6. **Fill the form**:
   - Select candidate from dropdown
   - Select replacement employee
   - Add assessment notes
   - Submit form
7. **Expected Result**: Job status changes to "Pending Manager Feedback"

### Step 3: Test Manager Feedback Workflow
1. **Login as Department HOD** (the HOD for the job's department)
2. **Navigate to**: Job listing page
3. **Look for**: Jobs with status "Pending Manager Feedback" should show orange "Provide Manager Feedback" button (comment icon)
4. **Click the button** → Should redirect to Manager Feedback Form
5. **Fill the form**:
   - Provide team strengths/weaknesses
   - Answer job posting questions
   - Provide team assessment
   - Submit form
6. **Expected Result**: Job status changes to "Closed" and closure is completed

### Step 4: Test Closure History
1. **Any authorized user** can view closure history
2. **Look for**: Jobs with status "Closed" or "Partially Closed" should show blue "View Closure History" button (history icon)
3. **Click the button** → Should show complete closure history with all approval details

## UI Elements to Verify

### Status Badges
- **Active**: Green badge
- **Pending Manager Feedback**: Orange badge with "Pending Manager Feedback" text
- **Partially Closed**: Blue badge with "Partially Closed" text
- **Closed**: Dark badge

### Action Buttons
- **HR Executive on Active jobs**: Red button with stop-circle icon
- **HOD on Pending Manager Feedback**: Orange button with comment icon
- **Anyone on Closed jobs**: Blue button with history icon
- **Others**: No closure buttons or "Waiting for Manager" badge

### Filter Dropdown
- New status options should appear in the status filter dropdown

## Expected Workflow
1. **Active** → (HR Executive starts) → **Pending Manager Feedback**
2. **Pending Manager Feedback** → (HOD provides feedback) → **Closed**

## Troubleshooting

### Common Issues:
1. **No buttons showing**: Check user role and job status
2. **Database errors**: Ensure `create_closure_table.sql` was executed
3. **Permission errors**: Verify user has correct employee_id and department access
4. **Forms not loading**: Check that all view files were created correctly

### URLs for Direct Testing:
- Job Listings: http://localhost:8080/recruitment/job-listing/all
- **Job Single View**: http://localhost:8080/recruitment/job-listing/view/{job_id}
- Start Closure: http://localhost:8080/recruitment/job-listing/closure/start/{job_id}
- HR Approval: http://localhost:8080/recruitment/job-listing/closure/hr-approval/{job_id}
- Manager Feedback: http://localhost:8080/recruitment/job-listing/closure/manager-feedback/{job_id}
- Closure History: http://localhost:8080/recruitment/job-listing/closure/history/{job_id}

### Testing in Job Single View:
1. **Navigate to individual job**: Click "View" button from job listings or go directly to job view URL
2. **Look for closure buttons**: Same role-based buttons as in job listings but integrated in single job view
3. **Closure Progress Section**: When job is in closure process, a "Closure Progress" section will appear showing:
   - Current step in closure workflow
   - Visual progress indicators
   - Direct link to closure history

## Test Scenarios

### Scenario 1: Happy Path
1. HR Executive starts closure on active job
2. Selects candidate and replacement
3. HOD provides complete feedback
4. Job is successfully closed

### Scenario 2: Role Validation
1. Non-HR user tries to start closure → Should be redirected to unauthorized
2. Non-HOD tries to provide manager feedback → Should be redirected to unauthorized

### Scenario 3: Status Validation
1. Try to start closure on non-active job → Should show error
2. Try to provide feedback on non-pending job → Should show error

The system is now fully integrated and ready for testing!