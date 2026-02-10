<?php

namespace App\Controllers\Pdf;

use \Dompdf\Options;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;

class Ncl extends BaseController
{
	public $session;
	public $uri;

	public function __construct()
	{
		helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
		$this->session    = session();
	}

	public function gstcCategoryA($id)
	{
		if (
			!in_array($this->session->get('current_user')['role'], ['superuser', 'hr']) &&
			!in_array($this->session->get('current_user')['employee_id'], [40, 293])
		) {
			return view('User/Unauthorised', ["page_title" => "Unauthorised Access"]);
		}

		$data = $this->getPdfData($id);
		$sister_companies = '';
		if ($data['company_id'] == '2') {
			$sister_companies = 'M/S ' . $data['company_name'];
			$CompanyModel = new CompanyModel();
			$sister_companies .= ', M/S ' . $CompanyModel->find(4)['company_name'];
			$sister_companies .= 'and M/S ' . $CompanyModel->find(1)['company_name'];
		}
		if ($data['company_id'] == '4') {
			$sister_companies = 'M/S ' . $data['company_name'];
			$sister_companies = new CompanyModel();
			$sister_companies .= ', M/S ' . $CompanyModel->find(2)['company_name'];
			$sister_companies .= ' and M/S ' . $CompanyModel->find(1)['company_name'];
		}
		// $data['competitors'] = !empty($competitor) ? $this->customImplode(array_column($competitor, 'company_name')) : '';
		$data['sister_companies'] = $sister_companies;

		// print_r($data['competitors']);
		// die();

		// return view('Master/EmployeeNclGstcCategoryA', $data);

		$options = new options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new \Dompdf\Dompdf($options);
		$content = view('Master/EmployeeNclGstcCategoryA', $data);
		$dompdf->loadHtml($content);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("ncl-" . strtolower($data['first_name'] . '-' . $data['last_name']) . "-.pdf");
	}

	public function hgiplCategoryB($id)
	{
		if (
			!in_array($this->session->get('current_user')['role'], ['superuser', 'hr']) &&
			!in_array($this->session->get('current_user')['employee_id'], [40, 293])
		) {
			return view('User/Unauthorised', ["page_title" => "Unauthorised Access"]);
		}

		$data = $this->getPdfData($id);


		// return view('Master/EmployeeNclHgiplCategoryB', $data);

		$options = new options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new \Dompdf\Dompdf($options);
		$content = view('Master/EmployeeNclHgiplCategoryB', $data);
		$dompdf->loadHtml($content);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("ncl-" . strtolower($data['first_name'] . '-' . $data['last_name']) . "-.pdf");
	}

	public function getPdfData($id)
	{
		$data = [
			'page_title'            => 'Non Compete Loan',
			'current_controller'    => $this->request->getUri()->getSegment(2),
			'current_method'        => $this->request->getUri()->getSegment(3),
		];

		$EmployeeModel = new EmployeeModel();
		$EmployeeModel
			->select('employees.*')
			->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name")
			->select('designations.designation_name as designation_name')
			->select('departments.department_name as department_name')
			->select('companies.company_name as company_name')
			->select('companies.state as company_state')
			->select('companies.city as company_city')
			->select('companies.address as company_address')
			->select('employee_salary.ctc as monthly_ctc')
			->select('employee_salary.basic_salary as monthly_basic_salary')
			->select('employee_salary.house_rent_allowance as monthly_house_rent_allowance')
			->select('employee_salary.conveyance as monthly_conveyance')
			->select('employee_salary.medical_allowance as monthly_medical_allowance')
			->select('employee_salary.special_allowance as monthly_special_allowance')
			->select('employee_salary.fuel_allowance as monthly_fuel_allowance')
			->select('employee_salary.vacation_allowance as monthly_vacation_allowance')
			->select('employee_salary.other_allowance as monthly_other_allowance')
			->select('employee_salary.gross_salary as monthly_gross_salary')
			->select('employee_salary.enable_bonus as enable_bonus')

			->select('employee_salary.non_compete_loan as non_compete_loan')
			->select('employee_salary.non_compete_loan_from as non_compete_loan_from')
			->select('employee_salary.non_compete_loan_amount_per_month as non_compete_loan_amount_per_month')

			->select('employee_salary.loyalty_incentive as loyalty_incentive')
			->select('employee_salary.loyalty_incentive_from as loyalty_incentive_from')
			->select('employee_salary.loyalty_incentive_to as loyalty_incentive_to')
			->select('employee_salary.loyalty_incentive_amount_per_month as loyalty_incentive_amount_per_month')
			->select('employee_salary.loyalty_incentive_mature_after_month as loyalty_incentive_mature_after_month')
			->select('employee_salary.loyalty_incentive_pay_after_month as loyalty_incentive_pay_after_month')
			->select('employee_salary.loyalty_incentive_remarks as loyalty_incentive_remarks')

			->select('employee_salary.pf as enable_pf')
			->select('employee_salary.esi as enable_esi')
			->select('employee_salary.lwf as enable_lwf')
			->select('minimum_wages_categories.minimum_wages_category_value as minimum_wages_category_value')
			->select('minimum_wages_categories.minimum_wages_category_name as minimum_wages_category_name')
			->join('designations as designations', 'designations.id=employees.designation_id', 'left')
			->join('departments as departments', 'departments.id=employees.department_id', 'left')
			->join('companies as companies', 'companies.id=employees.company_id', 'left')
			->join('employee_salary as employee_salary', 'employee_salary.employee_id=employees.id', 'left')
			->join('minimum_wages_categories as minimum_wages_categories', 'minimum_wages_categories.id=employees.min_wages_category', 'left')
			->where('employees.id=', $id);

		$GetEmployeeData = $EmployeeModel->first();

		if (empty($GetEmployeeData)) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		foreach ($GetEmployeeData as $key => $val) {
			$data[$key] = trim($val);
			if ($key == 'attachment') {
				$data[$key] = json_decode($val, true);
			}
		}

		$data['notice_period'] = !empty($data['notice_period']) ? $data['notice_period'] : 0;
		$data['number_of_cheques'] = $data['notice_period'] > 0 ? $data['notice_period'] / 30 : 0;
		$data['notice_period_in_months'] = $data['notice_period'] > 0 ? $data['notice_period'] / 30 : 0;
		$data['notice_period'] = $data['notice_period'] . " days";

		switch ($data['notice_period_in_months']) {
			case '1':
				$data['notice_period_in_months_text'] = 'one';
				break;
			case '2':
				$data['notice_period_in_months_text'] = 'two';
				break;
			case '3':
				$data['notice_period_in_months_text'] = 'three';
				break;
			case '4':
				$data['notice_period_in_months_text'] = 'four';
				break;
			case '5':
				$data['notice_period_in_months_text'] = 'five';
				break;
			case '6':
				$data['notice_period_in_months_text'] = 'six';
				break;

			default:
				$data['notice_period_in_months_text'] = 'zero';
				break;
		}

		if ($data['enable_pf'] == 'yes') {
			if ($data['monthly_gross_salary'] >= 15000) {
				$data['pf_employee_contribution'] = ((15000 * 12) / 100);
				$data['pf_employer_contribution'] = ((15000 * 13) / 100);
			} else {
				$data['pf_employee_contribution'] = (($data['monthly_gross_salary'] * 12) / 100);
				$data['pf_employer_contribution'] = (($data['monthly_gross_salary'] * 13) / 100);
			}
		} else {
			$data['pf_employee_contribution'] = 0;
			$data['pf_employer_contribution'] = 0;
		}

		if ($data['enable_esi'] == 'yes' && $data['monthly_gross_salary'] <= 21000) {
			$data['esi_employee_contribution'] = ($data['monthly_gross_salary'] * 0.75) / 100;
			$data['esi_employer_contribution'] = ($data['monthly_gross_salary'] * 3.25) / 100;
		} else {
			$data['esi_employee_contribution'] = 0;
			$data['esi_employer_contribution'] = 0;
		}

		if ($data['enable_bonus'] == 'yes' && !empty($data['minimum_wages_category_value'])) {
			$data['bonus'] = ($data['minimum_wages_category_value'] * 8.33) / 100;
		} else {
			$data['bonus'] = 0;
		}

		$data['gratuity'] = (($data['monthly_basic_salary'] / 26) * 15) * (1 / 12);

		$data['non_compete_loan_amount_per_month'] = $data['non_compete_loan'] == 'yes' ? $data['non_compete_loan_amount_per_month'] : 0;

		if (($data['enable_lwf'] == 'yes')) {
			$data['lwf_employee_contribution'] = ((($data['monthly_gross_salary'] * 0.2) / 100 <= 34) ? ($data['monthly_gross_salary'] * 0.2) / 100 : 34);
			$data['lwf_employer_contribution'] = $data['lwf_employee_contribution'] * 2;
		} else {
			$data['lwf_employee_contribution'] = 0;
			$data['lwf_employer_contribution'] = 0;
		}


		$data['net_pay'] = $data['monthly_gross_salary'] - $data['esi_employee_contribution'] - $data['pf_employee_contribution'] - $data['lwf_employee_contribution'];

		$data['monthly_el'] = round($data['monthly_basic_salary'] / 30 * 1.25);
		$data['monthly_cl'] = round($data['monthly_gross_salary'] / 30 * 1);

		$data['ctc'] = $data['monthly_gross_salary'] + $data['gratuity'] + $data['bonus'] + $data['pf_employer_contribution'] + $data['esi_employer_contribution'] + $data['lwf_employer_contribution'] + $data['monthly_el'] + $data['monthly_cl'] + $data['non_compete_loan_amount_per_month'];

		if (isset($data['family_members']) && !empty($data['family_members'])) {
			$family_members = json_decode($data['family_members'], true);
			foreach ($family_members as $index => $family_member) {
				if (isset($family_member['member_dob']) && !empty($family_member['member_dob'])) {
					$member_dob = $family_member['member_dob'];
					$today = date_create('today');
					$birthday = date_create($member_dob);
					$differenceInMilisecond = $today->getTimestamp() - $birthday->getTimestamp();
					$year_age = floor($differenceInMilisecond / 31536000);
					$day_age = floor(($differenceInMilisecond % 31536000) / 86400);
					$month_age = floor($day_age / 30);
					$day_age = $day_age % 30;

					if (is_nan($year_age) || is_nan($month_age) || is_nan($day_age)) {
						$member_age = 0;
					} else {
						$member_age = $year_age;
					}
					$family_members[$index]['member_age'] = $member_age;
				}
			}
			// $data['family_members'] = json_encode($family_members);
			$data['family_members'] = $family_members;
		}

		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// die();

		// return view('Master/EmployeeNcl', $data); die();

		return $data;
	}

	private function customImplode(array $items)
	{
		foreach ($items as $i => $item) {
			$items[$i] = 'M/S ' . $item;
		}

		// Get the last item
		$lastItem = array_pop($items);

		// If there are more than one items, join with commas, and append 'and' for the last item
		if (!empty($items)) {
			return implode(', ', $items) . ' and ' . $lastItem;
		}

		// If there was only one item, return it (already prefixed)
		return $lastItem;
	}
}
