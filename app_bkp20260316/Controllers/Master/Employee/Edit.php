<?php

namespace App\Controllers\Master\Employee;

use App\Models\RolesModel;
use App\Models\ShiftModel;
use App\Models\CustomModel;
use App\Models\SalaryModel;
use App\Models\CompanyModel;
use App\Models\HolidayModel;
use App\Models\EmployeeModel;
use App\Models\AnniversaryModel;
use App\Models\DesignationModel;
use App\Models\WelcomeEmailModel;
use App\Controllers\BaseController;
use App\Controllers\Master\Holiday;
use App\Models\PreFinalSalaryModel;
use App\Models\EmployeeRevisionModel;
use App\Controllers\Master\MinWagesCategory;
use App\Models\ProbationHodResponseModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use App\Models\ProbationNotificationModel;
use App\Models\EmployeeAttachmentModel;
use App\Models\ResignationModel;
use DateTime;
use DateInterval;

class Edit extends BaseController
{
    public $session;
    public $uri;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }

    public function index($id)
    {

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $EmployeeModel = new EmployeeModel();
        $CompanyModel = new CompanyModel();
        $DesignationModel = new DesignationModel();
        $RolesModel = new RolesModel();
        $ShiftModel = new ShiftModel();
        $EmployeeModel
            ->select('employees.*')
            ->select('companies.company_name as company_name')
            ->select('departments.department_name as department_name')
            ->select('designations.designation_name as designation_name')
            // ->select('users.role as role')
            ->select("(select fixed_rh.rh_id from fixed_rh where fixed_rh.employee_id = " . $id . " and fixed_rh.year = '" . date('Y') . "' order by fixed_rh.id asc limit 0,1) as rh_id_1")
            ->select("(select fixed_rh.rh_id from fixed_rh where fixed_rh.employee_id = " . $id . " and fixed_rh.year = '" . date('Y') . "' order by fixed_rh.id asc limit 1,1) as rh_id_2")
            ->select('(select balance from leave_balance where employee_id = ' . $id . ' and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "CL" limit 1) as cl_balance')
            ->select('(select id from leave_balance where employee_id = ' . $id . ' and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "CL" order by id desc limit 1) as cl_balance_id')
            ->select('(select balance from leave_balance where employee_id = ' . $id . ' and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "EL" limit 1) as el_balance')
            ->select('(select id from leave_balance where employee_id = ' . $id . ' and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "EL" order by id desc limit 1) as el_balance_id')
            ->select('(select balance from leave_balance where employee_id = ' . $id . ' and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "RH" limit 1) as rh_balance')
            ->select('(select id from leave_balance where employee_id = ' . $id . ' and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "RH" order by id desc limit 1) as rh_balance_id')
            ->join('companies', 'companies.id=employees.company_id', 'left')
            ->join('departments', 'departments.id=employees.department_id', 'left')
            ->join('designations', 'designations.id=employees.designation_id', 'left')
            // ->join('users', 'users.employee_id=employees.id', 'left')
            ->where('employees.id =', $id);
        $GetEmployeeData = $EmployeeModel->first();

        if (empty($GetEmployeeData)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $HolidayMaster = new Holiday();
        $holidays = $HolidayMaster->getAllHolidaysForEmployeePage();
        $MinWagesCategoryMaster = new MinWagesCategory();
        $MinWagesCategories = $MinWagesCategoryMaster->getAllMinWagesCategory(true);

        $ProbationHodResponseModel = new ProbationHodResponseModel();
        $probation_response = $ProbationHodResponseModel->where('employee_id', $id)->orderBy('id', 'DESC')->first();

        $data = [
            'page_title'            => 'Edit Employee',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'companies'             => $CompanyModel->findAll(),
            'designations'          => $DesignationModel->findAll(),
            'roles'                 => $RolesModel->where('role_name !=', 'hod')->orderBy('role_name ASC')->findAll(),
            'shifts'                => $ShiftModel->findAll(),
            'holidays'              => $holidays,
            'MinWagesCategories'    => $MinWagesCategories,
            'allRH'                 => $this->getAllRh(date('Y')),
            'can_override_rh'       => in_array($this->session->get('current_user')['employee_id'], ['40', '52', '93']) ? true : false,
            'can_override_special_benefits' => in_array($this->session->get('current_user')['employee_id'], ['40']) ? true : false,
            'can_change_password'       => in_array($this->session->get('current_user')['employee_id'], ['40', '52']) ? true : false,
            'can_override_leave_balance' => in_array($this->session->get('current_user')['employee_id'], ['40', '52', '93']) ? true : false,
            'can_override_special_holiday' => in_array($this->session->get('current_user')['employee_id'], ['40', '52', '93']) ? true : false,
            'can_update_salary' => in_array($this->session->get('current_user')['employee_id'], ['40', '52', '93', '223']) ? true : false,
            'can_view_salary' => in_array($this->session->get('current_user')['employee_id'], ['40', '52', '93', '223', '279', '471', '521', '461']) ? true : false,
        ];

        foreach ($GetEmployeeData as $key => $val) {
            $data[$key] = $val;
            if ($key == 'attachment') {
                $data[$key] = json_decode($val, true);
            }
        }


        // if( in_array($this->session->get('current_user')['employee_id'], ['40'] ) ){
        #echo '<pre>';
        #unset($data['page_title']);
        #unset($data['current_controller']);
        #unset($data['current_method']);
        #unset($data['companies']);
        #unset($data['designations']);
        #unset($data['roles']);
        #unset($data['shifts']);
        #unset($data['holidays']);
        #unset($data['MinWagesCategories']);

        $attachment = isset($data['attachment']) && !empty($data['attachment']) ? $data['attachment'] : array();
        if (isset($attachment['pan']['file']) && !empty($attachment['pan']['file'])) {
            $attachment['pan']['file'] = str_replace(WRITEPATH, "/", $attachment['pan']['file']);
        }
        if (isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front'])) {
            $attachment['adhar']['front'] = str_replace(WRITEPATH, "/", $attachment['adhar']['front']);
        }
        if (isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back'])) {
            $attachment['adhar']['back'] = str_replace(WRITEPATH, "/", $attachment['adhar']['back']);
        }
        if (isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file'])) {
            $attachment['bank_account']['file'] = str_replace(WRITEPATH, "/", $attachment['bank_account']['file']);
        }
        if (isset($attachment['passport']['file']) && !empty($attachment['passport']['file'])) {
            $attachment['passport']['file'] = str_replace(WRITEPATH, "/", $attachment['passport']['file']);
        }
        if (isset($attachment['kye_documents']['file']) && !empty($attachment['kye_documents']['file'])) {
            $attachment['kye_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['kye_documents']['file']);
        }
        if (isset($attachment['family_details']['file']) && !empty($attachment['family_details']['file'])) {
            $attachment['family_details']['file'] = str_replace(WRITEPATH, "/", $attachment['family_details']['file']);
        }
        if (isset($attachment['loan_documents']['file']) && !empty($attachment['loan_documents']['file'])) {
            $attachment['loan_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['loan_documents']['file']);
        }
        if (isset($attachment['educational_documents']['file']) && !empty($attachment['educational_documents']['file'])) {
            $attachment['educational_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['educational_documents']['file']);
        }
        if (isset($attachment['relieving_documents']['file']) && !empty($attachment['relieving_documents']['file'])) {
            $attachment['relieving_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['relieving_documents']['file']);
        }
        if (isset($attachment['misc_documents']['file']) && !empty($attachment['misc_documents']['file'])) {
            $attachment['misc_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['misc_documents']['file']);
        }
        if (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) {
            $attachment['avatar']['file'] = str_replace(WRITEPATH, "/", $attachment['avatar']['file']);
        }
        $data['attachment'] = $attachment;
        $data['probation_response'] = isset($probation_response['response']) ? $probation_response['response'] : '';

        // Check if employee has active resignation
        $ResignationModel = new ResignationModel();
        $active_resignation = $ResignationModel->where('employee_id', $id)
            ->where('status', 'active')
            ->first();
        $data['active_resignation'] = $active_resignation;

        $EmployeeAttachmentModel = new EmployeeAttachmentModel();
        $employee_attachments = $EmployeeAttachmentModel->getEmployeeAttachments($id);

        if (!empty($employee_attachments)) {
            foreach ($employee_attachments as &$attachment_item) {
                $attachment_item['file_path'] = str_replace(WRITEPATH, "/", $attachment_item['file_path']);
            }
        }
        $data['employee_attachments'] = $employee_attachments;
        #print_r($data['attachment']);
        #$pan_file = $data['attachment']['pan']['file'];
        #print_r($pan_file);
        #echo "\n";
        #$pan_file_new = str_replace(WRITEPATH, "/", $pan_file);
        #print_r($pan_file_new);
        #echo '</pre>';
        #die();
        // }

        if (isset($data['company_id']) && !empty($data['company_id'])) {
            $company_id = $data['company_id'];
            $departments_sql = "select d.*, c.company_short_name as company_short_name  from departments d left join companies c on c.id = d.company_id where d.company_id = '" . $company_id . "'";
            // $reportingManagers_sql = "select id as id, trim(concat(first_name, ' ', last_name)) as name from employees where company_id = '".$company_id."'";
            $reportingManagers_sql = "select e.id as id, trim(concat(e.first_name, ' ', e.last_name)) as name, d.department_name as department_name, c.company_short_name as company_short_name from employees e left join departments d on d.id = e.department_id left join companies c on c.id = e.company_id";
            $CustomModel = new CustomModel();
            $departments_query = $CustomModel->CustomQuery($departments_sql);
            $reportingManagers_query = $CustomModel->CustomQuery($reportingManagers_sql);
            if (!$departments_query) {
                $data['departments'] = ['' => 'DB:Error, Please contact administrator'];
            } elseif (!$reportingManagers_query) {
                $data['reportingManagers'] = ['' => 'DB:Error, Please contact administrator'];
            } else {
                $departments = $departments_query->getResultArray();
                $reportingManagers = $reportingManagers_query->getResultArray();
                if (!empty($departments)) {
                    $data['departments'] = $departments;
                } else {
                    $data['departments'] = ['' => 'No Department is associated with this company'];
                }
                if (!empty($reportingManagers)) {
                    $data['reportingManagers'] = $reportingManagers;
                } else {
                    $data['reportingManagers'] = ['' => 'No Employee is associated with this company'];
                }
            }
        }

        if (isset($data['family_members']) && !empty($data['family_members'])) {
            $family_members = json_decode($data['family_members'], true);
            foreach ($family_members as $index => $family_member) {
                if (isset($family_member['member_dob']) && !empty($family_member['member_dob'])) {
                    $member_dob = $family_member['member_dob'];
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



                    // $member_age = date_diff(date_create($member_dob), date_create('today'))->y;
                    // $member_age = $member_age > 0 ? $member_age : 0;

                    $family_members[$index]['member_age'] = $member_age;
                }
            }
            $data['family_members'] = json_encode($family_members);
        }

        $EmployeeRevisionModel = new EmployeeRevisionModel();
        $GetEmployeeRevisionData = $EmployeeRevisionModel
            ->select('employees_revision.*')
            ->select('employees_revision.id as revision_id')
            ->select('employees_revision.revision_date_time as revision_date_time')
            ->select("trim(concat(e1.first_name, ' ', e1.last_name)) as revised_by_name")
            ->join('employees as e1', 'e1.id=employees_revision.revised_by', 'left')
            ->where('employee_id=', $id)
            ->orderBy('revision_date_time', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();
        // ->get();
        // ->findAll();

        // echo $EmployeeRevisionModel->getLastQuery()->getQuery();
        // die();

        if (!empty($GetEmployeeRevisionData)) {
            foreach ($GetEmployeeRevisionData as $revisionIndex => $revisionData) {
                if ($revisionIndex == 0) {
                    $GetEmployeeRevisionData[$revisionIndex]['changes'] = $this->findChanges($revisionData, $GetEmployeeData);
                } else {
                    $newRevisionIndex = $revisionIndex;
                    $newRevisionIndex--;
                    $GetEmployeeRevisionData[$revisionIndex]['changes'] = $this->findChanges($revisionData, $GetEmployeeRevisionData[$newRevisionIndex]);
                }
            }
        }

        $data['revisions'] = $GetEmployeeRevisionData;

        ###Employee Salary
        $SalaryModel = new SalaryModel();
        $get_employee_salary = $SalaryModel->where('employee_id =', $id)->first();
        if (!empty($get_employee_salary)) {
            $salary = array();
            foreach ($get_employee_salary as $field_name => $field_value) {
                if (in_array($field_name, array('non_compete_loan_from', 'non_compete_loan_to', 'loyalty_incentive_from', 'loyalty_incentive_to'))) {
                    $salary[$field_name] = (!empty($field_value) && $field_value !== '0000-00-00') ? date('Y-m-d', strtotime($field_value)) : '';
                } else {
                    $salary[$field_name] = $field_value;
                }
            }
            $data['salary'] = $salary;
        } else {
            $data['salary'] = array(
                'employee_id' => $id,
            );
        }

        #begin::Salary disbursed or not
        $year_month_of_last_month = date('My', strtotime('first day of last month'));
        $year_month_of_current_month = date('My');
        $PreFinalSalaryModel = new PreFinalSalaryModel();
        $PreFinalSalaryModel->select('pre_final_salary.*');
        $PreFinalSalaryModel->where('employee_id =', $id);
        $PreFinalSalaryModel->where('year =', date('Y', strtotime($year_month_of_last_month)));
        $PreFinalSalaryModel->where('month =', date('m', strtotime($year_month_of_last_month)));
        $FinalSalary = $PreFinalSalaryModel->first();
        if (!empty($FinalSalary)) {
            $data['last_month_salary_disbursed'] = $FinalSalary['disbursed'];
        } else {
            $data['last_month_salary_disbursed'] = 'no';
        }
        #end::Salary disbursed or not

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // die();

        return view('Master/EmployeeEdit', $data);
    }


    public function update()
    {
        $response_array = array();
        $rules = [
            'employee_id'  =>  [
                'rules'         =>  'required|is_not_unique[employees.id]',
                'errors'        =>  [
                    'required'  => 'Emloyee ID is required',
                    'is_not_unique' => 'This Emloyee ID does not exist in our database Please contact administrator'
                ]
            ],
            'internal_employee_id'  =>  [
                'rules'         =>  'required|is_unique[employees.internal_employee_id,id,{employee_id}]',
                'errors'        =>  [
                    'required'  => 'Internal Emloyee ID is required',
                    'is_unique' => 'This Internal Emloyee ID already exist in our database'
                ]
            ],
            'first_name'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'First Name is required',
                ]
            ],
            'company_id'  =>  [
                'rules'         =>  'required|integer',
                'errors'        =>  [
                    'required'  => 'Please Select a Company',
                    'integer'   => 'ID of this company is not an integer',
                ]
            ],
            'department_id'  =>  [
                'rules'         =>  'required|integer',
                'errors'        =>  [
                    'required'  => 'Please Select a Department',
                    'integer'   => 'ID of this department is not an integer',
                ]
            ],
            'designation_id'  =>  [
                'rules'         =>  'required|integer',
                'errors'        =>  [
                    'required'  => 'Please Select a Designation',
                    'integer'   => 'ID of this designation is not an integer',
                ]
            ],
            'reporting_manager_id'  =>  [
                'rules'         =>  'required|integer',
                'errors'        =>  [
                    'required'  => 'Please Select a Reporting Manager',
                    'integer'   => 'ID of this reporting manager is not an integer',
                ]
            ],
            'shift_id'  =>  [
                'rules'         =>  'required|integer',
                'errors'        =>  [
                    'required'  => 'Please Select a Shift',
                    'integer'   => 'ID of this Shift is not an integer',
                ]
            ],
            'joining_date'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please Select a Joining Date',
                ]
            ],
            'probation'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please Select a probation status',
                ]
            ],
            'machine'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please Select a machine',
                ]
            ],
            'min_wages_category'  =>  [
                'rules'         =>  'required|is_not_unique[minimum_wages_categories.id]',
                'errors'        =>  [
                    'required'  => 'Please Select a category',
                    'is_not_unique' => 'This Category does not exist in our database Please contact administrator'
                ]
            ],

            // 'fathers_name'  =>  [
            //     'rules'         =>  'required',
            //     'errors'        =>  [
            //         'required'  => 'Please enter Father Name'
            //     ]
            // ],

            'date_of_birth'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select Date of Birth'
                ]
            ],
            /*'personal_email'  =>  [
                'rules'         =>  'required|valid_email|is_unique[employees.personal_email,id,{employee_id}]',
                'errors'        =>  [
                    'required'  => 'Personal Email is required',
                    'valid_email'  => 'Personal Email is not valid',
                    'is_unique'  => 'This Personal Email already exist in our database'
                ]
            ],
            'personal_mobile'  =>  [
                'rules'         =>  'required|integer|exact_length[10]|is_unique[employees.personal_mobile,id,{employee_id}]',
                'errors'        =>  [
                    'required'  => 'Please enter 10 digit mobile number',
                    'integer'  => 'Personal Mobile number must be only numbers',
                    'exact_length'  => 'Personal Mobile number must be exactly 10 digits',
                    'is_unique'  => 'This Personal Mobile number already exist in our database'
                ]
            ],*/
            /*'gender'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select your gender',
                ]
            ],
            'marital_status'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select your marital status',
                ]
            ],
            'permanent_city'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Permanent City is required',
                ]
            ],
            'permanent_district'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Permanent District is required',
                ]
            ],
            'permanent_state'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Permanent State is required',
                ]
            ],
            'permanent_pincode'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Permanent Pincode is required',
                ]
            ],
            'permanent_address'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Permanent Address is required',
                ]
            ],
            'present_city'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Present City is required',
                ]
            ],
            'present_district'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Present District is required',
                ]
            ],
            'present_state'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Present State is required',
                ]
            ],
            'present_pincode'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Present Pincode is required',
                ]
            ],
            'present_address'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Present Address is required',
                ]
            ],*/
        ];

        if (isset($_REQUEST['work_email']) && !empty($_REQUEST['work_email'])) {
            $rules['work_email'] =  [
                'rules'         =>  'valid_email|is_unique[employees.work_email,id,{employee_id}]',
                'errors'        =>  [
                    'valid_email'  => 'Work Email is not valid',
                    'is_unique'  => 'This Work Email already exist in our database'
                ]
            ];
        }
        if (isset($_REQUEST['work_mobile']) && !empty($_REQUEST['work_mobile'])) {
            $rules['work_mobile'] =  [
                'rules'         =>  'integer|exact_length[10]|is_unique[employees.work_mobile,id,{employee_id}]',
                'errors'        =>  [
                    'integer'  => 'Work Mobile number must be only numbers',
                    'exact_length'  => 'Work Mobile number must be exactly 10 digits',
                    'is_unique'  => 'This Work Mobile number already exist in our database'
                ]
            ];
        }
        if (isset($_REQUEST['emergency_contact_number']) && !empty($_REQUEST['emergency_contact_number'])) {
            $rules['emergency_contact_number'] =  [
                'rules'         =>  'integer|exact_length[10]',
                'errors'        =>  [
                    'integer'  => 'Emergency Contact must be only numbers',
                    'exact_length'  => 'Emergency Contact Number must be exactly 10 digits',
                ]
            ];
        }

        if (
            isset($_REQUEST['gender']) && $_REQUEST['gender'] == 'female'
            &&
            isset($_REQUEST['marital_status']) && $_REQUEST['marital_status'] == 'married'
        ) {
            $rules['husband_name'] =  [
                'rules'         =>  'required',
                'errors'        =>  ['required'  => 'Please enter Husband Name',]
            ];
        } else {
            $rules['fathers_name'] =  [
                'rules'         =>  'required',
                'errors'        =>  ['required'  => 'Please enter Father Name',]
            ];
        }

        $validation = $this->validate($rules);

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $employee_id = $this->request->getPost('employee_id');

            $newData = [
                'internal_employee_id'          => $this->request->getPost('internal_employee_id'),
                'role'                          => $this->request->getPost('role'),
                'status'                        => $this->request->getPost('status'),
                'first_name'                    => $this->request->getPost('first_name'),
                'last_name'                     => $this->request->getPost('last_name'),
                'company_id'                    => $this->request->getPost('company_id'),
                'department_id'                 => $this->request->getPost('department_id'),
                'designation_id'                => $this->request->getPost('designation_id'),
                'reporting_manager_id'          => $this->request->getPost('reporting_manager_id'),
                'shift_id'                      => $this->request->getPost('shift_id'),
                'joining_date'                  => !empty($this->request->getPost('joining_date')) ? $this->request->getPost('joining_date') : NULL,
                'probation'                     => $this->request->getPost('probation'),
                'notice_period'                 => $this->request->getPost('notice_period'),
                'date_of_leaving'               => !empty($this->request->getPost('date_of_leaving')) ? $this->request->getPost('date_of_leaving') : NULL,
                'machine'                       => !empty($this->request->getPost('machine')) ? $this->request->getPost('machine') : 'del',
                'min_wages_category'            => $this->request->getPost('min_wages_category'),
                'personal_email'                => $this->request->getPost('personal_email'),
                'fathers_name'                  => $this->request->getPost('fathers_name'),
                'husband_name'                  => ($this->request->getPost('gender') == 'female' && $this->request->getPost('marital_status') == 'married') ? $this->request->getPost('husband_name') : '',
                'gender'                        => $this->request->getPost('gender'),
                'marital_status'                => $this->request->getPost('marital_status'),
                'date_of_anniversary'           => !empty($this->request->getPost('date_of_anniversary')) ? $this->request->getPost('date_of_anniversary') : NULL,
                'personal_mobile'               => $this->request->getPost('personal_mobile'),
                'date_of_birth'                 => !empty($this->request->getPost('date_of_birth')) ? $this->request->getPost('date_of_birth') : NULL,
                'permanent_city'                => $this->request->getPost('permanent_city'),
                'permanent_district'            => $this->request->getPost('permanent_district'),
                'permanent_state'               => $this->request->getPost('permanent_state'),
                'permanent_pincode'             => $this->request->getPost('permanent_pincode'),
                'permanent_address'             => $this->request->getPost('permanent_address'),
                'present_city'                  => $this->request->getPost('present_city'),
                'present_district'              => $this->request->getPost('present_district'),
                'present_state'                 => $this->request->getPost('present_state'),
                'present_pincode'               => $this->request->getPost('present_pincode'),
                'present_address'               => $this->request->getPost('present_address'),

                'cl_allowed'                    => !empty($this->request->getPost('cl_allowed')) ? $this->request->getPost('cl_allowed') : 'no',
                'el_allowed'                    => !empty($this->request->getPost('el_allowed')) ? $this->request->getPost('el_allowed') : 'no',
                'co_allowed'                    => !empty($this->request->getPost('co_allowed')) ? $this->request->getPost('co_allowed') : 'no',
                'sl_allowed'                    => !empty($this->request->getPost('sl_allowed')) ? $this->request->getPost('sl_allowed') : 'no',

                'family_members'               => !empty($this->request->getPost('family_members')) ? json_encode($this->request->getPost('family_members')) : NULL,


                'work_email'                    => $this->request->getPost('work_email'),
                'work_mobile'                   => $this->request->getPost('work_mobile'),
                'work_phone_extension_number'   => $this->request->getPost('work_phone_extension_number'),
                'work_phone_cug_number'         => $this->request->getPost('work_phone_cug_number'),
                'desk_location'                 => $this->request->getPost('desk_location'),
                'emergency_contact_number'      => $this->request->getPost('emergency_contact_number'),
                'highest_qualification'         => $this->request->getPost('highest_qualification'),
                'total_experience'              => $this->request->getPost('total_experience'),
                'last_company_name'             => $this->request->getPost('last_company_name'),
                'relevant_experience'           => $this->request->getPost('relevant_experience'),
                'college_university'            => $this->request->getPost('college_university'),
                'hobbies'                       => $this->request->getPost('hobbies'),
            ];

            $EmployeeModel = new EmployeeModel();
            $oldData = $EmployeeModel->where('id', $employee_id)->first();
            // if ($oldData['probation'] == '45 Days Probation' && $newData['probation'] == '90 Days Probation') {
            //     $UpdateProbationData = [
            //         'employee_id' => $employee_id,
            //         'hod_id' => $this->session->get('current_user')['employee_id'],
            //         'response' => 'To be Extended'
            //     ];

            //     $ProbationHodResponseModel = new ProbationHodResponseModel();
            //     $ProbationHodResponseModel->save($UpdateProbationData);
            // }

            $attachment = !empty($oldData['attachment']) ? json_decode($oldData['attachment'], true) : array();

            #avatar
            if (!empty($this->request->getPost('avatar_attachment_remove'))) {
                $attachment['avatar']['file'] = '';
            } else {
                $avatar_attachment = $this->request->getFile('avatar_attachment');
                if ($avatar_attachment->isValid() && ! $avatar_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $avatar_uploaded = $avatar_attachment->move($upload_folder);
                    if ($avatar_uploaded) {
                        $attachment['avatar']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $avatar_attachment->getName());
                    }
                }
            }
            #avatar

            #bank_account
            $attachment['bank_account']['name'] = !empty($this->request->getPost('bank_name')) ? $this->request->getPost('bank_name') : NULL;
            $attachment['bank_account']['number'] = !empty($this->request->getPost('bank_account_number')) ? $this->request->getPost('bank_account_number') : NULL;
            if (!empty($this->request->getPost('bank_account_attachment_remove'))) {
                $attachment['bank_account']['file'] = '';
            } else {
                if (!empty($this->request->getFile('bank_account_attachment'))) {
                    $bank_account_attachment = $this->request->getFile('bank_account_attachment');
                    if ($bank_account_attachment->isValid() && ! $bank_account_attachment->hasMoved()) {
                        $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                        $bank_account_uploaded = $bank_account_attachment->move($upload_folder);
                        if ($bank_account_uploaded) {
                            $attachment['bank_account']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $bank_account_attachment->getName());
                        }
                    }
                }
            }
            #bank_account

            #pan card
            $attachment['pan']['number'] = !empty($this->request->getPost('pan_card_number')) ? $this->request->getPost('pan_card_number') : NULL;
            if (!empty($this->request->getPost('pan_card_attachment_remove'))) {
                $attachment['pan']['file'] = '';
            } else {
                $pan_card_attachment = $this->request->getFile('pan_card_attachment');
                if ($pan_card_attachment->isValid() && ! $pan_card_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $pan_card_uploaded = $pan_card_attachment->move($upload_folder);
                    if ($pan_card_uploaded) {
                        $attachment['pan']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $pan_card_attachment->getName());
                    }
                }
            }
            #pan card

            #uan card
            /*$attachment['uan']['number'] = !empty( $this->request->getPost('uan_number') ) ? $this->request->getPost('uan_number') : NULL;
            if( !empty( $this->request->getPost('uan_attachment_remove') ) ){
                $attachment['uan']['file'] = '';
            }else{
                $uan_attachment = $this->request->getFile('uan_attachment');
                if ($uan_attachment->isValid() && ! $uan_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/'.date('Y').'/'.date('m');
                    $uan_uploaded = $uan_attachment->move($upload_folder);
                    if( $uan_uploaded ){
                        $attachment['uan']['file'] = str_replace(WRITEPATH, "/", $upload_folder.'/'.$uan_attachment->getName());
                    }
                }
            }*/
            #uan card

            #passport
            $attachment['passport']['number'] = !empty($this->request->getPost('passport_number')) ? $this->request->getPost('passport_number') : NULL;
            if (!empty($this->request->getPost('passport_attachment_remove'))) {
                $attachment['passport']['file'] = '';
            } else {
                $passport_attachment = $this->request->getFile('passport_attachment');
                if ($passport_attachment->isValid() && ! $passport_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $passport_uploaded = $passport_attachment->move($upload_folder);
                    if ($passport_uploaded) {
                        $attachment['passport']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $passport_attachment->getName());
                    }
                }
            }
            #passport

            #adhar card
            $attachment['adhar']['number'] = !empty($this->request->getPost('adhar_card_number')) ? $this->request->getPost('adhar_card_number') : NULL;
            if (!empty($this->request->getPost('adhar_card_attachment_front_remove'))) {
                $attachment['adhar']['front'] = '';
            } else {
                $adhar_card_attachment_front = $this->request->getFile('adhar_card_attachment_front');
                if ($adhar_card_attachment_front->isValid() && ! $adhar_card_attachment_front->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $adhar_card_front_uploaded = $adhar_card_attachment_front->move($upload_folder);
                    if ($adhar_card_front_uploaded) {
                        $attachment['adhar']['front'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $adhar_card_attachment_front->getName());
                    }
                }
            }

            if (!empty($this->request->getPost('adhar_card_attachment_back_remove'))) {
                $attachment['adhar']['back'] = '';
            } else {
                $adhar_card_attachment_back = $this->request->getFile('adhar_card_attachment_back');
                if ($adhar_card_attachment_back->isValid() && ! $adhar_card_attachment_back->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $adhar_card_back_uploaded = $adhar_card_attachment_back->move($upload_folder);
                    if ($adhar_card_back_uploaded) {
                        $attachment['adhar']['back'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $adhar_card_attachment_back->getName());
                    }
                }
            }
            #adhar card

            #kye_documents
            $attachment['kye_documents']['remarks'] = !empty($this->request->getPost('kye_documents_remarks')) ? $this->request->getPost('kye_documents_remarks') : NULL;
            if (!empty($this->request->getPost('kye_documents_attachment_remove'))) {
                $attachment['kye_documents']['file'] = '';
            } else {
                $kye_documents_attachment = $this->request->getFile('kye_documents_attachment');
                if ($kye_documents_attachment->isValid() && ! $kye_documents_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $kye_documents_uploaded = $kye_documents_attachment->move($upload_folder);
                    if ($kye_documents_uploaded) {
                        $attachment['kye_documents']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $kye_documents_attachment->getName());
                    }
                }
            }
            #kye_documents

            #family_details
            $attachment['family_details']['remarks'] = !empty($this->request->getPost('family_details_remarks')) ? $this->request->getPost('family_details_remarks') : NULL;
            if (!empty($this->request->getPost('family_details_attachment_remove'))) {
                $attachment['family_details']['file'] = '';
            } else {
                $family_details_attachment = $this->request->getFile('family_details_attachment');
                if ($family_details_attachment->isValid() && ! $family_details_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $family_details_uploaded = $family_details_attachment->move($upload_folder);
                    if ($family_details_uploaded) {
                        $attachment['family_details']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $family_details_attachment->getName());
                    }
                }
            }
            #family_details

            #loan_documents
            $attachment['loan_documents']['remarks'] = !empty($this->request->getPost('loan_documents_remarks')) ? $this->request->getPost('loan_documents_remarks') : NULL;
            if (!empty($this->request->getPost('loan_documents_attachment_remove'))) {
                $attachment['loan_documents']['file'] = '';
            } else {
                $loan_documents_attachment = $this->request->getFile('loan_documents_attachment');
                if ($loan_documents_attachment->isValid() && ! $loan_documents_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $loan_documents_uploaded = $loan_documents_attachment->move($upload_folder);
                    if ($loan_documents_uploaded) {
                        $attachment['loan_documents']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $loan_documents_attachment->getName());
                    }
                }
            }
            #loan_documents

            #educational_documents
            $attachment['educational_documents']['remarks'] = !empty($this->request->getPost('educational_documents_remarks')) ? $this->request->getPost('educational_documents_remarks') : NULL;
            if (!empty($this->request->getPost('educational_documents_attachment_remove'))) {
                $attachment['educational_documents']['file'] = '';
            } else {
                $educational_documents_attachment = $this->request->getFile('educational_documents_attachment');
                if ($educational_documents_attachment->isValid() && ! $educational_documents_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $educational_documents_uploaded = $educational_documents_attachment->move($upload_folder);
                    if ($educational_documents_uploaded) {
                        $attachment['educational_documents']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $educational_documents_attachment->getName());
                    }
                }
            }
            #educational_documents

            #relieving_documents
            $attachment['relieving_documents']['remarks'] = !empty($this->request->getPost('relieving_documents_remarks')) ? $this->request->getPost('relieving_documents_remarks') : NULL;
            if (!empty($this->request->getPost('relieving_documents_attachment_remove'))) {
                $attachment['relieving_documents']['file'] = '';
            } else {
                $relieving_documents_attachment = $this->request->getFile('relieving_documents_attachment');
                if ($relieving_documents_attachment->isValid() && ! $relieving_documents_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $relieving_documents_uploaded = $relieving_documents_attachment->move($upload_folder);
                    if ($relieving_documents_uploaded) {
                        $attachment['relieving_documents']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $relieving_documents_attachment->getName());
                    }
                }
            }
            #relieving_documents

            #misc_documents
            $attachment['misc_documents']['remarks'] = !empty($this->request->getPost('misc_documents_remarks')) ? $this->request->getPost('misc_documents_remarks') : NULL;
            if (!empty($this->request->getPost('misc_documents_attachment_remove'))) {
                $attachment['misc_documents']['file'] = '';
            } else {
                $misc_documents_attachment = $this->request->getFile('misc_documents_attachment');
                if ($misc_documents_attachment->isValid() && ! $misc_documents_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $misc_documents_uploaded = $misc_documents_attachment->move($upload_folder);
                    if ($misc_documents_uploaded) {
                        $attachment['misc_documents']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $misc_documents_attachment->getName());
                    }
                }
            }
            #misc_documents

            #additional_attachments repeater-based attachments
            try {
                $EmployeeAttachmentModel = new EmployeeAttachmentModel();
                $attachments_to_delete = $this->request->getPost('attachments_to_delete');
                if (!empty($attachments_to_delete)) {
                    $delete_ids = explode(',', $attachments_to_delete);
                    foreach ($delete_ids as $delete_id) {
                        if (!empty($delete_id) && is_numeric($delete_id)) {
                            $EmployeeAttachmentModel->delete($delete_id);
                        }
                    }
                }

                $attachment_titles = $this->request->getPost('additional_attachments');
                if (!empty($attachment_titles) && is_array($attachment_titles)) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    foreach ($attachment_titles as $index => $attachment_data) {
                        if (!empty($attachment_data['attachment_title'])) {
                            $file_key = "additional_attachments.{$index}.attachment_file";
                            $uploaded_file = $this->request->getFile($file_key);

                            if ($uploaded_file && $uploaded_file->isValid() && !$uploaded_file->hasMoved()) {
                                $allowed_extensions = ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar'];
                                $file_extension = $uploaded_file->getExtension();
                                if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                                    continue;
                                }
                                $max_size = 5 * 1024 * 1024;
                                $file_size = $uploaded_file->getSize();
                                if ($file_size > $max_size) {
                                    continue;
                                }
                                $original_filename = $uploaded_file->getClientName();
                                $new_filename = $uploaded_file->getRandomName();
                                if ($uploaded_file->move($upload_folder, $new_filename)) {
                                    $attachment_record = [
                                        'employee_id' => $employee_id,
                                        'title' => trim($attachment_data['attachment_title']),
                                        'file_path' => str_replace(WRITEPATH, "/", $upload_folder . '/' . $new_filename),
                                        'file_name' => $original_filename,
                                        'file_extension' => $file_extension,
                                        'file_size' => $file_size,
                                        'uploaded_by' => $this->session->get('current_user')['employee_id'] ?? null
                                    ];

                                    $EmployeeAttachmentModel->insert($attachment_record);
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Employee Attachment Upload Error: ' . $e->getMessage());
            }
            #additional_attachments

            #pdc_cheque
            // unset($attachment['pdc_cheque']['numbers']);
            $attachment['pdc_cheque']['enable_pdc'] = !empty($this->request->getPost('enable_pdc')) ? $this->request->getPost('enable_pdc') : 'no';
            $attachment['pdc_cheque']['bank_name_1'] = !empty($this->request->getPost('pdc_bank_name_1')) ? $this->request->getPost('pdc_bank_name_1') : NULL;
            $attachment['pdc_cheque']['cheque_number_1'] = !empty($this->request->getPost('pdc_cheque_number_1')) ? $this->request->getPost('pdc_cheque_number_1') : NULL;
            $attachment['pdc_cheque']['bank_name_2'] = !empty($this->request->getPost('pdc_bank_name_2')) ? $this->request->getPost('pdc_bank_name_2') : NULL;
            $attachment['pdc_cheque']['cheque_number_2'] = !empty($this->request->getPost('pdc_cheque_number_2')) ? $this->request->getPost('pdc_cheque_number_2') : NULL;
            $attachment['pdc_cheque']['bank_name_3'] = !empty($this->request->getPost('pdc_bank_name_3')) ? $this->request->getPost('pdc_bank_name_3') : NULL;
            $attachment['pdc_cheque']['cheque_number_3'] = !empty($this->request->getPost('pdc_cheque_number_3')) ? $this->request->getPost('pdc_cheque_number_3') : NULL;

            if (!empty($this->request->getPost('pdc_cheque_attachment_remove'))) {
                $attachment['pdc_cheque']['file'] = '';
            } else {
                $pdc_cheque_attachment = $this->request->getFile('pdc_cheque_attachment');
                if ($pdc_cheque_attachment->isValid() && ! $pdc_cheque_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $pdc_cheque_uploaded = $pdc_cheque_attachment->move($upload_folder);
                    if ($pdc_cheque_uploaded) {
                        $attachment['pdc_cheque']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $pdc_cheque_attachment->getName());
                    }
                }
            }
            #pdc_cheque

            #temporary code to correct write path issue
            /*if( isset($attachment['pan']['file']) && !empty($attachment['pan']['file']) ){
                $attachment['pan']['file'] = str_replace(WRITEPATH, "/", $attachment['pan']['file']);
            }
            if( isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front']) ){
                $attachment['adhar']['front'] = str_replace(WRITEPATH, "/", $attachment['adhar']['front']);
            }
            if( isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back']) ){
                $attachment['adhar']['back'] = str_replace(WRITEPATH, "/", $attachment['adhar']['back']);
            }
            if( isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file']) ){
                $attachment['bank_account']['file'] = str_replace(WRITEPATH, "/", $attachment['bank_account']['file']);
            }
            if( isset($attachment['passport']['file']) && !empty($attachment['passport']['file']) ){
                $attachment['passport']['file'] = str_replace(WRITEPATH, "/", $attachment['passport']['file']);
            }
            if( isset($attachment['kye_documents']['file']) && !empty($attachment['kye_documents']['file']) ){
                $attachment['kye_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['kye_documents']['file']);
            }
            if( isset($attachment['family_details']['file']) && !empty($attachment['family_details']['file']) ){
                $attachment['family_details']['file'] = str_replace(WRITEPATH, "/", $attachment['family_details']['file']);
            }
            if( isset($attachment['loan_documents']['file']) && !empty($attachment['loan_documents']['file']) ){
                $attachment['loan_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['loan_documents']['file']);
            }
            if( isset($attachment['educational_documents']['file']) && !empty($attachment['educational_documents']['file']) ){
                $attachment['educational_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['educational_documents']['file']);
            }
            if( isset($attachment['relieving_documents']['file']) && !empty($attachment['relieving_documents']['file']) ){
                $attachment['relieving_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['relieving_documents']['file']);
            }
            if( isset($attachment['misc_documents']['file']) && !empty($attachment['misc_documents']['file']) ){
                $attachment['misc_documents']['file'] = str_replace(WRITEPATH, "/", $attachment['misc_documents']['file']);
            }
            if( isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file']) ){
                $attachment['avatar']['file'] = str_replace(WRITEPATH, "/", $attachment['avatar']['file']);
            }*/
            #temporary code to correct write path issue
            $newData['attachment'] = json_encode($attachment);

            $oldData['employee_id'] = $oldData['id'];
            $oldData['revised_by'] = $this->session->get('current_user')['employee_id'];
            unset($oldData['id']);
            $EmployeeRevisionModel = new EmployeeRevisionModel();
            $insertEmployeeRevisionQuery = $EmployeeRevisionModel->insert($oldData);
            if (!$insertEmployeeRevisionQuery) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error Failed to create revision <br> Please contact administrator.' . json_encode($EmployeeRevisionModel->error());
            } else {
                $updateQuery = $EmployeeModel->update($employee_id, $newData);

                if (!$updateQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error Failed to update employee <br> Please contact administrator.';
                } else {
                    if ($oldData['probation'] != 'confirmed' && $newData['probation'] == 'confirmed') {



                        $employee_email = $newData['work_email'];
                        $employee_name = $newData['first_name'] . '' . $newData['last_name'];
                        $employee_code = $newData['internal_employee_id'];
                        //$employee_probation_period = $newData['probation'];
                        $probationText = $oldData['probation']; // e.g., "45 Days Probation"
                        preg_match('/\d+/', $probationText, $matches);
                        $employee_probation_period = isset($matches[0]) ? (int)$matches[0] : 0;
                        // print_r($oldData);

                        $employee_probation_start_date = $newData['joining_date'];
                        //create employee_probation_end_date  =  joining_date + probation_period
                        $startDate = new DateTime($employee_probation_start_date);
                        $startDate->add(new DateInterval('P' . $employee_probation_period . 'D'));

                        $employee_probation_end_date = $startDate->format('Y-m-d');



                        $employee_status = $newData['status'];
                        $employee_probation_status = $newData['probation'];

                        $EmployeeModel = new EmployeeModel();

                        $EmployeeData = $EmployeeModel
                            ->select([
                                'employees.*',
                                'TRIM(CONCAT(COALESCE(employees.first_name, ""), " ", COALESCE(employees.last_name, ""))) AS employee_name',
                                'companies.company_name AS company_name',
                                'companies.address AS company_address',
                                'companies.logo_url AS company_logo_url',
                                'designations.designation_name AS designation_name'
                            ])
                            ->join('companies', 'companies.id = employees.company_id', 'left')
                            ->join('designations', 'designations.id = employees.designation_id', 'left')
                            ->where('employees.id', $employee_id)
                            ->first();

                        if ($EmployeeData) {
                            $designation_name    = $EmployeeData['designation_name'] ?? '';
                            $company_logo_url    = $EmployeeData['company_logo_url'] ?? '';
                            $employee_company    = $EmployeeData['company_name'] ?? ''; // fixed: was using 'last_company_name' which isn't selected
                            $employee_name       = $EmployeeData['employee_name'] ?? '';

                            // Assuming these are defined elsewhere
                            // $employee_email, $employee_name, $employee_code, etc.

                            $response = $this->sendConfirmProbationEndEmail(
                                $employee_email,
                                $employee_name,
                                $employee_code,
                                $employee_probation_period,
                                $employee_company,
                                $employee_probation_status,
                                $designation_name,
                                $company_logo_url
                            );

                            if ($response === true) {
                                $ProbationNotificationModel = new ProbationNotificationModel();
                                $ProbationNotificationModel->insert([
                                    'employee_id'           => $employee_id,
                                    'probation_period'      => $employee_probation_period,
                                    'probation_status'      => $employee_probation_status,
                                    'probation_confirmed_on' => date('Y-m-d'),
                                    'email_sent_at'         => date('Y-m-d H:i:s'),
                                    'acknowledged_at'       => null,
                                    'email_status'          => 'sent',
                                ]);
                            }
                        }
                    }
                    if ($oldData['probation'] == 'confirmed' && $newData['probation'] != 'confirmed') {
                        #send email
                        $email = \Config\Services::email();
                        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                        $to_emails = array('developer3@healthgenie.in', 'developer2@healthgenie.in', 'careers@gstc.com');
                        //$to_emails = array('developer3@healthgenie.in', 'developer2@healthgenie.in');
                        $email->setTo($to_emails);
                        $email->setSubject('Probation status changed from Confirmed to ' . $newData["probation"]);
                        $email->setMessage('
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
                                                        <strong>Probation status was changed from Confirmed to ' . $newData["probation"] . '</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">Please find the details below</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . trim($newData["first_name"] . ' ' . $newData["last_name"]) . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Code:</span> ' . $newData["internal_employee_id"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">By:</span> ' . session()->get('current_user')['name'] . '</div>
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
                            </div>
                            ');
                        $email->send();
                    }


                    $newRole = $this->request->getPost('role');
                    $sql = "update users set role = '" . $newRole . "' ";
                    if (isset($newData['status']) && !empty($newData['status'])) {
                        $sql .= ", status = '" . $newData['status'] . "' ";
                    }
                    $sql .= " where employee_id = '" . $employee_id . "'";
                    $CustomModel = new CustomModel();
                    $query = $CustomModel->CustomQuery($sql);
                    if (!$query) {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Employee Updated Successfully, but login credentials were not updated';
                    } else {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Employee Updated Successfully';
                    }
                }
            }
        }
        return $this->response->setJSON($response_array);
    }

    private function findChanges($oldData, $newData)
    {
        $changes = [];
        unset($newData['id']);
        unset($newData['changes']);
        unset($newData['revision_date_time']);
        unset($newData['revision_id']);
        unset($newData['revised_by']);
        unset($newData['revised_by_name']);
        unset($newData['late_sitting_allowed']);
        unset($newData['late_sitting_formula']);
        unset($newData['late_sitting_formula_effective_from']);
        unset($newData['sl_allowed']);


        unset($newData['company_name']);
        unset($newData['department_name']);
        unset($newData['designation_name']);
        unset($newData['rh_id_1']);
        unset($newData['rh_id_2']);
        unset($newData['cl_balance']);
        unset($newData['cl_balance_id']);
        unset($newData['el_balance']);
        unset($newData['el_balance_id']);
        unset($newData['rh_balance']);
        unset($newData['rh_balance_id']);

        foreach ($newData as $key => $value) {
            if ($oldData[$key] !== $value) {
                $changes[$key] = [
                    'old_value' => $oldData[$key],
                    'new_value' => $value,
                ];
            }
        }

        if (!empty($changes)) {
            foreach ($changes as $key => $change) {
                if ($key == 'attachment') {
                    $oldValue = isset($change['old_value']) && !empty($change['old_value']) ? json_decode($change['old_value'], true) : [];
                    $newValue = isset($change['new_value']) && !empty($change['new_value']) ? json_decode($change['new_value'], true) : [];
                    $oldKeys = isset($oldValue) && !empty($oldValue) && is_array($oldValue) ? array_keys($oldValue) : [];
                    $newKeys = isset($newValue) && !empty($newValue) && is_array($newValue) ? array_keys($newValue) : [];
                    $allKeys = [];
                    $allKeys = array_merge($newKeys, $oldKeys);
                    $allKeys = array_unique($allKeys);
                    sort($allKeys);
                    if (!empty($allKeys)) {
                        foreach ($allKeys as $k) {
                            $oldChildValue = isset($oldValue[$k]) && !empty($oldValue[$k]) ? $oldValue[$k] : [];
                            $newChildValue = isset($newValue[$k]) && !empty($newValue[$k]) ? $newValue[$k] : [];
                            $oldChildKeys = isset($oldChildValue) && !empty($oldChildValue) && is_array($oldChildValue) ? array_keys($oldChildValue) : [];
                            $newChildKeys = isset($newChildValue) && !empty($newChildValue) && is_array($newChildValue) ? array_keys($newChildValue) : [];
                            $allChildKeys = [];
                            $allChildKeys = array_merge($newChildKeys, $oldChildKeys);
                            $allChildKeys = array_unique($allChildKeys);
                            sort($allChildKeys);
                            if (!empty($allChildKeys)) {
                                foreach ($allChildKeys as $ck) {
                                    $oldGrandChild = isset($oldChildValue[$ck]) && !empty($oldChildValue[$ck]) ? $oldChildValue[$ck] : '';
                                    $newGrandChild = isset($newChildValue[$ck]) && !empty($newChildValue[$ck]) ? $newChildValue[$ck] : '';
                                    if ($oldGrandChild != $newGrandChild) {
                                        $changes[$key][$k][$ck] = [
                                            'old_value' => $oldGrandChild,
                                            'new_value' => $newGrandChild,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    unset($changes[$key]['old_value']);
                    unset($changes[$key]['new_value']);
                }

                if ($key == 'family_members') {
                    $oldValue = isset($change['old_value']) && !empty($change['old_value']) ? json_decode($change['old_value'], true) : [];
                    $newValue = isset($change['new_value']) && !empty($change['new_value']) ? json_decode($change['new_value'], true) : [];
                    // $oldKeys = isset($oldValue) && !empty($oldValue) && is_array($oldValue) ? array_keys($oldValue) : [];
                    // $newKeys = isset($newValue) && !empty($newValue) && is_array($newValue) ? array_keys($newValue) : [];
                    // $allKeys = [];
                    // $allKeys = array_merge( $newKeys, $oldKeys );
                    // $allKeys = array_unique($allKeys);
                    // sort($allKeys);
                    // if( !empty($allKeys) ){
                    //     foreach( $allKeys as $k ){
                    //         $oldChildValue = isset($oldValue[$k]) && !empty($oldValue[$k]) ? $oldValue[$k] : [];
                    //         $newChildValue = isset($newValue[$k]) && !empty($newValue[$k]) ? $newValue[$k] : [];
                    //         $oldChildKeys = isset($oldChildValue) && !empty($oldChildValue) && is_array($oldChildValue) ? array_keys($oldChildValue) : [];
                    //         $newChildKeys = isset($newChildValue) && !empty($newChildValue) && is_array($newChildValue) ? array_keys($newChildValue) : [];
                    //         $allChildKeys = [];
                    //         $allChildKeys = array_merge( $newChildKeys, $oldChildKeys );
                    //         $allChildKeys = array_unique($allChildKeys);
                    //         sort($allChildKeys);
                    //         if( !empty($allChildKeys) ){
                    //             foreach( $allChildKeys as $ck ){
                    //                 $oldGrandChild = isset($oldChildValue[$ck]) && !empty($oldChildValue[$ck]) ? $oldChildValue[$ck] : '';
                    //                 $newGrandChild = isset($newChildValue[$ck]) && !empty($newChildValue[$ck]) ? $newChildValue[$ck] : '';
                    //                 if( $oldGrandChild!= $newGrandChild ){
                    //                     $changes[$key][$k][$ck] = [
                    //                         'old_value' => $oldGrandChild,
                    //                         'new_value' => $newGrandChild,
                    //                     ];
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                    // unset($changes[$key]['old_value']);
                    // unset($changes[$key]['new_value']);

                    $changes[$key]['old_value'] = $oldValue;
                    $changes[$key]['new_value'] = $newValue;
                }
            }
        }

        return $changes;
    }


    public function getAllRh($year = '')
    {
        $year = $year ?? date('Y');
        $HolidayModel = new HolidayModel();
        $HolidayModel
            ->where('year(holiday_date) =', $year)
            ->where('holiday_code =', 'RH');
        $all_rhs = $HolidayModel->findAll();
        return !empty($all_rhs) ? $all_rhs : null;
    }


    public function sendWelcomeEmail()
    {
        $response_array = [
            'response_type' => 'failed',
            'response_description' => 'Invalid request'
        ];

        if (!$_POST['employee_id']) {
            $response_array = [
                'response_type' => "failed",
                'response_description' => "Employee ID was not provided"
            ];
        }

        $response_array = [
            'response_type' => "success",
            'response_description' => "Employee ID was provided"
        ];

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel->select("employees.*");
        $EmployeeModel->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $EmployeeModel->select("companies.company_name as company_name");
        $EmployeeModel->select("companies.address as company_address");
        $EmployeeModel->select("companies.logo_url as company_logo_url");
        $EmployeeModel->select("designations.designation_name as designation_name");
        $EmployeeModel->select("CONCAT(
                LPAD(DAY(joining_date), 2, '0'),
                CASE
                    WHEN DAY(employees.joining_date) IN (1, 21, 31) THEN '<sup>st</sup>'
                    WHEN DAY(employees.joining_date) IN (2, 22) THEN '<sup>nd</sup>'
                    WHEN DAY(employees.joining_date) IN (3, 23) THEN '<sup>rd</sup>'
                    ELSE '<sup>th</sup>'
                END,
                ' ',
                DATE_FORMAT(employees.joining_date, '%b %Y')
            ) AS formatted_joining_date,");
        $EmployeeModel->join("companies", "companies.id=employees.company_id", "left");
        $EmployeeModel->join("designations", "designations.id=employees.designation_id", "left");

        $EmployeeModel->where("employees.id =", $_POST['employee_id']);

        $EmployeeData = $EmployeeModel->first();

        $gender = $EmployeeData['gender'] ?? false;
        $marital_status = $EmployeeData['marital_status'] ?? false;
        $employee_name = $EmployeeData['employee_name'] ?? false;
        $company_name = $EmployeeData['company_name'] ?? false;
        $company_address = $EmployeeData['company_address'] ?? false;
        $designation_name = $EmployeeData['designation_name'] ?? false;
        $last_company_name = $EmployeeData['last_company_name'] ?? false;
        $total_experience = $EmployeeData['total_experience'] ?? false;
        $relevant_experience = $EmployeeData['relevant_experience'] ?? false;
        $highest_qualification = $EmployeeData['highest_qualification'] ?? false;
        $college_university = $EmployeeData['college_university'] ?? false;
        $hobbies = $EmployeeData['hobbies'] ?? false;
        $work_email = $EmployeeData['work_email'] ?? false;
        $company_logo_url = $EmployeeData['company_logo_url'] ?? '/assets/media/logos/logo-healthgenie.png';

        $attachment = $EmployeeData['attachment'] ? json_decode($EmployeeData['attachment'], true) : false;

        $employee_photo_url = $attachment['avatar']['file'] ?? false;


        // print_r($attachment);
        // die();

        $title = $gender == "male" ? "Mr." : "Ms.";
        $he_she = $gender == "male" ? "He" : "She";
        $his_her = $gender == "male" ? "His" : "Her";
        $him_her = $gender == "male" ? "Him" : "Her";


        if (
            !$gender
            || !$employee_name
            || !$company_name
            || !$designation_name
            || !$last_company_name
            || !$total_experience
            || !$relevant_experience
            || !$highest_qualification
            || !$college_university
            || !$hobbies
            || !$work_email
            || !$employee_photo_url
        ) {
            return $this->response->setJSON(
                [
                    'response_type' => "failed",
                    'response_description' => $EmployeeData['employee_name'] . "'s profile is incomplete, please update it. <br><p style='line-height: 1.15rem; font-size: 0.75rem;'>Required Fields: Gender, Company, Designation, Previous Company name, Total Experience, Relevant experience, Highest Education, College/University Name, Hobbies, Work Email ID, Employee Photo.</p>"
                ]
            );
        }


        $EmployeeModel = new EmployeeModel();
        $EmployeeModel->select("employees.work_email");
        $EmployeeModel->where("employees.status =", "active");
        // $EmployeeModel->where("employees.internal_employee_id =", "588");
        // $EmployeeModel->orWhere("employees.internal_employee_id =", "939");
        $EmailDataArray = $EmployeeModel->findAll();

        $EmailData = $EmailDataArray ? array_filter(array_column($EmailDataArray, 'work_email')) : null;

        $valid_emails = array_filter($EmailData, function ($email) {  // change by sunny
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });

        $email_chunks_of_40 = array_chunk($valid_emails, 40, true);
        $email_sent_to = [];

        foreach ($email_chunks_of_40 as $key => $bcc_emails_array) {

            $sender_email = 'app.hrm@healthgenie.in';
            $reply_to_email = 'payroll1@gstc.com';
            // $to_emails = array('hrd@gstc.com');
            $to_emails = array('developer3@healthgenie.in');
            // $bcc_emails = array('developer3@healthgenie.in', 'careers@gstc.com', 'payroll1@gstc.com');
            // $bcc_emails = array('developer3@healthgenie.in', 'developer2@healthgenie.in');
            $bcc_emails = array_values($bcc_emails_array);
            // print_r($bcc_emails);
            // die();

            $email = \Config\Services::email();
            $email->setFrom($sender_email, 'HRM');
            $email->setReplyTo($reply_to_email, 'HRM');
            $email->setTo($to_emails);
            $email->setBcc($bcc_emails);

            $email->setSubject('Welcome ' . $title . ' ' . $employee_name . ' (' . $designation_name . ')');
            ob_start();
?>
            <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; ">
                    <tbody>
                        <tr>
                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                <a href="<?= base_url('public') ?>" rel="noopener" target="_blank">
                                    <img alt="Logo" src="<?= base_url('public') . '/' . ltrim($company_logo_url, '/') ?>" style="width:150px; height: auto; object-fit: contain;" />
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="center">
                                <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">

                                    <!--begin:Email content-->
                                    <div style="padding-bottom: 10px">We welcome <b><?= $title ?> <?= $employee_name ?></b> on board, <?= strtolower($he_she) ?> has joined <b><?= $company_name ?></b> as an <b><?= $designation_name ?></b>.</div>

                                    <?php
                                    if ($employee_photo_url) {
                                    ?>
                                        <div style="padding-bottom: 10px">
                                            <img alt="Avatar" src="<?= base_url('public') . $employee_photo_url . '?referer=email' ?>" style="max-width:500px; height: auto; object-fit: contain; margin: 0px auto;" />
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($last_company_name && $total_experience && $relevant_experience) {
                                    ?>
                                        <div style="padding-top: 10px; padding-bottom: 10px">Previously <?= strtolower($he_she) ?> has worked with <b><?= $last_company_name ?></b> and has a total of <b><?= $total_experience ?></b> of experience, with <b><?= $relevant_experience ?></b> of relevant experience.</div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($highest_qualification && $college_university && $hobbies) {
                                    ?>
                                        <div style="padding-bottom: 10px"><?= ucfirst($he_she) ?> has completed <b><?= $highest_qualification ?></b> from <b><?= $college_university ?></b>, and <?= strtolower($his_her) ?> hobbies are <b><?= $hobbies ?></b>.</div>
                                    <?php
                                    }
                                    ?>

                                    <div style="padding-bottom: 10px">I am sure we will all have a good and enriching experience working together.</div>

                                    <div style="padding-bottom: 10px">Please join me in welcoming <?= strtolower($him_her) ?> and wishing <?= strtolower($him_her) ?> a long and successful career at <b><?= $company_name ?></b>.</div>

                                    <?php
                                    if ($work_email) {
                                    ?>
                                        <div style="padding-bottom: 10px"><?= ucfirst($he_she) ?> can be reached at <b><a href="mailto:<?= $work_email ?>"><?= $work_email ?></a></b></div>
                                    <?php
                                    }
                                    ?>

                                    <!--end:Email content-->

                                    <div style="padding-top:20px; padding-bottom: 10px">
                                        <br>Kind regards,
                                        <br>HRM Team.
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                                <p><?= $company_address ?></p>
                                <p>Copyright © <a href="<?= base_url('public') ?>" rel="noopener" target="_blank"><?= $company_name ?></a>.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

<?php
            $email_message = ob_get_clean();

            $email->setMessage($email_message);
            $email_send = $email->send();
            if (!$email_send) {
                $response_array = array();
                $response_array['response_type'] = 'failed';
                $response_array['response_description'] = 'Welcome email failed to send. Please Inform Developer on extension 452';
                $response_array['msg'] = $email_message;
                // $response_array['debugger'] = $email->get_debugger_messages();
                $response_array['loopkey'] = $key;
                return $this->response->setJSON($response_array);
            }
            $email_sent_to = array_merge($email_sent_to, $bcc_emails);
            sleep(5);
        }

        $WelcomeEmailData = [
            'employee_id' => $_POST['employee_id'],
            'sent_by' => $this->session->get('current_user')['employee_id'],
            'email_content' => addslashes($email_message),
            'sender_email' => addslashes($sender_email),
            'to_emails' => addslashes(implode(",", $to_emails)),
            'bcc_emails' => addslashes(implode(",", $email_sent_to)),
            'reply_to_email' => addslashes($reply_to_email),
        ];
        $WelcomeEmailModel = new WelcomeEmailModel();
        $InsertWelcomeEmailQuery = $WelcomeEmailModel->insert($WelcomeEmailData);
        if (!$InsertWelcomeEmailQuery) {
            $response_array['response_type'] = 'failed';
            $response_array['response_description'] = 'Welcome Email was sent but <br>DB:Error Failed to save record <br> Please contact administrator.';
            $response_array['error'] = $WelcomeEmailModel->error();
        }

        $response_array = [
            'response_type' => "success",
            'response_description' => "Welcome email sent successfully"
        ];

        return $this->response->setJSON($response_array);
    }


    public function getOneYearAnniversaryEmployees()
    {

        $oneYearAgoDate = date('Y-m-d', strtotime('-1 year')); // 1 year ago from today
        $oneYearMinusOneMonthDate = date('Y-m-d', strtotime('-1 year -1 month')); // 1 year + 1 month ago

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel->select("employees.id,
        employees.first_name,
        employees.last_name,
        employees.joining_date,
        companies.company_name,
        companies.company_short_name,
        employees.internal_employee_id,
        DATE_ADD(employees.joining_date, INTERVAL 1 YEAR) AS anniversary_date,
        ");
        $EmployeeModel->join("companies", "companies.id = employees.company_id", "left");
        $EmployeeModel->where("employees.joining_date >=", $oneYearMinusOneMonthDate); // Joined at most 1 year + 1 month ago
        $EmployeeModel->where("employees.joining_date <", $oneYearAgoDate); // Completed at least 1 year
        $EmployeeModel->where("employees.status", "active");
        $EmployeeModel->orderBy("employees.joining_date", "desc");


        $employeeData = $EmployeeModel->findAll();
        if (!$employeeData) {
            $responseData = [
                'status' => 'error',
                'data' => 'No employee found'
            ];
        } else {
            $responseData = [
                'status' => 'success',
                'data' => $employeeData
            ];
            $data = $this->addEmplyeeToAnniversaryList($employeeData);
            if ($data) {
                $this->sendOneYearAnniversaryEmailToHr($data);
            }
        }

        return json_encode($responseData);
    }

    private function addEmplyeeToAnniversaryList($employeeData)
    {
        $AnniversaryModel = new AnniversaryModel();
        $data = array();
        foreach ($employeeData as $employee) {
            $exists = $AnniversaryModel->where('employee_id', $employee['id'])
                ->where('anniversary_date', $employee['anniversary_date'])
                ->first();
            if (!$exists) {
                $AnniversaryModel->insert([
                    'employee_id' => $employee['id'],
                    'anniversary_date' => $employee['anniversary_date'],
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $data[] = $employee;
            }
        }
        return $data;
    }

    private function sendOneYearAnniversaryEmailToHr($employeeData)
    {
        $email = \Config\Services::email();
        $sender_email = 'app.hrm@healthgenie.in';
        $reply_to_email = 'payroll1@gstc.com';
        $to_emails = array('payroll1@gstc.com');
        $cc_emails = array('developer3@healthgenie.in', 'developer2@healthgenie.in', 'hrd@gstc.com');

        $email->setFrom($sender_email, 'HRM');
        $email->setReplyTo($reply_to_email, 'HRM');
        $email->setTo($to_emails);
        $email->setCc($cc_emails);

        $title = "One Year Completed Employees";
        $email_message = '<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">';
        $email_message .= "<table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' style='border-collapse:collapse;margin:0 auto; padding:0; '>";
        $email_message .= "<tbody>";
        $email_message .= "<tr>";
        $email_message .= "<td align='center' valign='center' style='text-align:center; padding: 40px'>";
        $email_message .= "<a href='" . base_url('public') . "' rel='noopener' target='_blank'>";
        $email_message .= "<img alt='Logo' src='" . base_url('public') . "/assets/media/logos/logo-healthgenie.png' style='width:150px; height: auto; object-fit: contain;'/>";
        $email_message .= "</a>";
        $email_message .= "</td>";
        $email_message .= "</tr>";
        $email_message .= "<tr>";
        $email_message .= "<td align='left' valign='center'>";
        $email_message .= "<div style='text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px'>";
        $email_message .= "<div style='padding-bottom: 10px'>Dear HRM Team,</div>";
        $email_message .= "Below is the list of employees who has completed 1 year recently!<br><br>";
        $email_message .= "<table border='1' style='border-collapse: collapse; width: 100%;'>
                            <tr>
                                <th>Employee Name</th>
                                <th>Joining Date</th>
                                <th>1 Year Completed Date</th>
                            </tr>";
        foreach ($employeeData as $employee) {
            $email_message .= "<tr>
                                <td>" . $employee['first_name'] . " " . $employee['last_name'] . " (" . $employee['internal_employee_id'] . ") - " . $employee['company_short_name'] . "</td>
                                <td>" . date('d M, Y', strtotime($employee['joining_date'])) . "</td>
                                <td>" . date('d M, Y', strtotime($employee['anniversary_date'])) . "</td>
                            </tr>";
        }
        $email_message .= "</table><br><br>Thanks,<br>HRM Team.";
        $email_message .= "</div>";
        $email_message .= "</td>";
        $email_message .= "</tr>";
        $email_message .= "<tr>";
        $email_message .= "<td align='center' valign='center' style='font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;'>";
        $email_message .= "<p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>";
        $email_message .= "<p>Copyright © <a href='" . base_url('public') . "' rel='noopener' target='_blank'>HealthGenie</a>.</p>";
        $email_message .= "</td>";
        $email_message .= "</tr>";
        $email_message .= "</tbody>";
        $email_message .= "</table>";
        $email_message .= "</div>";




        $email->setSubject($title);
        $email->setMessage($email_message);
        $email_send = $email->send();
        $email_message = ob_get_clean();


        if (!$email_send) {
            $response_array = array();
            $response_array['response_type'] = 'failed';
            $response_array['response_description'] = 'One Year Anniversary Email to HRD failed to send. Please Inform Developer on extension 452';
            return $this->response->setJSON($response_array);
        }

        $response_array = [
            'response_type' => "success",
            'response_description' => "One Year Anniversary Email to HRD sent successfully"
        ];

        return $this->response->setJSON($response_array);
    }


    private function sendConfirmProbationEndEmail(
        $employee_email,
        $employee_name,
        $employee_code,
        $employee_probation_period,
        $employee_company,
        $employee_probation_status,
        $designation_name,
        $company_logo_url
    ) {
        $email = \Config\Services::email();
        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
        //$employee_email = 'developer2@healthgenie.in';
        $to_emails = array('developer3@healthgenie.in', 'developer2@healthgenie.in', $employee_email);
        $email->setTo($to_emails);
        //$email->setTo([$employee_email]);

        $email->setSubject('Congratulations on Successful Completion of Probation');

        $logo_url = base_url() . '/' . ltrim($company_logo_url, '/');

        $site_url = base_url();
        $probationPeriodRow = '';
        if (!empty($employee_probation_period) && (int)$employee_probation_period > 0) {
            $probationPeriodRow = '<li><strong>Probation Period:</strong> ' . htmlspecialchars($employee_probation_period) . ' days</li>';
        }
        $message = '
<div style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #2F3044; background-color: #edf2f7; padding: 20px;">
    <table align="center" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:6px; overflow:hidden;">
        <tr>
            <td style="text-align:center; padding: 30px;">
                <a href="' . $site_url . '" target="_blank">
                    <img src="' . $logo_url . '" alt="Company Logo" style="width:150px; height:auto; object-fit:contain;" />
                </a>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px;">
                <div style="font-size:17px; margin-bottom: 20px;">
                    <strong>Dear ' . htmlspecialchars($employee_name) . ',</strong>
                </div>
                <p>We are delighted to inform you that you have <strong>successfully completed</strong> your probation period at <strong>' . htmlspecialchars($employee_company) . '</strong>. Your dedication, performance, and positive attitude have been truly commendable, and we deeply appreciate your contributions during this time.</p>

                <p>We are confident that you will continue to grow, take on new challenges, and play a key role in our shared success.</p>

                <p><strong>Confirmation Details:</strong></p>
                <ul style="list-style-type:none; padding-left:0;">
                    <li><strong>Employee Code:</strong> ' . htmlspecialchars($employee_code) . '</li>
                    <li><strong>Designation:</strong> ' . htmlspecialchars($designation_name) . '</li>'
            . $probationPeriodRow .
            '
                    <li><strong>Probation Status:</strong> ' . htmlspecialchars($employee_probation_status) . '</li>
                </ul>

                <p style="margin-top: 20px;">On behalf of the entire team, <strong>congratulations once again!</strong> We look forward to supporting your continued growth and success with us.</p>

                <p>Welcome aboard — as a confirmed and valued member of our team.</p>

                <p style="margin-top: 30px;">Warm regards,<br><strong>HRM Team</strong></p>
            </td>
        </tr>
        <tr>
            <td style="text-align:center; font-size:13px; color:#6d6e7c; padding: 20px;">
                <p>B-13, Okhla Industrial Area Phase 2, Delhi 110020, India</p>
                <p>&copy; ' . date('Y') . ' <a href="' . $site_url . '" target="_blank" style="color:#6d6e7c;">' . htmlspecialchars($employee_company) . '</a>. All rights reserved.</p>
            </td>
        </tr>
    </table>
</div>';


        $email->setMessage($message);
        $success = $email->send();

        if (!$success) {
            log_message('error', 'Email failed: ' . print_r($email->printDebugger(['headers', 'subject', 'body']), true));
        } else {
            log_message('info', 'Probation confirmation email sent to ' . $employee_email);
        }

        return $success;
    }
}
