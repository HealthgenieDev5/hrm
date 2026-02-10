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
}
