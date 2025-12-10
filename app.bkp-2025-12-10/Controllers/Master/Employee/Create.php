<?php

namespace App\Controllers\Master\Employee;

use App\Models\RolesModel;
use App\Models\ShiftModel;
use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\HolidayModel;
use App\Models\EmployeeModel;
use App\Models\DesignationModel;
use App\Controllers\BaseController;
use App\Controllers\Master\MinWagesCategory;

class Create extends BaseController
{
    public $session;

    public $can_override_rh;
    public $can_override_special_benefits;
    public $can_change_password;
    public $can_override_leave_balance;
    public $can_override_special_holiday;
    public $can_update_salary;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $this->can_override_rh = in_array($this->session->get('current_user')['employee_id'], ['40', '52']) ? true : false;
        $this->can_override_special_benefits = in_array($this->session->get('current_user')['employee_id'], ['40']) ? true : false;
        $this->can_change_password = in_array($this->session->get('current_user')['employee_id'], ['40', '52']) ? true : false;
        $this->can_override_leave_balance = in_array($this->session->get('current_user')['employee_id'], ['40', '52']) ? true : false;
        $this->can_override_special_holiday = in_array($this->session->get('current_user')['employee_id'], ['40', '52']) ? true : false;
        $this->can_update_salary = in_array($this->session->get('current_user')['employee_id'], ['40', '52']) ? true : false;
    }

    public function index()
    {
        // if( !in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'] ) ){
        //     return redirect()->to(base_url('/unauthorised'));
        // }
        $EmployeeModel = new EmployeeModel();
        $CompanyModel = new CompanyModel();
        $DesignationModel = new DesignationModel();
        $RolesModel = new RolesModel();
        $ShiftModel = new ShiftModel();

        $MinWagesCategoryMaster = new MinWagesCategory();
        $MinWagesCategories = $MinWagesCategoryMaster->getAllMinWagesCategory(true);

        $data = [
            'page_title'            => 'Add Employee',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'companies'             => $CompanyModel->findAll(),
            'designations'          => $DesignationModel->findAll(),
            'roles'                 => $RolesModel->where('role_name !=', 'hod')->orderBy('role_name ASC')->findAll(),
            'shifts'                => $ShiftModel->findAll(),
            'MinWagesCategories'    => $MinWagesCategories,
            'can_override_rh'       => $this->can_override_rh,
            'can_override_special_benefits' => $this->can_override_special_benefits,
            'can_change_password' => $this->can_change_password,
            'can_override_leave_balance' => $this->can_override_leave_balance,
            'can_override_special_holiday' => $this->can_override_special_holiday,
            'can_update_salary' => $this->can_update_salary,
            'allRH' => $this->getAllRh(date('Y')),
        ];
        return view('Master/EmployeeAddNew', $data);
    }


    public function store()
    {
        $response_array = array();

        $rules = [
            'internal_employee_id'  =>  [
                'rules'         =>  'required|is_unique[employees.internal_employee_id]',
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
            'joining_date' => ['rules' => 'required', 'errors' => ['required' => 'Joining date is required']],
            'notice_period' => ['rules' => 'required', 'errors' => ['required' => 'Notice period is required']],
            'status' => ['rules' => 'required', 'errors' => ['required' => 'Status is required']],
            'role' => ['rules' => 'required', 'errors' => ['required' => 'Roles is required']],

            'probation'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please Select a probation status',
                ]
            ],

            'role' => ['rules' => 'required', 'errors' => ['required' => 'Roles is required']],

            'shift_id'  =>  [
                'rules'         =>  'required|integer',
                'errors'        =>  [
                    'required'  => 'Please Select a Shift',
                    'integer'   => 'ID of this shift is not an integer',
                ]
            ],
            'joining_date'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please Select a Joining Date',
                ]
            ],
            'machine'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please Select a Machine',
                ]
            ],
            'min_wages_category'  =>  [
                'rules'         =>  'required|is_not_unique[minimum_wages_categories.id]',
                'errors'        =>  [
                    'required'  => 'Please Select a category',
                    'is_not_unique' => 'This Category does not exist in our database Please contact administrator'
                ]
            ],
            'personal_email'  =>  [
                'rules'         =>  'required|valid_email|is_unique[employees.personal_email]',
                'errors'        =>  [
                    'required'  => 'Personal Email is required',
                    'valid_email'  => 'Personal Email is not valid',
                    'is_unique'  => 'This Personal Email already exist in our database'
                ]
            ],
            'personal_mobile'  =>  [
                'rules'         =>  'required|integer|exact_length[10]|is_unique[employees.personal_mobile]',
                'errors'        =>  [
                    'required'  => 'Please enter 10 digit mobile number',
                    'integer'  => 'Personal Mobile number must be only numbers',
                    'exact_length'  => 'Personal Mobile number must be exactly 10 digits',
                    'is_unique'  => 'This Personal Mobile number already exist in our database'
                ]
            ],
            'gender'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select your gender',
                ]
            ],
            'date_of_birth'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select Date of Birth',
                ]
            ],
            // 'fathers_name'  =>  [
            //     'rules'         =>  'required',
            //     'errors'        =>  [
            //         'required'  => 'Please enter Father Name',
            //     ]
            // ],
            'marital_status'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select marital status',
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
                'rules'         =>  'required|integer|exact_length[6]',
                'errors'        =>  [
                    'required'  => 'Permanent Pincode is required',
                    'integer'   => 'Permanent Pincode must be integer only',
                    'integer'   => 'Permanent Pincode must be 6 digit only',
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
                'rules'         =>  'required|integer|exact_length[6]',
                'errors'        =>  [
                    'required'  => 'Present Pincode is required',
                    'integer'   => 'Present Pincode must be integer only',
                    'integer'   => 'Present Pincode must be 6 digit only',
                ]
            ],
            'present_address'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Present Address is required',
                ]
            ]
        ];

        if (isset($_REQUEST['work_email']) && !empty($_REQUEST['work_email'])) {
            $rules['work_email'] =  [
                'rules'         =>  'valid_email|is_unique[employees.work_email]',
                'errors'        =>  [
                    'valid_email'  => 'Work Email is not valid',
                    'is_unique'  => 'This Work Email already exist in our database'
                ]
            ];
        }
        if (isset($_REQUEST['work_mobile']) && !empty($_REQUEST['work_mobile'])) {
            $rules['work_mobile'] =  [
                'rules'         =>  'integer|exact_length[10]|is_unique[employees.work_mobile]',
                'errors'        =>  [
                    'integer'  => 'Work Mobile number must be only numbers',
                    'exact_length'  => 'Work Mobile number must be exactly 10 digits',
                    'is_unique'  => 'This Work Mobile number already exist in our database'
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

        if ($this->can_update_salary == true) {
            $rules['basic_salary'] = ['rules' => 'required', 'errors' => ['required' => 'Basic salary is required']];
            $rules['house_rent_allowance'] = ['rules' => 'required', 'errors' => ['required' => 'HRA is required']];
            $rules['conveyance'] = ['rules' => 'required', 'errors' => ['required' => 'Conveyance is required']];
            $rules['medical_allowance'] = ['rules' => 'required', 'errors' => ['required' => 'Medical allowance is required']];
            $rules['special_allowance'] = ['rules' => 'required', 'errors' => ['required' => 'Special allowance is required']];
            $rules['fuel_allowance'] = ['rules' => 'required', 'errors' => ['required' => 'Fuel allowance is required']];
            $rules['other_allowance'] = ['rules' => 'required', 'errors' => ['required' => 'Other allowance is required']];
            $rules['vacation_allowance'] = ['rules' => 'required', 'errors' => ['required' => 'Vacation allowance is required']];

            if (!empty($this->request->getPost('pf'))) {
                $rules['pf'] = ['rules' => 'required', 'errors' => ['required' => 'PF should be yes or no']];
                $rules['pf_number'] = ['rules' => 'required', 'errors' => ['required' => 'PF Number is required']];
            }
            if (!empty($this->request->getPost('esi'))) {
                $rules['esi'] = ['rules' => 'required', 'errors' => ['required' => 'ESI should be yes or no']];
                $rules['esi_number'] = ['rules' => 'required', 'errors' => ['required' => 'ESI Number is required']];
            }
            if (!empty($this->request->getPost('non_compete_loan'))) {
                $rules['non_compete_loan'] = ['rules' => 'required', 'errors' => ['required' => 'NCL should be yes or no']];
                $rules['non_compete_loan_from'] = ['rules' => 'required', 'errors' => ['required' => 'NCL From Date is required']];
                $rules['non_compete_loan_amount_per_month'] = ['rules' => 'required', 'errors' => ['required' => 'NCL Amount Per Month is required']];
            }
            if (!empty($this->request->getPost('loyalty_incentive'))) {
                $rules['loyalty_incentive'] = ['rules' => 'required', 'errors' => ['required' => 'Loyalty Incentive should be yes or no']];
                $rules['loyalty_incentive_from'] = ['rules' => 'required', 'errors' => ['required' => 'LI From Date is required']];
                $rules['loyalty_incentive_amount_per_month'] = ['rules' => 'required', 'errors' => ['required' => 'LI Amount Per Month is required']];
                $rules['loyalty_incentive_mature_after_month'] = ['rules' => 'required', 'errors' => ['required' => 'LI Mature After Month is required']];
                $rules['loyalty_incentive_pay_after_month'] = ['rules' => 'required', 'errors' => ['required' => 'LI Pay After Month is required']];
            }
            if (!empty($this->request->getPost('lwf'))) {
            }
            if (!empty($this->request->getPost('tds'))) {
            }
        }

        $validation = $this->validate($rules);

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $data = [
                'internal_employee_id'          => $this->request->getPost('internal_employee_id'),
                'role'                          => $this->request->getPost('role'),
                'first_name'                    => trim($this->request->getPost('first_name')),
                'last_name'                     => trim($this->request->getPost('last_name')),
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
                'family_members'                => !empty($this->request->getPost('family_members')) ? json_encode($this->request->getPost('family_members')) : NULL,
                'work_email'                    => $this->request->getPost('work_email'),
                'work_mobile'                   => $this->request->getPost('work_mobile'),
                'work_phone_extension_number'   => $this->request->getPost('work_phone_extension_number'),
                'work_phone_cug_number'         => $this->request->getPost('work_phone_cug_number'),
                'desk_location'                 => $this->request->getPost('desk_location'),
                'highest_qualification'         => $this->request->getPost('highest_qualification'),
                'total_experience'              => $this->request->getPost('total_experience'),
                'last_company_name'             => $this->request->getPost('last_company_name'),
                'relevant_experience'           => $this->request->getPost('relevant_experience'),
                'college_university'            => $this->request->getPost('college_university'),
                'hobbies'                       => $this->request->getPost('hobbies'),
            ];

            $attachment = array();

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

            #bank_account
            $attachment['bank_account']['name'] = !empty($this->request->getPost('bank_name')) ? $this->request->getPost('bank_name') : NULL;
            $attachment['bank_account']['number'] = !empty($this->request->getPost('bank_account_number')) ? $this->request->getPost('bank_account_number') : NULL;
            if (!empty($this->request->getPost('bank_account_attachment_remove'))) {
                $attachment['bank_account']['file'] = '';
            } else {
                $bank_account_attachment = $this->request->getFile('bank_account_attachment');
                if ($bank_account_attachment->isValid() && ! $bank_account_attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $bank_account_uploaded = $bank_account_attachment->move($upload_folder);
                    if ($bank_account_uploaded) {
                        $attachment['bank_account']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $bank_account_attachment->getName());
                    }
                }
            }
            #bank_account

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

            #kye_documents
            $attachment['kye_documents']['remarks'] = !empty($this->request->getPost('kye_documents_number')) ? $this->request->getPost('kye_documents_number') : NULL;
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
            $attachment['family_details']['remarks'] = !empty($this->request->getPost('family_details_number')) ? $this->request->getPost('family_details_number') : NULL;
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
            $attachment['loan_documents']['remarks'] = !empty($this->request->getPost('loan_documents_number')) ? $this->request->getPost('loan_documents_number') : NULL;
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
            $attachment['educational_documents']['remarks'] = !empty($this->request->getPost('educational_documents_number')) ? $this->request->getPost('educational_documents_number') : NULL;
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
            $attachment['relieving_documents']['remarks'] = !empty($this->request->getPost('relieving_documents_number')) ? $this->request->getPost('relieving_documents_number') : NULL;
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
            $attachment['misc_documents']['remarks'] = !empty($this->request->getPost('misc_documents_number')) ? $this->request->getPost('misc_documents_number') : NULL;
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

            $data['attachment'] = json_encode($attachment);

            $EmployeeModel = new EmployeeModel();
            $insertEmployeeQuery = $EmployeeModel->insert($data);
            if (!$insertEmployeeQuery) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error Failed to add new <br> Please contact administrator.';
                $response_array['error'] = $EmployeeModel->error();
            } else {
                $employee_id = $EmployeeModel->getInsertID();
                #create login credentials here
                if ($this->request->getPost('create_login_credentials') == 'yes') {
                    $username = strtolower(trim($this->request->getPost('first_name')) . trim($this->request->getPost('internal_employee_id')));
                    $password = md5($username);
                    $role = $this->request->getPost('role');
                    $status = 'active';

                    $sql = "insert into users (employee_id, username, password, role, status) values ('" . $employee_id . "', '" . $username . "', '" . $password . "', '" . $role . "', '" . $status . "')";
                    $CustomModel = new CustomModel();
                    $query = $CustomModel->CustomQuery($sql);
                    if (!$query) {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Employee Added Successfully but failed to create login credentials, please contact the developer';
                        $response_array['returned_id'] = $employee_id;
                    } else {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Employee Added Successfully, username: ' . $username . ' and password: ' . $username;
                        $response_array['returned_id'] = $employee_id;
                    }
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Employee Added Successfully, username and password was not created as you instructed ';
                    $response_array['returned_id'] = $employee_id;
                }
                #create login credentials here

                #####begin::send notification to Nazrul#####
                $notification_email = \Config\Services::email();
                $notification_email->setFrom('app.hrm@healthgenie.in', 'HRM');
                $to_emails = array('payroll1@gstc.com', 'payroll1@gstc.com');
                $notification_email->setTo($to_emails);
                $cc_emails = array('hrd@gstc.com', 'careers@gstc.com', 'careers2@gstc.com', 'carrers3@gstc.com');
                $notification_email->setCC($cc_emails);
                $notification_email->setBCC(['developer3@healthgenie.in']);
                $notification_email->setSubject('A new employee has been added by ' . $this->session->get("current_user")["name"]);
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
                                                <strong>A new employee has been joined and it has been added by ' . $this->session->get("current_user")["name"] . ' on ' . date('d M, Y') . '</strong>
                                            </div>
                                            <div style="padding-bottom: 10px">Details of the employee are mentioned below.</div>
                                            <div style="padding-bottom: 10px">Employee code : ' . $data["internal_employee_id"] . '</div>
                                            <div style="padding-bottom: 10px">Employee name : ' . trim($data["first_name"] . " " . $data["last_name"]) . '</div>
                                            <div style="padding-bottom: 10px">Joining date : ' . $data["joining_date"] . '</div>
                                            <div style="padding-bottom: 10px">@Santu Please make sure his ESIC & PF account should be updated from your end.</div>

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

        return $this->response->setJSON($response_array);
    }

    public function getDepartmentByCompanyId()
    {
        $response_array = array();
        $company_id   = $this->request->getPost('company_id');
        $sql = "select d.*, c.company_short_name as company_short_name  from departments d left join companies c on c.id = d.company_id where d.company_id = '" . $company_id . "'";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
        } else {
            $departments = $query->getResultArray();
            if (!empty($departments)) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Departments found';
                $response_array['response_data']['departments'] = $departments;
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'No Department is associated with this company';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getReportingManagersByCompanyId()
    {
        $response_array = array();
        $company_id   = $this->request->getPost('company_id');
        $sql = "select e.id as id, trim(concat(e.first_name, ' ', e.last_name)) as name, d.department_name as department_name, c.company_short_name as company_short_name from employees e left join departments d on d.id = e.department_id left join companies c on c.id = e.company_id";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
        } else {
            $reportingManagers = $query->getResultArray();
            if (!empty($reportingManagers)) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Reporting Managers found';
                $response_array['response_data']['reportingManagers'] = $reportingManagers;
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'No Reporting Manager is associated with this company';
            }
        }
        return $this->response->setJSON($response_array);
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
}
