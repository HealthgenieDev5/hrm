<?php

namespace App\Controllers\Additional;

use App\Controllers\BaseController;

class Unauthorised extends BaseController
{

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
    }
    public function index()
    {
        $data = [
            'page_title'            => 'Unauthorised Access',
        ];
        return view('User/Unauthorised', $data);
    }
}
