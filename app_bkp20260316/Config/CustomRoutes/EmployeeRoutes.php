<?php

/*begin::Employee Master*/

use App\Controllers\Master\Employee\All as EmployeeMaster;
use App\Controllers\Master\Employee\Create as EmployeeAddNew;
use App\Controllers\Master\Employee\Edit as EmployeeEdit;
use App\Controllers\Pdf\AppointmentLetter;
use App\Controllers\Pdf\LoaLetter;
use App\Controllers\Pdf\TerminationLetter;
use App\Controllers\Pdf\LoyaltyIncentive;
use App\Controllers\Pdf\Ncl;
use App\Controllers\User\Profile;
use App\Controllers\Master\SpecialHoliday;

$routes->match(['get', 'post'], '/backend/master/employee', [EmployeeMaster::class, 'index']);
$routes->match(['get', 'post'], '/ajax/load-employees', [EmployeeMaster::class, 'getAllEmployees']);
$routes->match(['get', 'post'], '/backend/master/employee/add-new', [EmployeeAddNew::class, 'index']);
$routes->match(['get', 'post'], '/ajax/master/employee/add-new/validate', [EmployeeAddNew::class, 'store']);
$routes->match(['get', 'post'], '/ajax/get-department-by-company-id', [EmployeeAddNew::class, 'getDepartmentByCompanyId']);
$routes->match(['get', 'post'], '/ajax/get-reporting-managers-by-company-id', [EmployeeAddNew::class, 'getReportingManagersByCompanyId']);
$routes->match(['get', 'post'], '/backend/master/employee/edit/id/(:num)', [EmployeeEdit::class, 'index/$1']);
$routes->match(['get', 'post'], '/ajax/master/employee/edit/validate', [EmployeeEdit::class, 'update']);
$routes->match(['get', 'post'], '/ajax/master/employee/delete-employee', [EmployeeMaster::class, 'deleteEmployee']);
$routes->match(['get', 'post'], '/ajax/hr/employee/send-welcome-email', [EmployeeEdit::class, 'sendWelcomeEmail']);

$routes->match(['get', 'post'], '/backend/master/employee/bulk-update', [EmployeeMaster::class, 'BulkUpdate']);
$routes->match(['get', 'post'], '/backend/master/employee/bulk-update/save', [EmployeeMaster::class, 'bulkUpdateSave']);


$routes->get('/backend/master/employee/appintment-letter/(:num)', [AppointmentLetter::class, 'index/$1']);
$routes->get('/backend/master/employee/loa-letter/(:num)', [LoaLetter::class, 'index/$1']);
$routes->get('/backend/master/employee/loyalty-incentive-letter/(:num)', [LoyaltyIncentive::class, 'index/$1']);
$routes->get('/backend/master/employee/ncl-letter-gstc-category-a/(:num)', [Ncl::class, 'gstcCategoryA/$1']);
$routes->get('/backend/master/employee/ncl-letter-hgipl-category-b/(:num)', [Ncl::class, 'hgiplCategoryB/$1']);
$routes->get('/backend/master/employee/termination-letter/(:num)', [TerminationLetter::class, 'index/$1']);
$routes->get('/backend/master/employee/probation-extended-letter/(:num)', [Profile::class, 'getProbationExtendedLetter/$1']);
/*end::Employee Master*/


/*begin::Employee Master Customised*/

$routes->match(['get', 'post'], '/backend/master/employee/custom', [EmployeeMaster::class, 'custom']);
// $routes->match(['get', 'post'], '/ajax/load-employees', [EmployeeMaster::class, 'GetAllEmployees']);
/*end::Employee Master Customised*/


$routes->match(['get', 'post'], '/backend/hr/password-reset-static', 'PasswordResetStatic::index');
$routes->match(['get', 'post'], '/ajax/hr/employee/password-update', 'PasswordResetStatic::resetPassword');

// $routes->match(['get', 'post'], '/backend/hr/special-holiday', 'SpecialHoliday\SpecialHoliday::index');
// $routes->match(['get', 'post'], '/ajax/backend/hr/special-holiday/get-all-employees', 'SpecialHoliday\SpecialHoliday::getAllEmployees');
// $routes->match(['get', 'post'], '/ajax/backend/hr/special-holiday/update', 'SpecialHoliday\SpecialHoliday::updateSpecialHoliday');

$routes->match(['get', 'post'], '/backend/hr/special-holiday', [SpecialHoliday::class, 'index']);
// $routes->match(['get', 'post'], '/ajax/backend/hr/special-holiday/get-all-employees', [SpecialHoliday::class, 'getAllEmployees']);
$routes->match(['get', 'post'], '/ajax/backend/hr/special-holiday/update', [SpecialHoliday::class, 'updateSpecialHoliday']);
/*end::Employee Master*/