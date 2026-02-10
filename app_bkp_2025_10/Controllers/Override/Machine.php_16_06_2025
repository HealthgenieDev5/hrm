<?php

namespace App\Controllers\Override;

use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Models\MachineOverrideModel;
use App\Models\MachineOverrideRevisionModel;

class Machine extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }

    public function index()
    {
        $current_user = $this->session->get('current_user');

        if (!in_array($current_user['employee_id'], ['40', '52', '93'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $data = [
            'page_title'            => 'Machine Override',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'    => $this->getAllEmployees(),
        ];
        /*echo '<pre>';
        print_r($data);
        die();*/
        return view('MachineOverride/MachineOverride', $data);
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

    public function overrideMachine()
    {

        $response_array = array();
        $validation = $this->validate(
            [
                'employee_id'  =>  [
                    'rules'         =>  'required|is_not_unique[employees.id]',
                    'errors'        =>  [
                        'required'  => 'Please select an employee',
                        'is_not_unique' => 'This Employee does not exist in our database'
                    ]
                ],
                'from_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a date range',
                    ]
                ],
                'to_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a date range',
                    ]
                ],
                'machine'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a machine',
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
            $employee_id = $this->request->getPost('employee_id');
            $from_date = $this->request->getPost('from_date');
            $to_date = $this->request->getPost('to_date');
            $machine = $this->request->getPost('machine');
            $remarks = $this->request->getPost('remarks');

            $MachineOverrideModel = new MachineOverrideModel();
            $MachineOverrideModel->where('employee_id=', $employee_id);
            $MachineOverrideModel->groupStart();
            $MachineOverrideModel->where("machine_override.from_date between '" . $from_date . "' and '" . $to_date . "'");
            $MachineOverrideModel->orWhere("machine_override.to_date between '" . $from_date . "' and '" . $to_date . "'");
            $MachineOverrideModel->orWhere("'" . $from_date . "' between machine_override.from_date and machine_override.to_date");
            $MachineOverrideModel->orWhere("'" . $to_date . "' between machine_override.from_date and machine_override.to_date");
            $MachineOverrideModel->groupEnd();
            $ExistingEntry = $MachineOverrideModel->first();

            if (!empty($ExistingEntry)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Data already exist within selected date range';
                $response_array['response_data'] = $ExistingEntry;
            } else {
                $data = [
                    'employee_id' => $employee_id,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'machine' => $machine,
                    'remarks' => $remarks,
                ];
                $MachineOverrideModel = new MachineOverrideModel();
                $machineOverrideQuery = $MachineOverrideModel->insert($data);
                if (!$machineOverrideQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB Error:: Data not inserted';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Machine Override complete for selected date range';
                }
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getMachineOverrideAll()
    {

        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

        $MachineOverrideModel = new MachineOverrideModel();
        $MachineOverrideModel->select('machine_override.*');
        $MachineOverrideModel->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $MachineOverrideModel->select('employees.internal_employee_id as internal_employee_id');
        $MachineOverrideModel->select('departments.department_name as department_name');
        $MachineOverrideModel->select('companies.company_short_name as company_short_name');
        $MachineOverrideModel->join('employees', 'employees.id = machine_override.employee_id', 'left');
        $MachineOverrideModel->join('companies as companies', 'companies.id = employees.company_id', 'left');
        $MachineOverrideModel->join('departments as departments', 'departments.id = employees.department_id', 'left');

        $MachineOverrideModel->groupStart();
        $MachineOverrideModel->where('employees.date_of_leaving is null');
        $MachineOverrideModel->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')");
        $MachineOverrideModel->groupEnd();

        $MachineOverrideModel->orderBy('employees.id', 'ASC');

        $allMachineOverrideEntries = $MachineOverrideModel->findAll();

        $data = [
            'page_title'            => 'All Machine Override',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'allMachineOverrideEntries' => $allMachineOverrideEntries,
        ];

        return view('MachineOverride/MachineOverrideAll', $data);
    }

    public function existingMachineOverrides()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        $employee_id     = isset($params['employee_id']) ? $params['employee_id'] : "";
        $MachineOverrideModel = new MachineOverrideModel();
        $MachineOverrideModel->select('machine_override.*');
        $MachineOverrideModel->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $MachineOverrideModel->select('employees.internal_employee_id as internal_employee_id');
        $MachineOverrideModel->select('departments.department_name as department_name');
        $MachineOverrideModel->select('companies.company_short_name as company_short_name');
        $MachineOverrideModel->join('employees', 'employees.id = machine_override.employee_id', 'left');
        $MachineOverrideModel->join('companies as companies', 'companies.id = employees.company_id', 'left');
        $MachineOverrideModel->join('departments as departments', 'departments.id = employees.department_id', 'left');

        $MachineOverrideModel->where('machine_override.employee_id =', $employee_id);

        $allMachineOverrideEntries = $MachineOverrideModel->findAll();

        if (!empty($allMachineOverrideEntries)) {
            foreach ($allMachineOverrideEntries as $i => $d) {
                $allMachineOverrideEntries[$i]['from_date'] = date('d M, Y', strtotime($d['from_date']));
                $allMachineOverrideEntries[$i]['to_date'] = date('d M, Y', strtotime($d['to_date']));
            }
        }

        echo json_encode($allMachineOverrideEntries);
    }

    public function deleteMachineOverride()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'override_id'  =>  [
                    'rules'         =>  'required|is_not_unique[machine_override.id]',
                    'errors'        =>  [
                        'required'  => 'Please click on the delete button',
                        'is_not_unique' => 'This data does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('company_id');
        } else {
            $override_id   = $this->request->getPost('override_id');
            $MachineOverrideModel = new MachineOverrideModel();
            #save revision
            $old_data = $MachineOverrideModel->find($override_id);
            $old_data['override_id'] = $old_data['id'];
            unset($old_data['id']);
            $old_data['revised_by'] = $this->session->get('current_user')['employee_id'];

            $MachineOverrideRevisionModel = new MachineOverrideRevisionModel();
            $revision_query = $MachineOverrideRevisionModel->insert($old_data);
            if (!$revision_query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Revision Error <br> Please contact administrator.';
                $response_array['response_data'] = $old_data;
                $response_array['response_error'] = $MachineOverrideRevisionModel->error();
            } else {
                #delete
                $MachineOverrideModel = new MachineOverrideModel();
                $query = $MachineOverrideModel->delete($override_id);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Override Deleted Successfully';
                }
            }
        }
        return $this->response->setJSON($response_array);
    }
}
