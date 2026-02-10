<?php

namespace App\Models;

use CodeIgniter\Model;

class PreFinalSalaryRevisionModel extends Model
{
	protected $table = 'pre_final_salary_revision';
	protected $primaryKey = 'id';
	protected $allowedFields = [
		'salary_id',
		'employee_id',
		'month',
		'year',
		'basic_salary',
		'house_rent_allowance',
		'conveyance',
		'medical_allowance',
		'special_allowance',
		'fuel_allowance',
		'vacation_allowance',
		'other_allowance',
		'ctc',
		'gross_salary',
		'total_deduction',
		'net_salary',
		'gratuity',
		'bonus',
		'pf_employee_contribution',
		'pf_employer_contribution',
		'esi_employee_contribution',
		'esi_employer_contribution',
		'lwf_employee_contribution',
		'lwf_employer_contribution',
		'loan_emi',
		'advance',
		'imprest',
		'non_compete_loan',
		'loyalty_incentive',
		'tds',
		'phone_bill',
		'month_days',
		'present_days',
		'half_days',
		'absent',
		'week_off',
		'sandwich',
		'holidays',
		'final_paid_days',
		'deduction_days',

		'disbursed',
		'disbursal_date',
		'disbursal_remarks',
		'disbursed_by',

		'status',
		'remarks',

		'remarks_timeline',

		'salary_structure',
		'employee_data',

		'revised_by',
		'revision_date_time',
		'stipend',
	];
}
