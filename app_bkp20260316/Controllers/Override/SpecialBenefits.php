<?php

namespace App\Controllers\Override;

use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Models\SpecialBenefitsModel;

class SpecialBenefits extends BaseController
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
        $data = [
            'page_title'            => 'SpecialBenifits',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'    => $this->getAllEmployees(),
        ];
        return view('SpecialBenifits/SpecialBenifits', $data);
    }

    public function getAllEmployees()
    {
        $EmployeeModel = new EmployeeModel();
        $AllEmployees =
            $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->select('employees.second_saturday_fixed_off as second_saturday_fixed_off')
            ->select('employees.late_sitting_allowed as late_sitting_allowed')
            ->select('employees.late_sitting_formula as late_sitting_formula')
            ->select('employees.late_sitting_formula_effective_from as late_sitting_formula_effective_from')
            ->select('employees.over_time_allowed as over_time_allowed')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            // ->where('employees.id=', '40')
            ->orderBy('employees.first_name', 'ASC')
            ->findAll();

        if (!empty($AllEmployees)) {
            return $AllEmployees;
        } else {
            return null;
        }
    }

    public function updateSpecialBenefit()
    {

        $response_array = array();
        $rules = [
            'employee_id'  =>  [
                'rules'         =>  'required|is_not_unique[employees.id]',
                'errors'        =>  [
                    'required'  => 'Please select an employee',
                    'is_not_unique' => 'This Employee does not exist in our database'
                ]
            ],
            'second_saturday_fixed_off'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select a Yes if second Saturday is fixed off',
                ]
            ],
            'late_sitting_allowed'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select a Yes if late sitting allowed',
                ]
            ],
            'over_time_allowed'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select a Yes if over time allowed',
                ]
            ],
        ];

        if (isset($_REQUEST['late_sitting_allowed']) && $_REQUEST['late_sitting_allowed'] == 'yes') {
            $rules['late_sitting_formula'] = [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select a formula if late sitting allowed',
                ]
            ];
            $rules['late_sitting_formula_effective_from'] = [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select a date if late sitting allowed',
                ]
            ];
        }

        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $employee_id = $this->request->getPost('employee_id');


            $data = [
                'second_saturday_fixed_off' => $this->request->getPost('second_saturday_fixed_off'),
                'late_sitting_allowed' => $this->request->getPost('late_sitting_allowed'),
                'late_sitting_formula' => $this->request->getPost('late_sitting_formula'),
                'late_sitting_formula_effective_from' => $this->request->getPost('late_sitting_formula_effective_from'),
                'over_time_allowed' => $this->request->getPost('over_time_allowed'),
            ];

            if ($data['late_sitting_allowed'] == 'yes' && $data['over_time_allowed'] == 'yes') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Either Late Sitting Or Overtime can be Yes';
            } else {
                $SpecialBenefitsModel = new SpecialBenefitsModel();
                $updateSpecialBenifitsQuery = $SpecialBenefitsModel->update($employee_id, $data);
                if ($updateSpecialBenifitsQuery) {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Special benifits updated';
                } else {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB Error: Unable to update epecial benifits';
                }
            }
        }
        return $this->response->setJSON($response_array);
    }
}
