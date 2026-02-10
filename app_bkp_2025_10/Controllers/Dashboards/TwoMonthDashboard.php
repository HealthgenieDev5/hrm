<?php

namespace App\Controllers\Dashboards;

use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;

class TwoMonthDashboard extends BaseController
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
        $current_user = $this->session->get('current_user');
        $current_employee_id = $this->session->get('current_user')['employee_id'];

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title'        => 'Dashboard - Historical',
            'current_controller'    => $this->request->getUri()->getSegment(1),
            'Companies'         =>  $Companies,
            'company_id_for_filter' =>  $current_company['company_id'],
        ];
        return view('Dashboards/TwoMonthDashboard', $data);
    }
}
