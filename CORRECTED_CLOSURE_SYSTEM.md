# ✅ Two-Stage Job Closure System

## Overview

The job closure system implements a **two-stage closure process** where HR Executive initiates partial closure and Reporting Manager completes final closure. The close button is positioned beside the approval system in the job view.

## Two-Stage Closure Workflow

### Stage 1: **HR Executive Partial Closure**

- **Who**: HR Executive (ID: 52)
- **Trigger**: Click "Close Job" button in job view
- **Action**: Modal appears to collect closure details
- **Collects**:
  - Selected candidate (who was hired)
  - Replacement of employee (which position was filled)
  - Closure reason/notes
  - Additional HR assessment details
- **Result**: Job status → `partially_closed`
- **Database**: Creates record in `rc_job_closure_approvals` table

### Stage 2: **Reporting Manager Final Closure**

- **Who**: Reporting Manager/Department HOD
- **Trigger**: "Finalize Closure" option appears when status is `partially_closed`
- **Action**: Modal appears to collect final closure details
- **Collects**:
  - Final closure notes
  - Team assessment (if applicable)
  - Confirmation of closure
  - Any additional manager comments
- **Result**: Job status → `closed`, closure complete
- **Database**: Updates existing closure record with manager approval

## Database Structure

### Table: `rc_job_closure_approvals`

## Table Structure

CREATE TABLE `rc_job_closure_approvals` (
`id` int NOT NULL AUTO_INCREMENT,
`job_listing_id` int NOT NULL,
`selected_candidate_id` int DEFAULT NULL,
`replacement_of_employee_id` int DEFAULT NULL,
`hr_assessment_notes` text,
`hr_approved_by` int DEFAULT NULL,
`hr_approved_at` datetime DEFAULT NULL,
`strengths` text,
`weaknesses` text,
`keep_posting_open` enum('yes','no') DEFAULT NULL,
`keep_posting_reason` text,
`current_team_size` int DEFAULT NULL,
`best_performer_id` int DEFAULT NULL,
`worst_performer_id` int DEFAULT NULL,
`need_replacement` enum('yes','no') DEFAULT NULL,
`replacement_details` text,
`manager_comments` text,
`manager_approved_by` int DEFAULT NULL,
`manager_approved_at` datetime DEFAULT NULL,
`current_step` enum('hr_approval','manager_approval') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'hr_approval',
`final_closure_date` datetime DEFAULT NULL,
`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
`updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
KEY `idx_job_listing_id` (`job_listing_id`),
KEY `idx_selected_candidate` (`selected_candidate_id`),
KEY `idx_replacement_employee` (`replacement_of_employee_id`),
KEY `idx_best_performer` (`best_performer_id`),
KEY `idx_worst_performer` (`worst_performer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3

- Stores all closure approval data
- Follows same pattern as job opening approvals
- Contains fields for each approval step

### Updated: `rc_job_listing`

- **Enhanced `status` field** to include:

  - `active`: Job is open and active
  - `open`: Job recruitment in progress
  - `partially_closed`: HR Executive has initiated closure (Stage 1 complete)
  - `closed`: Final closure by Reporting Manager (Stage 2 complete)
  - `inactive`, `pending`, `draft`, `rejected`: Other existing statuses

- **Migration Required**:

```sql
ALTER TABLE `rc_job_listing`
MODIFY COLUMN `status` ENUM(
    'active',
    'open',
    'inactive',
    'pending',
    'draft',
    'partially_closed',
    'closed',
    'rejected'
) DEFAULT 'active';
```

## UI Integration Strategy

### Close Button Placement

The closure system integrates into the existing approval section in `JobSingleView.php`:

#### For HR Executive (Stage 1):

```php
// After fully approved section, show close button for HR Executive
if (!empty($hrExecutive) && !empty($hodApproval) && !empty($hrManager) && $job->status != 'partially_closed' && $job->status != 'closed') {
    if ($currentUser == 52) { // HR Executive
        echo '<button class="btn btn-danger w-100 text-center mt-3 job-close-btn" data-job-id="' . $job->id . '" data-closure-stage="hr_executive">';
        echo '<i class="fas fa-times-circle me-2"></i>Close Job';
        echo '</button>';
    }
}
```

#### For Reporting Manager (Stage 2):

```php
// When status is partially_closed, show finalize closure for reporting manager
if ($job->status == 'partially_closed') {
    echo '<div class="alert alert-warning">Job partially closed by HR Executive</div>';
    if ($currentUser == $departmentHodId || $currentUser == $job->reporting_to) {
        echo '<button class="btn btn-success w-100 text-center job-close-btn" data-job-id="' . $job->id . '" data-closure-stage="reporting_manager">';
        echo '<i class="fas fa-check-circle me-2"></i>Finalize Closure';
        echo '</button>';
    }
}
```

### Closure Modals

#### HR Executive Closure Modal:

```html
<div class="modal fade" id="hrClosureModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Close Job - HR Executive</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
        ></button>
      </div>
      <div class="modal-body">
        <form id="hr-closure-form">
          <div class="mb-3">
            <label class="form-label">Selected Candidate *</label>
            <select class="form-select" name="selected_candidate_id" required>
              <!-- Populate with candidates -->
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Replacement of Employee</label>
            <select class="form-select" name="replacement_of_employee_id">
              <!-- Populate with employees -->
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Closure Reason/Notes *</label>
            <textarea
              class="form-control"
              name="closure_notes"
              rows="3"
              required
            ></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-danger" id="confirm-hr-closure">
          Partially Close Job
        </button>
      </div>
    </div>
  </div>
</div>
```

#### Reporting Manager Finalization Modal:

```html
<div class="modal fade" id="managerClosureModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Finalize Job Closure - Manager Assessment</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
        ></button>
      </div>
      <div class="modal-body">
        <form id="manager-closure-form">
          <!-- Team Assessment Section -->
          <div class="card mb-3">
            <div class="card-header">
              <h6 class="mb-0">Assessment</h6>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Strengths *</label>
                <textarea
                  class="form-control"
                  name="strengths"
                  rows="3"
                  placeholder="Describe the strengths of the hired team member"
                  required
                ></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Weaknesses *</label>
                <textarea
                  class="form-control"
                  name="weaknesses"
                  rows="3"
                  placeholder="Areas for improvement for the hired team member"
                  required
                ></textarea>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label class="form-label">Current Team Size *</label>
                  <input
                    type="number"
                    class="form-control"
                    name="current_team_size"
                    min="1"
                    placeholder="Enter current team size"
                    required
                  />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Best Performer</label>
                  <select class="form-select" name="best_performer_id">
                    <option value="">Select best performer</option>
                    <!-- Populate with team members -->
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Performance Management Section -->
          <div class="card mb-3">
            <div class="card-header">
              <h6 class="mb-0">Performance Management</h6>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Worst Performer (if any)</label>
                <select class="form-select" name="worst_performer_id">
                  <option value="">Select worst performer</option>
                  <!-- Populate with team members -->
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label"
                  >Need Replacement for Worst Performer?</label
                >
                <select
                  class="form-select"
                  name="need_replacement"
                  onchange="toggleReplacementDetails(this)"
                >
                  <option value="">Select option</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
              <div
                class="mb-3"
                id="replacement-details-section"
                style="display: none;"
              >
                <label class="form-label">Replacement Details</label>
                <textarea
                  class="form-control"
                  name="replacement_details"
                  rows="3"
                  placeholder="Provide details about replacement requirements"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Future Planning Section -->
          <div class="card mb-3">
            <div class="card-header">
              <h6 class="mb-0">Future Planning</h6>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Keep Posting Open? *</label>
                <select
                  class="form-select"
                  name="keep_posting_open"
                  onchange="togglePostingReason(this)"
                  required
                >
                  <option value="">Select option</option>
                  <option value="yes">Yes, keep open</option>
                  <option value="no">No, close completely</option>
                </select>
              </div>
              <div
                class="mb-3"
                id="posting-reason-section"
                style="display: none;"
              >
                <label class="form-label">Reason for Keeping Open *</label>
                <textarea
                  class="form-control"
                  name="keep_posting_reason"
                  rows="2"
                  placeholder="Explain why the posting should remain open"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Manager Comments Section -->
          <div class="card mb-3">
            <div class="card-header">
              <h6 class="mb-0">Manager Comments</h6>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Additional Manager Comments</label>
                <textarea
                  class="form-control"
                  name="manager_comments"
                  rows="3"
                  placeholder="Any additional comments or observations"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Final Confirmation -->
          <div class="card">
            <div class="card-header">
              <h6 class="mb-0">Final Confirmation</h6>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Confirm Final Closure *</label>
                <select class="form-select" name="confirm_closure" required>
                  <option value="">Select option</option>
                  <option value="yes">Yes, finalize closure</option>
                  <option value="no">No, keep partially closed</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
        <button
          type="button"
          class="btn btn-success"
          id="confirm-manager-closure"
        >
          Finalize Closure
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleReplacementDetails(select) {
    const detailsSection = document.getElementById(
      "replacement-details-section"
    );
    if (select.value === "yes") {
      detailsSection.style.display = "block";
      detailsSection.querySelector("textarea").required = true;
    } else {
      detailsSection.style.display = "none";
      detailsSection.querySelector("textarea").required = false;
    }
  }

  function togglePostingReason(select) {
    const reasonSection = document.getElementById("posting-reason-section");
    if (select.value === "yes") {
      reasonSection.style.display = "block";
      reasonSection.querySelector("textarea").required = true;
    } else {
      reasonSection.style.display = "none";
      reasonSection.querySelector("textarea").required = false;
    }
  }
</script>
```

## Controller Implementation

### RecruitmentController Methods

#### Method: `initiateJobClosure`

```php
public function initiateJobClosure()
{
    $jobId = $this->request->getPost('job_id');
    $currentUser = $this->session->get('current_user')['employee_id'];

    // Validate HR Executive permission
    if ($currentUser != 52) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Unauthorized to initiate job closure'
        ]);
    }

    $data = [
        'job_listing_id' => $jobId,
        'selected_candidate_id' => $this->request->getPost('selected_candidate_id'),
        'replacement_of_employee_id' => $this->request->getPost('replacement_of_employee_id'),
        'hr_closure_notes' => $this->request->getPost('closure_notes'),
        'hr_closed_by' => $currentUser,
        'hr_closed_at' => date('Y-m-d H:i:s'),
        'current_step' => 'pending_manager_closure'
    ];

    $closureModel = new RcJobClosureApprovalModel();
    if ($closureModel->insert($data)) {
        // Update job status to partially_closed
        $this->jobListingModel->update($jobId, ['status' => 'partially_closed']);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Job partially closed successfully'
        ]);
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to initiate closure'
    ]);
}
```

#### Method: `finalizeJobClosure`

```php
public function finalizeJobClosure()
{
    $jobId = $this->request->getPost('job_id');
    $currentUser = $this->session->get('current_user')['employee_id'];

    // Get job details to validate reporting manager
    $job = $this->jobListingModel->getJobWithDetails($jobId);
    if (!$job || ($currentUser != $job->department_hod_id && $currentUser != $job->reporting_to)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Unauthorized to finalize job closure'
        ]);
    }

    $confirmClosure = $this->request->getPost('confirm_closure');
    if ($confirmClosure != 'yes') {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Closure not confirmed'
        ]);
    }

    // Validate required fields
    $requiredFields = ['strengths', 'weaknesses', 'current_team_size', 'keep_posting_open'];
    foreach ($requiredFields as $field) {
        if (empty($this->request->getPost($field))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Required field '{$field}' is missing"
            ]);
        }
    }

    // Validate conditional required fields
    if ($this->request->getPost('keep_posting_open') == 'yes' && empty($this->request->getPost('keep_posting_reason'))) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Reason for keeping posting open is required'
        ]);
    }

    if ($this->request->getPost('need_replacement') == 'yes' && empty($this->request->getPost('replacement_details'))) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Replacement details are required when replacement is needed'
        ]);
    }

    $closureModel = new RcJobClosureApprovalModel();
    $closureRecord = $closureModel->where('job_listing_id', $jobId)->first();

    if ($closureRecord) {
        $updateData = [
            // Team Assessment
            'strengths' => $this->request->getPost('strengths'),
            'weaknesses' => $this->request->getPost('weaknesses'),
            'current_team_size' => (int)$this->request->getPost('current_team_size'),
            'best_performer_id' => $this->request->getPost('best_performer_id') ?: null,
            'worst_performer_id' => $this->request->getPost('worst_performer_id') ?: null,

            // Performance Management
            'need_replacement' => $this->request->getPost('need_replacement') ?: null,
            'replacement_details' => $this->request->getPost('replacement_details') ?: null,

            // Future Planning
            'keep_posting_open' => $this->request->getPost('keep_posting_open'),
            'keep_posting_reason' => $this->request->getPost('keep_posting_reason') ?: null,

            // Manager Comments & System Fields
            'manager_comments' => $this->request->getPost('manager_comments') ?: null,
            'manager_closed_by' => $currentUser,
            'manager_closed_at' => date('Y-m-d H:i:s'),
            'current_step' => 'completed',
            'final_closure_date' => date('Y-m-d H:i:s')
        ];

        if ($closureModel->update($closureRecord['id'], $updateData)) {
            // Determine final job status based on keep_posting_open
            $finalStatus = ($this->request->getPost('keep_posting_open') == 'yes') ? 'open' : 'closed';

            $jobUpdateData = [
                'status' => $finalStatus,
                'job_closing_date' => date('Y-m-d')
            ];

            // If keeping posting open, don't set closing date
            if ($finalStatus == 'open') {
                unset($jobUpdateData['job_closing_date']);
            }

            $this->jobListingModel->update($jobId, $jobUpdateData);

            $message = ($finalStatus == 'open')
                ? 'Job closure finalized successfully. Posting remains open as requested.'
                : 'Job closure finalized successfully. Job is now closed.';

            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'final_status' => $finalStatus
            ]);
        }
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to finalize closure'
    ]);
}
```

## Required Routes

Add to `RecruitmentRoutes.php`:

```php
$routes->post('/recruitment/job-listing/initiate-closure', [RecruitmentController::class, 'initiateJobClosure']);
$routes->post('/recruitment/job-listing/finalize-closure', [RecruitmentController::class, 'finalizeJobClosure']);
$routes->get('/recruitment/job-listing/closure-details/(:num)', [RecruitmentController::class, 'getClosureDetails/$1']);
```

## Benefits of Two-Stage Approach

1. **✅ Clear Separation**: HR handles candidate selection, Manager handles final approval
2. **✅ Proper Authorization**: Each stage has appropriate role permissions
3. **✅ Status Tracking**: Clear status progression (active → partially_closed → closed)
4. **✅ Audit Trail**: Complete record of who closed what and when
5. **✅ Flexible Process**: Manager can review HR decisions before final closure
6. **✅ UI Integration**: Seamlessly integrates with existing approval system

## Updated Database Structure

### Enhanced Table: `rc_job_closure_approvals`

```sql
CREATE TABLE `rc_job_closure_approvals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_listing_id` int NOT NULL,

  -- HR Executive Stage 1 Fields
  `selected_candidate_id` int DEFAULT NULL,
  `replacement_of_employee_id` int DEFAULT NULL,
  `hr_closure_notes` text,
  `hr_closed_by` int DEFAULT NULL,
  `hr_closed_at` datetime DEFAULT NULL,

  -- Manager Stage 2 Fields - Team Assessment
  `strengths` text,
  `weaknesses` text,
  `current_team_size` int DEFAULT NULL,
  `best_performer_id` int DEFAULT NULL,
  `worst_performer_id` int DEFAULT NULL,

  -- Manager Stage 2 Fields - Performance Management
  `need_replacement` enum('yes','no') DEFAULT NULL,
  `replacement_details` text,

  -- Manager Stage 2 Fields - Future Planning
  `keep_posting_open` enum('yes','no') DEFAULT NULL,
  `keep_posting_reason` text,

  -- Manager Stage 2 Fields - Comments & Closure
  `manager_comments` text,
  `manager_closed_by` int DEFAULT NULL,
  `manager_closed_at` datetime DEFAULT NULL,

  -- System Fields
  `current_step` enum('pending_manager_closure','completed') DEFAULT 'pending_manager_closure',
  `final_closure_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  -- Indexes
  PRIMARY KEY (`id`),
  KEY `idx_job_listing_id` (`job_listing_id`),
  KEY `idx_selected_candidate` (`selected_candidate_id`),
  KEY `idx_replacement_employee` (`replacement_of_employee_id`),
  KEY `idx_best_performer` (`best_performer_id`),
  KEY `idx_worst_performer` (`worst_performer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
```

## Implementation Checklist

1. **✅ Database Schema**: Update `rc_job_listing` status enum to include `partially_closed`
2. **✅ Closure Table**: Update `rc_job_closure_approvals` for two-stage process
3. **⏳ Controller Methods**: Add `initiateJobClosure` and `finalizeJobClosure` methods
4. **⏳ Routes**: Add closure routes to `RecruitmentRoutes.php`
5. **⏳ UI Integration**: Add close buttons and modals to `JobSingleView.php`
6. **⏳ JavaScript**: Add closure handling logic
7. **⏳ Testing**: Test complete two-stage workflow
8. **⏳ Permissions**: Validate HR Executive and Reporting Manager access

## Key Features

- **Two-Stage Process**: HR Executive → Reporting Manager
- **Status Tracking**: `active` → `partially_closed` → `closed`
- **Modal-Based**: Clean UI for collecting closure details
- **Role-Based**: Proper permission validation at each stage
- **Audit Trail**: Complete tracking of closure process
- **Seamless Integration**: Works with existing approval system

This two-stage approach provides proper separation of concerns while maintaining a clean, intuitive user experience!
