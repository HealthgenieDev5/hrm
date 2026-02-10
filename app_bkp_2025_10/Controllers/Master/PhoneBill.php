<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\PreFinalSalaryModel;
use App\Models\PhoneBillMasterModel;
use App\Models\PhoneBillMasterRevisionModel;

class PhoneBill extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }
    public function get($employee_id)
    {
        $PhoneBillMasterModel = new PhoneBillMasterModel();
        $PhoneBillMasterModel
            ->where('employee_id =', $employee_id)
            ->where('status =', 'active')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC');
        $PhoneBillRecords = $PhoneBillMasterModel->findAll();

        $year_month_of_last_month = date('My', strtotime('first day of last month'));
        $year_month_of_current_month = date('My');

        if (!empty($PhoneBillRecords)) {
            foreach ($PhoneBillRecords as $i => $record) {
                $year_month = $record['year'] . '-' . $record['month'] . '-01';
                $PhoneBillRecords[$i]['year_month'] = date('My', strtotime($year_month));

                if ($PhoneBillRecords[$i]['year_month'] == $year_month_of_current_month) {
                    $PhoneBillRecords[$i]['salary_disbursed'] = 'no';
                } elseif ($PhoneBillRecords[$i]['year_month'] == $year_month_of_last_month) {
                    $PreFinalSalaryModel = new PreFinalSalaryModel();
                    $PreFinalSalaryModel->select('pre_final_salary.*');
                    $PreFinalSalaryModel->where('employee_id =', $employee_id);
                    $PreFinalSalaryModel->where('year =', date('Y', strtotime($year_month_of_last_month)));
                    $PreFinalSalaryModel->where('month =', date('m', strtotime($year_month_of_last_month)));
                    $FinalSalary = $PreFinalSalaryModel->first();
                    if (!empty($FinalSalary)) {
                        if (in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
                            $PhoneBillRecords[$i]['salary_disbursed'] = 'no';
                        } else {
                            $PhoneBillRecords[$i]['salary_disbursed'] = 'yes';
                        }
                    } else {
                        $PhoneBillRecords[$i]['salary_disbursed'] = 'no';
                    }
                } else {
                    $PhoneBillRecords[$i]['salary_disbursed'] = 'yes';
                }
            }
        }
        return json_encode(['data' => $PhoneBillRecords]);
    }

    public function add()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'phone_bill_employee_id'  =>  [
                    'rules'         =>  'integer',
                    'errors'        =>  [
                        'integer'  => 'Please provide employee id',
                    ]
                ],
                'phone_bill_salary_month'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Salary month is required',
                    ]
                ],
                'phone_bill_deduction_amount'  =>  [
                    'rules'         =>  'integer',
                    'errors'        =>  [
                        'integer'   => 'Deduction amount must be an integer'
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
            $phone_bill_salary_month = $this->request->getPost('phone_bill_salary_month');
            $year = date('Y', strtotime($phone_bill_salary_month));
            $month = date('m', strtotime($phone_bill_salary_month));
            $phone_bill_employee_id = $this->request->getPost('phone_bill_employee_id');

            #check if salary disbursed
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $PreFinalSalaryModel->select('pre_final_salary.*');
            $PreFinalSalaryModel->where('employee_id =', $phone_bill_employee_id);
            $PreFinalSalaryModel->where('year =', $year);
            $PreFinalSalaryModel->where('month =', $month);
            $FinalSalary = $PreFinalSalaryModel->first();

            if (!empty($FinalSalary) && !in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Salary is locked';
                return $this->response->setJSON($response_array);
            }
            #check if salary disbursed


            $phone_bill_deduction_amount = $this->request->getPost('phone_bill_deduction_amount');
            $PhoneBillMasterModel = new PhoneBillMasterModel();
            $existingPhoneBillrecord = $PhoneBillMasterModel->where('employee_id =', $phone_bill_employee_id)->where('year =', $year)->where('month =', $month)->where('status =', 'active')->findAll();
            if (!empty($existingPhoneBillrecord)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Record for this month already exists';
            } else {
                $values = [
                    'year'  => $year,
                    'month'  => $month,
                    'deduction_amount'  => $phone_bill_deduction_amount,
                    'employee_id'  => $phone_bill_employee_id,
                ];
                $PhoneBillMasterModel = new PhoneBillMasterModel();
                $query = $PhoneBillMasterModel->insert($values);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Phone Bill Record Added Successfully';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function delete()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'phone_bill_record_id'  =>  [
                    'rules'         =>  'required|is_not_unique[phone_bill_master.id]',
                    'errors'        =>  [
                        'required'  => 'Id is required',
                        'is_not_unique' => 'This record does not exist in our database'
                    ]
                ],
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('company_id');
        } else {
            $phone_bill_record_id   = $this->request->getPost('phone_bill_record_id');
            $PhoneBillMasterModel = new PhoneBillMasterModel();
            $oldData = $PhoneBillMasterModel->find($phone_bill_record_id);

            #check if salary disbursed
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $PreFinalSalaryModel->select('pre_final_salary.*');
            $PreFinalSalaryModel->where('employee_id =', $oldData['employee_id']);
            $PreFinalSalaryModel->where('year =', $oldData['year']);
            $PreFinalSalaryModel->where('month =', $oldData['month']);
            $FinalSalary = $PreFinalSalaryModel->first();

            if (!empty($FinalSalary) && !in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Salary is locked';
                return $this->response->setJSON($response_array);
            }
            #check if salary disbursed


            $oldData['phone_bill_record_id'] = $oldData['id'];
            unset($oldData['id']);
            $oldData['revised_by'] = $this->session->get('current_user')['employee_id'];

            $PhoneBillMasterRevisionModel = new PhoneBillMasterRevisionModel();
            $revisionQuery = $PhoneBillMasterRevisionModel->insert($oldData);
            if ($revisionQuery) {
                $data = [
                    'status' => 'inactive',
                ];
                $PhoneBillMasterModel = new PhoneBillMasterModel();
                $query = $PhoneBillMasterModel->update($phone_bill_record_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Phone Bill Records Deleted Successfully';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Revision did not saved, Kindly contact developer' . json_encode($PhoneBillMasterRevisionModel->error());
            }
        }
        return $this->response->setJSON($response_array);
    }
}
