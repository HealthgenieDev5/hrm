<?php
namespace App\Models;
use CodeIgniter\Model;

class FinalSalaryModel extends Model{
	protected $table = 'final_salary';
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
		'other_allowance', 
		'ctc', 
		'gross_salary', 
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
		'non_compete_loan', 
		'loyalty_incentive', 
		'tds', 
		'month_days', 
		'present_days', 
		'half_days', 
		'absent', 
		'week_off', 
		'sandwich', 
		'holidays', 
		'paid_days_before_settlement', 
		'settlement', 
		'final_paid_days', 
		'deduction_days', 

		'disbursed', 
		'disbursal_date', 
		'disbursal_remarks', 
		'disbursed_by', 

		'hold', 
		'hold_date', 
		'hold_remarks', 
		'hold_by', 

		'unhold', 
		'unhold_date', 
		'unhold_remarks', 
		'unhold_by', 
		
		'send_to_accounts', 
		'send_to_accounts_date', 
		'send_to_accounts_remarks', 
		'send_to_accounts_by', 

		'remarks_timeline', 

		'employee_data',
		'salary_structure', 
		'updated_by', 
	];
}
?>