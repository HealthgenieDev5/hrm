<?php

namespace App\Controllers\Override;

use App\Models\UserModel;
use App\Models\CustomModel;
use App\Controllers\BaseController;

class PasswordResetStatic extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }
    public function index()
    {

        #if( $this->session->get('current_user')['employee_id'] !== '52' ){
        $current_user_employee_id = $this->session->get('current_user')['employee_id'];
        if (!in_array($current_user_employee_id, ['52', '40', '99', '95'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $CustomModel = new CustomModel();
        $CustomSql = "select 
        u.id as user_id_in_users_table,
        e.id as id, 
        e.first_name as first_name, 
        e.last_name as last_name, 
        d.department_name, 
        c.company_name 
        from employees e 
        left join departments d on d.id = e.department_id 
        left join companies c on c.id = e.company_id 
        left join users u on u.employee_id = e.id
        where e.id != '40' and e.id != '1' and e.work_email = '' and e.personal_email = '' 
        order by e.first_name
        ";
        $employees = $CustomModel->CustomQuery($CustomSql)->getResultArray();





        $current_user = $this->session->get('current_user');
        $data = [
            'page_title'            => 'Password Reset Static',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'             => $employees,
        ];
        return view('Master/PasswordResetStatic', $data);
    }

    public function resetPassword()
    {
        $response_array = array();

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'employee_id'  =>  [
                    'rules'         =>  'required|is_not_unique[users.employee_id]',
                    'errors'        =>  [
                        'required'  => 'Emloyee ID is required',
                        'is_not_unique' => 'This Emloyee ID does not exist in our database Please contact administrator'
                    ]
                ],
                'newPassword'  =>  [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'This field is required'
                    ]
                ]
            ];

            $validation = $this->validate($rules);

            if (!$validation) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
                $errors = $this->validator->getErrors();
                $response_array['response_data']['validation'] = $errors;
            } else {
                $employee_id = $this->request->getPost('employee_id');
                $UserModel = new UserModel();
                $userrow = $UserModel->where('employee_id =', $employee_id)->first();
                $user_id = $userrow['id'] ?? false;
                if (!$user_id) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error Employee ID not found.';
                    return $this->response->setJSON($response_array);
                }
                $data = [
                    'password' =>  md5($this->request->getPost('newPassword')),
                ];
                $UserModel = new UserModel();
                $PasswordUpdateQuery = $UserModel->update($user_id, $data);

                if (!$PasswordUpdateQuery) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error Failed to update password <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Password Updated Successfully';
                }
            }
        } else {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, This form only accept Post Method';
        }
        return $this->response->setJSON($response_array);
    }
}
