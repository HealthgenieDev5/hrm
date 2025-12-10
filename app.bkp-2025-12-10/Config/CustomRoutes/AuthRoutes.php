<?php

/*begin::Authentication*/

use App\Controllers\Auth\Auth;

$routes->get('/login', [Auth::class, 'login']);
$routes->get('/logout', [Auth::class, 'logout']);
$routes->post('/login-validate', [Auth::class, 'loginValidate']);
$routes->get('/signup', [Auth::class, 'signup']);
$routes->get('/password-reset', [Auth::class, 'passwordReset']);
$routes->match(['get', 'post'], '/ajax/password-reset/validate-email', [Auth::class, 'passwordResetValidateEmail']);
$routes->get('/reset-password', [Auth::class, 'resetPassword']);
$routes->post('/ajax/password-reset/new-password', [Auth::class, 'newPassword']);


$routes->get('/ex-employee', [Auth::class, 'ExEmployee']);
$routes->post('/ex-employee', [Auth::class, 'ExEmployee__Validate']);
$routes->get('/ex-employee/validate-otp', [Auth::class, 'ExEmployee__Step2']);
$routes->post('/ex-employee/validate-otp', [Auth::class, 'ExEmployee__Validate_OTP']);
$routes->get('/ex-employee/relieving-documents', [Auth::class, 'ExEmployee__RelievingDocuments']);
/*end::Authentication*/