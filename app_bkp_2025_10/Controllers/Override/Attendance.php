<?php

namespace App\Controllers\Override;

use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Models\AttendanceOverrideModel;
use App\Models\AttendanceOverrideRevisionModel;

class Attendance extends BaseController
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

        if (!in_array($current_user['employee_id'], ['40', '52', '93'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $data = [
            'page_title'            => 'Attendance Override',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'    => $this->getAllEmployees(),
        ];
        /*echo '<pre>';
        print_r($data);
        die();*/
        return view('AttendanceOverride/AttendanceOverride', $data);
    }

    public function getAllEmployees()
    {
        $EmployeeModel = new EmployeeModel();
        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
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

    public function overrideAttendance()
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
                'attendance_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a date',
                    ]
                ],
                'attendance'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a attendance',
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
            $attendance_date = $this->request->getPost('attendance_date');
            $attendance = $this->request->getPost('attendance');
            $remarks = $this->request->getPost('remarks');

            $AttendanceOverrideModel = new AttendanceOverrideModel();
            $AttendanceOverrideModel->where('employee_id=', $employee_id);
            $AttendanceOverrideModel->where('attendance_override.attendance_date =', $attendance_date);
            $ExistingEntry = $AttendanceOverrideModel->first();

            if (!empty($ExistingEntry)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Data already exist within selected date range';
                $response_array['response_data'] = $ExistingEntry;
            } else {
                $data = [
                    'employee_id' => $employee_id,
                    'attendance_date' => $attendance_date,
                    'attendance' => $attendance,
                    'remarks' => $remarks,
                ];
                $AttendanceOverrideModel = new AttendanceOverrideModel();
                $attendanceOverrideQuery = $AttendanceOverrideModel->insert($data);
                if (!$attendanceOverrideQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB Error:: Data not inserted';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Attendance Override complete for selected date range';
                }
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getAttendanceOverrideAll()
    {

        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

        $AttendanceOverrideModel = new AttendanceOverrideModel();
        $AttendanceOverrideModel->select('attendance_override.*');
        $AttendanceOverrideModel->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $AttendanceOverrideModel->select('employees.internal_employee_id as internal_employee_id');
        $AttendanceOverrideModel->select('departments.department_name as department_name');
        $AttendanceOverrideModel->select('companies.company_short_name as company_short_name');
        $AttendanceOverrideModel->join('employees', 'employees.id = attendance_override.employee_id', 'left');
        $AttendanceOverrideModel->join('companies as companies', 'companies.id = employees.company_id', 'left');
        $AttendanceOverrideModel->join('departments as departments', 'departments.id = employees.department_id', 'left');

        $AttendanceOverrideModel->groupStart();
        $AttendanceOverrideModel->where('employees.date_of_leaving is null');
        $AttendanceOverrideModel->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')");
        $AttendanceOverrideModel->groupEnd();

        $AttendanceOverrideModel->orderBy('employees.id', 'ASC');

        $allAttendanceOverrideEntries = $AttendanceOverrideModel->findAll();

        $data = [
            'page_title'            => 'All Attendance Override',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'allAttendanceOverrideEntries' => $allAttendanceOverrideEntries,
        ];

        return view('AttendanceOverride/AttendanceOverrideAll', $data);
    }

    public function existingAttendanceOverrides()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        $employee_id     = isset($params['employee_id']) ? $params['employee_id'] : "";
        $AttendanceOverrideModel = new AttendanceOverrideModel();
        $AttendanceOverrideModel->select('attendance_override.*');
        $AttendanceOverrideModel->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $AttendanceOverrideModel->select('employees.internal_employee_id as internal_employee_id');
        $AttendanceOverrideModel->select('departments.department_name as department_name');
        $AttendanceOverrideModel->select('companies.company_short_name as company_short_name');
        $AttendanceOverrideModel->join('employees', 'employees.id = attendance_override.employee_id', 'left');
        $AttendanceOverrideModel->join('companies as companies', 'companies.id = employees.company_id', 'left');
        $AttendanceOverrideModel->join('departments as departments', 'departments.id = employees.department_id', 'left');

        $AttendanceOverrideModel->where('attendance_override.employee_id =', $employee_id);

        $allAttendanceOverrideEntries = $AttendanceOverrideModel->findAll();

        if (!empty($allAttendanceOverrideEntries)) {
            foreach ($allAttendanceOverrideEntries as $i => $d) {
                $allAttendanceOverrideEntries[$i]['attendance_date'] = date('d M, Y', strtotime($d['attendance_date']));
            }
        }

        echo json_encode($allAttendanceOverrideEntries);
    }

    public function deleteAttendanceOverride()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'override_id'  =>  [
                    'rules'         =>  'required|is_not_unique[attendance_override.id]',
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
            $AttendanceOverrideModel = new AttendanceOverrideModel();
            #save revision
            $old_data = $AttendanceOverrideModel->find($override_id);
            $old_data['override_id'] = $old_data['id'];
            unset($old_data['id']);
            $old_data['revised_by'] = $this->session->get('current_user')['employee_id'];

            $AttendanceOverrideRevisionModel = new AttendanceOverrideRevisionModel();
            $revision_query = $AttendanceOverrideRevisionModel->insert($old_data);
            if (!$revision_query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Revision Error <br> Please contact administrator.';
                $response_array['response_data'] = $old_data;
                $response_array['response_error'] = $AttendanceOverrideRevisionModel->error();
            } else {
                #delete
                $AttendanceOverrideModel = new AttendanceOverrideModel();
                $query = $AttendanceOverrideModel->delete($override_id);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Override Deleted Successfully';
                }
            }
        }
        return $this->response->setJSON($response_array);
    }
}
