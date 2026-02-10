<?php

namespace App\Models;

use CodeIgniter\Model;

class SalaryRevisionModel extends Model
{
	protected $table = 'employee_salary_revision';
	protected $allowedFields = [
		'salary_id',
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
		'lwf_employee_contribution',
		'lwf_employer_contribution',
		'lwf_deduction_on_every_n_month',
		'tds',
		'tds_amount_per_month',
		'tds_preferred_slab',
		'date_time',
		'revised_by',
		'stipend',
	];
}
