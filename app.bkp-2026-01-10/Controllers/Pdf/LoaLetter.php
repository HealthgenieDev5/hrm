<?php

namespace App\Controllers\Pdf;

use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use \Dompdf\Options;

class LoaLetter extends BaseController
{
	public $session;
	public $uri;

	public function __construct()
	{
		helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
		$this->session    = session();
	}

	public function index($id)
	{
		if (
			!in_array($this->session->get('current_user')['role'], ['superuser', 'hr']) &&
			!in_array($this->session->get('current_user')['employee_id'], [40])
		) {
			return view('User/Unauthorised', ["page_title" => "Unauthorised Access"]);
		}

		$data = [
			'page_title'            => 'LOA Letter',
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
			->select('COALESCE(employee_salary.stipend, 0) as monthly_stipend')
			->join('designations as designations', 'designations.id=employees.designation_id', 'left')
			->join('departments as departments', 'departments.id=employees.department_id', 'left')
			->join('companies as companies', 'companies.id=employees.company_id', 'left')
			->join('employee_salary as employee_salary', 'employee_salary.employee_id=employees.id', 'left')
			->where('employees.id=', $id);

		$GetEmployeeData = $EmployeeModel->first();

		if (empty($GetEmployeeData)) {
			throw PageNotFoundException::forPageNotFound();
		}

		// Check if the employee is an intern
		if (!preg_match('/\bintern\b/i', $GetEmployeeData['designation_name'])) {
			throw PageNotFoundException::forPageNotFound('LOA Letter is only available for interns');
		}

		foreach ($GetEmployeeData as $key => $val) {
			$data[$key] = trim($val);
			if ($key == 'attachment') {
				$data[$key] = json_decode($val, true);
			}
		}

		$options = new options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new \Dompdf\Dompdf($options);
		$content = view('Master/EmployeeLoaLetter', $data);
		$dompdf->loadHtml($content);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("loa-letter-" . strtolower($data['first_name'] . '-' . $data['last_name']) . ".pdf");
	}
}