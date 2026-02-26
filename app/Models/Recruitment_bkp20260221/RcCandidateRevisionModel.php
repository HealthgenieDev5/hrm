<?php

namespace App\Models\Recruitment;

use CodeIgniter\Model;

class RcCandidateRevisionModel extends Model
{
    protected $table            = 'rc_candidates_revision';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'candidate_id',
        'listing_id',
        'first_name',
        'last_name',
        'email',
        'alternate_email',
        'mobile',
        'alternate_mobile',
        'gender',
        'marital_status',
        'date_of_birth',
        'present_address',
        'present_city',
        'present_state',
        'present_pincode',
        'permanent_address',
        'permanent_city',
        'permanent_state',
        'permanent_pincode',
        'total_experience_year',
        'total_experience_month',
        'relevent_experience_year',
        'relevent_experience_month',
        'is_working',
        'current_company',
        'current_company_joining_date',
        'current_designation',
        'functional_area',
        'role',
        'industry',
        'notice_period',
        'annual_salary',
        'last_drawn_salary',
        'last_drawn_salary_date',
        'current_company_address',
        'current_company_city',
        'current_company_state',
        'current_company_pincode',
        'preferred_location',
        'skills',
        'resume',
        'resume_headline',
        'summary',
        'ug_degree',
        'ug_specialization',
        'ug_university_institute',
        'ug_graduation_year',
        'pg_degree',
        'pg_specialization',
        'pg_university_institute',
        'pg_graduation_year',
        'dr_degree',
        'dr_specialization',
        'dr_university_institute',
        'dr_graduation_year',
        'source',
        'source_url',
        'disposition_id',
        'subdisposition_id',
        'call_remarks',
        'first_call_date',
        'date_first_call_date',
        'last_call_date',
        'date_last_call_date',
        'call_back_date',
        'date_call_back_date',
        'interview_scheduled_date',
        'date_interview_scheduled_date',
        'date_data_assigned_date',
        'data_assigned_date',
        'agent_id',
        'created_at',
        'updated_by',
        'updated_at'
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
