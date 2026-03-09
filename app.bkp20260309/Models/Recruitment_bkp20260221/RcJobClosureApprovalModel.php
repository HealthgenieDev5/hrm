<?php

namespace App\Models\Recruitment;

use CodeIgniter\Model;

class RcJobClosureApprovalModel extends Model
{
    protected $table = 'rc_job_closure_approvals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'job_listing_id',
        'selected_candidate_id',
        'replacement_of_employee_id',
        'hr_assessment_notes',
        'hr_approved_by',
        'hr_approved_at',
        'strengths',
        'weaknesses',
        'current_team_size',
        'best_performer_id',
        'worst_performer_id',
        'need_replacement',
        'replacement_details',
        'keep_posting_open',
        'keep_posting_reason',
        'notice_period_compliance',
        'doubtful_notice_members',
        'manager_comments',
        'manager_approved_by',
        'manager_approved_at',
        'current_step',
        'final_closure_date'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'job_listing_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'job_listing_id' => [
            'required' => 'Job listing ID is required',
            'integer' => 'Job listing ID must be an integer'
        ]
    ];

    /**
     * Get closure approval details with related job information
     */
    public function getClosureWithJobDetails($closureId)
    {
        return $this->select('rc_job_closure_approvals.*')
            ->select('rc_job_listing.job_title, rc_job_listing.status as job_status')
            ->select('hr_employee.first_name as hr_first_name, hr_employee.last_name as hr_last_name')
            ->select('manager_employee.first_name as manager_first_name, manager_employee.last_name as manager_last_name')
            ->join('rc_job_listing', 'rc_job_listing.id = rc_job_closure_approvals.job_listing_id', 'left')
            ->join('employees as hr_employee', 'hr_employee.id = rc_job_closure_approvals.hr_approved_by', 'left')
            ->join('employees as manager_employee', 'manager_employee.id = rc_job_closure_approvals.manager_approved_by', 'left')
            ->where('rc_job_closure_approvals.id', $closureId)
            ->first();
    }

    /**
     * Get closure approval by job listing ID
     */
    public function getByJobListingId($jobListingId)
    {
        return $this->where('job_listing_id', $jobListingId)->first();
    }

    /**
     * Check if job has pending closure
     */
    public function hasPendingClosure($jobListingId)
    {
        return $this->where('job_listing_id', $jobListingId)
            ->where('current_step', 'pending_manager_closure')
            ->countAllResults() > 0;
    }

    /**
     * Get all pending manager closures
     */
    public function getPendingManagerClosures()
    {
        return $this->select('rc_job_closure_approvals.*')
            ->select('rc_job_listing.job_title, rc_job_listing.company_id, rc_job_listing.department_id')
            ->select('hr_employee.first_name as hr_first_name, hr_employee.last_name as hr_last_name')
            ->join('rc_job_listing', 'rc_job_listing.id = rc_job_closure_approvals.job_listing_id', 'left')
            ->join('employees as hr_employee', 'hr_employee.id = rc_job_closure_approvals.hr_approved_by', 'left')
            ->where('rc_job_closure_approvals.current_step', 'pending_manager_closure')
            ->orderBy('rc_job_closure_approvals.created_at', 'DESC')
            ->findAll();
    }
}
