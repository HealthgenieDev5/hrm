<?php

namespace App\Controllers\Master;

use App\Models\LeaveModel;
use App\Controllers\BaseController;

class Leave extends BaseController
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

        $model = new LeaveModel();
        $data = [
            'page_title'            => 'Leave Master',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
        ];
        return view('Master/LeaveMaster', $data);
    }

    public function getAllLeaves()
    {
        #this is database details
        $dbDetails = array(
            "host" => $this->db->hostname,
            "user" => $this->db->username,
            "pass" => $this->db->password,
            "db" => $this->db->database,
        );

        $table = "leaves";

        #primary key
        $primaryKey = "id";
        $columns = array(
            array("db" => "id", "dt" => 0),
            array("db" => "leave_code", "dt" => 1),
            array("db" => "leave_name", "dt" => 2),
            array("db" => "encash", "dt" => 3),
            array("db" => "allocation", "dt" => 4),
            array("db" => "limit", "dt" => 5),
            array("db" => "carry_forward", "dt" => 6),
            array("db" => "carry_forward_threshold", "dt" => 7),
            array("db" => "date_time", "dt" => 8),
            array(
                "db" => "id",
                "dt" => 9,
                "formatter" => function ($d, $row) {
                    return '<div class="d-flex justify-content-center">
                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-leave" data-id="' . $row['id'] . '">
                                    <span class="svg-icon svg-icon-3">
                                        <i class="fa fa-pencil-alt" aria-hidden="true" ></i>
                                    </span>
                                </a>
                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-leave" data-id="' . $row['id'] . '">
                                    <span class="svg-icon svg-icon-3">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </a>
                            </div>';
                },
            ),
        );

        echo json_encode(
            \SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    public function addLeave()
    {
        $response_array = array();
        $rules = [
            'leave_code'  =>  [
                'rules'         =>  'required|is_unique[leaves.leave_code]',
                'errors'        =>  [
                    'required'  => 'Leave Code is required',
                    'is_unique' => 'This Leave Code already exist in our database'
                ]
            ],
            'leave_name'  =>  [
                'rules'         =>  'required|is_unique[leaves.leave_name]',
                'errors'        =>  [
                    'required'  => 'Leave Name is required',
                    'is_unique' => 'This Leave already exist in our database.'
                ]
            ],
            'encash'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Encash is required',
                ]
            ],
            'allocation'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Allocation is required',
                ]
            ],
            'limit'  =>  [
                'rules'         =>  'required|integer',
                'errors'        =>  [
                    'integer'   => 'Limit must be an integer',
                    'required'  => 'Limit is required',
                ]
            ],
            'carry_forward'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Carry Forward is required',
                ]
            ],
        ];

        if (isset($_REQUEST['carry_forward']) && $_REQUEST['carry_forward'] == 'yes') {
            $rules['carry_forward_threshold'] =  [
                'rules'         =>  'required|integer|greater_than[0]',
                'errors'        =>  [
                    'required'  => 'Carry Forward Threshold is required (Only if Carry Forward is set to Yes)',
                    'integer'   => 'Carry Forward Threshold must be an integer (Only if Carry Forward is set to Yes)',
                    'greater_than'   => 'Carry Forward Threshold must be greater than 0 (Only if Carry Forward is set to Yes)',
                ]
            ];
        }

        $validation = $this->validate($rules);

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $values = [
                'leave_code'  => $this->request->getPost('leave_code'),
                'leave_name'  => $this->request->getPost('leave_name'),
                'encash'  => $this->request->getPost('encash'),
                'allocation'  => $this->request->getPost('allocation'),
                'limit'  => $this->request->getPost('limit'),
                'carry_forward'  => $this->request->getPost('carry_forward'),
                'carry_forward_threshold'  => $this->request->getPost('carry_forward_threshold'),
            ];
            $LeaveModel = new LeaveModel();
            $query = $LeaveModel->insert($values);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Leave Added Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function deleteLeave()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'leave_id'  =>  [
                    'rules'         =>  'required|is_not_unique[leaves.id]',
                    'errors'        =>  [
                        'required'  => 'Leave ID is required',
                        'is_not_unique' => 'This Leave is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('leave_id');
        } else {
            $leave_id   = $this->request->getPost('leave_id');
            $LeaveModel = new LeaveModel();
            $query = $LeaveModel->delete($leave_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Leave Deleted Successfully';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getLeave()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'leave_id'  =>  [
                    'rules'         =>  'required|is_not_unique[leaves.id]',
                    'errors'        =>  [
                        'required'  => 'Leave ID is required',
                        'is_not_unique' => 'This Leave is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('leave_id');
        } else {
            $leave_id   = $this->request->getPost('leave_id');
            $LeaveModel = new LeaveModel();
            $query = $LeaveModel->find($leave_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Leave Found';
                $response_array['response_data']['leave'] = $query;
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function updateLeave()
    {
        $response_array = array();
        $rules = [
            'leave_id'  =>  [
                'rules'         =>  'required|is_not_unique[leaves.id]',
                'errors'        =>  [
                    'required'  => 'Leave id is required',
                    'is_not_unique' => 'This Leave does not exist in our database anymore. Please contact Administrator'
                ]
            ],
            'leave_code'  =>  [
                'rules'         =>  'required|is_unique[leaves.leave_code,id,{leave_id}]',
                'errors'        =>  [
                    'required'  => 'Leave Code is required',
                    'is_unique' => 'This Leave Code already exist in our database'
                ]
            ],
            'leave_name'  =>  [
                'rules'         =>  'required|is_unique[leaves.leave_name,id,{leave_id}]',
                'errors'        =>  [
                    'required'  => 'Leave Name is required',
                    'is_unique' => 'This Leave already exist in our database.'
                ]
            ],
            'encash'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Encash is required',
                ]
            ],
            'allocation'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Allocation is required',
                ]
            ],
            'limit'  =>  [
                'rules'         =>  'required|integer',
                'errors'        =>  [
                    'integer'   => 'Limit must be an integer',
                    'required'  => 'Limit is required',
                ]
            ],
            'carry_forward'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Carry Forward is required',
                ]
            ],
        ];

        if (isset($_REQUEST['carry_forward']) && $_REQUEST['carry_forward'] == 'yes') {
            $rules['carry_forward_threshold'] =  [
                'rules'         =>  'required|integer|greater_than[0]',
                'errors'        =>  [
                    'required'  => 'Carry Forward Threshold is required (Only if Carry Forward is set to Yes)',
                    'integer'   => 'Carry Forward Threshold must be an integer (Only if Carry Forward is set to Yes)',
                    'greater_than'   => 'Carry Forward Threshold must be greater than 0 (Only if Carry Forward is set to Yes)',
                ]
            ];
        }

        $validation = $this->validate($rules);

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $leave_id   = $this->request->getPost('leave_id');
            $data = [
                'leave_code'                => $this->request->getPost('leave_code'),
                'leave_name'                => $this->request->getPost('leave_name'),
                'encash'                    => $this->request->getPost('encash'),
                'allocation'                => $this->request->getPost('allocation'),
                'limit'                     => $this->request->getPost('limit'),
                'carry_forward'             => $this->request->getPost('carry_forward'),
                'carry_forward_threshold'   => $this->request->getPost('carry_forward_threshold'),
            ];
            $LeaveModel = new LeaveModel();
            $query = $LeaveModel->update($leave_id, $data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Leave Updated Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }
}
