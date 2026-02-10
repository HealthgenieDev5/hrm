<?php

namespace App\Models;

use CodeIgniter\Model;

class PreFinalSalaryModel extends Model
{
	protected $table = 'pre_final_salary';
	protected $primaryKey = 'id';
	protected $allowedFields = [
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
		'stipend',
	];

	// protected $beforeUpdate = ['backupRow'];
	public function saveRivision($id)
	{
		$originalRow = $this->find($id);
		if ($originalRow) {
			$originalRow['salary_id'] = $originalRow['id'];
			$originalRow['revised_by'] = session()->get('current_user')['employee_id'] ?? 40;
			unset($originalRow['id']);
			$PreFinalSalaryRevisionModel = new PreFinalSalaryRevisionModel();
			$PreFinalSalaryRevisionModel->insert($originalRow);
		}
	}
}
