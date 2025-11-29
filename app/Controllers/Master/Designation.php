<?php

namespace App\Controllers\Master;

use App\Models\DesignationModel;
use App\Controllers\BaseController;

class Designation extends BaseController
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

        $model = new DesignationModel();
        $data = [
            'page_title'            => 'Designation Master',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
        ];
        return view('Master/DesignationMaster', $data);
    }

    public function getAllDesignations()
    {
        #this is database details
        $dbDetails = array(
            "host" => $this->db->hostname,
            "user" => $this->db->username,
            "pass" => $this->db->password,
            "db" => $this->db->database,
        );

        $table = "designations";

        #primary key
        $primaryKey = "id";
        $columns = array(
            array("db" => "id", "dt" => 0),
            array("db" => "designation_name", "dt" => 1),
            array("db" => "date_time", "dt" => 2),
            array(
                "db" => "id",
                "dt" => 3,
                "formatter" => function ($d, $row) {
                    return '<div class="d-flex justify-content-center">
                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-designation" data-id="' . $row['id'] . '">
                                    <span class="svg-icon svg-icon-3">
                                        <i class="fa fa-pencil-alt" aria-hidden="true" ></i>
                                    </span>
                                </a>
                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-designation" data-id="' . $row['id'] . '">
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

    public function addDesignation()
    {
        // print_r($_REQUEST);
        // die();
        $response_array = array();
        $validation = $this->validate(
            [
                'designation_name'  =>  [
                    'rules'         =>  'required|is_unique[designations.designation_name]',
                    'errors'        =>  [
                        'required'  => 'Designation Name is required',
                        'is_unique' => 'This Designation is already registered'
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
                'designation_name'  => $this->request->getPost('designation_name'),
            ];
            // return $this->response->setJSON($values);
            $DesignationModel = new DesignationModel();
            $query = $DesignationModel->insert($values);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Designation Added Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function deleteDesignation()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'designation_id'  =>  [
                    'rules'         =>  'required|is_not_unique[designations.id]',
                    'errors'        =>  [
                        'required'  => 'Designation ID is required',
                        'is_not_unique' => 'This Designation is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('designation_id');
        } else {
            $designation_id   = $this->request->getPost('designation_id');
            $DesignationModel = new DesignationModel();
            $query = $DesignationModel->delete($designation_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Designation Deleted Successfully';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getDesignation()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'designation_id'  =>  [
                    'rules'         =>  'required|is_not_unique[designations.id]',
                    'errors'        =>  [
                        'required'  => 'Designation ID is required',
                        'is_not_unique' => 'This Designation is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('designation_id');
        } else {
            $designation_id   = $this->request->getPost('designation_id');
            $DesignationModel = new DesignationModel();
            $query = $DesignationModel->find($designation_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Designation Found';
                $response_array['response_data']['designation'] = $query;
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function updateDesignation()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'designation_id'  =>  [
                    'rules'         =>  'required|is_not_unique[designations.id]',
                    'errors'        =>  [
                        'required'  => 'Designation id is required',
                        'is_not_unique' => 'This Designation does not exist in our database anymore. Please contact Administrator'
                    ]
                ],
                'designation_name'  =>  [
                    'rules'         =>  'required|is_unique[designations.designation_name,id,{designation_id}]',
                    'errors'        =>  [
                        'required'  => 'Designation Name is required',
                        'is_unique' => 'This Designation already exist in our database.'
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
            $designation_id   = $this->request->getPost('designation_id');
            $data = [
                'designation_name'  => $this->request->getPost('designation_name'),
            ];
            $DesignationModel = new DesignationModel();
            $query = $DesignationModel->update($designation_id, $data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Designation Updated Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }
}
