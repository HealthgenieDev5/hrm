<?php

namespace App\Controllers\Auth;

use App\Libraries\Hash;
use App\Models\UserModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Models\PasswordResetTokenModel;

class Auth extends BaseController
{
    public $session;

    public function __construct()
    {
        $this->session    = session();
        helper(['url', 'form', 'Form_helper', 'global_helper', 'Config_defaults_helper']);
    }
    public function login()
    {
        $data = [];
        if ($this->session->has('current_user')) {
            return redirect()->back();
        }
        return view('Auth/Login', $data);
    }

    public function loginValidate()
    {
        // print_r($_REQUEST);
        // die();



        $validation = $this->validate(
            [
                'username' =>  [
                    'rules' =>  'required',
                    'errors' =>  [
                        'required' => 'This field is required',
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[200]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must have atleast 5 charecters in length',
                        'max_length' => 'Password must not have more than 200 charecters in length',
                    ]
                ]
            ]
        );

        if (!$validation) {
            $this->session->setFlashdata('fail', 'Sorry, looks like there are some errors,<br>Click ok to view errors');
            return view('Auth/Login', ['validation' => $this->validator]);
        } else {
            $username           = $this->request->getPost('username');
            $password           = $this->request->getPost('password');
            $UserModel          = new UserModel();
            $current_user_data  = $UserModel->where('username', $username)->first();



            if (empty($current_user_data)) {
                $getEmployeeByEmail = $this->getEmployeeByEmail($username);
                if ($getEmployeeByEmail) {
                    $UserModel          = new UserModel();
                    $current_user_data  = $UserModel->where('employee_id', $getEmployeeByEmail['id'])->first();
                }
            }

            if (empty($current_user_data)) {
                $this->session->setFlashdata('fail', 'Sorry, looks like there are some errors,<br>Click ok to view errors');
                $this->validator->setError('username', 'The UserName or Email Id you entered does not exist in our database');
                return view('Auth/Login', ['validation' => $this->validator]);
            }

            $check_password = Hash::check($password, $current_user_data['password']);

            if (!$check_password) {
                $this->session->setFlashdata('fail', 'Incorrect Password...');
                return redirect()->to(base_url('login'))->withInput();
            } elseif ($current_user_data['status'] !== 'active') {
                $this->session->setFlashdata('fail', 'Sorry, your credentials are no longer active');
                return redirect()->to(base_url('login'))->withInput();
            } else {
                $current_user = [
                    'user_id'       => $current_user_data['id'],
                    'employee_id'   => $current_user_data['employee_id'],
                    'status'        => $current_user_data['status'],
                ];
                $EmployeeModel = new EmployeeModel();
                $current_employee_data = $EmployeeModel->find($current_user_data['employee_id']);
                foreach ($current_employee_data as $key => $val) {
                    $current_user[$key] = $val;
                }
                $current_user['name'] = trim($current_employee_data['first_name'] . ' ' . $current_employee_data['last_name']);
                $current_user['role'] = $current_user_data['role'];

                $this->session->set('current_user', $current_user);
                return redirect()->to(base_url());
            }
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('login'));
    }

    public function signup()
    {
        if ($this->session->has('current_user')) {
            return redirect()->back();
        }
        return view('Auth/SignUp');
    }

    public function passwordReset()
    {
        if ($this->session->has('current_user')) {
            return redirect()->back();
        }

        // print_r( $this->getEmployeeByEmail('odoo2@gstc.com') );
        // die();
        return view('Auth/PasswordReset');
    }

    public function passwordResetValidateEmail()
    {

        $response_array = array();
        $validation = $this->validate(
            [
                'your_email'  =>  [
                    'rules'         =>  'required|valid_email',
                    'errors'        =>  [
                        'required'  => 'Email id is required',
                        'valid_email'  => 'Email id is not valid',
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
            // $your_email = $this->request->getPost('your_email');
            $your_email = $this->request->getVar('your_email');
            $existingUser = $this->getEmployeeByEmail($your_email);
            if (!$existingUser) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
                $this->validator->setError('your_email', 'The email id you entered does not exist in our database');
                $errors = $this->validator->getErrors();
                $response_array['response_data']['validation'] = $errors;
                return $this->response->setJSON($response_array);
                die();
            }

            $tokenData = [
                'employee_id' => $existingUser['id'],
                'token' => $this->generateToken(),
                'used' => 'no',
            ];


            ###email
            $email = \Config\Services::email();
            $email->setFrom('app.hrm@healthgenie.in', 'HRM');
            $toArray = ['developer3@healthgenie.in'];
            if (isset($existingUser['personal_email']) && !empty($existingUser['personal_email'])) {
                $toArray[] = $existingUser['personal_email'];
            }
            if (isset($existingUser['work_email']) && !empty($existingUser['work_email'])) {
                $toArray[] = $existingUser['work_email'];
            }
            $email->setTo($toArray);
            $email->setSubject('Password Reset Request - HRM');
            ob_start();
            #cGFzc3dvcmQtcmVzZXQtYWN0aW9uLXRva2Vu is the key to get actual token, like this $_GET['cGFzc3dvcmQtcmVzZXQtYWN0aW9uLXRva2Vu'];
?>
            <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                    <tbody>
                        <tr>
                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                <a href="<?= base_url('public') ?>" rel="noopener" target="_blank">
                                    <img alt="Logo" src="<?= base_url('public') . "/assets/media/logos/logo-healthgenie.png" ?>" />
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="center">
                                <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                    <div style="padding-bottom: 10px">Hello,</div>
                                    <div style="padding-bottom: 10px">We received a request to reset your password. Click the link below to reset your password:</div>
                                    <div style="padding-bottom: 10px">
                                        <a href="<?= base_url('reset-password?cGFzc3dvcmQtcmVzZXQtYWN0aW9uLXRva2Vu=' . $tokenData["token"]) ?>" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem;background-color:#009EF7;" target="_blank">Reset Password</a>
                                    </div>
                                    <div style="padding-bottom: 10px">If you did not request this change, please ignore this email.</div>

                                    <div style="padding-bottom: 10px">Kind regards,
                                        <br>HRM Team.
                        <tr>
                            <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                                <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                                <p>Copyright ©
                                    <a href="<?= base_url('public') ?>" rel="noopener" target="_blank">Healthgenie/Gstc</a>.
                                </p>
                            </td>
                        </tr></br>
            </div>
            </div>
            </td>
            </tr>
            </tbody>
            </table>
            </div>
<?php
            $emailContent = ob_get_clean();
            $email->setMessage($emailContent);
            ###email

            if ($email->send()) {
                $PasswordResetTokenModel = new PasswordResetTokenModel();
                $getExistingToken = $PasswordResetTokenModel->where('employee_id =', $existingUser['id'])->first();

                $isTokenSaved = false;
                if (!empty($getExistingToken)) {
                    $tokenData['id'] = $getExistingToken['id'];
                    $PasswordResetTokenModel = new PasswordResetTokenModel();
                    $isTokenSaved = $PasswordResetTokenModel->save($tokenData);
                } else {
                    $PasswordResetTokenModel = new PasswordResetTokenModel();
                    $isTokenSaved = $PasswordResetTokenModel->save($tokenData);
                }

                if ($isTokenSaved) {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Token has been sent to your work email and personal email id, The token is valid for 5 minutes only';
                } else {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'Error sending token to you';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Error sending password reset link to your email';
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function resetPassword()
    {
        if ($this->session->has('current_user')) {
            return redirect()->back();
        }
        $data = [];
        $token = $this->request->getVar('cGFzc3dvcmQtcmVzZXQtYWN0aW9uLXRva2Vu');
        if (!empty($token)) {
            $token = htmlentities($token);

            $PasswordResetTokenModel = new PasswordResetTokenModel();
            $existingToken = $PasswordResetTokenModel->where('token=', $token)->first();
            if (!empty($existingToken)) {
                if ($existingToken['used'] == 'no') {
                    $currentDate = date('Y-m-d H:i:s');
                    // $expiryDate = date('Y-m-d H:i:s', strtotime($existingToken['date_time'] . ' +5 minutes'));
                    $expiryDate = date('Y-m-d H:i:s', strtotime($existingToken['date_time'] . ' +59 minutes'));
                    if (strtotime($currentDate) > strtotime($existingToken['date_time']) && strtotime($currentDate) < strtotime($expiryDate)) {
                        $password_reset_session = $this->generateToken(20);
                        $this->session->set('password_reset_session', $password_reset_session);
                        $data['success_message'] = 'Please set new password';
                        $data['token'] = $token;
                    } else {
                        $data['error_message'] = 'The url is expired';
                    }
                } else {
                    $data['error_message'] = 'The url is no longer valid';
                }
            } else {
                $data['error_message'] = 'The url is invalid or expired';
            }
        } else {
            $data['error_message'] = 'The url is invalid or expired';
        }
        return view('Auth/ResetPassword', $data);
    }

    public function newPassword()
    {
        if ($this->session->has('current_user')) {
            return redirect()->back();
        }

        $response_array = array();
        $validation = $this->validate(
            [
                'password'  =>  [
                    'rules'         =>  'required|min_length[8]',
                    'errors'        =>  [
                        'required'  => 'Password is required',
                        'min_length'  => 'Minimum length should be 8',
                    ]
                ],
                'password_confirmation'  =>  [
                    'rules'         =>  'matches[password]',
                    'errors'        =>  [
                        'matches'  => 'Password did not match',
                    ]
                ],
                'token'  =>  [
                    'rules'         =>  'required|min_length[100]',
                    'errors'        =>  [
                        'required'  => 'Password is required',
                        'min_length'  => 'Invalid Token',
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
            $token = $this->request->getPost('token');
            $newPassword = md5($this->request->getPost('password'));

            $PasswordResetTokenModel = new PasswordResetTokenModel();
            $existingToken = $PasswordResetTokenModel->where('token=', $token)->where('used=', 'no')->first();

            if (!empty($existingToken)) {
                $existingToken['used'] = 'yes';
                $PasswordResetTokenModel->save($existingToken);

                $UserModel          = new UserModel();
                $existingUser = $UserModel->where('employee_id=', $existingToken['employee_id'])->first();
                if (!empty($existingUser)) {
                    $passwordUpdateQuery  = $UserModel->update($existingUser['id'], ['password' => $newPassword]);
                    if ($passwordUpdateQuery) {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Your password is updated.';
                    } else {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'There was an error while updating your password';
                    }
                } else {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'Invalid User';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Invalid or expired token.';
            }

            // if( !$this->session->has('password_reset_session') ){
            //     return redirect()->back();
            // }

        }

        return $this->response->setJSON($response_array);
    }

    public function getEmployeeByEmail($email)
    {
        // return $email;
        if (!empty($email)) {
            $EmployeeModel = new EmployeeModel();
            $employeeData = $EmployeeModel->where('personal_email=', $email)->orWhere('work_email=', $email)->first();
            return !empty($employeeData) ? $employeeData : false;
            // return $EmployeeModel->getLastQuery()->getQuery();
        }
        return false;
    }

    public function generateToken($length = 100)
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes($length / 2);
        } else {
            $bytes = openssl_random_pseudo_bytes($length / 2);
        }
        $randomString = bin2hex($bytes);
        return $randomString;
    }


    public function ExEmployee()
    {
        $data = [];
        return view('Auth/ExEmployee', $data);
    }

    public function ExEmployee__Validate()
    {
        $validation = $this->validate(
            [
                'username' =>  [
                    'rules' =>  'required',
                    'errors' =>  [
                        'required' => 'This field is required',
                    ]
                ]
            ]
        );

        if (!$validation) {
            $this->session->setFlashdata('fail', 'Sorry, looks like there are some errors,<br>Click ok to view errors');
            // return redirect()->to(base_url('ex-employee'))->withInput();
            return redirect()->back()->withInput()->with('validation', $this->validator);
        } else {
            $input = $this->request->getPost('username');

            $EmployeeModel = new EmployeeModel();
            $EmployeeModel
                ->select('employees.*')
                // ->where('employees.status', 'left')
                // ->groupStart()
                ->where('employees.personal_mobile=', $input)
                ->orWhere('JSON_EXTRACT(attachment, "$.adhar.number") =', $input);
            // ->groupEnd();
            $ExEmployee = $EmployeeModel->first();
            if (!empty($ExEmployee)) {
                $ExEmployeeName = trim($ExEmployee['first_name'] . " " . $ExEmployee['last_name']);
                if ($ExEmployee['status'] != 'left') {
                    $this->session->setFlashdata('fail', 'Sorry ' . $ExEmployeeName . ',<br>you are still working with the organization, therefore you are not authorised to view this page.');
                    return redirect()->back()->withInput();
                } else {
                    $ExEmployeeMobile = $ExEmployee['personal_mobile'];
                    #SendOTP
                    $otp = $this->generate_otp($ExEmployeeMobile);

                    if ("success" == $this->sendOTP($otp, $ExEmployeeMobile)) {
                        // return view('Auth/ExEmployeeStep2', []);
                        return redirect()->to(base_url('ex-employee/validate-otp'));
                    } else {
                        $this->session->setFlashdata('fail', 'There was an error while sending verification code to your mobile number');
                        return redirect()->back()->withInput();
                    }

                    // echo '<pre>';
                    // print_r($ExEmployee);
                    // echo '</pre>';
                }
            } else {
                $this->session->setFlashdata('fail', 'Sorry, Mobile number or Adhar Number was not found');
                return redirect()->back()->withInput();
            }
        }
    }

    private function generate_otp($mobile_number)
    {
        // $this->session->set('current_user', $current_user);
        $otp = rand(100000, 999999);
        $otp_expiration = time() + 3000;
        $otpArray = ['otp_mobile' => $mobile_number, 'otp_value' => $otp, 'otp_expiration' => $otp_expiration];
        $this->session->set('otp', $otpArray);
        return $otp;
    }

    private function sendOTP($otp, $mobile_number)
    {
        $otp_content =    $otp . " is your OTP for your mobile number verification on Healthgenie.in";
        $url = "http://sms6.rmlconnect.net/bulksms/bulksms";
        $postData = "username=healthgenie&password=hjd6hjks&type=0&dlr=1&destination=" . $mobile_number . "&source=HLTHGN&message=" . urlencode($otp_content) . "&entityid=1201159083846376389&tempid=1007165397626214742";
        $ch = curl_init();
        curl_setopt_array($ch, array(CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $postData));
        $output = curl_exec($ch);
        curl_close($ch);
        // return $output;
        if (!empty($output) && $output != '1702') {
            return "success";
        } else {
            return "failed";
        }
    }

    public function ExEmployee__Step2()
    {
        return view('Auth/ExEmployeeStep2', []);
    }

    public function ExEmployee__Validate_OTP()
    {
        $validation = $this->validate(
            [
                'otp' =>  [
                    'rules' =>  'required|min_length[6]',
                    'errors' =>  [
                        'required' => 'OTP is required',
                        'min_length' => 'Minimum 6 digits is required',
                    ]
                ]
            ]
        );

        if (!$validation) {
            $this->session->setFlashdata('fail', 'Please enter 6 digits verification code sent to your mobile number');
            return redirect()->back();
        } else {
            $otp = (int) $this->request->getPost('otp');
            $mobile_number = $this->request->getPost('mobile_number');
            $SessionOTP = $this->session->get('otp');

            // echo '<pre>';
            // var_dump($SessionOTP);
            // echo '</pre>';
            // die();

            if ($mobile_number !== $SessionOTP['otp_mobile']) {
                $this->session->setFlashdata('fail', 'Mobile number was changed during transaction');
                return redirect()->back();
            }
            if ($otp !== $SessionOTP['otp_value']) {
                $this->session->setFlashdata('fail', 'Please enter correct 6 digits verification code sent to your mobile number');
                return redirect()->back();
            }
            $this->session->set('ex_employee_mobile', $SessionOTP['otp_mobile']);
            $this->session->remove('otp');

            return redirect()->to(base_url('ex-employee/relieving-documents'));
        }
    }

    public function ExEmployee__RelievingDocuments()
    {

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel
            ->select('employees.*')
            ->where('employees.personal_mobile=', $this->session->get('ex_employee_mobile'));
        $ExEmployee = $EmployeeModel->first();
        $ExEmployee['name'] = trim($ExEmployee['first_name'] . ' ' . $ExEmployee['last_name']);
        $data = [
            'page_title' => 'Relieving Documents',
            'ExEmployee' => $ExEmployee
        ];

        return view('Auth/ExEmployee__RelievingDocuments', $data);
    }
}
