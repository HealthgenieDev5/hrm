<?php

namespace App\Models;

use CodeIgniter\Model;

class AppraisalsRevisionModel extends Model
{
    protected $table            = 'appraisals_revision';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'appraisal_id',
        'employee_id',
        'ctc',
        'gross_salary',
        'basic_salary',
        'house_rent_allowance',
        'conveyance',
        'medical_allowance',
        'special_allowance',
        'fuel_allowance',
        'vacation_allowance',
        'other_allowance',
        'gratuity',
        'enable_bonus',
        'bonus',
        'non_compete_loan',
        'non_compete_loan_from',
        'non_compete_loan_to',
        'non_compete_loan_amount_per_month',
        'non_compete_loan_remarks',
        'loyalty_incentive',
        'loyalty_incentive_from',
        'loyalty_incentive_to',
        'loyalty_incentive_amount_per_month',
        'loyalty_incentive_mature_after_month',
        'loyalty_incentive_pay_after_month',
        'loyalty_incentive_remarks',
        'pf',
        'pf_number',
        'pf_employee_contribution',
        'pf_employer_contribution',
        'esi',
        'esi_number',
        'esi_employee_contribution',
        'esi_employer_contribution',
        'lwf',
        'lwf_number',
        'lwf_employee_contribution',
        'lwf_employer_contribution',
        'lwf_deduction_on_every_n_month',
        'appraisal_remarks',
        'appraisal_date',
        'created_by',
        'action',
        'created_at',
        'updated_at',
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
