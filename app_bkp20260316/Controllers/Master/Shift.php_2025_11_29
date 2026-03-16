<?php

namespace App\Controllers\Master;

use App\Models\ShiftModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\ShiftPerDayModel;
use App\Controllers\BaseController;
use App\Models\ShiftAttendanceRuleModel;

class Shift extends BaseController
{
    public $session;
    public $db;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();

        require_once APPPATH . 'ThirdParty/ssp.class.php';
        $this->db = db_connect();
    }

    public function index($the_shift_id = null)
    {

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $current_user = $this->session->get('current_user');

        $EmployeeModel = new EmployeeModel();
        $CompanyModel = new CompanyModel();

        $data = [
            'page_title'            => 'Shift Master',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'all_companies'         => $CompanyModel->orderBy('company_name ASC')->findAll(),
            'all_employees'         => $EmployeeModel->orderBy('first_name ASC')->findAll(),
            'the_shift_id'          => $the_shift_id,
        ];
        /*echo '<pre>';
        print_r($data);
        die();*/
        return view('Master/ShiftMaster', $data);
    }

    public function getAllShifts()
    {
        $ShiftModel = new ShiftModel();
        $ShiftPerDayModel = new ShiftPerDayModel();
        $EmployeeModel = new EmployeeModel();
        $all_shifts = $ShiftModel->findAll();
        if (!empty($all_shifts)) {
            foreach ($all_shifts as $index => $shift_row) {
                $shift_id = $shift_row['id'];
                $all_shifts[$index]['shift_id'] = $shift_id;
                $all_shifts[$index]['employee_count'] = $EmployeeModel->where('shift_id =', $shift_id)->countAllResults();
                $all_shifts[$index]['date_time'] = !empty($shift_row['date_time']) ? date('d-M-Y h:i A', strtotime($shift_row['date_time'])) : '';
                $all_shifts[$index]['actions'] = '';

                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                foreach ($days as $day) {
                    $day_data = $ShiftPerDayModel
                        ->where('shift_id =', $shift_id)
                        ->where('day =', $day)
                        ->first();
                    $shift_start = (!empty($day_data['shift_start']) && $day_data['shift_start'] !== '00:00:00') ? date('h:i A', strtotime($day_data['shift_start'])) : '';
                    $shift_end = (!empty($day_data['shift_end']) && $day_data['shift_end'] !== '00:00:00') ? date('h:i A', strtotime($day_data['shift_end'])) : '';
                    $shift_of_day = $shift_start . '-' . $shift_end;
                    $all_shifts[$index][$day] = $shift_of_day;
                }
            }
        }

        echo json_encode($all_shifts);
    }

    public function addShift()
    {

        $response_array = array();
        $rules = [
            'shift_name'  =>  [
                'rules'         =>  'required|is_unique[shifts.shift_name]',
                'errors'        =>  [
                    'required'  => 'Shift Name is required',
                    'is_unique' => 'This Shift Name is already exist'
                ]
            ],
            'shift_code'  =>  [
                'rules'         =>  'required|is_unique[shifts.shift_code]',
                'errors'        =>  [
                    'required'  => 'Shift Code is required',
                    'is_unique' => 'This Shift Code is already exist'
                ]
            ],
            'weekoff'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select weekoff day or days',
                ]
            ],
        ];


        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        // $fields = [ 'shift_start', 'shift_end', 'lunch_start', 'lunch_end', 'break_one_start', 'break_one_end', 'break_two_start', 'break_two_end' ];
        $fields = ['shift_start', 'shift_end'];
        foreach ($days as $day) {
            foreach ($fields as $field) {
                $rules[$field . '_' . $day] = ['rules' => 'required', 'errors' => ['required' => 'Required']];
            }
        }

        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {


            /*$response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Testing new form';
            $response_array['response_data'] = $_REQUEST;
            return $this->response->setJSON($response_array);
            die();*/


            $values = [
                'shift_name'   => $this->request->getPost('shift_name'),
                'shift_code'   => $this->request->getPost('shift_code'),
                'weekoff'      => json_encode($this->request->getPost('weekoff')),
            ];
            $ShiftModel = new ShiftModel();
            $query = $ShiftModel->insert($values);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $shift_id = $ShiftModel->getInsertID();


                #add shift rule
                $attendance_rule = $this->request->getPost('attendance_rule');

                $shift_start_monday     = date_create($this->request->getPost('shift_start_monday'));
                $shift_end_monday       = date_create($this->request->getPost('shift_end_monday'));

                if (strtotime($this->request->getPost('shift_start_monday')) > strtotime($this->request->getPost('shift_end_monday'))) {
                    $Diff_day1                   = $shift_start_monday->diff(date_create("23:59"));
                    $diff_minutes_day1           = (int)$Diff_day1->format('%r%i');
                    $diff_hrs_day1               = (int)$Diff_day1->format('%r%h');
                    $diff_minutes_day1 = $diff_minutes_day1 + $diff_hrs_day1 * 60;

                    $Diff_day2                   = date_create("00:00")->diff($shift_end_monday);
                    $diff_minutes_day2           = (int)$Diff_day2->format('%r%i');
                    $diff_hrs_day2               = (int)$Diff_day2->format('%r%h');
                    $diff_minutes_day2 = $diff_minutes_day2 + $diff_hrs_day2 * 60;

                    $totalDiff = $diff_minutes_day1 + $diff_minutes_day2 + 1;


                    $half_day_for_work_hours = str_pad(floor($totalDiff / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($totalDiff - floor($totalDiff / 60) * 60), 2, '0', STR_PAD_LEFT);
                    // $half_day_for_work_hours= $diff_hrs.":".$diff_minutes;
                } else {
                    $Diff                   = $shift_start_monday->diff($shift_end_monday);
                    $diff_minutes           = (int)$Diff->format('%r%i');
                    $diff_hrs               = (int)$Diff->format('%r%h');
                    $half_day_for_work_hours = $diff_hrs . ":" . $diff_minutes;
                }

                $attendance_rule['half_day_for_work_hours'] = $half_day_for_work_hours;





                $ShiftAttendanceRuleModel = new ShiftAttendanceRuleModel();
                $shift_rule_data = [
                    'shift_id'                          => $shift_id,
                    // 'consider_early_arrival'            => ( $this->request->getPost('consider_early_arrival') == 'yes' ) ? 'yes' : 'no',
                    // 'consider_early_arrival_max_hours'  => ( $this->request->getPost('consider_early_arrival') == 'yes' ) ? $this->request->getPost('consider_early_arrival_max_hours') : null,
                    // 'consider_late_departure'           => ( $this->request->getPost('consider_late_departure') == 'yes' ) ? 'yes' : 'no',
                    // 'consider_late_departure_max_hours' => ( $this->request->getPost('consider_late_departure') == 'yes' ) ? $this->request->getPost('consider_late_departure_max_hours') : null,
                    'late_coming_rule'                  => json_encode($this->request->getPost('late_coming_rule')),
                    // 'attendance_rule'                   => json_encode($this->request->getPost('attendance_rule')),
                    'attendance_rule'                   => json_encode($attendance_rule),
                ];

                $ShiftAttendanceRuleQuery = $ShiftAttendanceRuleModel->insert($shift_rule_data);
                #end add shift rule
                if (!$ShiftAttendanceRuleQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator. yaha pe error h' . $ShiftAttendanceRuleModel->errors();
                } else {
                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

                    $fields = ['shift_start', 'shift_end'];

                    $per_day_shift_updated = true;
                    foreach ($days as $day) {
                        $day_data = array();
                        $day_data['day'] = $day;
                        $day_data['shift_id'] = $shift_id;
                        foreach ($fields as $field) {
                            $field_name = $field . '_' . $day;
                            $day_data[$field] = (!empty($this->request->getPost($field_name)) && $this->request->getPost($field_name) !== '') ? date('H:i', strtotime($this->request->getPost($field_name))) : null;
                        }
                        // $response_array['response_data'][] = $day_data;
                        $ShiftPerDayModel = new ShiftPerDayModel();
                        $existing_day_data = $ShiftPerDayModel
                            ->where('shift_id =', $shift_id)
                            ->where('day =', $day)
                            ->first();
                        if (!empty($existing_day_data)) {
                            $shift_per_day_query = $ShiftPerDayModel->update($existing_day_data['id'], $day_data);
                        } else {
                            $shift_per_day_query = $ShiftPerDayModel->insert($day_data);
                        }
                        if (!$shift_per_day_query) {
                            $per_day_shift_updated = false;
                        }
                    }
                    if ($per_day_shift_updated == false) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                    } else {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Shift Added Successfully';
                    }
                }
            }
        }
        /*print_r($response_array);
        die();*/
        return $this->response->setJSON($response_array);
    }

    public function deleteShift()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'shift_id'  =>  [
                    'rules'         =>  'required|is_not_unique[shifts.id]',
                    'errors'        =>  [
                        'required'  => 'Shift ID is required',
                        'is_not_unique' => 'This Shift is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('shift_id');
        } else {
            $shift_id   = $this->request->getPost('shift_id');
            $ShiftPerDayModel = new ShiftPerDayModel();
            $deleteShiftPerDayQuery = $ShiftPerDayModel->where('shift_id', $shift_id)->delete();
            if (!$deleteShiftPerDayQuery) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $ShiftModel = new ShiftModel();
                $query = $ShiftModel->delete($shift_id);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Shift Deleted Successfully';
                }
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getShift()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'shift_id'  =>  [
                    'rules'         =>  'required|is_not_unique[shifts.id]',
                    'errors'        =>  [
                        'required'  => 'Shift ID is required',
                        'is_not_unique' => 'This Shift is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('shift_id');
        } else {
            $shift_id   = $this->request->getPost('shift_id');
            $ShiftModel = new ShiftModel();
            $ShiftPerDayModel = new ShiftPerDayModel();

            $the_shift = $ShiftModel->where('id =', $shift_id)->first();

            $the_shift_response = array();
            $the_shift_response['shift_id']['value'] = $the_shift['id'];
            $the_shift_response['shift_id']['data_value'] = $the_shift['id'];
            $the_shift_response['shift_code']['value'] = $the_shift['shift_code'];
            $the_shift_response['shift_code']['data_value'] = $the_shift['shift_code'];
            $the_shift_response['shift_name']['value'] = $the_shift['shift_name'];
            $the_shift_response['shift_name']['data_value'] = $the_shift['shift_name'];
            $the_shift_response['weekoff']['value'] = json_decode($the_shift['weekoff'], true);
            $the_shift_response['weekoff']['data_value'] = json_decode($the_shift['weekoff'], true);
            unset($the_shift['id']);

            $ShiftAttendanceRuleModel = new ShiftAttendanceRuleModel();
            $ShiftAttendanceRule = $ShiftAttendanceRuleModel->where('shift_id =', $shift_id)->first();
            if (!empty($ShiftAttendanceRule)) {
                unset($ShiftAttendanceRule['id']);
                unset($ShiftAttendanceRule['shift_id']);
                unset($ShiftAttendanceRule['date_time']);
                foreach ($ShiftAttendanceRule as $rule_name => $rule_value) {
                    if ($rule_name == 'late_coming_rule' || $rule_name == 'attendance_rule') {
                        $the_shift_response[$rule_name] = json_decode($rule_value);
                    } elseif (in_array($rule_name, array('consider_early_arrival_max_hours', 'consider_late_departure_max_hours'))) {
                        $the_shift_response[$rule_name] = (!empty($rule_value) && $rule_value !== '00:00:00') ? date('H:i', strtotime($rule_value)) : '';
                    } else {
                        $the_shift_response[$rule_name] = $rule_value;
                    }
                }
            }

            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days as $day) {
                $day_data = $ShiftPerDayModel
                    ->where('shift_id =', $shift_id)
                    ->where('day =', $day)
                    ->first();
                if (!empty($day_data)) {
                    unset($day_data['id']);
                    unset($day_data['shift_id']);
                    unset($day_data['day']);
                    unset($day_data['date_time']);
                    foreach ($day_data as $field_name => $field_value) {
                        $the_shift_response[$field_name . '_' . $day . '_edit']['value'] = (!empty($field_value) && $field_value != '00:00:00') ? date('h:i A', strtotime($field_value)) : '';
                        $the_shift_response[$field_name . '_' . $day . '_edit']['data_value'] = (!empty($field_value) && $field_value != '00:00:00') ? date('Y-m-d H:i:s', strtotime($field_value)) : '';
                    }
                }
            }
            $response_array['response_type'] = 'success';
            $response_array['response_description'] = 'Shift Found';
            $response_array['response_data']['shift'] = $the_shift_response;
        }
        return $this->response->setJSON($response_array);
    }

    public function updateShift()
    {
        $response_array = array();
        $rules = [
            'shift_id'  =>  [
                'rules'         =>  'required|is_not_unique[shifts.id]',
                'errors'        =>  [
                    'required'  => 'Shift ID is required',
                    'is_not_unique' => 'This Shift is does not exist in our database'
                ]
            ],
            'shift_name'  =>  [
                'rules'         =>  'required|is_unique[shifts.shift_name,id,{shift_id}]',
                'errors'        =>  [
                    'required'  => 'Shift Name is required',
                    'is_unique' => 'This Shift Name is already exist'
                ]
            ],
            'shift_code'  =>  [
                'rules'         =>  'required|is_unique[shifts.shift_code,id,{shift_id}]',
                'errors'        =>  [
                    'required'  => 'Shift Code is required',
                    'is_unique' => 'This Shift Code is already exist'
                ]
            ],
            'weekoff'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select weekoff day or days',
                ]
            ],
        ];

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $fields = ['shift_start', 'shift_end'];
        foreach ($days as $day) {
            foreach ($fields as $field) {
                $rules[$field . '_' . $day . '_edit'] = ['rules' => 'required', 'errors' => ['required' => 'Required']];
            }
        }

        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $shift_id   = $this->request->getPost('shift_id');
            $data = [
                'shift_name'   => $this->request->getPost('shift_name'),
                'shift_code'   => $this->request->getPost('shift_code'),
                'weekoff'      => json_encode($this->request->getPost('weekoff')),
            ];
            $ShiftModel = new ShiftModel();
            $query = $ShiftModel->update($shift_id, $data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                #add shift rule
                $attendance_rule_edit = $this->request->getPost('attendance_rule');

                $shift_start_monday_edit     = date_create($this->request->getPost('shift_start_monday_edit'));
                $shift_end_monday_edit       = date_create($this->request->getPost('shift_end_monday_edit'));

                if (strtotime($this->request->getPost('shift_start_monday_edit')) > strtotime($this->request->getPost('shift_end_monday_edit'))) {
                    $Diff_edit_day1                   = $shift_start_monday_edit->diff(date_create("23:59"));
                    $diff_minutes_edit_day1           = (int)$Diff_edit_day1->format('%r%i');
                    $diff_hrs_edit_day1               = (int)$Diff_edit_day1->format('%r%h');
                    $diff_minutes_edit_day1 = $diff_minutes_edit_day1 + $diff_hrs_edit_day1 * 60;

                    $Diff_edit_day2                   = date_create("00:00")->diff($shift_end_monday_edit);
                    $diff_minutes_edit_day2           = (int)$Diff_edit_day2->format('%r%i');
                    $diff_hrs_edit_day2               = (int)$Diff_edit_day2->format('%r%h');
                    $diff_minutes_edit_day2 = $diff_minutes_edit_day2 + $diff_hrs_edit_day2 * 60;

                    $totalDiff = $diff_minutes_edit_day1 + $diff_minutes_edit_day2 + 1;


                    $half_day_for_work_hours_edit = str_pad(floor($totalDiff / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($totalDiff - floor($totalDiff / 60) * 60), 2, '0', STR_PAD_LEFT);
                    // $half_day_for_work_hours_edit= $diff_hrs_edit.":".$diff_minutes_edit;
                } else {
                    $Diff_edit                   = $shift_start_monday_edit->diff($shift_end_monday_edit);
                    $diff_minutes_edit           = (int)$Diff_edit->format('%r%i');
                    $diff_hrs_edit               = (int)$Diff_edit->format('%r%h');
                    $half_day_for_work_hours_edit = $diff_hrs_edit . ":" . $diff_minutes_edit;
                }




                $attendance_rule_edit['half_day_for_work_hours'] = $half_day_for_work_hours_edit;

                $shift_rule_data = [
                    'late_coming_rule'                  => json_encode($this->request->getPost('late_coming_rule')),
                    'attendance_rule'                   => json_encode($attendance_rule_edit),
                ];
                $ShiftAttendanceRuleModel = new ShiftAttendanceRuleModel();
                $getShiftAttendanceRule = $ShiftAttendanceRuleModel->where('shift_id =', $shift_id)->first();
                if (!empty($getShiftAttendanceRule)) {
                    $ShiftAttendanceRuleQuery = $ShiftAttendanceRuleModel->update($getShiftAttendanceRule['id'], $shift_rule_data);
                } else {
                    $shift_rule_data['shift_id'] = $shift_id;
                    $ShiftAttendanceRuleQuery = $ShiftAttendanceRuleModel->insert($shift_rule_data);
                }


                #end add shift rule

                if (!$ShiftAttendanceRuleQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator. yaha pe error h' . $ShiftAttendanceRuleModel->errors();
                } else {

                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    $fields = ['shift_start', 'shift_end'];

                    $per_day_shift_updated = true;
                    foreach ($days as $day) {
                        $day_data = array();
                        $day_data['day'] = $day;
                        $day_data['shift_id'] = $shift_id;
                        foreach ($fields as $field) {
                            $field_name = $field . '_' . $day;
                            $day_data[$field] = (!empty($this->request->getPost($field_name . '_edit')) && $this->request->getPost($field_name . '_edit') !== '') ? date('H:i', strtotime($this->request->getPost($field_name . '_edit'))) : null;
                        }
                        $ShiftPerDayModel = new ShiftPerDayModel();
                        $existing_day_data = $ShiftPerDayModel
                            ->where('shift_id =', $shift_id)
                            ->where('day =', $day)
                            ->first();
                        if (!empty($existing_day_data)) {
                            $shift_per_day_query = $ShiftPerDayModel->update($existing_day_data['id'], $day_data);
                        } else {
                            $shift_per_day_query = $ShiftPerDayModel->insert($day_data);
                        }
                        if (!$shift_per_day_query) {
                            $per_day_shift_updated = false;
                        }
                    }
                    if ($per_day_shift_updated == false) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                    } else {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Shift Updated Successfully';
                    }
                }
            }
        }
        return $this->response->setJSON($response_array);
    }
}
