<?php

namespace App\Controllers\Master\Employee;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\DesignationModel;
use App\Controllers\BaseController;
use App\Models\EmployeeRevisionModel;


class All extends BaseController
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

    public function index()
    {

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $current_user = $this->session->get('current_user');
        $EmployeeModel = new EmployeeModel();
        $DesignationModel = new DesignationModel();
        $CustomModel = new CustomModel();
        $designations = $DesignationModel->findAll();
        $desk_locations = $CustomModel->CustomQuery("select distinct desk_location from employees")->getResultArray();

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $data = [
            'page_title'            => 'Employee Master',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'designations'          => $designations,
            'desk_locations'        => $desk_locations,
            'Companies'             => $Companies,
        ];
        return view('Master/EmployeeMaster', $data);
    }

    public function getAllEmployees()
    {

        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $EmployeeModel = new EmployeeModel();
        $CustomModel = new CustomModel();
        $sql =  "select 
                emp.*, 
                shift.shift_code as shift_code, 
                shift.shift_name as shift_name, 
                concat(reporting.first_name, ' ', reporting.last_name) as reporting_manager_name, 
                concat(emp.first_name, ' ', emp.last_name) as employee_name, 
                ifnull(emp.attachment, '') as attachment, 
                dep.department_name as department_name, 
                des.designation_name as designation_name, 
                comp.company_short_name as company_short_name, 
                minimum_wages_categories.minimum_wages_category_name as minimum_wages_category_name, 
                minimum_wages_categories.minimum_wages_category_state as minimum_wages_category_state, 
                minimum_wages_categories.minimum_wages_category_value as minimum_wages_category_value 
                from employees emp 
                left join departments dep on dep.id = emp.department_id 
                left join designations des on des.id = emp.designation_id 
                left join companies comp on comp.id = emp.company_id 
                left join employees reporting on reporting.id = emp.reporting_manager_id
                left join shifts shift on shift.id = emp.shift_id
                left join minimum_wages_categories minimum_wages_categories on minimum_wages_categories.id = emp.min_wages_category
                ";
        /*echo '<pre>'.$sql;
            die();*/
        /* $count_sql = "
                select count(emp.id) as count 
                from employees emp 
                left join departments dep 
                on dep.id = emp.department_id 
                left join designations des 
                on des.id = emp.designation_id 
                left join companies comp 
                on comp.id = emp.company_id
                left join employees reporting 
                on reporting.id = emp.reporting_manager_id
                ";*/
        $condition = " where emp.id is not null";

        $company_id = isset($params['company_id']) ? $params['company_id'] : [""];
        if (isset($company_id) && !empty($company_id) && !in_array('all_companies', $company_id) && !in_array('', $company_id)) {
            $company_id_imploded = "'" . implode("', '", $company_id) . "'";
            $condition .= " and emp.company_id in (" . $company_id_imploded . ") ";
        } else {
            $condition .= " ";
        }
        $department_id = isset($params['department_id']) ? $params['department_id'] : [""];
        if (isset($department_id) && !empty($department_id) && !in_array('all_departments', $department_id) && !in_array('', $department_id) && !in_array(' ', $department_id)) {
            $department_id_imploded = "'" . implode("', '", $department_id) . "'";
            $condition .= " and emp.department_id in (" . $department_id_imploded . ") ";
        } else {
            $condition .= " ";
        }

        $ordering = " order by emp.first_name ASC ";

        $sql .= $condition . $ordering;
        // $count_sql .= $condition.$ordering;
        // $data_count = $CustomModel->CustomQuery($count_sql)->getResultArray();
        // if( isset($params['page']) && !empty($params['page']) ){
        //     $pageno = $params['page'];
        // }else{
        //     $pageno = 1;
        // }
        // $per_page = 10;
        // $offset = ($pageno-1) * $per_page;
        // $sql .= " LIMIT ".$offset.", ".$per_page;

        $data = $CustomModel->CustomQuery($sql)->getResultArray();


        foreach ($data as $index => $dataRow) {

            $data[$index]['attachment'] = !empty($dataRow['attachment']) ? $dataRow['attachment'] : '';

            #send first cell value as empty, the datatable will generate checkboxes itselt
            $data[$index]['checkbox'] = '';
            #send last cell value as button group like this
            $data[$index]['actions'] = '';
            $data[$index]['notice_period'] = !empty($dataRow['notice_period']) ? $dataRow['notice_period'] . ' Days' : '-';

            $joining_date_formatted = !empty($dataRow['joining_date']) ? date('d M Y', strtotime($dataRow['joining_date'])) : '-';
            $joining_date_ordering = !empty($dataRow['joining_date']) ? strtotime($dataRow['joining_date']) : '0';
            $data[$index]['joining_date'] = array('formatted' => $joining_date_formatted, 'ordering' => $joining_date_ordering);

            $data[$index]['date_of_leaving'] = !empty($dataRow['date_of_leaving']) ? date('d M Y', strtotime($dataRow['date_of_leaving'])) : '-';

            $date_of_leaving_formatted = !empty($dataRow['date_of_leaving']) ? date('d M Y', strtotime($dataRow['date_of_leaving'])) : '-';
            $date_of_leaving_ordering = !empty($dataRow['date_of_leaving']) ? strtotime($dataRow['date_of_leaving']) : '0';
            $data[$index]['date_of_leaving'] = array('formatted' => $date_of_leaving_formatted, 'ordering' => $date_of_leaving_ordering);
        }

        /*if( $this->session->get('current_user')['employee_id'] == 40 ){
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            die();
        }*/


        // sleep(30);
        $return_data =  [
            'data' => $data,
            // 'data_count' => $data_count[0]['count'],
            // 'pageno' => $pageno,
            // 'per_page' => $per_page,
        ];

        echo json_encode($return_data);
    }


    public function getAllEmployees__backup()
    {
        #this is database details
        $dbDetails = array(
            "host" => $this->db->hostname,
            "user" => $this->db->username,
            "pass" => $this->db->password,
            "db" => $this->db->database,
        );

        $table = "employees";

        #primary key
        $primaryKey = "id";
        $columns = array(
            array("db" => "id", "dt" => 0),
            array("db" => "department_id", "dt" => 1),
            array("db" => "company_id", "dt" => 2),
            array("db" => "reporting_manager_id", "dt" => 3),
            array("db" => "designation_id", "dt" => 4),
            array("db" => "internal_employee_id", "dt" => 5),
            array("db" => "joining_date", "dt" => 6),
            array("db" => "first_name", "dt" => 7),
            array("db" => "last_name", "dt" => 8),
            array("db" => "highest_qualification", "dt" => 9),
            array("db" => "total_experience", "dt" => 10),
            array("db" => "permanent_address", "dt" => 11),
            array("db" => "permanent_city", "dt" => 12),
            array("db" => "permanent_state", "dt" => 13),
            array("db" => "permanent_pincode", "dt" => 14),
            array("db" => "present_address", "dt" => 15),
            array("db" => "present_city", "dt" => 16),
            array("db" => "present_state", "dt" => 17),
            array("db" => "present_pincode", "dt" => 18),
            array("db" => "personal_email", "dt" => 19),
            array("db" => "work_email", "dt" => 20),
            array("db" => "personal_mobile", "dt" => 21),
            array("db" => "work_mobile", "dt" => 22),
            array("db" => "work_phone_extension_number", "dt" => 23),
            array("db" => "work_phone_cug_number", "dt" => 24),
            array("db" => "desk_location", "dt" => 25),
            array("db" => "date_time", "dt" => 26),
            array(
                "db" => "id",
                "dt" => 27,
                "formatter" => function ($d, $row) {
                    return '<div class="d-flex justify-content-center">
                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-employee" data-id="' . $row['id'] . '">
                                    <span class="svg-icon svg-icon-3">
                                        <i class="fa fa-pencil-alt" aria-hidden="true" ></i>
                                    </span>
                                </a>
                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-employee" data-id="' . $row['id'] . '">
                                    <span class="svg-icon svg-icon-3">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </a>
                            </div>';
                },
            ),
            array(
                "db" => "id",
                "dt" => 28,
                "formatter" => function ($d, $row) {
                    return $row['first_name'] . ' ' . $row['last_name'];
                },
            ),
        );

        echo json_encode(
            \SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }


    public function addEmployee()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'employee_name'  =>  [
                    'rules'         =>  'required|is_unique[employees.employee_name]',
                    'errors'        =>  [
                        'required'  => 'Employee Name is required',
                        'is_unique' => 'This Employee is already registered'
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
            $values = [
                'employee_name'  => $this->request->getPost('employee_name'),
            ];
            // return $this->response->setJSON($values);
            $EmployeeModel = new EmployeeModel();
            $query = $EmployeeModel->insert($values);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Employee Added Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function deleteEmployee()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'employee_id'  =>  [
                    'rules'         =>  'required|is_not_unique[employees.id]',
                    'errors'        =>  [
                        'required'  => 'Employee ID is required',
                        'is_not_unique' => 'This Employee is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('employee_id');
        } else {
            $employee_id   = $this->request->getPost('employee_id');
            $EmployeeModel = new EmployeeModel();
            $query = $EmployeeModel->delete($employee_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                #delete from login
                $sql = "delete from users where employee_id = '" . $employee_id . "'";
                $CustomModel = new CustomModel();
                $query = $CustomModel->CustomQuery($sql);
                if (!$query) {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Employee Deleted Successfully, but login credentials still working, contact developer';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Employee Deleted Successfully';
                }
                #delete from login
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getEmployee()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'employee_id'  =>  [
                    'rules'         =>  'required|is_not_unique[employees.id]',
                    'errors'        =>  [
                        'required'  => 'Employee ID is required',
                        'is_not_unique' => 'This Employee is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('employee_id');
        } else {
            $employee_id   = $this->request->getPost('employee_id');
            $EmployeeModel = new EmployeeModel();
            $query = $EmployeeModel->find($employee_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Employee Found';
                $response_array['response_data']['employee'] = $query;
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function updateEmployee()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'employee_id'  =>  [
                    'rules'         =>  'required|is_not_unique[employees.id]',
                    'errors'        =>  [
                        'required'  => 'Employee id is required',
                        'is_not_unique' => 'This Employee does not exist in our database anymore. Please contact Administrator'
                    ]
                ],
                'employee_name'  =>  [
                    'rules'         =>  'required|is_unique[employees.employee_name,id,{employee_id}]',
                    'errors'        =>  [
                        'required'  => 'Employee Name is required',
                        'is_unique' => 'This Employee already exist in our database.'
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
            $employee_id   = $this->request->getPost('employee_id');
            $data = [
                'employee_name'  => $this->request->getPost('employee_name'),
            ];
            $EmployeeModel = new EmployeeModel();
            $query = $EmployeeModel->update($employee_id, $data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Employee Updated Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function bulkUpdate()
    {
        $data = [
            'page_title'            => 'Employee Bulk Update',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
        ];
        return view('Master/EmployeeBulkUpdate', $data);
    }

    public function bulkUpdateSave()
    {

        /*$EmployeeModel = new EmployeeModel();
        $OldData = $EmployeeModel->find(40);
        echo $OldData['attachment'].'<br>';
        die();*/
        $response_array = array();
        $csv_file = $this->request->getFile('csv_file');
        $csv_data   =   array();
        if ($csv_file->isValid() && !$csv_file->hasMoved()) {
            if ($csv_file->guessExtension() == 'csv') {
                $file = fopen($csv_file, "r");
                $header     =   fgetcsv($file);
                while (($data = fgetcsv($file, ",")) !== FALSE) {
                    $csv_data[] = array_combine($header, $data);
                }

                if (!empty($csv_data)) {
                    $csv_data_response = array();
                    $i = 0;
                    foreach ($csv_data as $csv_data_row) {

                        $i++;
                        $employee_id = $csv_data_row['employee_id'];
                        $EmployeeModel = new EmployeeModel();
                        $OldData = $EmployeeModel->find($employee_id);

                        if (!empty($OldData)) {
                            $attachment = !empty($OldData['attachment']) ? json_decode($OldData['attachment'], true) : array();

                            if (!empty($csv_data_row['pan_number'])) {
                                $attachment['pan']['number'] = $csv_data_row['pan_number'];
                            }
                            if (!empty($csv_data_row['adhar_number'])) {
                                $attachment['adhar']['number'] = trim($csv_data_row['adhar_number']);
                            }
                            if (!empty($csv_data_row['bank_account_number'])) {
                                $attachment['bank_account']['number'] = trim($csv_data_row['bank_account_number']);
                            }
                            if (!empty($csv_data_row['bank_name'])) {
                                $attachment['bank_account']['name'] = trim($csv_data_row['bank_name']);
                            }

                            $OldData['employee_id'] = $OldData['id'];
                            $OldData['revised_by'] = $this->session->get('current_user')['employee_id'];
                            unset($OldData['id']);
                            $EmployeeRevisionModel = new EmployeeRevisionModel();
                            $insertEmployeeRevisionQuery = $EmployeeRevisionModel->insert($OldData);
                            if (!$insertEmployeeRevisionQuery) {
                                $csv_data_response[] = '<div class="alert alert-danger">Error at Line Number ' . $i . ' <br> DB:Error Failed to create revision <br> Please contact administrator' . json_encode($EmployeeRevisionModel->error()) . '</div>';
                            } else {
                                $newData['attachment'] = json_encode($attachment);
                                $EmployeeModel = new EmployeeModel();
                                $updateQuery = $EmployeeModel->update($employee_id, $newData);
                                if (!$updateQuery) {
                                    $csv_data_response[] = '<div class="alert alert-danger">Error at Line Number ' . $i . ' <br> DB:Error Failed to update employee <br> Please contact administrator' . json_encode($EmployeeModel->error()) . '</div>';
                                } else {
                                    $csv_data_response[] = '<div class="alert alert-success">Line Number ' . $i . ' is updated</div>';
                                }
                            }
                        }
                    }
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Employees Data updated';
                    $response_array['csv_data_response'] = $csv_data_response;
                } else {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'Data not found in csv file';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'This is not csv file';
            }
        } else {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'CSV File Not Found';
        }
        return $this->response->setJSON($response_array);
    }


    public function custom()
    {

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $current_user = $this->session->get('current_user');
        $EmployeeModel = new EmployeeModel();
        $DesignationModel = new DesignationModel();
        $CustomModel = new CustomModel();
        $designations = $DesignationModel->findAll();
        $desk_locations = $CustomModel->CustomQuery("select distinct desk_location from employees")->getResultArray();

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $data = [
            'page_title'            => 'Employee Master custom',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            // 'designations'          => $designations,
            // 'desk_locations'        => $desk_locations,
            // 'Companies'             => $Companies,
        ];

        $employees = $EmployeeModel->findAll();
        $employeesFinal = [];
        if (!empty($employees)) {
            foreach ($employees as $employee) {
                $employee_code = $employee['internal_employee_id'];
                $name = trim($employee['first_name'] . " " . $employee['last_name']);
                $family_members = !empty($employee['family_members']) ? json_decode($employee['family_members'], true) : [];
                if (!empty($family_members)) {
                    foreach ($family_members as $family_member) {
                        $member_dob = $family_member['member_dob'];
                        if (!empty($member_dob)) {
                            $today = date_create('today');
                            $birthday = date_create($member_dob);
                            $differenceInMilisecond = $today->getTimestamp() - $birthday->getTimestamp();
                            $year_age = floor($differenceInMilisecond / 31536000);
                            $day_age = floor(($differenceInMilisecond % 31536000) / 86400);
                            $month_age = floor($day_age / 30);
                            $day_age = $day_age % 30;

                            if (is_nan($year_age) || is_nan($month_age) || is_nan($day_age)) {
                                $member_age = 0;
                            } else {
                                $member_age = $year_age;
                            }
                        } else {
                            $member_age = $family_member['member_age'];
                        }


                        $employeesFinal[] = array(
                            'employee_code' => $employee_code,
                            'name' => $name,
                            'family_member_name' => $family_member['member_name'],
                            'family_member_relation' => $family_member['member_relation'],
                            'family_member_dob' => $family_member['member_dob'],
                            'family_member_age' => $member_age
                        );
                    }
                } else {
                    $employeesFinal[] = array(
                        'employee_code' => $employee_code,
                        'name' => $name,
                        'family_member_name' => '-',
                        'family_member_relation' => '-',
                        'family_member_dob' => '-',
                        'family_member_age' => '-'
                    );
                }
            }
        }

        // echo '<pre>';
        // print_r($employeesFinal);
        // echo '</pre>';
        // die();

?>
        <table>
            <tr>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Family Member Name</th>
                <th>Family Member Relation</th>
                <th>Family Member DOB</th>
                <th>Family Member Age</th>
            </tr>
            <?php
            foreach ($employeesFinal as $empData) {
            ?>
                <tr>
                    <td><?= $empData['employee_code']; ?></td>
                    <td><?= $empData['name']; ?></td>
                    <td><?= $empData['family_member_name']; ?></td>
                    <td><?= $empData['family_member_relation']; ?></td>
                    <td><?= $empData['family_member_dob']; ?></td>
                    <td><?= $empData['family_member_age']; ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
<?php
        // return view('Master/EmployeeMaster', $data);
    }
}
