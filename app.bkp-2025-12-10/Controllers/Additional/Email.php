<?php

namespace App\Controllers\Additional;

use App\Controllers\BaseController;

class Email extends BaseController
{

	public $session;

	public function __construct()
	{
		helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
		$this->session    = session();
	}

	public function index()
	{

		$email = \Config\Services::email();
		$email->setFrom('app.hrm@healthgenie.in', 'HRM');
		$email->setReplyTo('payroll@healthgenie.in', 'HRM');
		$email->setTo('developer3@healthgenie.in');
		$email->setCC('developer@healthgenie.in');
		$email->setBCC('healthgeniedev3@gmail.com');
		$email->setSubject('Email Test');
		$email->setMessage('Testing the email class.');
		if ($email->send()) {
			echo "email sent";
		} else {
			$data = $email->printDebugger(['headers']);
			echo '<pre>';
			print_r($data);
			echo '</pre>';
		}
		// echo 'zdf sdfg sdfg';
	}
}
