<?php

namespace App\Controllers\Override;

use App\Models\WaveOffModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Models\WaveOffRevisionModel;

class WaveOffMinutes extends BaseController
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

        if (!in_array($current_user['employee_id'], ['40', '52'])) {
            return redirect()->to(base_url('/unauthorised'));
        }
        $current_year = date('Y');

        $data = [
            'page_title'            => 'Wave Off Minutes',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'             => $this->getAllEmployees(),
        ];
        return view('WaveOffMinutes/WaveOffMinutes', $data);
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

    public function updateWaveOffMinutes()
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
                'wave_off_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a Date',
                    ]
                ],
                'wave_off_minutes'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please enter minutes to wave off',
                    ]
                ],
                'wave_off_remarks'  =>  [
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
            $wave_off_date = $this->request->getPost('wave_off_date');
            $wave_off_minutes = $this->request->getPost('wave_off_minutes');
            $wave_off_remarks = $this->request->getPost('wave_off_remarks');

            $WaveOffModel = new WaveOffModel();
            $WaveOffModel->where('employee_id=', $employee_id)->where('date', $wave_off_date);
            $existing_wave_off_entry = $WaveOffModel->first();

            if (!empty($existing_wave_off_entry)) {
                $existing_wave_off_entry_id = $existing_wave_off_entry['id'];
                $data = [
                    'employee_id' => $employee_id,
                    'date' => $wave_off_date,
                    'minutes' => $wave_off_minutes,
                    'remarks' => $wave_off_remarks,
                    'added_by' => $this->session->get("current_user")["employee_id"],
                ];
                $WaveOffModel = new WaveOffModel();
                $wave_off_entry_update_query = $WaveOffModel->update($existing_wave_off_entry_id, $data);
                $last_wave_off_id = $existing_wave_off_entry_id;
            } else {
                $data = [
                    'employee_id' => $employee_id,
                    'date' => $wave_off_date,
                    'minutes' => $wave_off_minutes,
                    'remarks' => $wave_off_remarks,
                    'added_by' => $this->session->get("current_user")["employee_id"],
                ];
                $WaveOffModel = new WaveOffModel();
                $wave_off_entry_update_query = $WaveOffModel->insert($data);
                $last_wave_off_id = $WaveOffModel->getInsertID();
            }

            if ($wave_off_entry_update_query) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Wave off minutes entry updated';

                #####begin::send notification to Nazrul#####
                $notification_email = \Config\Services::email();
                $notification_email->setFrom('app.hrm@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                $notification_email->setTo($to_emails);
                $notification_email->setSubject('Wave Off Minutes updated using Overrides by ' . $this->session->get("current_user")["name"]);
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
                                                <strong>Wave Off Minutes updated using Overrides by ' . $this->session->get("current_user")["name"] . '</strong>
                                            </div>
                                            <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                            <div style="padding-bottom: 10px">Employee id : ' . $data["employee_id"] . '</div>
                                            <div style="padding-bottom: 10px">date : ' . $data["date"] . '</div>
                                            <div style="padding-bottom: 10px">minutes : ' . $data["minutes"] . '</div>
                                            <div style="padding-bottom: 10px">remarks : ' . $data["remarks"] . '</div>
                                            <div style="padding-bottom: 10px">details can be found in wave_off_minutes at id ' . $last_wave_off_id . '</div>
                                            
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

    public function getAllWaveOffMinutes()
    {
        $WaveOffModel = new WaveOffModel();
        $WaveOffModel
            ->select('wave_off_minutes.*')
            ->select('employees.id as employee_id')
            ->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name")
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->join('employees', 'employees.id=wave_off_minutes.employee_id', 'left')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            ->orderBy('employees.id', 'ASC');
        $allWaveOffMinutesEntries = $WaveOffModel->findAll();

        $data = [
            'page_title'            => 'All Wave Off Minutes',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'allWaveOffMinutesEntries' => $allWaveOffMinutesEntries,
        ];
        return view('WaveOffMinutes/WaveOffMinutesAll', $data);
    }

    public function existingWaveOffMinutes()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        $employee_id     = isset($params['employee_id']) ? $params['employee_id'] : "";

        $WaveOffModel = new WaveOffModel();
        $WaveOffModel
            ->select('wave_off_minutes.*')
            ->select('employees.id as employee_id')
            ->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name")
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->join('employees', 'employees.id=wave_off_minutes.employee_id', 'left')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            ->where('wave_off_minutes.employee_id =', $employee_id)
            ->orderBy('employees.id', 'ASC');
        $allWaveOffMinutesEntries = $WaveOffModel->findAll();

        if (!empty($allWaveOffMinutesEntries)) {
            foreach ($allWaveOffMinutesEntries as $i => $d) {
                $allWaveOffMinutesEntries[$i]['date'] = date('d M, Y', strtotime($d['date']));
            }
        }

        echo json_encode($allWaveOffMinutesEntries);
    }

    public function deleteWaveOffMinutes()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'override_id'  =>  [
                    'rules'         =>  'required|is_not_unique[wave_off_minutes.id]',
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
            $WaveOffModel = new WaveOffModel();
            #save revision
            $old_data = $WaveOffModel->find($override_id);
            $old_data['override_id'] = $old_data['id'];
            unset($old_data['id']);
            $old_data['revised_by'] = $this->session->get('current_user')['employee_id'];

            $WaveOffRevisionModel = new WaveOffRevisionModel();
            $revision_query = $WaveOffRevisionModel->insert($old_data);
            if (!$revision_query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Revision Error <br> Please contact administrator.';
                $response_array['response_data'] = $old_data;
                $response_array['response_error'] = $WaveOffRevisionModel->error();
            } else {
                $lastrevisionID = $WaveOffRevisionModel->getInsertID();
                #delete
                $WaveOffModel = new WaveOffModel();
                $query = $WaveOffModel->delete($override_id);
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
                    $notification_email->setSubject('Wave Off Minutes deleted using Overrides by ' . $this->session->get("current_user")["name"]);
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
                                                    <strong>Wave Off Minutes deleted using Overrides by ' . $this->session->get("current_user")["name"] . '</strong>
                                                </div>
                                                <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                <div style="padding-bottom: 10px">Employee id : ' . $old_data["employee_id"] . '</div>
                                                <div style="padding-bottom: 10px">date : ' . $old_data["date"] . '</div>
                                                <div style="padding-bottom: 10px">minutes : ' . $old_data["minutes"] . '</div>
                                                <div style="padding-bottom: 10px">remarks : ' . $old_data["remarks"] . '</div>
                                                <div style="padding-bottom: 10px">details can be found in revision at id ' . $lastrevisionID . '</div>                                                
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
