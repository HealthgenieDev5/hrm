<?php

/*begin::Profile*/

use App\Controllers\Cron\ServerCron;
use App\Controllers\Master\Employee\Edit as EmployeeEdit;
use App\Controllers\Notification\Notification;
use App\Controllers\User\Profile;

$routes->get('/', [Profile::class, 'index']);

$routes->get('/profile', [Profile::class, 'index']);

$routes->get('/profile/edit', [Profile::class, 'editProfile']);
$routes->match(['get', 'post'], '/ajax/hr/employee/send-absent-without-leave-notification', [ServerCron::class, 'sendAbsentWithoutLeaveNotification']);

$routes->match(['get', 'post'], '/ajax/hr/employee/send-absent-without-leave-notification-heuer-only', [ServerCron::class, 'sendAbsentWithoutLeaveNotificationHeuerOnly']);

$routes->match(['get', 'post'], '/cron/checkEmployeeAbseanceWithoutApplingLeave', [ServerCron::class, 'checkEmployeeAbseanceWithoutApplingLeave']);

$routes->match(['get', 'post'], '/ajax/profile/get-punching-reports', [Profile::class, 'getPunchingReports']);
$routes->match(['get', 'post'], '/ajax/profile/get-leave-reports', [Profile::class, 'getLeaveReports']);
$routes->match(['get', 'post'], '/ajax/profile/get-od-reports-pending', [Profile::class, 'getOdReportsPending']);
$routes->match(['get', 'post'], '/ajax/profile/get-od-reports-approved', [Profile::class, 'getOdReportsApproved']);

$routes->match(['get', 'post'], '/ajax/profile/get-leave-balance-on-profile-page', [Profile::class, 'getLeaveBalanceOnProfilePage']);
$routes->match(['get', 'post'], '/ajax/profile/get-leave-balance-of-next-month-on-profile-page', [Profile::class, 'getLeaveBalanceOfNextMonthOnProfilePage']);

/*begin::Notification*/
$routes->match(['get', 'post'], '/backend/reports/notification/main', [Notification::class, 'index']);
/*end::Notification*/



$routes->match(['get', 'post'], '/ajax/profile/get-probation-employees', [Profile::class, 'getProbationCompletedEmployees']);
$routes->get('/backend/master/employee/probation-confirmation-letter/(:num)', [Profile::class, 'getProbationConfirmationLetter/$1']);

$routes->match(['get', 'post'], '/backend/master/employee/save-probation-response-of-hod', [Profile::class, 'saveProbationResponseOfHod']);

// HR Manager probation confirmation routes
$routes->match(['get', 'post'], '/ajax/probation/hr-confirmations', [Profile::class, 'getHrProbationConfirmations']);
$routes->post('/ajax/probation/hr-action', [Profile::class, 'handleHrProbationAction']);

$routes->match(['get', 'post'], '/ajax/profile/get-welcome-email-waiting', [Profile::class, 'getEmployeesWaitingforWelcome']);
$routes->get('/ajax/profile/get-upcoming-birthdays', [Profile::class, 'getUpcomingBirthdays']);

/*begin::Employee anniversary*/
$routes->match(['get', 'post'], '/ajax/hr/employee/get-one-year-anniversary-employees', [EmployeeEdit::class, 'getOneYearAnniversaryEmployees']);
/*end::Employee anniversary*/
$routes->match(['get', 'post'], '/ajax/profile/probation-completed-notification', [Profile::class, 'ProbationCompletedNotificationModel']);
$routes->match(['get', 'post'], '/ajax/profile/acknowledge-probation', [Profile::class, 'acknowledgeProbation']);

// $routes->get('/ajax/profile/get-balance-grace', [Profile::class, 'getBalanceGrace']);
$routes->get('/ajax/profile/get-attendance-stats', [Profile::class, 'getAttendanceStats']);
$routes->match(['get', 'post'], '/ajax/profile/get-holidays-on-profile-page', [Profile::class, 'getHolidaysOnProfilePage']);
