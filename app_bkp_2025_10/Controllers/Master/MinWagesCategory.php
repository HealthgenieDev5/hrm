<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\MinWagesCategoryModel;
use App\Models\MinWagesCategoryRevisionModel;

class MinWagesCategory extends BaseController
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

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $current_user = $this->session->get('current_user');
        $data = [
            'page_title'            => 'Minimum Wages Category Master',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
        ];
        return view('Master/MinimumWagesMaster', $data);
    }

    public function getAllMinWagesCategory($only_data = false)
    {

        $MinWagesCategoryModel = new MinWagesCategoryModel();
        $minimum_wages = $MinWagesCategoryModel->where('minimum_wages_category_status =', 'active')->findAll();
        if ($only_data == true) {
            return $minimum_wages;
        }
        $return_data =  [
            'data' => $minimum_wages,
        ];
        return $this->response->setJSON($return_data);
    }

    public function getMinWagesCategory()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'minimum_wages_category_id'  =>  [
                    'rules'         =>  'required|is_not_unique[minimum_wages_categories.id]',
                    'errors'        =>  [
                        'required'  => 'ID is required',
                        'is_not_unique' => 'This Minimum Wages Category does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('minimum_wages_category_id');
        } else {
            $minimum_wages_category_id   = $this->request->getPost('minimum_wages_category_id');
            $MinWagesCategoryModel = new MinWagesCategoryModel();
            $query = $MinWagesCategoryModel->find($minimum_wages_category_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Minimum Wages Category Found';
                $response_array['response_data']['minimum_wages_category'] = $query;
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function updateMinWagesCategory()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'minimum_wages_category_id'  =>  [
                    'rules'         =>  'required|is_not_unique[minimum_wages_categories.id]',
                    'errors'        =>  [
                        'required'  => 'Id is required',
                        'is_not_unique' => 'This Minimum Wages Category does not exist in our database'
                    ]
                ],
                'minimum_wages_category_name'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Name is required'
                    ]
                ],
                'minimum_wages_category_state'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'State name is required'
                    ]
                ],
                'minimum_wages_category_value'  =>  [
                    'rules'         =>  'required|integer',
                    'errors'        =>  [
                        'required'  => 'Company Short Name is required',
                        'integer'   => 'only integer is accepted'
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
            $minimum_wages_category_id   = $this->request->getPost('minimum_wages_category_id');
            $MinWagesCategoryModel = new MinWagesCategoryModel();
            $oldData = $MinWagesCategoryModel->find($minimum_wages_category_id);
            $oldData['minimum_wages_category_id'] = $oldData['id'];
            unset($oldData['id']);
            $oldData['revised_by'] = $this->session->get('current_user')['employee_id'];

            $MinWagesCategoryRevisionModel = new MinWagesCategoryRevisionModel();
            $revisionQuery = $MinWagesCategoryRevisionModel->insert($oldData);
            if ($revisionQuery) {
                $data = [
                    'minimum_wages_category_name' => ucwords(ucwords($this->request->getPost('minimum_wages_category_name'), '-')),
                    'minimum_wages_category_state' => ucwords($this->request->getPost('minimum_wages_category_state')),
                    'minimum_wages_category_value' => $this->request->getPost('minimum_wages_category_value'),
                ];
                $MinWagesCategoryModel = new MinWagesCategoryModel();
                $query = $MinWagesCategoryModel->update($minimum_wages_category_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Minimum Wage entry Updated Successfully';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Revision did not saved, Kindly contact developer' . json_encode($MinWagesCategoryRevisionModel->error());
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function addMinWagesCategory()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'minimum_wages_category_name'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Name is required'
                    ]
                ],
                'minimum_wages_category_state'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'State name is required'
                    ]
                ],
                'minimum_wages_category_value'  =>  [
                    'rules'         =>  'integer',
                    'errors'        =>  [
                        'integer'   => 'Must be Integer only'
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
            $values = [
                'minimum_wages_category_name'  => ucwords(ucwords($this->request->getPost('minimum_wages_category_name'), '-')),
                'minimum_wages_category_state'  => ucwords($this->request->getPost('minimum_wages_category_state')),
                'minimum_wages_category_value'  => $this->request->getPost('minimum_wages_category_value'),
                'created_by'  => $this->session->get('current_user')['employee_id'],
            ];
            $MinWagesCategoryModel = new MinWagesCategoryModel();
            $query = $MinWagesCategoryModel->insert($values);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Minimum Wages Category Added Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function deleteMinWagesCategory()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'minimum_wages_category_id'  =>  [
                    'rules'         =>  'required|is_not_unique[minimum_wages_categories.id]',
                    'errors'        =>  [
                        'required'  => 'Id is required',
                        'is_not_unique' => 'This Minimum Wages Category does not exist in our database'
                    ]
                ],
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('company_id');
        } else {
            $minimum_wages_category_id   = $this->request->getPost('minimum_wages_category_id');
            $MinWagesCategoryModel = new MinWagesCategoryModel();
            $oldData = $MinWagesCategoryModel->find($minimum_wages_category_id);
            $oldData['minimum_wages_category_id'] = $oldData['id'];
            unset($oldData['id']);
            $oldData['revised_by'] = $this->session->get('current_user')['employee_id'];

            $MinWagesCategoryRevisionModel = new MinWagesCategoryRevisionModel();
            $revisionQuery = $MinWagesCategoryRevisionModel->insert($oldData);
            if ($revisionQuery) {
                $data = [
                    'minimum_wages_category_status' => 'inactive',
                ];
                $MinWagesCategoryModel = new MinWagesCategoryModel();
                $query = $MinWagesCategoryModel->update($minimum_wages_category_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Minimum Wage entry Deleted Successfully';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Revision did not saved, Kindly contact developer' . json_encode($MinWagesCategoryRevisionModel->error());
            }
        }
        return $this->response->setJSON($response_array);
    }
}
