<?php

namespace App\Controllers\Cron;

use App\Models\EmployeeModel;
use App\Models\OdRequestsModel;
use App\Models\SentEmailLogModel;
use App\Models\LeaveRequestsModel;
use App\Controllers\BaseController;
use App\Controllers\Attendance\Processor;

class ServerCron extends BaseController
{
    public $session;
    public $uri;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
    }

    public function index()
    {
        save_raw_punching_data('ALL', first_date_of_month(), current_date_of_month());
    }
    public function updateFromLastMonth($EmployeeCode = 'ALL')
    {
        save_raw_punching_data($EmployeeCode, first_date_of_last_month(), current_date_of_month());
    }
    public function updateMyPunchingDataToday($EmployeeCode)
    {
        if (!empty($EmployeeCode)) {
            save_raw_punching_data($EmployeeCode, first_date_of_month(), current_date_of_month());
        }
    }

    public function checkEmployeeAbseanceWithoutApplingLeave()
    {

        // $currentDate = date('Y-m-d');
        // $before2Days = date('Y-m-d', strtotime('-2 days'));

        $currentDate = date('Y-m-d');
        $firstDayOfMonth = date('Y-m-01');
        $before2Days = date('Y-m-d', strtotime('-2 days'));

        // if ($before2Days < $firstDayOfMonth) {
        //     $before2Days = $firstDayOfMonth;
        // }

        if ($before2Days < $firstDayOfMonth) {
            $prevMonth = date('Y-m', strtotime('-1 month', strtotime($currentDate)));
            $before2Days = date('Y-m-d', strtotime($prevMonth . '-' . (date('d', strtotime($firstDayOfMonth)) - 2)));
            if (strtotime($before2Days) < strtotime($prevMonth . '-01')) {
                $before2Days = date('Y-m-d', strtotime($prevMonth . '-01'));
            }
        }

        $excludedEmployee = array(1, 452);

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel->join('employees as reporting_managers', 'reporting_managers.id = employees.reporting_manager_id', 'left');
        $EmployeeModel->where('employees.status', 'active');
        $EmployeeModel->whereNotIn('employees.id', $excludedEmployee);

        $EmployeeModel->where('employees.joining_date <=', $before2Days);
        $EmployeeModel->select('employees.id as id');
        $EmployeeModel->select('employees.first_name as first_name');
        $EmployeeModel->select('employees.last_name as last_name');
        $EmployeeModel->select('employees.internal_employee_id as internal_employee_id');
        $EmployeeModel->select('employees.personal_mobile as personal_mobile');
        $EmployeeModel->select("trim( concat( reporting_managers.first_name, ' ', reporting_managers.last_name ) ) as  reporting_manager_name");

        $employees = $EmployeeModel->findAll();


        $absentEmployees = [];

        foreach ($employees as $employee) {
            $employee_id = $employee['id'];

            $punchingData = Processor::getProcessedPunchingData($employee_id, $before2Days, $currentDate);

            $punchingDataStatus = array_column($punchingData, 'status');
            $statusCount = array_count_values($punchingDataStatus);

            $absentCount = 0;
            $absentCount += $statusCount['A'] ?? 0;
            $absentCount += $statusCount['S/W'] ?? 0;

            $LeaveRequestsModel = new LeaveRequestsModel();
            $LeaveRequestsModel->where('employee_id=', $employee_id);

            $LeaveRequestsModel->whereIn('status', ['pending', 'approved']);
            if (!empty($before2Days) && !empty($currentDate)) {
                $LeaveRequestsModel->groupStart();
                $LeaveRequestsModel->where("leave_requests.from_date between '" . $before2Days . "' and '" . $currentDate . "'");
                $LeaveRequestsModel->orWhere("leave_requests.to_date between '" . $before2Days . "' and '" . $currentDate . "'");
                $LeaveRequestsModel->orWhere("'" . $before2Days . "' between leave_requests.from_date and leave_requests.to_date");
                $LeaveRequestsModel->orWhere("'" . $currentDate . "' between leave_requests.from_date and leave_requests.to_date");

                $LeaveRequestsModel->groupEnd();
            }
            $LeaveRequests = $LeaveRequestsModel->findAll();
            $leaveCount = count($LeaveRequests);

            $OdRequestsModel = new OdRequestsModel();
            $OdRequestsModel->where('employee_id=', $employee_id);
            if (!empty($before2Days) && !empty($currentDate)) {
                $OdRequestsModel->groupStart();
                $OdRequestsModel->where("(date(od_requests.estimated_from_date_time) between '" . $before2Days . "' and '" . $currentDate . "')");
                $OdRequestsModel->orWhere("(date(od_requests.estimated_to_date_time) between '" . $before2Days . "' and '" . $currentDate . "')");
                $OdRequestsModel->orWhere("('" . $before2Days . "' between date(od_requests.estimated_from_date_time) and date(od_requests.estimated_to_date_time))");
                $OdRequestsModel->orWhere("('" . $currentDate . "' between date(od_requests.estimated_from_date_time) and date(od_requests.estimated_to_date_time))");
                $OdRequestsModel->groupEnd();
            }

            $od_requests = $OdRequestsModel->findAll();
            $od_count = count($od_requests);


            if ($absentCount >= 3 && $leaveCount == 0 && $od_count == 0) {

                $absentEmployees[] = [
                    'employee_code' => $employee['internal_employee_id'],
                    'employee_name' => trim($employee['first_name'] . ' ' . $employee['last_name']),
                    'personal_mobile' => $employee['personal_mobile'] ?? '',
                    'department_name' => $punchingData[0]['employee_data']['department_name'],
                    'company_short_name' => $punchingData[0]['employee_data']['company_short_name'],
                    'designation_name' => $punchingData[0]['employee_data']['designation_name'],
                    'reporting_manager_name' => $employee['reporting_manager_name'] ?? '',

                ];
            }
        }

        // return $this->response->setJSON($absentEmployees);
        return $absentEmployees;
    }

    public function sendAbsentWithoutLeaveNotification()
    {
        if (strtotime(date('H:i:s')) < strtotime('12:00:00')) {
            return $this->response->setJSON([
                'response_type' => 'failed',
                'response_description' => 'Emails will trigger after 12:00PM only.'
            ]);
            // dd([
            //     'response_type' => 'failed',
            //     'response_description' => 'Emails will trigger after 12:00PM only.'
            // ]);
        }

        $SentEmailLogModel = new SentEmailLogModel();
        $emailType = 'absent_notification';

        if ($SentEmailLogModel->hasEmailBeenSentToday($emailType)) {
            return $this->response->setJSON([
                'response_type' => 'failed',
                'response_description' => 'Absent notification email has already been sent today.'
            ]);

            // dd([
            //     'response_type' => 'failed',
            //     'response_description' => 'Absent notification email has already been sent today.'
            // ]);
        }

        $absentEmployees = $this->checkEmployeeAbseanceWithoutApplingLeave();
        if (empty($absentEmployees)) {
            return $this->response->setJSON([
                'response_type' => 'failed',
                'response_description' => 'No absent employees to notify.'
            ]);

            // dd([
            //     'response_type' => 'failed',
            //     'response_description' => 'No absent employees to notify.',
            //     'absentEmployees' => $absentEmployees
            // ]);
        }

        $email = \Config\Services::email();
        // $email->setFrom('app.hrm@healthgenie.in', 'HRM');
        // $to_emails = array('developer3@healthgenie.in', 'hrd@gstc.com', 'careers@gstc.com', 'developer2@healthgenie.in');
        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
        $reply_to_email = 'payroll1@gstc.com';
        //$to_emails = array('developer3@healthgenie.in','developer2@healthgenie.in', 'payroll@gstc.com', 'payroll1@gstc.com','hrd@gstc.com', 'careers@gstc.com');
        //$to_emails = array('developer2@healthgenie.in');
        $to_emails = array('payroll1@gstc.com');
        $cc_emails = array('developer3@healthgenie.in', 'developer2@healthgenie.in', 'hrd@gstc.com', 'payroll1@gstc.com', 'careers@gstc.com');
        $email->setTo($to_emails);
        $email->setCc($cc_emails);
        $email->setReplyTo($reply_to_email, 'HRM');



        $email->setSubject('Absent Without Leave Notification');
        $email_message = '
                            <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                                    <tbody>
                                        <tr>
                                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                                <a href="' . base_url('public') . '" rel="noopener" target="_blank">
                                                    <img alt="Logo" src="' . base_url('public') . '/assets/media/logos/logo-healthgenie.png" />
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="center">
                                                <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                                    <!--begin:Email content-->
                                                    <div style="padding-bottom: 30px; font-size: 17px;">
                                                        <strong>Absent Without Leave Notification</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">The following employees have been absent for the past 3 days, possibly without applying for leave. Please check their attendance and leave status.</div>
                                                    <table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Employee</th>
                                                                <th>P-Mob</th>
                                                                <th>Dept</th>
                                                                <th>Company</th>
                                                                <th>R. Mgr</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';

        foreach ($absentEmployees as $employee) {
            $email_message .= '<tr>
                    <td>' . $employee['employee_name'] . ' (' . $employee['employee_code'] . ')' . '</td>
                    <td>' . $employee['personal_mobile'] . '</td>
                    <td>' . $employee['department_name'] . '</td>
                    <td>' . $employee['company_short_name'] . '</td>
                    <td>' . $employee['reporting_manager_name'] . '</td>
                </tr>';
        }


        $email_message .= '</tbody>
                                    </table>
                                    <div style="padding-top: 30px; font-size: 14px;">
                                        <strong>Note:</strong> Please verify data before taking any action and This is an auto-generated email So please do not reply to this email.
                                    </div>
                                    <!--end:Email content-->
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                <p style="margin: 0; padding: 0; font-size: 14px; color: #999999">
                                    © 2025 HealthGenie. All rights reserved.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>';
        //$email_message = str_replace('{absent_employees}', json_encode(implode(',', (array)$absentEmployees['employee_id'])), $email_message);
        if ($SentEmailLogModel->hasEmailBeenSentToday($emailType)) {
            return $this->response->setJSON([
                'response_type' => 'failed',
                'response_description' => 'Absent notification email has already been sent today.'
            ]);

            // dd([
            //     'response_type' => 'failed',
            //     'response_description' => 'Absent notification email has already been sent today.'
            // ]);
        }
        $email->setMessage($email_message);
        $email_send = $email->send();
        if (!$email_send) {
            $response_array = array();
            $response_array['response_type'] = 'failed';
            $response_array['response_description'] = 'Sending email to HR was failed. Please Inform Developer on extension 452';
            return $this->response->setJSON($response_array);
            // dd($response_array);
        }
        $response_array = array();
        $response_array['response_type'] = 'success';
        $response_array['response_description'] = 'Email sent successfully';
        $SentEmailLogModel->logEmailSent($to_emails, $emailType, 'sent');
        return $this->response->setJSON($response_array);
        // dd($response_array);
    }

    public function sendAbsentWithoutLeaveNotificationHeuerOnly()
    {
        if (strtotime(date('H:i:s')) < strtotime('12:00:00')) {
            return $this->response->setJSON([
                'response_type' => 'failed',
                'response_description' => 'Emails will trigger after 12:00PM only.'
            ]);
        }
        $absentEmployees = $this->checkEmployeeAbseanceWithoutApplingLeave();
        if (!$absentEmployees) {
            return $this->response->setJSON([
                'response_type' => 'failed',
                'response_description' => 'Absent notification email has already been sent today.'
            ]);
        }
        $SentEmailLogModel = new SentEmailLogModel();
        $emailType = 'absent_notification_heuer_only';

        if ($SentEmailLogModel->hasEmailBeenSentToday($emailType)) {
            return $this->response->setJSON([
                'response_type' => 'failed',
                'response_description' => 'Absent notification email has already been sent today.'
            ]);
        }
        $email = \Config\Services::email();
        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
        $reply_to_email = 'payroll1@gstc.com';
        $to_emails = array('hrd@heuerinternational.com');
        // $to_emails = array('developer2@healthgenie.in');
        // $cc_emails = array('developer2@healthgenie.in');
        $cc_emails = array('developer2@healthgenie.in');

        $email->setTo($to_emails);
        $email->setCc($cc_emails);
        $email->setReplyTo($reply_to_email, 'HRM');
        $email->setSubject("Absent Without Leave Notification (Heuer)");

        $email_message = '<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                                    <tbody>
                                        <tr>
                                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                                <a href="' . base_url('public') . '" rel="noopener" target="_blank">
                                                    <img alt="Logo" src="' . base_url('public') . '/assets/media/logos/logo-healthgenie.png" />
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="center">
                                                <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                                    <!--begin:Email content-->
                                                    <div style="padding-bottom: 30px; font-size: 17px;">
                                                        <strong>Absent Without Leave Notification</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">The following employees have been absent for the past 3 days, possibly without applying for leave. Please check their attendance and leave status.</div>
                                                    <table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Employee</th>
                                                                <th>P-Mob</th>
                                                                <th>Dept</th>
                                                                <th>R. Mgr</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';
        foreach ($absentEmployees as $employee) {
            if ($employee['company_short_name'] == 'Heuer') {
                $email_message .= '<tr>
                                                                <td>' . $employee['employee_name'] . ' (' . $employee['employee_code'] . ')' . '</td>
                                                                <td>' . $employee['personal_mobile'] . '</td>
                                                                <td>' . $employee['department_name'] . '</td>
                                                                <td>' . $employee['reporting_manager_name'] . '</td>
                                                             </tr>';
            }
        }
        $email_message .= '</tbody>
                                                    </table>
                                                    <div style="padding-top: 30px; font-size: 14px;">
                                                        <strong>Note:</strong> Please verify data before taking any action and This is an auto-generated email So please do not reply to this email.
                                                    </div>
                                                    <!--end:Email content-->
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                                <p style="margin: 0; padding: 0; font-size: 14px; color: #999999">
                                                    © 2025 HealthGenie. All rights reserved.
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>';
        if ($SentEmailLogModel->hasEmailBeenSentToday($emailType)) {
            return $this->response->setJSON([
                'response_type' => 'failed',
                'response_description' => 'Absent notification email has already been sent today.'
            ]);
        }
        $email->setMessage($email_message);
        $email_send = $email->send();
        if (!$email_send) {
            $response_array = array();
            $response_array['response_type'] = 'failed';
            $response_array['response_description'] = 'Sending email to HR was failed. Please Inform Developer on extension 452';
            return $this->response->setJSON($response_array);
        }
        $response_array = array();
        $response_array['response_type'] = 'success';
        $response_array['response_description'] = 'Email sent successfully';
        $SentEmailLogModel->logEmailSent($to_emails, $emailType, 'sent');
        return $this->response->setJSON($response_array);
    }
}
