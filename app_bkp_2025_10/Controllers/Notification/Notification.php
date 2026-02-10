<?php

namespace App\Controllers\Notification;

use App\Models\NotificationModel;
use App\Controllers\BaseController;

class Notification extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'global_helper']);
        $this->session    = session();
    }

    public function index()
    {
        $current_user = $this->session->get('current_user');
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        // echo 'Notification System';

        $NotificationModel = new NotificationModel();
        $Notifications = $NotificationModel->where('target_employee_id =', $current_employee_id)->where('status =', 'unread')->findAll();
        if ($Notifications) {
            echo count($Notifications);
        }
    }
}
