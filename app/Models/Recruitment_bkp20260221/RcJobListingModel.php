<?php

namespace App\Models\Recruitment;

use CodeIgniter\Model;

class RcJobListingModel extends Model
{
    protected $table            = 'rc_job_listing';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_title',
        'company_id',
        'department_id',
        'listing_title',
        'min_experience',
        'max_experience',
        'min_budget',
        'max_budget',
        'job_location',
        'interview_location',
        'no_of_vacancy',
        'priority',
        'target_closure_date',
        'expected_closure_date',
        'type_of_job',
        'seating_location',
        'system_required',
        'reporting_to',
        'salient_points',
        'educational_qualification',
        'technical_test_required',
        'iq_test_required',
        'eng_test_required',
        'operation_test_required',
        'other_test_required',
        'job_description',
        'shift_timing',
        'specific_industry',
        'attachment',
        'review_schedule_3m',
        'review_schedule_6m',
        'review_schedule_12m',
        'job_opening_date',
        'job_closing_date',
        'job_closing_reason',
        'created_by',
        'approved_by_hr_executive',
        'approved_by_hr_manager',
        'approved_by_hod',
        'remarks',
        'status',
        'created_at'
    ];



    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get job with detailed information including related data
     */
    public function getJobWithDetails($jobId)
    {
        return $this->select('rc_job_listing.*, companies.company_short_name as company_name, departments.department_name, CONCAT(employees.first_name, " ", employees.last_name) as reporting_to_name')
            ->select("CONCAT(created_by.first_name, ' ', created_by.last_name) as created_by_name")
            ->select('reporting_to_designation.designation_name as reporting_to_designation')
            ->select("CONCAT(review_schedule_3m.first_name, ' ', review_schedule_3m.last_name) as review_schedule_3m_name")
            ->select("CONCAT(review_schedule_6m.first_name, ' ', review_schedule_6m.last_name) as review_schedule_6m_name")
            ->select("CONCAT(review_schedule_12m.first_name, ' ', review_schedule_12m.last_name) as review_schedule_12m_name")
            ->select('departments.hod_employee_id as department_hod_id')
            ->select("CONCAT(hod_employee.first_name, ' ', hod_employee.last_name) as department_hod_name")
            ->join('companies', 'companies.id = rc_job_listing.company_id', 'left')
            ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
            ->join('employees', 'employees.id = rc_job_listing.reporting_to', 'left')
            ->join('employees as created_by', 'created_by.id = rc_job_listing.created_by', 'left')
            ->join('employees as hod_employee', 'hod_employee.id = departments.hod_employee_id', 'left')
            ->join('designations as reporting_to_designation', 'reporting_to_designation.id = employees.designation_id', 'left')
            ->join('employees as review_schedule_3m', 'review_schedule_3m.id = rc_job_listing.review_schedule_3m', 'left')
            ->join('employees as review_schedule_6m', 'review_schedule_6m.id = rc_job_listing.review_schedule_6m', 'left')
            ->join('employees as review_schedule_12m', 'review_schedule_12m.id = rc_job_listing.review_schedule_12m', 'left')
            ->where('rc_job_listing.id', $jobId)
            ->asObject()
            ->first();
    }
}
