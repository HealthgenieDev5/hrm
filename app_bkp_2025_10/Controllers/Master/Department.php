<?php

namespace App\Controllers\Master;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\DepartmentModel;
use App\Controllers\BaseController;

class Department extends BaseController
{
    public $session;
    public $db;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();

        require_once APPPATH . 'ThirdParty/ssp.class.php';
        $this->db = db_connect();
    }

    public function index()
    {

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $current_user = $this->session->get('current_user');
        $EmployeeModel = new EmployeeModel();
        $CompanyModel = new CompanyModel();

        $data = [
            'page_title'            => 'Department Master',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'all_companies'         => $CompanyModel->orderBy('company_name ASC')->findAll(),
            'all_employees'         => $EmployeeModel->orderBy('first_name ASC')->findAll(),
        ];
        return view('Master/DepartmentMaster', $data);
    }

    public function getAllDepartments()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        /*print_r($params);
        die();*/
        $get_departments_sql = "select 
        d.id as department_id, 
        d.department_name as department_name, 
        d.hod_employee_id as hod_employee_id, 
        d.date_time as date_time, 
        c.company_name as company_name, 
        c.company_short_name as company_short_name, 
        trim( concat( e.first_name, ' ', e.last_name ) ) as hod_name 
        from departments d 
        left join companies c on c.id = d.company_id 
        left join employees e on e.id = d.hod_employee_id";

        $condition = " where d.id is not null";

        $company_id = isset($params['filter_company']) ? $params['filter_company'] : '';
        if (isset($company_id) && !empty($company_id) && !in_array('', $company_id)) {
            $company_id_imploded = "'" . implode("', '", $company_id) . "'";
            $condition .= " and d.company_id in (" . $company_id_imploded . ") ";
        } else {
            $condition .= " ";
        }

        $get_departments_sql .= $condition;

        $CustomModel = new CustomModel();
        $all_departments = $CustomModel->CustomQuery($get_departments_sql)->getResultArray();
        foreach ($all_departments as $index => $department_row) {
            $all_departments[$index]['action'] = '';
            $all_departments[$index]['date_time'] = !empty($all_departments[$index]['date_time']) ? date('d-M-Y h:i A', strtotime($all_departments[$index]['date_time'])) : '';
        }
        echo json_encode($all_departments);
    }



    public function addDepartment()
    {
        // print_r($_REQUEST);
        // die();
        $response_array = array();
        $rules = [
            'department_name'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Department Name is required',
                ]
            ],
            'company_id'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select a company',
                ]
            ]
        ];

        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $department_name    = $this->request->getPost('department_name');
            $company_id         = $this->request->getPost('company_id');
            $DepartmentModel = new DepartmentModel();
            $find_exisiting = $DepartmentModel
                ->where('department_name=', $department_name)
                ->where('company_id=', $company_id)
                ->first();
            if (!empty($find_exisiting)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
                $response_array['error_type'] = 'Check Existing Department and Company';
                $response_array['response_data']['validation'] = ['department_name' => 'This department already exist in selected Company'];
            } else {
                $values = [
                    'department_name'   => $this->request->getPost('department_name'),
                    'hod_employee_id'   => $this->request->getPost('hod_employee_id'),
                    'company_id'        => $this->request->getPost('company_id'),
                ];
                $DepartmentModel = new DepartmentModel();
                $query = $DepartmentModel->insert($values);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Department Added Successfully';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }


    public function deleteDepartment()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'department_id'  =>  [
                    'rules'         =>  'required|is_not_unique[departments.id]',
                    'errors'        =>  [
                        'required'  => 'Department ID is required',
                        'is_not_unique' => 'This Department is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('department_id');
        } else {
            $department_id   = $this->request->getPost('department_id');
            $DepartmentModel = new DepartmentModel();
            $query = $DepartmentModel->delete($department_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Department Deleted Successfully';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getDepartment()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'department_id'  =>  [
                    'rules'         =>  'required|is_not_unique[departments.id]',
                    'errors'        =>  [
                        'required'  => 'Department ID is required',
                        'is_not_unique' => 'This Department is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('department_id');
        } else {
            $department_id   = $this->request->getPost('department_id');
            $DepartmentModel = new DepartmentModel();
            $query = $DepartmentModel->find($department_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Department Found';
                $response_array['response_data']['department'] = $query;
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function updateDepartment()
    {
        /*print_r($_REQUEST);
        die();*/
        $response_array = array();
        $validation = $this->validate(
            [
                'department_id'     =>  [
                    'rules'         =>  'required|is_not_unique[departments.id]',
                    // 'rules'         =>  'required|is_unique[departments.id,id,{department_id}]',
                    'errors'        =>  [
                        'required'  => 'Department id is required',
                        'is_unique' => 'This Department does not exist in our database anymore. Please contact Administrator'
                    ]
                ],
                'department_name'   =>  [
                    // 'rules'         =>  'required|is_unique[departments.department_name,id,{department_id}]',
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Department Name is required',
                        // 'is_unique' => 'This Department already exist in our database......',
                    ]
                ],
                'company_id'        =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a company',
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $department_id   = $this->request->getPost('department_id');
            $department_name    = $this->request->getPost('department_name');
            $company_id         = $this->request->getPost('company_id');
            $DepartmentModel = new DepartmentModel();
            $find_exisiting = $DepartmentModel
                ->where('department_name=', $department_name)
                ->where('company_id=', $company_id)
                ->where('id!=', $department_id)
                ->first();
            if (!empty($find_exisiting)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
                $response_array['error_type'] = 'Check Existing Department and Company';
                $response_array['response_data']['validation'] = ['department_name' => 'This department already exist in selected Company'];
            } else {
                $data = [
                    'hod_employee_id'   => $this->request->getPost('hod_employee_id'),
                    'company_id'        => $this->request->getPost('company_id'),
                    'department_name'   => $this->request->getPost('department_name'),
                ];
                $DepartmentModel = new DepartmentModel();
                $query = $DepartmentModel->update($department_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Department Updated Successfully';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }
}
