<?php

namespace App\Controllers\Override;

use App\Models\EmployeeModel;
use App\Models\DeductionModel;
use App\Controllers\BaseController;
use App\Models\DeductionTrashModel;

class DeductionMinute extends BaseController
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

        if (!in_array($current_user['employee_id'], ['40', '52'])) {
            return redirect()->to(base_url('/unauthorised'));
        }
        $current_year = date('Y');

        $data = [
            'page_title'            => 'Deduction Minutes',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'             => $this->getAllEmployees(),
        ];
        return view('DeductionMinutes/DeductionMinutes', $data);
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

    public function updateDeductionMinutes()
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
                'deduction_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a Date',
                    ]
                ],
                'deduction_minutes'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please enter minutes to deduction',
                    ]
                ],
                'deduction_remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please enter Remarks',
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
            $deduction_date = $this->request->getPost('deduction_date');
            $deduction_minutes = $this->request->getPost('deduction_minutes');
            $deduction_remarks = $this->request->getPost('deduction_remarks');

            $DeductionModel = new DeductionModel();
            $DeductionModel
                ->where('employee_id=', $employee_id)
                ->where('date', $deduction_date)
                ->where('current_status !=', 'trashed');
            $existing_deduction_entry = $DeductionModel->first();

            if (!empty($existing_deduction_entry)) {
                /*$existing_deduction_entry_id = $existing_deduction_entry['id'];
                $data = [
                    'employee_id' => $employee_id,
                    'date' => $deduction_date,
                    'minutes' => $deduction_minutes,
                    'remarks' => $deduction_remarks,
                    'added_by' => $this->session->get("current_user")["employee_id"],
                ];
                $DeductionModel = new DeductionModel();
                $deduction_entry_update_query = $DeductionModel->update($existing_deduction_entry_id, $data);
                $last_deduction_id = $existing_deduction_entry_id;*/

                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'A deduction is already present for same date.';

                return $this->response->setJSON($response_array);
                die();
            } else {
                $data = [
                    'employee_id' => $employee_id,
                    'date' => $deduction_date,
                    'minutes' => $deduction_minutes,
                    'initial_remarks' => $deduction_remarks,
                    'deducted_by' => $this->session->get("current_user")["employee_id"],
                ];

                $attachment = $this->request->getFile('attachment');
                if (!$attachment->isValid()) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'Please select an attachment';
                    return $this->response->setJSON($response_array);
                    die();
                }

                if ($attachment->isValid()) {
                    $attachment = $this->request->getFile('attachment');
                    if ($attachment->isValid() && ! $attachment->hasMoved()) {
                        $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                        $uploaded = $attachment->move($upload_folder);
                        if ($uploaded) {
                            $data['attachment'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $attachment->getName());
                        }
                    }
                }


                $DeductionModel = new DeductionModel();
                $deduction_entry_query = $DeductionModel->insert($data);
                $last_deduction_id = $DeductionModel->getInsertID();
            }

            if ($deduction_entry_query) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Request for minutes deduction has been created';

                #####begin::send notification to Nazrul#####
                $notification_email = \Config\Services::email();
                $notification_email->setFrom('app.hrm@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                $notification_email->setTo($to_emails);
                $notification_email->setSubject('Deduction Minutes created using Overrides by ' . $this->session->get("current_user")["name"]);
                $notification_email->setMessage(
                    '<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
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
                                                <strong>Deduction Minutes updated using Overrides by ' . $this->session->get("current_user")["name"] . '</strong>
                                            </div>
                                            <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                            <div style="padding-bottom: 10px">Employee id : ' . $data["employee_id"] . '</div>
                                            <div style="padding-bottom: 10px">date : ' . $data["date"] . '</div>
                                            <div style="padding-bottom: 10px">minutes : ' . $data["minutes"] . '</div>
                                            <div style="padding-bottom: 10px">remarks : ' . $data["initial_remarks"] . '</div>
                                            <div style="padding-bottom: 10px">details can be found in deduction_minutes at id ' . $last_deduction_id . '</div>
                                            
                                            <!--end:Email content-->
                                            <div style="padding-bottom: 10px">Kind regards,
                                            <br>HRM Team.
                                            <tr>
                                                <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                                                    <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                                                    <p>Copyright ©
                                                    <a href="' . base_url('public') . '" rel="noopener" target="_blank">Healthgenie/Gstc</a>.</p>
                                                </td>
                                            </tr></br></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>'
                );
                $notification_email->send();
                #####end::send notification to Nazrul#####

            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB Error:: Please contact developer';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getAllDeductionMinutes()
    {
        $DeductionModel = new DeductionModel();
        $DeductionModel
            ->select('deduction_minutes .*')
            ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
            ->select('e1.internal_employee_id as internal_employee_id')
            ->select('c.company_short_name as company_short_name')
            ->select('d.department_name as department_name')
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as department_hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->select("trim( concat( e5.first_name, ' ', e5.last_name ) ) as deducted_by_name")
            ->join('employees as e1', 'e1.id = deduction_minutes.employee_id', 'left')
            ->join('departments as d', 'd.id = e1.department_id', 'left')
            ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = deduction_minutes.reviewed_by', 'left')
            ->join('employees as e5', 'e5.id = deduction_minutes.deducted_by', 'left')
            ->join('companies as c', 'c.id = e1.company_id', 'left');
        $allDeductionMinutesEntries = $DeductionModel->findAll();

        $data = [
            'page_title'            => 'All Deduction Minutes',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'allDeductionMinutesEntries' => $allDeductionMinutesEntries,
        ];
        return view('DeductionMinutes/DeductionMinutesAll', $data);
    }

    public function existingDeductionMinutes()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        $employee_id     = isset($params['employee_id']) ? $params['employee_id'] : "";

        $DeductionModel = new DeductionModel();
        $DeductionModel
            ->select('deduction_minutes.*')
            ->select('employees.id as employee_id')
            ->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name")
            ->select("trim(concat(e1.first_name, ' ', e1.last_name)) as deducted_by_name")
            ->select("trim(concat(e2.first_name, ' ', e2.last_name)) as reviewed_by_name")
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->join('employees', 'employees.id=deduction_minutes.employee_id', 'left')
            ->join('employees as e1', 'e1.id=deduction_minutes.deducted_by', 'left')
            ->join('employees as e2', 'e2.id=deduction_minutes.reviewed_by', 'left')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            ->where('deduction_minutes.employee_id =', $employee_id)
            ->orderBy('employees.id', 'ASC');
        $allDeductionMinutesEntries = $DeductionModel->findAll();

        if (!empty($allDeductionMinutesEntries)) {
            foreach ($allDeductionMinutesEntries as $i => $d) {
                $allDeductionMinutesEntries[$i]['date'] = date('d M, Y', strtotime($d['date']));
                $allDeductionMinutesEntries[$i]['date_time'] = date('d M, Y h:i A', strtotime($d['date_time']));
                $allDeductionMinutesEntries[$i]['reviewed_date'] = !empty($d['reviewed_date']) ? date('d M, Y h:i A', strtotime($d['reviewed_date'])) : '';
                $allDeductionMinutesEntries[$i]['attachment'] = !empty($d['attachment']) ? base_url('public') . $d['attachment'] : '';
            }
        }

        echo json_encode($allDeductionMinutesEntries);
    }

    public function deleteDeductionMinutes()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'deduction_id'  =>  [
                    'rules'         =>  'required|is_not_unique[deduction_minutes.id]',
                    'errors'        =>  [
                        'required'  => 'Please click on the delete button',
                        'is_not_unique' => 'This data does not exist in our database'
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
            $deduction_id   = $this->request->getPost('deduction_id');
            $DeductionModel = new DeductionModel();
            #save trash
            $old_data = $DeductionModel->find($deduction_id);
            $old_data['deduction_id'] = $old_data['id'];
            unset($old_data['id']);
            $old_data['deleted_by'] = $this->session->get('current_user')['employee_id'];

            $DeductionTrashModel = new DeductionTrashModel();
            $trash_query = $DeductionTrashModel->insert($old_data);
            if (!$trash_query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Trash Error <br> Please contact administrator.';
                $response_array['response_data'] = $old_data;
                $response_array['response_error'] = $DeductionTrashModel->error();
            } else {
                $lasttrashID = $DeductionTrashModel->getInsertID();
                #delete
                $DeductionModel = new DeductionModel();
                $query = $DeductionModel->delete($deduction_id);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Deduction Request Deleted Successfully';
                    #####begin::send notification to Nazrul#####
                    $notification_email = \Config\Services::email();
                    $notification_email->setFrom('app.hrm@healthgenie.in', 'HRM');
                    $to_emails = array('developer3@healthgenie.in');
                    $notification_email->setTo($to_emails);
                    $notification_email->setSubject('Deduction Minutes deleted using Overrides by ' . $this->session->get("current_user")["name"]);
                    $notification_email->setMessage(
                        '<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
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
                                                    <strong>Deduction Minutes deleted using Overrides by ' . $this->session->get("current_user")["name"] . '</strong>
                                                </div>
                                                <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                <div style="padding-bottom: 10px">Employee id : ' . $old_data["employee_id"] . '</div>
                                                <div style="padding-bottom: 10px">date : ' . $old_data["date"] . '</div>
                                                <div style="padding-bottom: 10px">minutes : ' . $old_data["minutes"] . '</div>
                                                <div style="padding-bottom: 10px">remarks : ' . $old_data["remarks"] . '</div>
                                                <div style="padding-bottom: 10px">details can be found in trash at id ' . $lasttrashID . '</div>                                                
                                                <!--end:Email content-->
                                                <div style="padding-bottom: 10px">Kind regards,
                                                <br>HRM Team.
                                                <tr>
                                                    <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                                                        <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                                                        <p>Copyright ©
                                                        <a href="' . base_url('public') . '" rel="noopener" target="_blank">Healthgenie/Gstc</a>.</p>
                                                    </td>
                                                </tr></br></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>'
                    );
                    $notification_email->send();
                    #####end::send notification to Nazrul#####
                }
            }
        }
        return $this->response->setJSON($response_array);
    }
}
