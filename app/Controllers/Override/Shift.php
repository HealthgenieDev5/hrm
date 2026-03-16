<?php

namespace App\Controllers\Override;

use App\Models\ShiftModel;
use App\Models\EmployeeModel;
use App\Models\ShiftOverrideModel;
use App\Controllers\BaseController;
use App\Models\ShiftOverrideRevisionModel;

class Shift extends BaseController
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

        if (!in_array($current_user['employee_id'], ['40', '52', '93', '461', '592'])) {
            return redirect()->to(base_url('/unauthorised'));
        }
        $ShiftModel = new ShiftModel();
        $data = [
            'page_title'            => 'Shift Override',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'             => $this->getAllEmployees(),
            'shifts'                => $ShiftModel->findAll(),
        ];
        /*echo '<pre>';
        print_r($data);
        die();*/
        return view('ShiftOverride/ShiftOverride', $data);
    }

    public function getAllEmployees()
    {
        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            /*->where('employees.status =', 'active')*/
            ->groupStart()
            ->where('employees.date_of_leaving is null')
            ->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')")
            ->groupEnd()
            ->orderBy('employees.first_name', 'ASC');
        $AllEmployees = $EmployeeModel->findAll();

        if (!empty($AllEmployees)) {
            return $AllEmployees;
        } else {
            return null;
        }
    }

    public function overrideShift()
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
                'from_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a date range',
                    ]
                ],
                'to_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a date range',
                    ]
                ],
                'shift_id'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a shift',
                    ]
                ],
                'remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please enter your remarks',
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
            $from_date = $this->request->getPost('from_date');
            $to_date = $this->request->getPost('to_date');
            $shift_id = $this->request->getPost('shift_id');
            $remarks = $this->request->getPost('remarks');

            $ShiftOverrideModel = new ShiftOverrideModel();
            $ShiftOverrideModel->where('employee_id=', $employee_id);
            $ShiftOverrideModel->groupStart();
            $ShiftOverrideModel->where("shift_override.from_date between '" . $from_date . "' and '" . $to_date . "'");
            $ShiftOverrideModel->orWhere("shift_override.to_date between '" . $from_date . "' and '" . $to_date . "'");
            $ShiftOverrideModel->orWhere("'" . $from_date . "' between shift_override.from_date and shift_override.to_date");
            $ShiftOverrideModel->orWhere("'" . $to_date . "' between shift_override.from_date and shift_override.to_date");
            $ShiftOverrideModel->groupEnd();
            $ExistingEntry = $ShiftOverrideModel->first();

            if (!empty($ExistingEntry)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Data already exist within selected date range';
                $response_array['response_data'] = $ExistingEntry;
            } else {
                $data = [
                    'employee_id' => $employee_id,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'shift_id' => $shift_id,
                    'remarks' => $remarks,
                ];
                $ShiftOverrideModel = new ShiftOverrideModel();
                $shiftOverrideQuery = $ShiftOverrideModel->insert($data);
                if (!$shiftOverrideQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB Error:: Data not inserted';
                } else {
                    $last_ShiftOverride_id = $ShiftOverrideModel->getInsertID();
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Shift Override complete for selected date range';
                    #####begin::send notification to Nazrul#####
                    $notification_email = \Config\Services::email();
                    $notification_email->setFrom('app.hrm@healthgenie.in', 'HRM');
                    $to_emails = array('developer3@healthgenie.in');
                    $notification_email->setTo($to_emails);
                    $notification_email->setSubject('Shift changed using Overrides by ' . $this->session->get("current_user")["employee_id"]);
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
                                                    <strong>Shift changed using Overrides by ' . $this->session->get("current_user")["employee_id"] . '</strong>
                                                </div>
                                                <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                <div style="padding-bottom: 10px">Employee id : ' . $data["employee_id"] . '</div>
                                                <div style="padding-bottom: 10px">from_date : ' . $data["from_date"] . '</div>
                                                <div style="padding-bottom: 10px">to_date : ' . $data["to_date"] . '</div>
                                                <div style="padding-bottom: 10px">shift_id : ' . $data["shift_id"] . '</div>
                                                <div style="padding-bottom: 10px">remarks : ' . $data["remarks"] . '</div>
                                                <div style="padding-bottom: 10px">details can be found in ShiftOverrideModel at id ' . $last_ShiftOverride_id . '</div>

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

    public function getShiftOverrideAll()
    {

        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

        $ShiftOverrideModel = new ShiftOverrideModel();
        $ShiftOverrideModel->select('shift_override.*');
        $ShiftOverrideModel->select('shifts.shift_name as shift_name');
        $ShiftOverrideModel->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $ShiftOverrideModel->select('employees.internal_employee_id as internal_employee_id');
        $ShiftOverrideModel->select('departments.department_name as department_name');
        $ShiftOverrideModel->select('companies.company_short_name as company_short_name');
        $ShiftOverrideModel->join('shifts', 'shifts.id = shift_override.shift_id', 'left');
        $ShiftOverrideModel->join('employees', 'employees.id = shift_override.employee_id', 'left');
        $ShiftOverrideModel->join('companies as companies', 'companies.id = employees.company_id', 'left');
        $ShiftOverrideModel->join('departments as departments', 'departments.id = employees.department_id', 'left');

        $ShiftOverrideModel->groupStart();
        $ShiftOverrideModel->where('employees.date_of_leaving is null');
        $ShiftOverrideModel->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')");
        $ShiftOverrideModel->groupEnd();


        $ShiftOverrideModel->orderBy('employees.id', 'ASC');

        $allShiftOverrideEntries = $ShiftOverrideModel->findAll();

        $data = [
            'page_title'            => 'All Shift Override',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'allShiftOverrideEntries' => $allShiftOverrideEntries,
        ];

        return view('ShiftOverride/ShiftOverrideAll', $data);
    }

    public function existingShiftOverrides()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        $employee_id     = isset($params['employee_id']) ? $params['employee_id'] : "";
        $ShiftOverrideModel = new ShiftOverrideModel();
        $ShiftOverrideModel->select('shift_override.*');
        $ShiftOverrideModel->select('shifts.shift_name as shift_name');
        $ShiftOverrideModel->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $ShiftOverrideModel->select('employees.internal_employee_id as internal_employee_id');
        $ShiftOverrideModel->select('departments.department_name as department_name');
        $ShiftOverrideModel->select('companies.company_short_name as company_short_name');
        $ShiftOverrideModel->join('shifts', 'shifts.id = shift_override.shift_id', 'left');
        $ShiftOverrideModel->join('employees', 'employees.id = shift_override.employee_id', 'left');
        $ShiftOverrideModel->join('companies as companies', 'companies.id = employees.company_id', 'left');
        $ShiftOverrideModel->join('departments as departments', 'departments.id = employees.department_id', 'left');

        $ShiftOverrideModel->where('shift_override.employee_id =', $employee_id);

        $allShiftOverrideEntries = $ShiftOverrideModel->findAll();

        if (!empty($allShiftOverrideEntries)) {
            foreach ($allShiftOverrideEntries as $i => $d) {
                $allShiftOverrideEntries[$i]['from_date'] = date('d M, Y', strtotime($d['from_date']));
                $allShiftOverrideEntries[$i]['to_date'] = date('d M, Y', strtotime($d['to_date']));
            }
        }

        echo json_encode($allShiftOverrideEntries);
    }

    public function deleteShiftOverride()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'override_id'  =>  [
                    'rules'         =>  'required|is_not_unique[shift_override.id]',
                    'errors'        =>  [
                        'required'  => 'Please click on the delete button',
                        'is_not_unique' => 'This data does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('company_id');
        } else {
            $override_id   = $this->request->getPost('override_id');
            $ShiftOverrideModel = new ShiftOverrideModel();
            #save revision
            $old_data = $ShiftOverrideModel->find($override_id);
            $old_data['override_id'] = $old_data['id'];
            unset($old_data['id']);
            $old_data['revised_by'] = $this->session->get('current_user')['employee_id'];

            $ShiftOverrideRevisionModel = new ShiftOverrideRevisionModel();
            $revision_query = $ShiftOverrideRevisionModel->insert($old_data);
            if (!$revision_query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Revision Error <br> Please contact administrator.';
                $response_array['response_data'] = $old_data;
                $response_array['response_error'] = $ShiftOverrideRevisionModel->error();
            } else {
                $last_ShiftOverride_id = $ShiftOverrideRevisionModel->getInsertID();
                #delete
                $ShiftOverrideModel = new ShiftOverrideModel();
                $query = $ShiftOverrideModel->delete($override_id);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Override Deleted Successfully';
                    #####begin::send notification to Nazrul#####
                    $notification_email = \Config\Services::email();
                    $notification_email->setFrom('app.hrm@healthgenie.in', 'HRM');
                    $to_emails = array('developer3@healthgenie.in');
                    $notification_email->setTo($to_emails);
                    $notification_email->setSubject('Shift override deleted using Overrides by ' . $this->session->get("current_user")["employee_id"]);
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
                                                    <strong>Shift override deleted using Overrides by ' . $this->session->get("current_user")["employee_id"] . '</strong>
                                                </div>
                                                <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                <div style="padding-bottom: 10px">Employee id : ' . $old_data["employee_id"] . '</div>
                                                <div style="padding-bottom: 10px">from_date : ' . $old_data["from_date"] . '</div>
                                                <div style="padding-bottom: 10px">to_date : ' . $old_data["to_date"] . '</div>
                                                <div style="padding-bottom: 10px">shift_id : ' . $old_data["shift_id"] . '</div>
                                                <div style="padding-bottom: 10px">remarks : ' . $old_data["remarks"] . '</div>
                                                <div style="padding-bottom: 10px">details can be found in ShiftOverrideRevisionModel at id ' . $last_ShiftOverride_id . '</div>

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
