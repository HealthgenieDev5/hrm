<?php

namespace App\Controllers\Master;

use App\Models\CustomModel;
use App\Models\SalaryModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Models\PreFinalSalaryModel;
use App\Models\SalaryRevisionModel;
use App\Models\MinWagesCategoryModel;

class Salary extends BaseController
{
    public $session;
    public $intern;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
        $this->intern = [75];
    }
    public function index($employee_id)
    {
        // if( !in_array($this->session->get('current_user')['role'], ['superuser', 'hr'] )  ){
        // if (!in_array(session()->get('current_user')['employee_id'], ['40', '93'])) {
        if (!in_array(session()->get('current_user')['employee_id'], ['40', '52', '93', '223'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        if (!empty($employee_id)) {
            $current_user = $this->session->get('current_user');

            $CustomModel = new CustomModel();
            $CustomSql = "select e.id as id, e.first_name as first_name, e.last_name as last_name, e.internal_employee_id as internal_employee_id,
            d.department_name, 
            c.company_name 
            from employees e 
            left join departments d on d.id = e.department_id 
            left join companies c on c.id = e.company_id 
            order by e.first_name
            ";
            $employees = $CustomModel->CustomQuery($CustomSql)->getResultArray();
            $data = [
                'page_title'            => 'Employee Salary Management (Monthly)',
                'current_controller'    => $this->request->getUri()->getSegment(2),
                'current_method'        => $this->request->getUri()->getSegment(3),
                'employees'             => $employees,
                'employee_id'           => $employee_id,
            ];
            $EmployeeModel = new EmployeeModel();
            $employee = $EmployeeModel->find($employee_id);



            $SalaryModel = new SalaryModel();
            $get_employee_salary = $SalaryModel->where('employee_id =', $employee_id)->first();
            if (!empty($get_employee_salary)) {
                $salary = array();
                foreach ($get_employee_salary as $field_name => $field_value) {
                    if (in_array($field_name, array('non_compete_loan_from', 'non_compete_loan_to', 'loyalty_incentive_from', 'loyalty_incentive_to'))) {
                        $salary[$field_name] = (!empty($field_value) && $field_value !== '0000-00-00') ? date('Y-m-d', strtotime($field_value)) : '';
                    } else {
                        $salary[$field_name] = $field_value;
                    }
                }
                $data['salary'] = $salary;
            } else {
                $data['salary'] = array(
                    'employee_id' => $employee_id,
                );
            }

            #begin::Salary disbursed or not
            $year_month_of_last_month = date('My', strtotime('first day of last month'));
            $year_month_of_current_month = date('My');
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $PreFinalSalaryModel->select('pre_final_salary.*');
            $PreFinalSalaryModel->where('employee_id =', $employee_id);
            $PreFinalSalaryModel->where('year =', date('Y', strtotime($year_month_of_last_month)));
            $PreFinalSalaryModel->where('month =', date('m', strtotime($year_month_of_last_month)));
            $FinalSalary = $PreFinalSalaryModel->first();
            if (!empty($FinalSalary)) {
                $data['last_month_salary_disbursed'] = $FinalSalary['disbursed'];
            } else {
                $data['last_month_salary_disbursed'] = 'no';
            }
            #end::Salary disbursed or not
            if (in_array($employee['designation_id'], $this->intern)) {
                return view('Master/InternStipend', $data);
            }

            return view('Master/SalaryMaster', $data);
        } else {
            $data = [
                'page_title' => 'Missing parameter in the url',
                'message' => 'Missing parameter in the url',
            ];
            return view('show-error', $data);
        }
    }

    public function updateSalary()
    {

        $response_array = array();

        // if ($this->request->getMethod() == 'post') {
        $rules = [
            'employee_id'  =>  [
                'rules'         =>  'required|is_not_unique[employees.id]',
                'errors'        =>  [
                    'required'  => 'Emloyee ID is required',
                    'is_not_unique' => 'This Emloyee ID does not exist in our database Please contact administrator'
                ]
            ],
        ];

        if (!empty($this->request->getPost('pf'))) {
            $rules['pf']                                 = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['pf_number']                          = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            // $rules['pf_employee_contribution']           = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
            // $rules['pf_employer_contribution']           = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
        }
        if (!empty($this->request->getPost('esi'))) {
            $rules['esi']                                = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['esi_number']                         = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            // $rules['esi_employee_contribution']          = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
            // $rules['esi_employer_contribution']          = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
        }
        if (!empty($this->request->getPost('non_compete_loan'))) {
            $rules['non_compete_loan']                   = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['non_compete_loan_from']              = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            // $rules['non_compete_loan_to']                = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
            $rules['non_compete_loan_amount_per_month']  = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        }
        if (!empty($this->request->getPost('loyalty_incentive'))) {
            $rules['loyalty_incentive']                  = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['loyalty_incentive_from']             = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            // $rules['loyalty_incentive_to']               = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
            $rules['loyalty_incentive_amount_per_month'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['loyalty_incentive_mature_after_month'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['loyalty_incentive_pay_after_month'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        }
        if (!empty($this->request->getPost('lwf'))) {
            // $rules['lwf_employee_contribution']          = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
            // $rules['lwf_employer_contribution']          = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
            // $rules['lwf_deduction_on_every_n_month']     = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
        }
        if (!empty($this->request->getPost('tds'))) {
            // $rules['tds_amount_per_month']          = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
            // $rules['tds_preferred_slab']          = ['rules' => 'required', 'errors' => [ 'required' => 'This field is required'] ];
        }

        $validation = $this->validate($rules);

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {

            $data = [
                'employee_id'                               => $this->request->getPost('employee_id'),
                'basic_salary'                              => !empty($this->request->getPost('basic_salary')) ? $this->request->getPost('basic_salary') : 0,
                'house_rent_allowance'                      => !empty($this->request->getPost('house_rent_allowance')) ? $this->request->getPost('house_rent_allowance') : 0,
                'conveyance'                                => !empty($this->request->getPost('conveyance')) ? $this->request->getPost('conveyance') : 0,
                'medical_allowance'                         => !empty($this->request->getPost('medical_allowance')) ? $this->request->getPost('medical_allowance') : 0,
                'special_allowance'                         => !empty($this->request->getPost('special_allowance')) ? $this->request->getPost('special_allowance') : 0,
                'fuel_allowance'                            => !empty($this->request->getPost('fuel_allowance')) ? $this->request->getPost('fuel_allowance') : 0,
                'vacation_allowance'                        => !empty($this->request->getPost('vacation_allowance')) ? $this->request->getPost('vacation_allowance') : 0,
                'other_allowance'                           => !empty($this->request->getPost('other_allowance')) ? $this->request->getPost('other_allowance') : 0,
                // 'gratuity'                                  => !empty($this->request->getPost('gratuity')) ? $this->request->getPost('gratuity') : 0,
                // 'bonus'                                     => !empty($this->request->getPost('bonus')) ? $this->request->getPost('bonus') : 0,
            ];

            if (!empty($this->request->getPost('enable_bonus'))) {
                $data['enable_bonus']                       = $this->request->getPost('enable_bonus');
            } else {
                $data['enable_bonus']                       = 'no';
            }
            if (!empty($this->request->getPost('pf'))) {
                $data['pf']                                 = $this->request->getPost('pf');
                $data['pf_number']                          = $this->request->getPost('pf_number');
                $data['pf_employee_contribution']           = $this->request->getPost('pf_employee_contribution');
                $data['pf_employer_contribution']           = $this->request->getPost('pf_employer_contribution');
            } else {
                $data['pf']                                 = 'no';
                $data['pf_number']                          = '';
                $data['pf_employee_contribution']           = '';
                $data['pf_employer_contribution']           = '';
            }
            if (!empty($this->request->getPost('esi'))) {
                $data['esi']                                = $this->request->getPost('esi');
                $data['esi_number']                         = $this->request->getPost('esi_number');
                $data['esi_employee_contribution']          = $this->request->getPost('esi_employee_contribution');
                $data['esi_employer_contribution']          = $this->request->getPost('esi_employer_contribution');
            } else {
                $data['esi']                                = 'no';
                $data['esi_number']                         = '';
                $data['esi_employee_contribution']          = '';
                $data['esi_employer_contribution']          = '';
            }
            if (!empty($this->request->getPost('non_compete_loan'))) {
                $data['non_compete_loan']                   = $this->request->getPost('non_compete_loan');
                $data['non_compete_loan_from']              = !empty($this->request->getPost('non_compete_loan_from')) ? $this->request->getPost('non_compete_loan_from') : null;
                $data['non_compete_loan_to']                = !empty($this->request->getPost('non_compete_loan_to')) ? $this->request->getPost('non_compete_loan_to') : null;
                $data['non_compete_loan_amount_per_month']  = $this->request->getPost('non_compete_loan_amount_per_month');
                $data['non_compete_loan_remarks']           = !empty($this->request->getPost('non_compete_loan_remarks')) ? $this->request->getPost('non_compete_loan_remarks') : null;
            } else {
                $data['non_compete_loan']                   = 'no';
                $data['non_compete_loan_from']              = null;
                $data['non_compete_loan_to']                = null;
                $data['non_compete_loan_amount_per_month']  = '';
                $data['non_compete_loan_remarks']           = null;
            }
            if (!empty($this->request->getPost('loyalty_incentive'))) {
                $data['loyalty_incentive']                  = $this->request->getPost('loyalty_incentive');
                $data['loyalty_incentive_from']             = !empty($this->request->getPost('loyalty_incentive_from')) ? $this->request->getPost('loyalty_incentive_from') : null;
                $data['loyalty_incentive_to']               = !empty($this->request->getPost('loyalty_incentive_to')) ? $this->request->getPost('loyalty_incentive_to') : null;
                $data['loyalty_incentive_amount_per_month'] = $this->request->getPost('loyalty_incentive_amount_per_month');
                $data['loyalty_incentive_mature_after_month'] = $this->request->getPost('loyalty_incentive_mature_after_month');
                $data['loyalty_incentive_pay_after_month'] = $this->request->getPost('loyalty_incentive_pay_after_month');
                $data['loyalty_incentive_remarks']          = !empty($this->request->getPost('loyalty_incentive_remarks')) ? $this->request->getPost('loyalty_incentive_remarks') : null;
            } else {
                $data['loyalty_incentive']                  = 'no';
                $data['loyalty_incentive_from']             = null;
                $data['loyalty_incentive_to']               = null;
                $data['loyalty_incentive_amount_per_month'] = '';
                $data['loyalty_incentive_mature_after_month'] = null;
                $data['loyalty_incentive_pay_after_month'] = null;
                $data['loyalty_incentive_remarks']          = null;
            }
            if (!empty($this->request->getPost('lwf'))) {
                $data['lwf']                                = $this->request->getPost('lwf');
                $data['lwf_employee_contribution']          = $this->request->getPost('lwf_employee_contribution');
                $data['lwf_employer_contribution']          = $this->request->getPost('lwf_employer_contribution');
                $data['lwf_deduction_on_every_n_month']     = $this->request->getPost('lwf_deduction_on_every_n_month');
            } else {
                $data['lwf']                                = 'no';
                $data['lwf_employee_contribution']          = '';
                $data['lwf_employer_contribution']          = '';
                $data['lwf_deduction_on_every_n_month']     = '';
            }
            if (!empty($this->request->getPost('tds'))) {
                $data['tds']                                = $this->request->getPost('tds');
                $data['tds_amount_per_month']               = $this->request->getPost('tds_amount_per_month');
                $data['tds_preferred_slab']                 = $this->request->getPost('tds_preferred_slab');
            } else {
                $data['tds']                                = 'no';
                $data['tds_amount_per_month']               = '';
                $data['tds_preferred_slab']                 = '';
            }

            $gross_salary = $data['basic_salary'] + $data['house_rent_allowance'] + $data['conveyance'] + $data['medical_allowance'] + $data['special_allowance'] + $data['fuel_allowance'] + $data['vacation_allowance'] + $data['other_allowance'];
            $data['gross_salary'] = $gross_salary;

            $ctc = $data['gross_salary'];

            $data['gratuity'] = round((($data['basic_salary'] / 26) * 15) * (1 / 12));
            $ctc += $data['gratuity'];

            if ($data['enable_bonus'] == 'yes') {
                $EmployeeModel = new EmployeeModel();
                $employee = $EmployeeModel->find($data['employee_id']);
                if (!empty($employee['min_wages_category'])) {
                    $MinWagesCategoryModel = new MinWagesCategoryModel();
                    $MinWage = $MinWagesCategoryModel->find($employee['min_wages_category']);
                    $MinWageValue = $MinWage['minimum_wages_category_value'];
                    if (!empty($MinWageValue)) {
                        $data['bonus'] = round(($MinWageValue * 8.33) / 100);
                    } else {
                        $data['bonus'] = 0;
                    }
                } else {
                    $data['bonus'] = 0;
                }
            } else {
                $data['bonus'] = 0;
            }
            $ctc += $data['bonus'];

            if ($data['pf'] == 'yes') {
                if ($data['gross_salary'] >= 15000) {
                    $data['pf_employee_contribution'] = ((15000 * 12) / 100);
                    $data['pf_employer_contribution'] = round((15000 * 13) / 100);
                } else {
                    $data['pf_employee_contribution'] = (($data['gross_salary'] * 12) / 100);
                    $data['pf_employer_contribution'] = round(($data['gross_salary'] * 13) / 100);
                }
                $ctc += $data['pf_employer_contribution'];
            } else {
                $data['pf_employee_contribution'] = 0;
                $data['pf_employer_contribution'] = 0;
            }

            if ($data['esi'] == 'yes' && $data['gross_salary'] <= 21000) {
                $data['esi_employee_contribution'] = ($data['gross_salary'] * 0.75) / 100;
                $data['esi_employer_contribution'] = round(($data['gross_salary'] * 3.25) / 100);
                $ctc += $data['esi_employer_contribution'];
            } else {
                $data['esi_employee_contribution'] = 0;
                $data['esi_employer_contribution'] = 0;
            }

            if (($data['lwf'] == 'yes')) {
                $data['lwf_employee_contribution'] = ((($data['gross_salary'] * 0.2) / 100 <= 31) ? ($data['gross_salary'] * 0.2) / 100 : 31);
                $data['lwf_employer_contribution'] = round($data['lwf_employee_contribution'] * 2);
                $ctc += $data['lwf_employer_contribution'];
            } else {
                $data['lwf_employee_contribution'] = 0;
                $data['lwf_employer_contribution'] = 0;
            }

            $ctc += ($data['non_compete_loan'] == 'yes' && $data['non_compete_loan_amount_per_month'] > 0)
                ? $data['non_compete_loan_amount_per_month']
                : 0;

            // $ctc += ($data['loyalty_incentive'] == 'yes' && $data['loyalty_incentive_amount_per_month'] > 0)
            //     ? $data['loyalty_incentive_amount_per_month']
            //     : 0;

            $monthly_el = round($data['basic_salary'] / 30 * 1.25);
            $ctc += $monthly_el;

            $monthly_cl = round($data['gross_salary'] / 30 * 1);
            $ctc += $monthly_cl;

            $data['ctc'] = $ctc;

            $salary_id = $this->request->getPost('salary_id');

            if (!empty($salary_id)) {
                $SalaryModel = new SalaryModel();
                $oldData = $SalaryModel->where('id', $salary_id)->first();
                $oldData['salary_id'] = $oldData['id'];
                $oldData['revised_by'] = $this->session->get('current_user')['employee_id'];
                unset($oldData['id']);
                $SalaryRevisionModel = new SalaryRevisionModel();
                $insertSalaryRevisionQuery = $SalaryRevisionModel->insert($oldData);
                if (!$insertSalaryRevisionQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error Failed to create revision <br> Please contact administrator.' . json_encode($SalaryRevisionModel->error());
                } else {
                    $SalaryModel = new SalaryModel();
                    $SalaryUpdateQuery = $SalaryModel->update($salary_id, $data);
                    if (!$SalaryUpdateQuery) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB:Error Failed to update salary <br> Please contact administrator.';
                    } else {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Salary Updated Successfully';
                    }
                }
            } else {
                /*echo '<pre>';
                    print_r($data);
                    die();*/
                $SalaryModel = new SalaryModel();
                $SalaryInsertQuery = $SalaryModel->insert($data);
                if (!$SalaryInsertQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error Failed to update salary <br> Please contact administrator.' . json_encode($SalaryModel->error());
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Salary Updated Successfully';
                }
            }
        }
        // } else {
        //     $response_array['response_type'] = 'error';
        //     $response_array['response_description'] = 'Sorry, This form only accept Post Method';
        // }
        return $this->response->setJSON($response_array);
    }


    public function updateStipend()
    {
        $response_array = array();
        $rules = [
            'stipend'      => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'This field is required'
                ]
            ],
            'employee_id'  => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'This field is required'
                ]
            ],
        ];
        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data'] = $errors;
        } else {
            $data = [
                'employee_id'  => $this->request->getPost('employee_id'),
                'stipend'      => $this->request->getPost('stipend'),
                'enable_bonus'      => 'no',
            ];

            $salary_id = $this->request->getPost('salary_id');

            if (!empty($salary_id)) {
                $SalaryModel = new SalaryModel();
                $oldData = $SalaryModel->where('id', $salary_id)->first();
                $oldData['salary_id'] = $oldData['id'];
                $oldData['revised_by'] = $this->session->get('current_user')['employee_id'];
                unset($oldData['id']);
                $SalaryRevisionModel = new SalaryRevisionModel();
                $insertSalaryRevisionQuery = $SalaryRevisionModel->insert($oldData);
                if (!$insertSalaryRevisionQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error Failed to create revision <br> Please contact administrator.' . json_encode($SalaryRevisionModel->error());
                } else {
                    $SalaryModel = new SalaryModel();
                    $SalaryUpdateQuery = $SalaryModel->update($salary_id, $data);
                    if (!$SalaryUpdateQuery) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB:Error Failed to update salary <br> Please contact administrator.';
                    } else {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Salary Updated Successfully';
                    }
                }
            } else {
                $SalaryModel = new SalaryModel();
                $SalaryInsertQuery = $SalaryModel->insert($data);
                if (!$SalaryInsertQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error Failed to update salary <br> Please contact administrator.' . json_encode($SalaryModel->error());
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Salary Updated Successfully';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }
}
