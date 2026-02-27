<?php

namespace App\Controllers\Cron;

use App\Models\SalaryModel;
use App\Models\EmployeeModel;
use App\Models\UserLoanModel;
use App\Models\TdsMasterModel;
use App\Models\UserLoanEmiModel;
use App\Models\AdvanceSalaryModel;
use App\Models\ImprestMasterModel;
use App\Controllers\BaseController;
use App\Models\PreFinalSalaryModel;
use App\Models\PhoneBillMasterModel;
use App\Models\AdvanceSalaryEmiModel;
use App\Models\MinWagesCategoryModel;
use App\Models\PreFinalPaidDaysModel;

class FinalSalary extends BaseController
{
	public $session;
	public $uri;
	private $intern_designation_id;
	public function __construct()
	{
		helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
		$this->session    = session();
		// $this->url = service('uri');
		$this->intern_designation_id = 75;
	}

	public function calculateSalary($employee_id, $salary_month, $remarks = null, $return = false)
	{


		// return $salary_month;
		$days = date('t', strtotime($salary_month));
		$PreFinalPaidDaysModel = new PreFinalPaidDaysModel();
		$get_final_paid_days = $PreFinalPaidDaysModel
			->select('pre_final_paid_days.*')
			->select('employees.designation_id as designation_id')
			->join('employees', 'employees.id = pre_final_paid_days.employee_id', 'left')
			->where('pre_final_paid_days.employee_id =', $employee_id)
			->where('month(pre_final_paid_days.date) =', date('m', strtotime($salary_month)))
			->where('year(pre_final_paid_days.date) =', date('Y', strtotime($salary_month)))
			->findAll();
		// print_r($get_final_paid_days[0]['designation_id']);
		// 	die();
		if (!empty($get_final_paid_days)) {
			$final_paid_days = array_sum(array_column($get_final_paid_days, 'paid'));
			$final_salary = array();
			$final_salary['employee_id'] = $employee_id;
			$final_salary['year'] = date('Y', strtotime($salary_month));
			$final_salary['month'] = date('m', strtotime($salary_month));
			$SalaryModel = new SalaryModel();
			$salary = $SalaryModel->where("employee_id =", $employee_id)->first();
			if (!empty($salary)) {



				if ($get_final_paid_days[0]['designation_id'] == $this->intern_designation_id) {
					$final_salary['stipend'] 			= ($salary['stipend'] / $days) * $final_paid_days;
				}

				$final_salary['basic_salary'] 			= ($salary['basic_salary'] / $days) * $final_paid_days;
				$final_salary['house_rent_allowance'] 	= ($salary['house_rent_allowance'] / $days) * $final_paid_days;
				$final_salary['conveyance'] 			= ($salary['conveyance'] / $days) * $final_paid_days;
				$final_salary['medical_allowance'] 		= ($salary['medical_allowance'] / $days) * $final_paid_days;
				$final_salary['special_allowance'] 		= ($salary['special_allowance'] / $days) * $final_paid_days;
				$final_salary['fuel_allowance'] 		= ($salary['fuel_allowance'] / $days) * $final_paid_days;
				$final_salary['other_allowance'] 		= ($salary['other_allowance'] / $days) * $final_paid_days;
				$final_salary['vacation_allowance']  	= ($salary['vacation_allowance'] / $days) * $final_paid_days;

				$gross_salary 							= 	$final_salary['basic_salary']
					+ $final_salary['house_rent_allowance']
					+ $final_salary['conveyance']
					+ $final_salary['medical_allowance']
					+ $final_salary['special_allowance']
					+ $final_salary['fuel_allowance']
					+ $final_salary['vacation_allowance']
					+ $final_salary['other_allowance'];
				$final_salary['gross_salary'] 			= $gross_salary;

				if ($get_final_paid_days[0]['designation_id'] != $this->intern_designation_id) {
					$final_salary['gratuity'] = (($salary['basic_salary'] / 26) * 15) * (1 / 12);
				} else {
					$final_salary['gratuity'] = 0;
				}

				if ($salary['enable_bonus'] == 'yes') {
					$EmployeeModel = new EmployeeModel();
					$employee = $EmployeeModel->find($employee_id);
					if (!empty($employee['min_wages_category'])) {
						$MinWagesCategoryModel = new MinWagesCategoryModel();
						$MinWage = $MinWagesCategoryModel->find($employee['min_wages_category']);
						$MinWageValue = $MinWage['minimum_wages_category_value'];
						if (!empty($MinWageValue)) {
							$thisMonthMinWage = ($MinWageValue / $days) * $final_paid_days;
							$final_salary['bonus'] = ($thisMonthMinWage * 8.33) / 100;
							$salary['bonus'] = ($MinWageValue * 8.33) / 100;
						} else {
							$final_salary['bonus'] = 0;
							$salary['bonus'] = 0;
						}
					} else {
						$final_salary['bonus'] = 0;
						$salary['bonus'] = 0;
					}
				} else {
					$final_salary['bonus'] = 0;
					$salary['bonus'] = 0;
				}

				if ($salary['pf'] == 'yes') {
					if ($gross_salary >= 15000) {
						$pf_employee_contribution = ((15000 * 12) / 100);
					} else {
						$pf_employee_contribution = (($gross_salary * 12) / 100);
					}
					$final_salary['pf_employee_contribution'] = $pf_employee_contribution;
				} else {
					$final_salary['pf_employee_contribution'] = 0;
				}

				if ($salary['pf'] == 'yes') {
					if ($gross_salary >= 15000) {
						$pf_employer_contribution = ((15000 * 13) / 100);
					} else {
						$pf_employer_contribution = (($gross_salary * 13) / 100);
					}
					$final_salary['pf_employer_contribution'] = $pf_employer_contribution;
				} else {
					$final_salary['pf_employer_contribution'] = 0;
				}


				/*
				* Commented on 2024-07-08, as per Santu's instruction, The ESI should be removed only in April's salary and onwards, or October's salary onwards.
				*If salary was below 21000 in a month and the employee got an appraisal then esi will keep deducting till next April or October
				$final_salary['esi_employee_contribution'] 	= ($salary['esi'] == 'yes' && $gross_salary <= 21000)
															? ($gross_salary*0.75)/100
															: 0;

				$final_salary['esi_employer_contribution'] 	= ($salary['esi'] == 'yes' && $gross_salary <= 21000)
															? ($gross_salary*3.25)/100
															: 0;*/


				$final_salary['esi_employee_contribution'] 	= ($salary['esi'] == 'yes')
					? ($gross_salary * 0.75) / 100
					: 0;

				$final_salary['esi_employer_contribution'] 	= ($salary['esi'] == 'yes')
					? ($gross_salary * 3.25) / 100
					: 0;



				if ($salary['lwf'] == 'yes') {
					if ((($gross_salary * 0.2) / 100) <= 34) {
						$final_salary['lwf_employee_contribution'] = ($gross_salary * 0.2) / 100;
					} else {
						$final_salary['lwf_employee_contribution'] = 34;
					}
				} else {
					$final_salary['lwf_employee_contribution'] = 0;
				}
				// $final_salary['lwf_employee_contribution'] 	= ($salary['lwf'] == 'yes')
				// 	? (($gross_salary * 0.2) / 100 <= 31) ? ($gross_salary * 0.2) / 100 : 31
				// 	: 0;

				$final_salary['lwf_employer_contribution'] 	= ($salary['lwf'] == 'yes')
					? ((($gross_salary * 0.2) / 100 <= 34) ? ($gross_salary * 0.2) / 100 : 34) * 2
					: 0;

				if ($salary['loyalty_incentive'] == 'yes'  && $salary['loyalty_incentive_amount_per_month'] > 0) {
					$first_date_of_salary_month = date('Y-m-01', strtotime($salary_month));
					$last_date_of_salary_month = date('Y-m-t', strtotime($salary_month));

					if (strtotime($last_date_of_salary_month) >= strtotime($salary['loyalty_incentive_from']) && empty($salary['loyalty_incentive_to'])) {
						$final_salary['loyalty_incentive'] = ($salary['loyalty_incentive_amount_per_month'] / $days) * $final_paid_days;
					} elseif (strtotime($last_date_of_salary_month) >= strtotime($salary['loyalty_incentive_from']) && strtotime($last_date_of_salary_month) <= strtotime($salary['loyalty_incentive_to'])) {
						$final_salary['loyalty_incentive'] = ($salary['loyalty_incentive_amount_per_month'] / $days) * $final_paid_days;
					} else {
						$final_salary['loyalty_incentive'] = 0;
					}
				} else {
					$final_salary['loyalty_incentive'] = 0;
				}

				$TdsMasterModel = new TdsMasterModel();
				$TdsMasterModel
					->where('year =', date('Y', strtotime($salary_month)))
					->where('month =', date('m', strtotime($salary_month)))
					->where('employee_id =', $employee_id)
					->where('status =', 'active');
				$tdsRecordCurrentMonth = $TdsMasterModel->first();
				if (!empty($tdsRecordCurrentMonth)) {
					$tdsCurrentMonth = $tdsRecordCurrentMonth['deduction_amount'];
					if ($tdsCurrentMonth > 0) {
						$final_salary['tds'] = $tdsCurrentMonth;
					} else {
						$final_salary['tds'] = 0;
					}
				} else {
					$final_salary['tds'] = 0;
				}

				$statuses = array_column($get_final_paid_days, 'status');
				$statuses_counts = array_count_values($statuses);
				$final_salary['month_days'] 	= $days;
				$final_salary['present_days'] 	= ((array_key_exists('P', $statuses_counts)) ? $statuses_counts['P'] : 0) + ((array_key_exists('H/D', $statuses_counts)) ? $statuses_counts['H/D'] : 0);
				$final_salary['half_days'] 		= ((array_key_exists('H/D', $statuses_counts)) ? $statuses_counts['H/D'] : 0);
				$final_salary['absent'] 		= ((array_key_exists('A', $statuses_counts)) ? $statuses_counts['A'] : 0);
				$final_salary['week_off'] 		= ((array_key_exists('W/O', $statuses_counts)) ? $statuses_counts['W/O'] : 0);
				$final_salary['sandwich'] 		= ((array_key_exists('S/W', $statuses_counts)) ? $statuses_counts['S/W'] : 0);

				$final_salary['holidays'] 		= ((array_key_exists('HL', $statuses_counts)) ? $statuses_counts['HL'] : 0);
				$final_salary['holidays'] 		+= ((array_key_exists('SPL HL', $statuses_counts)) ? $statuses_counts['SPL HL'] : 0);

				if ($salary['non_compete_loan'] == 'yes' && $salary['non_compete_loan_amount_per_month'] > 0) {

					$first_date_of_salary_month = date('Y-m-01', strtotime($salary_month));
					$last_date_of_salary_month = date('Y-m-t', strtotime($salary_month));

					if (strtotime($last_date_of_salary_month) >= strtotime($salary['non_compete_loan_from']) && empty($salary['non_compete_loan_to'])) {

						if ($final_paid_days < 12) {
							$final_salary['non_compete_loan'] = 0;
						} elseif ($final_paid_days >= 12 && $final_paid_days < 18) {
							$final_salary['non_compete_loan'] = $salary['non_compete_loan_amount_per_month'] / 2;
						} else {
							$final_salary['non_compete_loan'] = $salary['non_compete_loan_amount_per_month'];
						}
					} elseif (strtotime($last_date_of_salary_month) >= strtotime($salary['non_compete_loan_from']) && strtotime($last_date_of_salary_month) <= strtotime($salary['non_compete_loan_to'])) {

						if ($final_paid_days < 12) {
							$final_salary['non_compete_loan'] = 0;
						} elseif ($final_paid_days >= 12 && $final_paid_days < 18) {
							$final_salary['non_compete_loan'] = $salary['non_compete_loan_amount_per_month'] / 2;
						} else {
							$final_salary['non_compete_loan'] = $salary['non_compete_loan_amount_per_month'];
						}
					} else {
						$final_salary['non_compete_loan'] = 0;

						$testArray = [
							'non_compete_loan_enabled' => @$salary['non_compete_loan'],
							'non_compete_loan_amount_per_month' => @$salary['non_compete_loan_amount_per_month'],
							'salary_month' => @$salary_month,
							'non_compete_loan_from' => @$salary['non_compete_loan_from'],
							'non_compete_loan_to' => @$salary['non_compete_loan_to'],
							'final_paid_days' => @$final_paid_days,
						];

						$email = \Config\Services::email();
						$email->setFrom('app.hrm@healthgenie.in', 'HRM');
						$to_emails = array('developer3@healthgenie.in');
						$email->setTo($to_emails);
						$email->setSubject('Test email for debugging NCL');

						$condition_1 = strtotime($salary_month) >= strtotime($salary['non_compete_loan_from']) && empty($salary['non_compete_loan_to']);
						$condition_2 = strtotime($salary_month) >= strtotime($salary['non_compete_loan_from']) && strtotime($salary_month) <= strtotime($salary['non_compete_loan_to']);
						$strtotime_salary_month = strtotime($salary_month);
						$strtotime_non_compete_loan_from = strtotime($salary['non_compete_loan_from']);
						$strtotime_non_compete_loan_to = strtotime($salary['non_compete_loan_to']);
						$date_salary_month = date('Y-m-d', $strtotime_salary_month);
						$email->setMessage('
                            <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                                    <tbody>
                                        <tr>
                                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                                <a href="' . base_url('public') . '" rel="noopener" target="_blank">
                                                    <img alt="Logo" src="' . base_url('public') . '/assets/media/logos/logo-healthgenie.png" />
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="center">
                                                <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                                    <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>

                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">non_compete_loan_enabled:</span> ' . $testArray["non_compete_loan_enabled"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">non_compete_loan_amount_per_month:</span> ' . $testArray["non_compete_loan_amount_per_month"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">salary_month:</span> ' . $testArray["salary_month"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">non_compete_loan_from:</span> ' . $testArray["non_compete_loan_from"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">non_compete_loan_to:</span> ' . $testArray["non_compete_loan_to"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">final_paid_days:</span> ' . $testArray["final_paid_days"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">condition_1:</span> ' . $condition_1 . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">condition_2:</span> ' . $condition_2 . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">strtotime_salary_month:</span> ' . $strtotime_salary_month . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">strtotime_non_compete_loan_from:</span> ' . $strtotime_non_compete_loan_from . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">strtotime_non_compete_loan_to:</span> ' . $strtotime_non_compete_loan_to . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">date_salary_month:</span> ' . $date_salary_month . '</div>

                                                    <div style="padding-bottom: 10px">Kind regards,
                                                    <br>HRM Team.
                                                    <tr>
                                                        <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                                                            <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                                                            <p>Copyright ©
                                                            <a href="' . base_url('public') . '" rel="noopener" target="_blank">Healthgenie/Gstc</a>.</p>
                                                        </td>
                                                    </tr></br></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>');

						$email->send();
					}
				} else {
					$final_salary['non_compete_loan'] = 0;
				}

				$final_salary['final_paid_days']  = $final_paid_days;
				$final_salary['deduction_days'] = ($final_salary['present_days'] + $final_salary['week_off'] + $final_salary['holidays']) - $final_salary['final_paid_days'];

				$final_salary['ctc'] = $gross_salary + $final_salary['pf_employer_contribution'] + $final_salary['esi_employer_contribution'] + $final_salary['lwf_employer_contribution'] + $final_salary['non_compete_loan'] + $final_salary['loyalty_incentive'] + $final_salary['bonus'] + $final_salary['gratuity'];

				$UserLoanModel = new UserLoanModel();
				$userLoans = $UserLoanModel
					->select('loan_emis.id as emi_id')
					->select('loan_emis.emi as emi_amount')
					->join('loan_emis as loan_emis', 'loan_emis.loan_id = loan_requests.id', 'left')
					->where('loan_requests.employee_id =', $employee_id)
					->where('loan_emis.year =', date('Y', strtotime($salary_month)))
					->where('loan_emis.month =', date('m', strtotime($salary_month)))
					->findAll();

				if (!empty($userLoans)) {
					$loan_emi = 0;
					foreach ($userLoans as $loan) {
						$loan_emi += $loan['emi_amount'];
						$UserLoanEmiModel = new UserLoanEmiModel();
						$UserLoanEmiUpdateQuery = $UserLoanEmiModel->update($loan['emi_id'], ['deducted' => 'yes', 'deduction_date' => date('Y-m-t H:i:s', strtotime($salary_month))]);
						if (!$UserLoanEmiUpdateQuery) {
							echo json_encode(['response' => 'failed', 'description' => 'There was an error updating Loan EMI' . json_encode($UserLoanEmiModel->error())]);
							die();
						}
					}
					$final_salary['loan_emi'] = round($loan_emi, 2);
				} else {
					$final_salary['loan_emi'] = 0;
				}

				$AdvanceSalaryModel = new AdvanceSalaryModel();
				$AdvanceSalaryEmis = $AdvanceSalaryModel
					->select('advance_salary_emis.id as emi_id')
					->select('advance_salary_emis.emi as emi')
					->join('advance_salary_emis as advance_salary_emis', 'advance_salary_emis.advance_salary_request_id = advance_salary_requests.id', 'left')
					->where('advance_salary_requests.employee_id =', $employee_id)
					->where('advance_salary_emis.year =', date('Y', strtotime($salary_month)))
					->where('advance_salary_emis.month =', date('m', strtotime($salary_month)))
					->findAll();
				if (!empty($AdvanceSalaryEmis)) {
					$advance_emi = 0;
					foreach ($AdvanceSalaryEmis as $emi_row) {
						$advance_emi += $emi_row['emi'];
						$AdvanceSalaryEmiModel = new AdvanceSalaryEmiModel();
						$AdvanceSalaryEmiUpdateQuery = $AdvanceSalaryEmiModel->update($emi_row['emi_id'], ['deducted' => 'yes', 'deduction_date' => date('Y-m-t H:i:s', strtotime($salary_month))]);
						if (!$AdvanceSalaryEmiUpdateQuery) {
							echo json_encode(['response' => 'failed', 'description' => 'There was an error updating Advance Salary EMI' . json_encode($AdvanceSalaryEmiModel->error())]);
							die();
						}
					}
					$final_salary['advance'] = round($advance_emi, 2);
				} else {
					$final_salary['advance'] = 0;
				}

				$ImprestMasterModel = new ImprestMasterModel();
				$ImprestMasterModel
					->where('year =', date('Y', strtotime($salary_month)))
					->where('month =', date('m', strtotime($salary_month)))
					->where('employee_id =', $employee_id)
					->where('status =', 'active');
				$imprestRecordCurrentMonth = $ImprestMasterModel->first();
				if (!empty($imprestRecordCurrentMonth)) {
					$imprestCurrentMonth = $imprestRecordCurrentMonth['deduction_amount'];
					if ($imprestCurrentMonth > 0) {
						$final_salary['imprest'] = $imprestCurrentMonth;
					} else {
						$final_salary['imprest'] = 0;
					}
				} else {
					$final_salary['imprest'] = 0;
				}

				$PhoneBillMasterModel = new PhoneBillMasterModel();
				$PhoneBillMasterModel
					->where('year =', date('Y', strtotime($salary_month)))
					->where('month =', date('m', strtotime($salary_month)))
					->where('employee_id =', $employee_id)
					->where('status =', 'active');
				$PhoneBillRecordCurrentMonth = $PhoneBillMasterModel->first();
				if (!empty($PhoneBillRecordCurrentMonth)) {
					$PhoneBillCurrentMonth = $PhoneBillRecordCurrentMonth['deduction_amount'];
					if ($PhoneBillCurrentMonth > 0) {
						$final_salary['phone_bill'] = $PhoneBillCurrentMonth;
					} else {
						$final_salary['phone_bill'] = 0;
					}
				} else {
					$final_salary['phone_bill'] = 0;
				}

				$final_salary['total_deduction'] = $final_salary['pf_employee_contribution'] + $final_salary['esi_employee_contribution'] + $final_salary['lwf_employee_contribution'] + $final_salary['tds'] + $final_salary['loan_emi'] + $final_salary['advance'] + $final_salary['imprest'] + $final_salary['phone_bill'];

				$final_salary['net_salary'] = $final_salary['gross_salary'] - $final_salary['total_deduction'];

				$final_salary['salary_structure'] 		= json_encode($salary);
				$EmployeeModel = new EmployeeModel();
				$EmployeeModel
					->select('employees.*')
					->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name")
					->select('designations.designation_name as designation_name')
					->select('departments.department_name as department_name')
					->select('companies.company_name as company_name')
					->select('companies.company_short_name as company_short_name')
					->select('companies.address as company_address')
					->select('companies.logo_url as company_logo_url')
					->join('designations as designations', 'designations.id = employees.designation_id', 'left')
					->join('departments as departments', 'departments.id = employees.department_id', 'left')
					->join('companies as companies', 'companies.id = employees.company_id', 'left')
					->where('employees.id=', $employee_id);
				$final_salary['employee_data'] = json_encode($EmployeeModel->first());

				$PreFinalSalaryModel = new PreFinalSalaryModel();
				$OldData = $PreFinalSalaryModel
					->where('employee_id =', $final_salary['employee_id'])
					->where('month =', $final_salary['month'])
					->where('year =', $final_salary['year'])
					->first();

				if (!empty($OldData)) {
					$remarks_timeline = !empty($OldData['remarks_timeline']) ? json_decode($OldData['remarks_timeline']) : [];
					$remarks_timeline = [
						[
							'date' => date('Y-m-d H:i:s'),
							'action' => 're-generated',
							'remarks' => !empty($remarks) ? $remarks : 'Manually Regenerated',
							'by' => trim($this->session->get('current_user')['name'] ?? 'Nazrul')
						],
						...$remarks_timeline
					];
					$final_salary['remarks_timeline'] = json_encode($remarks_timeline);
					$final_salary['status'] = 're-generated';
					$final_salary['remarks'] = !empty($remarks) ? $remarks : 'Manually Regenerated';
					$PreFinalSalaryModel = new PreFinalSalaryModel();
					$PreFinalSalaryModel->saveRivision($OldData['id']);
					$query = $PreFinalSalaryModel->update($OldData['id'], $final_salary);
				} else {

					$final_salary['remarks_timeline'] = json_encode(
						[
							[
								'date' => date('Y-m-d H:i:s'),
								'action' => 'generated',
								'remarks' => !empty($remarks) ? $remarks : 'System Generated',
								'by' => trim($this->session->get('current_user')['name'] ?? 40)
							]
						]
					);
					$final_salary['status'] = 'generated';
					$final_salary['remarks'] = !empty($remarks) ? $remarks : 'System Generated';
					$PreFinalSalaryModel = new PreFinalSalaryModel();
					$query = $PreFinalSalaryModel->insert($final_salary);
				}
				if ($query) {
					// return $query;
					$response = json_encode(['response' => 'success', 'description' => 'Final salary saved']);
					if ($return == true) {
						return $response;
					}
					echo $response;
				} else {
					// return $query;
					$response = json_encode(['response' => 'failed', 'description' => 'There was an error saving the final salary data' . json_encode($PreFinalSalaryModel->error())]);
					if ($return == true) {
						return $response;
					}
					echo $response;
				}
			} else {
				$response = json_encode(['response' => 'failed', 'description' => 'Salary structure not found']);
				if ($return == true) {
					return $response;
				}
				echo $response;
			}
		} else {
			$response = json_encode(['response' => 'failed', 'description' => 'Final paid days data not found']);
			if ($return == true) {
				return $response;
			}
			echo $response;
		}
	}

	public function calculateSalaryAll()
	{
		$EmployeeModel = new EmployeeModel();
		// $allEmployees = $EmployeeModel->where('id=', 489)->findAll();
		$allEmployees = $EmployeeModel->findAll();

		$employees = array();

		if (!empty($allEmployees)) {
			foreach ($allEmployees as $e_data) {
				if ($e_data['status'] != 'active') {
					if (!empty($e_data['date_of_leaving'])) {

						if (date('Y-m', strtotime($e_data['date_of_leaving'])) == date('Y-m', strtotime(first_date_of_last_month()))) {
							$employees[] = $e_data;
						}
					} else {
						$employees[] = $e_data;
					}
				} else {
					$employees[] = $e_data;
				}
			}
		}


		// echo '<pre>';
		// print_r( array_column($employees, 'internal_employee_id') );
		// echo '</pre>';
		// die();


		if (!empty($employees)) {
?>
			<table border="1" style="text-align: center;">
				<thead>
					<tr>
						<th>Employee Id</th>
						<th>Employee name</th>
						<th>Employee Code</th>
						<th>Employement Status</th>
						<th>Response</th>
						<th>Description</th>
						<th>Attention</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($employees as $employee) {
						ob_start();
						$this->calculateSalary($employee['id'], date('Y-m', strtotime(first_date_of_last_month())));
						$response = ob_get_clean();
						$response = json_decode($response, true);
					?>
						<tr>
							<td><?php echo $employee['id']; ?></td>
							<td><?php echo trim($employee['first_name'] . " " . $employee['last_name']); ?></td>
							<td><?php echo $employee['internal_employee_id']; ?></td>
							<td><?php echo $employee['status']; ?></td>
							<td>
								<?php echo $response['response']; ?>
							</td>
							<td>
								<?php echo $response['description']; ?>
							</td>
							<td>
								<?php
								if ($response['response'] == 'failed' && $employee['status'] == 'active') {
									echo 'Check error on this line';
								}
								?>
							</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
<?php
		} else {
			echo "no employees found";
		}
	}
}
