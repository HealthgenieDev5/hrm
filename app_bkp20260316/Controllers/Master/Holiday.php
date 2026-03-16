<?php

namespace App\Controllers\Master;

use App\Models\CompanyModel;
use App\Models\HolidayModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Models\SpecialHolidayEmployeesModel;

class Holiday extends BaseController
{

    public $session;
    public $tableName;
    // public $db;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
        $this->tableName = '';
        // $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }
        $CompanyModel = new CompanyModel();
        $EmployeeModel = new EmployeeModel();
        $AllEmployees =
            $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.company_id as company_id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            ->where('employees.status =', 'active')
            ->orderBy('employees.first_name', 'ASC')
            ->findAll();
        $HolidayModel = new HolidayModel();
        $data = [
            'page_title'            => 'Holiday Master',
            'current_controller'    => 'master',
            'current_method'        => 'holiday',
            'companies'             => $CompanyModel->findAll(),
            'years'                 => $HolidayModel->select('distinct(year(holidays.holiday_date)) as year')->orderBy('year(holidays.holiday_date)', 'desc')->findAll(),
            'AllEmployees'          => $AllEmployees,
        ];

        return view('Master/HolidayMaster', $data);
    }

    public function getAllHolidays()
    {

        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $HolidayModel = new HolidayModel();
        $filter_year = isset($params['filter_year']) ? $params['filter_year'] : date('Y');
        $HolidayModel->where('year(holiday_date) =', $filter_year);

        $all_holidays = $HolidayModel->findAll();
        if (!empty($all_holidays)) {
            foreach ($all_holidays as $index => $holidayRow) {
                $all_holidays[$index]['actions'] = '';
                $holiday_date_formatted = !empty($holidayRow['holiday_date']) ? date('d M Y', strtotime($holidayRow['holiday_date'])) : '-';
                $holiday_date_ordering = !empty($holidayRow['holiday_date']) ? strtotime($holidayRow['holiday_date']) : '0';
                $all_holidays[$index]['holiday_date'] = array('formatted' => $holiday_date_formatted, 'ordering' => $holiday_date_ordering);
            }
        }

        echo json_encode($all_holidays);
    }

    public function getAllHolidaysForEmployeePage()
    {

        $HolidayModel = new HolidayModel();
        $HolidayModel->where('year(holiday_date) =', date('Y'))->orderBy('holiday_date', 'ASC');

        $all_holidays = $HolidayModel->findAll();
        if (!empty($all_holidays)) {
            foreach ($all_holidays as $index => $holidayRow) {
                $all_holidays[$index]['actions'] = '';
                $holiday_date_formatted = !empty($holidayRow['holiday_date']) ? date('d M Y', strtotime($holidayRow['holiday_date'])) : '-';
                $holiday_date_ordering = !empty($holidayRow['holiday_date']) ? strtotime($holidayRow['holiday_date']) : '0';
                $all_holidays[$index]['holiday_date'] = array('formatted' => $holiday_date_formatted, 'ordering' => $holiday_date_ordering);
            }
        }

        return $all_holidays;
    }


    public function addHoliday()
    {
        $response_array = array();
        $rules = [
            'holiday_code'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Holiday Code is required',
                ]
            ],
            'holiday_name'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Holiday Name is required',
                ]
            ],
            'holiday_type'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Holiday Type is required',
                ]
            ],
            'holiday_date'  =>  [
                'rules'         =>  'required|is_unique[holidays.holiday_date]',
                'errors'        =>  [
                    'required'  => 'Holiday Date is required',
                    'is_unique' => 'There is another holiday on this date',
                ]
            ],
        ];
        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $data = [
                'holiday_code' => $this->request->getPost('holiday_code'),
                'holiday_name' => $this->request->getPost('holiday_name'),
                'holiday_type' => $this->request->getPost('holiday_type'),
                'holiday_date' => (!empty($this->request->getPost('holiday_date')) && $this->request->getPost('holiday_date') !== '') ? $this->request->getPost('holiday_date') : null
            ];
            $HolidayModel = new HolidayModel();
            $HolidayInsertQuery = $HolidayModel->insert($data);
            if ($HolidayInsertQuery) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Holiday Added Successfully';
                $response_array['holiday_id'] = $HolidayModel->insertID();
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function deleteHoliday()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'holiday_id'  =>  [
                    'rules'         =>  'required|is_not_unique[holidays.id]',
                    'errors'        =>  [
                        'required'  => 'Holiday ID is required',
                        'is_not_unique' => 'This Holiday does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('holiday_id');
        } else {
            $holiday_id   = $this->request->getPost('holiday_id');
            $HolidayModel = new HolidayModel();
            $DeleteHolidayQuery = $HolidayModel->delete($holiday_id);
            if (!$DeleteHolidayQuery) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Holiday Deleted Successfully';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getHoliday()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'holiday_id'  =>  [
                    'rules'         =>  'required|is_not_unique[holidays.id]',
                    'errors'        =>  [
                        'required'  => 'Holiday ID is required',
                        'is_not_unique' => 'This Holiday does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('holiday_id');
        } else {
            $holiday_id   = $this->request->getPost('holiday_id');
            $HolidayModel = new HolidayModel();
            $Holiday = $HolidayModel->find($holiday_id);
            if (!empty($Holiday)) {
                // $Holiday['employees'] = explode(",", $Holiday['employees']);
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Holiday Found';
                $response_array['response_data']['holiday'] = $Holiday;
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Holiday Not Found!';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getEmployeeList()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'holiday_id'  =>  [
                    'rules'         =>  'required|is_not_unique[holidays.id]',
                    'errors'        =>  [
                        'required'  => 'Holiday ID is required',
                        'is_not_unique' => 'This Holiday does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('holiday_id');
        } else {
            $holiday_id   = $this->request->getPost('holiday_id');
            $SpecialHolidayEmployeesModel = new SpecialHolidayEmployeesModel();
            $SpecialHolidayEmployees = $SpecialHolidayEmployeesModel->where('holiday_id = ', $holiday_id)->first()['employee_id'];
            $SpecialHolidayEmployees = explode(",", $SpecialHolidayEmployees);

            if (!empty($SpecialHolidayEmployees)) {
                $EmployeeModel = new EmployeeModel();
                $EmployeesOfThisLeave = $EmployeeModel
                    ->select('employees.id as id')
                    ->select('employees.company_id as company_id')
                    ->select('employees.internal_employee_id as internal_employee_id')
                    ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
                    ->select('companies.company_short_name as company_short_name')
                    ->select('departments.department_name as department_name')
                    ->join('companies as companies', 'companies.id = employees.company_id', 'left')
                    ->join('departments as departments', 'departments.id = employees.department_id', 'left')
                    ->orderBy('employees.first_name', 'ASC')
                    ->whereIn('employees.id', $SpecialHolidayEmployees)
                    ->findAll();
            } else {
                $EmployeesOfThisLeave = null;
            }
            if (!empty($EmployeesOfThisLeave)) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Employees Found';
                $response_array['response_data']['employees'] = $EmployeesOfThisLeave;
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Employees Not Found!';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function updateHoliday()
    {
        $response_array = array();
        $rules = [
            'holiday_id'  =>  [
                'rules'         =>  'required|is_not_unique[holidays.id]',
                'errors'        =>  [
                    'required'  => 'Holiday ID is required',
                    'is_not_unique'  => 'Holiday Does not exist in our database',
                ]
            ],
            'holiday_code'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Holiday Code is required',
                ]
            ],
            'holiday_name'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Holiday Name is required',
                ]
            ],
            'holiday_type'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Holiday Type is required',
                ]
            ],
            'holiday_date'  =>  [
                'rules'         =>  'required|is_unique[holidays.holiday_date,id,{holiday_id}]',
                'errors'        =>  [
                    'required'  => 'Holiday Date is required',
                    'is_unique' => 'There is another holiday on this date',
                ]
            ],
        ];
        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $holiday_id = $this->request->getPost('holiday_id');
            $data = [
                'holiday_code' => $this->request->getPost('holiday_code'),
                'holiday_name' => $this->request->getPost('holiday_name'),
                'holiday_type' => $this->request->getPost('holiday_type'),
                // 'employees' => !empty($this->request->getPost('employee_id')) ? implode(",", $this->request->getPost('employee_id')) : '',
                'holiday_date' => (!empty($this->request->getPost('holiday_date')) && $this->request->getPost('holiday_date') !== '') ? $this->request->getPost('holiday_date') : null
            ];
            $HolidayModel = new HolidayModel();
            $HolidayUpdateQuery = $HolidayModel->update($holiday_id, $data);
            if ($HolidayUpdateQuery) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Holiday Updated Successfully';

                $data = [
                    'holiday_id' => $holiday_id,
                    'employee_id' => !empty($this->request->getPost('employee_id')) ? implode(",", $this->request->getPost('employee_id')) : '',
                ];
                $SpecialHolidayEmployeesModel = new SpecialHolidayEmployeesModel();
                $specialHolidayRecord = $SpecialHolidayEmployeesModel->select('special_holiday_employees.id as special_holiday_employees_record_row_id')->where('holiday_id=', $holiday_id)->first();
                if (!empty($specialHolidayRecord['special_holiday_employees_record_row_id'])) {
                    #update
                    $SpecialHolidayEmployeesModel = new SpecialHolidayEmployeesModel();
                    $updateSpecialHolidayEmployeesQuery = $SpecialHolidayEmployeesModel->update($specialHolidayRecord['special_holiday_employees_record_row_id'], $data);
                } else {
                    #insert
                    $SpecialHolidayEmployeesModel = new SpecialHolidayEmployeesModel();
                    $updateSpecialHolidayEmployeesQuery = $SpecialHolidayEmployeesModel->insert($data);
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function holidaySingle($id)
    {
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $EmployeeModel = new EmployeeModel();
        $AllEmployees =
            $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.company_id as company_id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('employees.machine as machine')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            ->where('employees.status =', 'active')
            ->orderBy('employees.first_name', 'ASC')
            ->findAll();
        $CompanyModel = new CompanyModel();

        $HolidayModel = new HolidayModel();
        $Holiday = $HolidayModel->find($id);
        if (!empty($Holiday)) {
            $Holiday['employees'] = explode(",", $Holiday['employees']);
        }

        $data = [
            'page_title'            => 'Edit Holiday',
            'current_controller'    => 'master',
            'current_method'        => 'holiday',
            'AllEmployees'          => $AllEmployees,
            'Holiday'               => $Holiday,
            'companies'             => $CompanyModel->findAll(),
        ];

        return view('Master/HolidayEdit', $data);
    }
}
