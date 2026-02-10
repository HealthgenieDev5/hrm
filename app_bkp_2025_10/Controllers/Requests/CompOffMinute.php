<?php

namespace App\Controllers\Requests;

use App\Controllers\BaseController;
use App\Models\PreFinalSalaryModel;
use App\Models\CompOffMinutesUtilizedModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;

class CompOffMinute extends BaseController
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
        $data = [
            'page_title'            => 'My Comp Off Minutes Utilization',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            // 'employees'             => $employees,
        ];
        // print_r($data);
        return view('User/CompOffUtilizationRequests', $data);
    }

    public function getAllCompOffUtilizationRequests()
    {

        #this is database details
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        $CompOffMinutesUtilizedModel = new CompOffMinutesUtilizedModel();
        $CompOffUtilizationRequests = $CompOffMinutesUtilizedModel
            ->select('comp_off_minutes_utilized.*')
            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as requested_by_name")
            ->join('employees as e', 'e.id = comp_off_minutes_utilized.requested_by', 'left')
            ->where('comp_off_minutes_utilized.employee_id =', $current_employee_id)
            ->orderBy('comp_off_minutes_utilized.date_time', 'DESC')
            ->findAll();

        if (!empty($CompOffUtilizationRequests)) {
            foreach ($CompOffUtilizationRequests as $index => $row) {

                $date_formatted = !empty($row['date']) ? date('d M Y', strtotime($row['date'])) : '';
                $date_ordering = !empty($row['date']) ? strtotime($row['date']) : '0';
                $CompOffUtilizationRequests[$index]['date'] = array('formatted' => $date_formatted, 'ordering' => $date_ordering);

                $date_time_formatted = !empty($row['date_time']) ? date('d M Y h:i A', strtotime($row['date_time'])) : '';
                $date_time_ordering = !empty($row['date_time']) ? strtotime($row['date_time']) : '0';
                $CompOffUtilizationRequests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

                $isCancellable = strtotime($row['date']) < strtotime(first_date_of_last_month()) ? 'no' : 'yes';
                #begin::Salary disbursed or not
                $PreFinalSalaryModel = new PreFinalSalaryModel();
                $PreFinalSalaryModel->select('pre_final_salary.*');
                $PreFinalSalaryModel->where('employee_id =', $this->session->get('current_user')['employee_id']);
                $PreFinalSalaryModel->where('year =', date('Y', strtotime($row['date'])));
                $PreFinalSalaryModel->where('month =', date('m', strtotime($row['date'])));
                $FinalSalary = $PreFinalSalaryModel->first();
                if (!empty($FinalSalary)) {
                    #$CompOffUtilizationRequests[$index]['salary_status'] = $FinalSalary['status'];
                    $isCancellable = in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold']) ? 'yes' : 'no';
                }
                // if( $isCancellable == 'yes' && strtotime(date('Y-m-d')) > strtotime(date('Y-m-04')) && strtotime($row['date']) < strtotime(date('Y-m-01')) ){
                //     $isCancellable = 'no';
                // }
                if ($isCancellable == 'yes' && strtotime($row['date']) < strtotime(date('Y-m-01'))) {
                    $isCancellable = 'no';
                }
                $CompOffUtilizationRequests[$index]['cancellable'] = $isCancellable;

                #end::Salary disbursed or not

            }
        }

        return $this->response->setJSON($CompOffUtilizationRequests);
    }

    public function cancelCompOffUtilizationRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'request_id'  =>  [
                    'rules'         =>  'required|is_not_unique[comp_off_minutes_utilized.id]',
                    'errors'        =>  [
                        'required'  => 'Request ID is required',
                        'is_not_unique' => 'This Request is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('request_id');
        } else {
            $request_id   = $this->request->getVar('request_id');
            $CompOffMinutesUtilizedModel = new CompOffMinutesUtilizedModel();
            $data = ['type' => 'cancelled'];
            $updateQuery = $CompOffMinutesUtilizedModel->update($request_id, $data);
            if (!$updateQuery) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Request Cancelled';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function createCOMPOFFUtilizationRequest()
    {
        $response_array = array();

        $validation = $this->validate(
            [
                'comp_off_minutes_utilization_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Date is required'
                    ]
                ],
                'comp_off_minutes_utilization_minutes'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Minute is required'
                    ]
                ],
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('request_id');
        } else {
            $comp_off_minutes_utilization_date = $this->request->getVar('comp_off_minutes_utilization_date');
            $comp_off_minutes_utilization_minutes = $this->request->getVar('comp_off_minutes_utilization_minutes');

            #check existing
            $CompOffMinutesUtilizedModel = new CompOffMinutesUtilizedModel();
            $existingCount = $CompOffMinutesUtilizedModel
                ->select('count(comp_off_minutes_utilized.id) as existing_request_count')
                ->where('comp_off_minutes_utilized.employee_id =', $this->session->get('current_user')["employee_id"])
                ->where('comp_off_minutes_utilized.date =', $comp_off_minutes_utilization_date)
                ->where('comp_off_minutes_utilized.type =', 'utilized')
                ->first()['existing_request_count'];
            if ($existingCount > 0) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Duplicate Request';
                $this->validator->setError('comp_off_minutes_utilization_date', 'There is a request present for this date');
                $errors = $this->validator->getErrors();
                $response_array['response_data']['validation'] = $errors;
                return $this->response->setJSON($response_array);
                die();
            }

            #begin::Salary disbursed or not
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $PreFinalSalaryModel->select('pre_final_salary.*');
            $PreFinalSalaryModel->where('employee_id =', $this->session->get('current_user')["employee_id"]);
            $PreFinalSalaryModel->where('year =', date('Y', strtotime($comp_off_minutes_utilization_date)));
            $PreFinalSalaryModel->where('month =', date('m', strtotime($comp_off_minutes_utilization_date)));
            $FinalSalary = $PreFinalSalaryModel->first();
            if (!empty($FinalSalary)) {
                $CompOffUtilizationRequests['salary_status'] = $FinalSalary['status'];

                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'There is an error while creating your request';
                $this->validator->setError('comp_off_minutes_utilization_date', 'Salary has been locked for the selected date');
                $errors = $this->validator->getErrors();
                $response_array['response_data']['validation'] = $errors;
                return $this->response->setJSON($response_array);
                die();
            }
            #end::Salary disbursed or not

            $CurrentMonthLeaveBalance = ProcessorHelper::getLeaveBalance($this->session->get('current_user')["employee_id"]);

            $utilizedMinutes = ProcessorHelper::convertToMinutes($comp_off_minutes_utilization_minutes);

            if (!empty($CurrentMonthLeaveBalance)) {
                $availableCompOffMinutes = 0;
                foreach ($CurrentMonthLeaveBalance as $balanceRow) {
                    if ($balanceRow['leave_code'] == 'COMP OFF Minutes') {
                        $availableCompOffHours = $balanceRow['balance'];
                        $availableCompOffMinutes = ProcessorHelper::convertToMinutes($availableCompOffHours);
                    }
                }

                if ($utilizedMinutes > $availableCompOffMinutes) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'There is an error while creating your request';
                    $this->validator->setError('comp_off_minutes_utilization_minutes', 'Balance minutes are not enough');
                    $errors = $this->validator->getErrors();
                    $response_array['response_data']['validation'] = $errors;
                    return $this->response->setJSON($response_array);
                    die();
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'There is an error while creating your request';
                $this->validator->setError('comp_off_minutes_utilization_minutes', 'Balance Minutes not generated');
                $errors = $this->validator->getErrors();
                $response_array['response_data']['validation'] = $errors;
                return $this->response->setJSON($response_array);
                die();
            }

            $CompOffMinutesUtilizedModel = new CompOffMinutesUtilizedModel();
            $data = [
                'employee_id' => $this->session->get('current_user')["employee_id"],
                'minutes' => $utilizedMinutes,
                'date' => $comp_off_minutes_utilization_date,
                'requested_by' => $this->session->get('current_user')["employee_id"]
            ];
            $insertQuery = $CompOffMinutesUtilizedModel->insert($data);
            if (!$insertQuery) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Request Created';
            }
        }
        return $this->response->setJSON($response_array);
    }
}
