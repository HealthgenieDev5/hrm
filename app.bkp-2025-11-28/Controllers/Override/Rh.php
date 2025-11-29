<?php

namespace App\Controllers\Override;

use App\Models\HolidayModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Models\FixedRhModel;

class Rh extends BaseController
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

        if (!in_array($current_user['employee_id'], ['40', '93'])) {
            return redirect()->to(base_url('/unauthorised'));
        }
        $current_year = date('Y');

        $data = [
            'page_title'            => 'Override RH',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'             => $this->getAllEmployees(),
            'allRH'                 => $this->getAllRh($current_year),
        ];
        /*echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();*/
        return view('RhOverride/RhOverride', $data);
    }

    public function getAllRh($year)
    {
        $HolidayModel = new HolidayModel();
        $HolidayModel
            ->where('year(holiday_date) =', $year)
            ->where('holiday_code =', 'RH');
        $all_rhs = $HolidayModel->findAll();
        return !empty($all_rhs) ? $all_rhs : null;
    }

    public function getAllEmployees()
    {

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->select("(select fixed_rh.id from fixed_rh where fixed_rh.employee_id = employees.id and fixed_rh.year = '" . date('Y') . "' order by fixed_rh.id asc limit 0,1) as rh_index_1")
            ->select("(select fixed_rh.rh_id from fixed_rh where fixed_rh.employee_id = employees.id and fixed_rh.year = '" . date('Y') . "' order by fixed_rh.id asc limit 0,1) as rh_id_1")
            ->select("(select fixed_rh.id from fixed_rh where fixed_rh.employee_id = employees.id and fixed_rh.year = '" . date('Y') . "' order by fixed_rh.id asc limit 1,1) as rh_index_2")
            ->select("(select fixed_rh.rh_id from fixed_rh where fixed_rh.employee_id = employees.id and fixed_rh.year = '" . date('Y') . "' order by fixed_rh.id asc limit 1,1) as rh_id_2")
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            ->where('employees.status =', 'active')
            ->orderBy('employees.first_name', 'ASC');
        $AllEmployees = $EmployeeModel->findAll();

        if (!empty($AllEmployees)) {
            return $AllEmployees;
        } else {
            return null;
        }
    }

    public function overrideRh()
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
                'rh_id_1'  =>  [
                    'rules'         =>  'required|is_not_unique[holidays.id]',
                    'errors'        =>  [
                        'required'  => 'Please select an RH',
                        'is_not_unique' => 'This RH does not exist in our database'
                    ]
                ],
                'rh_id_2'  =>  [
                    'rules'         =>  'required|is_not_unique[holidays.id]',
                    'errors'        =>  [
                        'required'  => 'Please select an RH',
                        'is_not_unique' => 'This RH does not exist in our database'
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
            $rh_id_1 = $this->request->getPost('rh_id_1');
            $rh_id_2 = $this->request->getPost('rh_id_2');

            if ($rh_id_1 == $rh_id_2) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Please select different RH for both fields';
            } else {
                $FixedRhModel_1 = new FixedRhModel();
                $FixedRhModel_1->where('employee_id=', $employee_id)->where('year=', date('Y'))->orderBy('id', 'ASC')->limit(1, 0);
                $rh_index_1 = $FixedRhModel_1->first()['id'] ?? null;
                if (!empty($rh_index_1)) {
                    $rh_id_1_data = array("rh_id" => $rh_id_1);
                    $FixedRhModel_update_1 = new FixedRhModel();
                    $rh_id_1_update_query = $FixedRhModel_update_1->update($rh_index_1, $rh_id_1_data);
                } else {
                    $rh_id_1_data = array("employee_id" => $employee_id, "rh_id" => $rh_id_1, "year" => date('Y'));
                    $FixedRhModel_update_1 = new FixedRhModel();
                    $rh_id_1_update_query = $FixedRhModel_update_1->insert($rh_id_1_data);
                }

                if ($rh_id_1_update_query) {
                    $FixedRhModel_2 = new FixedRhModel();
                    $FixedRhModel_2->where('employee_id=', $employee_id)->where('year=', date('Y'))->orderBy('id', 'ASC')->limit(1, 1);
                    $rh_index_2 = $FixedRhModel_2->first()['id'] ?? null;
                    if (!empty($rh_index_2)) {
                        $rh_id_2_data = array("rh_id" => $rh_id_2);
                        $FixedRhModel_update_2 = new FixedRhModel();
                        $rh_id_2_update_query = $FixedRhModel_update_2->update($rh_index_2, $rh_id_2_data);
                    } else {
                        $rh_id_2_data = array("employee_id" => $employee_id, "rh_id" => $rh_id_2, "year" => date('Y'));
                        /*print_r($rh_id_2_data);
                        die();*/
                        $FixedRhModel_update_2 = new FixedRhModel();
                        $rh_id_2_update_query = $FixedRhModel_update_2->insert($rh_id_2_data);
                    }
                    if ($rh_id_2_update_query) {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Both RH Updated';
                    } else {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB Error::Second RH didnot update, but first RH updated.Please contact developer';
                    }
                } else {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB Error::Both RH didnot update.Please contact developer';
                }
            }
        }
        return $this->response->setJSON($response_array);
    }
}
