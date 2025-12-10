<?php

namespace App\Controllers\Master;

use App\Models\AdvanceSalaryModel;
use App\Controllers\BaseController;
use App\Models\PreFinalSalaryModel;
use App\Models\AdvanceSalaryEmiModel;
use App\Models\AdvanceSalaryRevisionModel;

class Voucher extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }
    public function get($employee_id)
    {
        $VoucherModel = new AdvanceSalaryModel();
        $VoucherModel
            ->where('employee_id =', $employee_id)
            ->where('type =', 'voucher')
            ->orderBy('date(date_time)', 'DESC');
        $VoucherRecords = $VoucherModel->findAll();

        $year_month_of_last_month = date('My', strtotime('first day of last month'));
        $year_month_of_current_month = date('My');

        if (!empty($VoucherRecords)) {
            foreach ($VoucherRecords as $i => $record) {
                $VoucherRecords[$i]['year_month'] = date('My', strtotime($record['date_time']));
                if ($VoucherRecords[$i]['year_month'] == $year_month_of_current_month) {
                    $VoucherRecords[$i]['salary_disbursed'] = 'no';
                } elseif ($VoucherRecords[$i]['year_month'] == $year_month_of_last_month) {
                    $PreFinalSalaryModel = new PreFinalSalaryModel();
                    $PreFinalSalaryModel->select('pre_final_salary.*');
                    $PreFinalSalaryModel->where('employee_id =', $employee_id);
                    $PreFinalSalaryModel->where('year =', date('Y', strtotime($year_month_of_last_month)));
                    $PreFinalSalaryModel->where('month =', date('m', strtotime($year_month_of_last_month)));
                    $FinalSalary = $PreFinalSalaryModel->first();
                    if (!empty($FinalSalary)) {
                        if (in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
                            $VoucherRecords[$i]['salary_disbursed'] = 'no';
                        } else {
                            $VoucherRecords[$i]['salary_disbursed'] = 'yes';
                        }
                    } else {
                        $VoucherRecords[$i]['salary_disbursed'] = 'no';
                    }
                } else {
                    $VoucherRecords[$i]['salary_disbursed'] = 'yes';
                }
            }
        }
        return json_encode(['data' => $VoucherRecords]);
    }

    public function add()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'voucher_employee_id'  =>  [
                    'rules'         =>  'integer',
                    'errors'        =>  [
                        'integer'  => 'Please provide employee id',
                    ]
                ],
                'voucher_salary_month'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Salary month is required',
                    ]
                ],
                'voucher_amount'  =>  [
                    'rules'         =>  'integer',
                    'errors'        =>  [
                        'integer'   => 'Amount must be an integer'
                    ]
                ],
                'voucher_reason'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'integer'   => 'Please select reason'
                    ]
                ],
                'voucher_note'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'integer'   => 'Please enter note'
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
            $voucher_salary_month = $this->request->getPost('voucher_salary_month');

            if (date('Ym', strtotime($voucher_salary_month)) !== date('Ym')) {
                $date_time = date('Y-m-t 18:30:00', strtotime($voucher_salary_month));
            } else {
                $date_time = date('Y-m-d H:i:s');
            }


            $year = date('Y', strtotime($voucher_salary_month));
            $month = date('m', strtotime($voucher_salary_month));
            $voucher_employee_id = $this->request->getPost('voucher_employee_id');

            $VoucherModel = new AdvanceSalaryModel();
            $VoucherModel
                ->where('employee_id =', $voucher_employee_id)
                ->where('type =', 'voucher')
                ->where('year(date_time) =', $year)
                ->where('month(date_time) =', $month)
                ->orderBy('date(date_time)', 'DESC');
            $existingVoucherrecord = $VoucherModel->findAll();

            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $PreFinalSalaryModel->select('pre_final_salary.*');
            $PreFinalSalaryModel->where('employee_id =', $voucher_employee_id);
            $PreFinalSalaryModel->where('year =', $year);
            $PreFinalSalaryModel->where('month =', $month);
            $FinalSalary = $PreFinalSalaryModel->first();

            if (!empty($FinalSalary) && !in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Salary is locked';
            } elseif (!empty($existingVoucherrecord)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Record for this month already exists';
            } else {
                $values = [
                    'amount'            => $this->request->getPost('voucher_amount'),
                    'reason'            => $this->request->getPost('voucher_reason'),
                    'note'              => $this->request->getPost('voucher_note'),
                    'employee_id'       => $voucher_employee_id,
                    'type'              => 'voucher',
                    'date_time'         => $date_time,
                    'disbursed'         => 'yes',
                    'disbursed_date'    => date('Y-m-d', strtotime($date_time)),
                    'disbursed_by'      => $this->session->get('current_user')['employee_id'],
                    'disbursal_remarks' => 'Disbursed already',
                    'deduct_from_month' => date('F Y', strtotime($date_time)),
                    'review_status'     => 'approved',
                    'remarks'           => 'Already approved',
                    'reviewed_by'       => $this->session->get('current_user')['employee_id'],
                    'reviewed_date'     => date('Y-m-d', strtotime($date_time)),
                ];

                $VoucherModel = new AdvanceSalaryModel();
                $query = $VoucherModel->insert($values);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $advance_salary_request_id = $VoucherModel->insertID();
                    $emi_data = [
                        'advance_salary_request_id' => $advance_salary_request_id,
                        'year'              => date('Y', strtotime($date_time)),
                        'month'             => date('m', strtotime($date_time)),
                        'principle_amount'  => $this->request->getPost('voucher_amount'),
                        'emi'               => $this->request->getPost('voucher_amount'),
                    ];
                    $AdvanceSalaryEmiModel = new AdvanceSalaryEmiModel();
                    $AdvanceSalaryEmiModel->insert($emi_data);

                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Voucher Record Added Successfully';
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
                'voucher_record_id'  =>  [
                    'rules'         =>  'required|is_not_unique[advance_salary_requests.id]',
                    'errors'        =>  [
                        'required'  => 'Id is required',
                        'is_not_unique' => 'This record does not exist in our database'
                    ]
                ],
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('voucher_record_id');
        } else {
            $voucher_record_id   = $this->request->getPost('voucher_record_id');
            $VoucherModel = new AdvanceSalaryModel();
            $oldData = $VoucherModel->find($voucher_record_id);

            #check if salary disbursed
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $PreFinalSalaryModel->select('pre_final_salary.*');
            $PreFinalSalaryModel->where('employee_id =', $oldData['employee_id']);
            $PreFinalSalaryModel->where('year =', date('Y', strtotime($oldData['disbursed_date'])));
            $PreFinalSalaryModel->where('month =', date('m', strtotime($oldData['disbursed_date'])));
            $FinalSalary = $PreFinalSalaryModel->first();

            if (!empty($FinalSalary) && !in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Salary is locked';
                return $this->response->setJSON($response_array);
            }
            #check if salary disbursed

            $oldData['advance_salary_request_id'] = $oldData['id'];
            unset($oldData['id']);
            $oldData['revised_by'] = $this->session->get('current_user')['employee_id'];

            $VoucherRevisionModel = new AdvanceSalaryRevisionModel();
            $revisionQuery = $VoucherRevisionModel->insert($oldData);
            if ($revisionQuery) {
                $VoucherModel = new AdvanceSalaryModel();
                $query = $VoucherModel->delete($voucher_record_id);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Voucher Record Deleted Successfully';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Revision did not saved, Kindly contact developer' . json_encode($VoucherRevisionModel->error());
            }
        }
        return $this->response->setJSON($response_array);
    }
}
