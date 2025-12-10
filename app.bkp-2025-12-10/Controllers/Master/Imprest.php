<?php

namespace App\Controllers\Master;

use App\Models\ImprestMasterModel;
use App\Controllers\BaseController;
use App\Models\PreFinalSalaryModel;
use App\Models\ImprestMasterRevisionModel;

class Imprest extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }
    public function get($employee_id)
    {
        $ImprestMasterModel = new ImprestMasterModel();
        $ImprestMasterModel
            ->where('employee_id =', $employee_id)
            ->where('status =', 'active')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC');
        $ImprestRecords = $ImprestMasterModel->findAll();

        $year_month_of_last_month = date('My', strtotime('first day of last month'));
        $year_month_of_current_month = date('My');

        if (!empty($ImprestRecords)) {
            foreach ($ImprestRecords as $i => $record) {
                $year_month = $record['year'] . '-' . $record['month'] . '-01';
                $ImprestRecords[$i]['year_month'] = date('My', strtotime($year_month));

                if ($ImprestRecords[$i]['year_month'] == $year_month_of_current_month) {
                    $ImprestRecords[$i]['salary_disbursed'] = 'no';
                } elseif ($ImprestRecords[$i]['year_month'] == $year_month_of_last_month) {
                    $PreFinalSalaryModel = new PreFinalSalaryModel();
                    $PreFinalSalaryModel->select('pre_final_salary.*');
                    $PreFinalSalaryModel->where('employee_id =', $employee_id);
                    $PreFinalSalaryModel->where('year =', date('Y', strtotime($year_month_of_last_month)));
                    $PreFinalSalaryModel->where('month =', date('m', strtotime($year_month_of_last_month)));
                    $FinalSalary = $PreFinalSalaryModel->first();
                    if (!empty($FinalSalary)) {
                        if (in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
                            $ImprestRecords[$i]['salary_disbursed'] = 'no';
                        } else {
                            $ImprestRecords[$i]['salary_disbursed'] = 'yes';
                        }
                    } else {
                        $ImprestRecords[$i]['salary_disbursed'] = 'no';
                    }
                } else {
                    $ImprestRecords[$i]['salary_disbursed'] = 'yes';
                }
            }
        }
        return json_encode(['data' => $ImprestRecords]);
    }

    public function add()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'imprest_employee_id'  =>  [
                    'rules'         =>  'integer',
                    'errors'        =>  [
                        'integer'  => 'Please provide employee id',
                    ]
                ],
                'imprest_salary_month'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Salary month is required',
                    ]
                ],
                'imprest_deduction_amount'  =>  [
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
            $imprest_salary_month = $this->request->getPost('imprest_salary_month');
            $year = date('Y', strtotime($imprest_salary_month));
            $month = date('m', strtotime($imprest_salary_month));
            $imprest_employee_id = $this->request->getPost('imprest_employee_id');

            #check if salary disbursed
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $PreFinalSalaryModel->select('pre_final_salary.*');
            $PreFinalSalaryModel->where('employee_id =', $imprest_employee_id);
            $PreFinalSalaryModel->where('year =', $year);
            $PreFinalSalaryModel->where('month =', $month);
            $FinalSalary = $PreFinalSalaryModel->first();

            if (!empty($FinalSalary) && !in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Salary is locked';
                return $this->response->setJSON($response_array);
            }
            #check if salary disbursed


            $imprest_deduction_amount = $this->request->getPost('imprest_deduction_amount');
            $ImprestMasterModel = new ImprestMasterModel();
            $existingIMPRESTrecord = $ImprestMasterModel->where('employee_id =', $imprest_employee_id)->where('year =', $year)->where('month =', $month)->where('status =', 'active')->findAll();
            if (!empty($existingIMPRESTrecord)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Record for this month already exists';
            } else {
                $values = [
                    'year'  => $year,
                    'month'  => $month,
                    'deduction_amount'  => $imprest_deduction_amount,
                    'employee_id'  => $imprest_employee_id,
                ];
                $ImprestMasterModel = new ImprestMasterModel();
                $query = $ImprestMasterModel->insert($values);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'IMPREST Record Added Successfully';
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
                'imprest_record_id'  =>  [
                    'rules'         =>  'required|is_not_unique[imprest_master.id]',
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
            $imprest_record_id   = $this->request->getPost('imprest_record_id');
            $ImprestMasterModel = new ImprestMasterModel();
            $oldData = $ImprestMasterModel->find($imprest_record_id);

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


            $oldData['imprest_record_id'] = $oldData['id'];
            unset($oldData['id']);
            $oldData['revised_by'] = $this->session->get('current_user')['employee_id'];

            $ImprestMasterRevisionModel = new ImprestMasterRevisionModel();
            $revisionQuery = $ImprestMasterRevisionModel->insert($oldData);
            if ($revisionQuery) {
                $data = [
                    'status' => 'inactive',
                ];
                $ImprestMasterModel = new ImprestMasterModel();
                $query = $ImprestMasterModel->update($imprest_record_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'IMPREST Records Deleted Successfully';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Revision did not saved, Kindly contact developer' . json_encode($ImprestMasterRevisionModel->error());
            }
        }
        return $this->response->setJSON($response_array);
    }
}
