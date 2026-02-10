<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        // return view('welcome_message');
        // echo 'Hello User';
        $model = new UserModel();
        print_r($model->findAll());
    }
}
