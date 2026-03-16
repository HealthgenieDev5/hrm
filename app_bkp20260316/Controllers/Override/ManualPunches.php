<?php

namespace App\Controllers\Override;

use App\Models\EmployeeModel;
use App\Models\ManualPunchModel;
use App\Controllers\BaseController;
use App\Models\ManualPunchRevisionModel;

class ManualPunches extends BaseController
{
    public $session;
    public $uri;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }


    public function index()
    {
        $current_user = $this->session->get('current_user');

        if (!in_array($current_user['employee_id'], ['40', '52', '93', '461'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $data = [
            'page_title'            => 'Manual Punch',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'             => $this->getAllEmployees(),
        ];
        return view('ManualPunches/ManualPunches', $data);
    }

    public function getAllEmployees()
    {
        $EmployeeModel = new EmployeeModel();
        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
        $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            /*->where('employees.status =', 'active')*/
            ->groupStart()
            ->where('employees.date_of_leaving is null')
            ->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')")
            ->groupEnd()
            ->orderBy('employees.first_name', 'ASC');
        $AllEmployees = $EmployeeModel->findAll();

        if (!empty($AllEmployees)) {
            return $AllEmployees;
        } else {
            return null;
        }
    }

    public function createManualPunch()
    {
        // print_r($_REQUEST);
        // die();
        $response_array = [];
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Invalid request type',
                'response_data' => []
            ]);
        }



        $validation = $this->validate(
            [
                'employee_id'  =>  [
                    'rules'         =>  'required|is_not_unique[employees.id]',
                    'errors'        =>  [
                        'required'  => 'Please select an employee',
                        'is_not_unique' => 'This Employee does not exist in our database'
                    ]
                ],
                'punch_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'lease select a punch date',
                    ]
                ],
                'remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please enter your remarks',
                    ]
                ],
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $employee_id    = $this->request->getPost('employee_id');
            $punch_date     = $this->request->getPost('punch_date');
            $punch_in       = $this->request->getPost('punch_in') ?? null;
            $punch_out      = $this->request->getPost('punch_out') ?? null;
            $remarks        = $this->request->getPost('remarks');

            $data = [
                'employee_id'   => $employee_id,
                'punch_date'    => $punch_date,
                'remarks'       => $remarks,
                'created_by'    => $this->session->get('current_user')['id'],
            ];
            if (!empty($punch_in)) {
                $data['punch_in'] = $punch_in;
            }
            if (!empty($punch_out)) {
                $data['punch_out'] = $punch_out;
            }

            // print_r($data);
            // die();
            $ManualPunchModel = new ManualPunchModel();

            $isExists = $ManualPunchModel->where('employee_id', $employee_id)->where('punch_date', $punch_date)->first();

            if ($isExists) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'A record already exists for selected date';
            } else {
                $createManualPunch = $ManualPunchModel->insert($data);
                if (!$createManualPunch) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB Error:: Data not inserted';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Manual Punch created successfully';
                }
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getManualPunch()
    {
        $employee_id = $this->request->getPost('employee_id');

        $ManualPunchModel = new ManualPunchModel();
        $ManualPunchModel
            ->select('manual_punches.*')
            ->select('CONCAT(employees.first_name, " ", employees.last_name) AS employee_name')
            ->join('employees', 'employees.id = manual_punches.employee_id', 'left');
        if ($employee_id) {
            $ManualPunchModel->where('manual_punches.employee_id', $employee_id);
        }

        $results = $ManualPunchModel->findAll();
        return $this->response->setJSON($results);
    }

    public function deleteManualPunch()
    {
        $id = $this->request->getPost('id');
        $ManualPunchModel = new ManualPunchModel();
        $isExists = $ManualPunchModel->find($id);
        if ($isExists) {

            $ManualPunchRevisionModel = new ManualPunchRevisionModel();
            $deleteManualPunch = $ManualPunchModel->delete($id);
            if (!$deleteManualPunch) {
                return $this->response->setJSON([
                    'response_type' => 'error',
                    'response_description' => 'DB Error:: Data not deleted'
                ]);
            } else {
                $revisionData = [
                    'employee_id' => $isExists['employee_id'],
                    'punch_date'  => $isExists['punch_date'],
                    'punch_in'    => $isExists['punch_in'],
                    'punch_out'   => $isExists['punch_out'],
                    'remarks'     => $isExists['remarks'],
                    'created_by'  => $isExists['created_by'],
                    'revised_by'  => $this->session->get('current_user')['id'],
                    'created_at' =>  $isExists['updated_at'],
                ];
                $ManualPunchRevisionModel->insert($revisionData);
                return $this->response->setJSON([
                    'response_type' => 'success',
                    'response_description' => 'Manual Punch deleted successfully'
                ]);
            }
        }
    }

    public function getManualPunchesAll()
    {


        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

        $ManualPunchModel = new ManualPunchModel();
        $ManualPunchModel->select('manual_punches.*');
        $ManualPunchModel->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $ManualPunchModel->select('employees.internal_employee_id as internal_employee_id');
        $ManualPunchModel->select('departments.department_name as department_name');
        $ManualPunchModel->select('companies.company_short_name as company_short_name');
        $ManualPunchModel->join('employees', 'employees.id = manual_punches.employee_id', 'left');
        $ManualPunchModel->join('companies as companies', 'companies.id = employees.company_id', 'left');
        $ManualPunchModel->join('departments as departments', 'departments.id = employees.department_id', 'left');

        $ManualPunchModel->groupStart();
        $ManualPunchModel->where('employees.date_of_leaving is null');
        $ManualPunchModel->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')");
        $ManualPunchModel->groupEnd();

        $ManualPunchModel->orderBy('employees.id', 'ASC');

        $allManualPunchEntries = $ManualPunchModel->findAll();

        $data = [
            'page_title'            => 'All Manual Punches',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'allManualPunchEntries' => $allManualPunchEntries,
        ];

        return view('ManualPunches/ManualPunchesAll', $data);
    }
}
