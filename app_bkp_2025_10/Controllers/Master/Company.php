<?php

namespace App\Controllers\Master;

use App\Models\CompanyModel;
use App\Controllers\BaseController;

class Company extends BaseController
{
    public $session;
    public $uri;
    public $db;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'global_helper', 'Config_defaults_helper']);
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
        $model = new CompanyModel();
        $data = [
            'page_title'            => 'Company Master',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
        ];
        return view('Master/CompanyMaster', $data);
    }

    public function getAllCompanies()
    {

        $CompanyModel = new CompanyModel();
        $companies = $CompanyModel->getAllCompanies();
        $return_data =  [
            'data' => $companies,
        ];
        return $this->response->setJSON($return_data);
    }

    public function AddCompany()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'company_name'  =>  [
                    'rules'         =>  'required|is_unique[companies.company_name]',
                    'errors'        =>  [
                        'required'  => 'Company Name is required',
                        'is_unique' => 'This Company is already registered'
                    ]
                ],
                'company_short_name'  =>  [
                    'rules'         =>  'required|is_unique[companies.company_short_name]',
                    'errors'        =>  [
                        'required'  => 'Company Short Name is required',
                        'is_unique' => 'This Name is already taken'
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

            $company_name   = $this->request->getPost('company_name');
            $company_short_name   = $this->request->getPost('company_short_name');
            $company_hod   = $this->request->getPost('company_hod');
            $address        = $this->request->getPost('address');
            $city           = $this->request->getPost('city');
            $state          = $this->request->getPost('state');
            $pincode        = $this->request->getPost('pincode');
            $phone_number   = $this->request->getPost('phone_number');
            $contact_person_name   = $this->request->getPost('contact_person_name');
            $contact_person_mobile   = $this->request->getPost('contact_person_mobile');
            $contact_person_email_id   = $this->request->getPost('contact_person_email_id');
            $values = [
                'company_name'  => $company_name,
                'company_short_name'  => $company_short_name,
                'company_hod'  => $company_hod,
                'address'       => $address,
                'city'          => $city,
                'state'         => $state,
                'pincode'       => $pincode,
                'phone_number'  => $phone_number,
                'contact_person_name'  => $contact_person_name,
                'contact_person_mobile'  => $contact_person_mobile,
                'contact_person_email_id'  => $contact_person_email_id,
            ];
            #Logo
            if (!empty($this->request->getPost('logo_attachment_remove'))) {
                $values['logo_url'] = '';
            } else {
                $logo_attachment = $this->request->getFile('logo_attachment');
                if ($logo_attachment->isValid() && ! $logo_attachment->hasMoved()) {
                    $upload_folder = ROOTPATH . 'public/uploads/assets/media/company-logo';
                    $logo_uploaded = $logo_attachment->move($upload_folder);
                    if ($logo_uploaded) {
                        $values['logo_url'] = str_replace(ROOTPATH . "public/", "/", $upload_folder . '/' . $logo_attachment->getName());
                    }
                }
            }
            // return $this->response->setJSON($values);
            $CompanyModel = new CompanyModel();
            $query = $CompanyModel->insert($values);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Company Added Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function deleteCompany()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'company_id'  =>  [
                    'rules'         =>  'required|is_not_unique[companies.id]',
                    'errors'        =>  [
                        'required'  => 'Company ID is required',
                        'is_not_unique' => 'This Company is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('company_id');
        } else {
            $company_id   = $this->request->getPost('company_id');
            $CompanyModel = new CompanyModel();
            $query = $CompanyModel->delete($company_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Company Deleted Successfully';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getCompany()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'company_id'  =>  [
                    'rules'         =>  'required|is_not_unique[companies.id]',
                    'errors'        =>  [
                        'required'  => 'Company ID is required',
                        'is_not_unique' => 'This Company is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('company_id');
        } else {
            $company_id   = $this->request->getPost('company_id');
            $CompanyModel = new CompanyModel();
            $query = $CompanyModel->find($company_id);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Company Found';
                $response_array['response_data']['company'] = $query;
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function updateCompany()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'company_id'  =>  [
                    'rules'         =>  'required|is_not_unique[companies.id]',
                    'errors'        =>  [
                        'required'  => 'Company Id is required',
                        'is_not_unique' => 'This Company does not exist in our database'
                    ]
                ],
                'company_name'  =>  [
                    'rules'         =>  'required|is_unique[companies.company_name,id,{company_id}]',
                    'errors'        =>  [
                        'required'  => 'Company Name is required',
                        'is_unique' => 'This Company already exist in our database.'
                    ]
                ],
                'company_short_name'  =>  [
                    'rules'         =>  'required|is_unique[companies.company_short_name,id,{company_id}]',
                    'errors'        =>  [
                        'required'  => 'Company Short Name is required',
                        'is_unique' => 'This Name is already taken'
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
            $company_id   = $this->request->getPost('company_id');
            $data = [
                'company_name'              => $this->request->getPost('company_name'),
                'company_short_name'        => $this->request->getPost('company_short_name'),
                'company_hod'               => $this->request->getPost('company_hod'),
                'address'                   => $this->request->getPost('address'),
                'city'                      => $this->request->getPost('city'),
                'state'                     => $this->request->getPost('state'),
                'pincode'                   => $this->request->getPost('pincode'),
                'phone_number'              => $this->request->getPost('phone_number'),
                'contact_person_name'       => $this->request->getPost('contact_person_name'),
                'contact_person_mobile'     => $this->request->getPost('contact_person_mobile'),
                'contact_person_email_id'   => $this->request->getPost('contact_person_email_id'),
            ];

            #Logo
            if (!empty($this->request->getPost('logo_edit_attachment_remove'))) {
                $data['logo_url'] = '';
            } else {
                $logo_edit_attachment = $this->request->getFile('logo_edit_attachment');
                if ($logo_edit_attachment->isValid() && ! $logo_edit_attachment->hasMoved()) {
                    // $upload_folder = WRITEPATH . 'uploads/'.date('Y').'/'.date('m');
                    $upload_folder = ROOTPATH . 'public/uploads/assets/media/company-logo';

                    $logo_edit_uploaded = $logo_edit_attachment->move($upload_folder);
                    if ($logo_edit_uploaded) {
                        $data['logo_url'] = str_replace(ROOTPATH . "public/", "/", $upload_folder . '/' . $logo_edit_attachment->getName());
                    }
                }
            }

            $CompanyModel = new CompanyModel();
            $query = $CompanyModel->update($company_id, $data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Company Updated Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }
}
