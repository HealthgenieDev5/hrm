<?php
/*begin::Job Recruitment  Master*/

use App\Controllers\Recruitment\RecruitmentController;
use App\Controllers\Recruitment\JobListingCommentsController;

$routes->match(['get', 'post'], '/recruitment/job-listing', [RecruitmentController::class, 'index']);
$routes->match(['get', 'post'], '/recruitment/job-listing/store', [RecruitmentController::class, 'store']);
$routes->get('/recruitment/job-listing/all', [RecruitmentController::class, 'jobListView']);
$routes->match(['get', 'post'], '/recruitment/job-listing/ajax', [RecruitmentController::class, 'getJobListAjax']);
$routes->match(['get', 'post'], '/recruitment/job-listing/edit/(:num)', [RecruitmentController::class, 'edit/$1']);
$routes->get('/recruitment/job-listing/view/(:num)', [RecruitmentController::class, 'view/$1']);
$routes->match(['get', 'post'], '/recruitment/job-listing/update/(:num)', [RecruitmentController::class, 'update/$1']);
$routes->match(['get', 'post'], '/recruitment/job-listing/download-job-opening-pdf/(:num)',  [RecruitmentController::class, 'downloadJobOpeningPdf/$1']);
$routes->match(['get', 'post'], '/recruitment/job-listing/download-job-closure-pdf/(:num)',  [RecruitmentController::class, 'downloadJobClosurePdf/$1']);
$routes->get('/recruitment/job-listing/download-attachment/(:num)', [RecruitmentController::class, 'downloadAttachment/$1']);
$routes->get('/recruitment/job-listing/download-other-test/(:num)/(:num)', [RecruitmentController::class, 'downloadOtherTest/$1/$2']);
// $routes->get('/recruitment/job-listing/close/(:num)', [RecruitmentController::class, 'close/$1']);
// $routes->get('/recruitment/job-listing/reject/(:num)', [RecruitmentController::class, 'reject/$1']);
//$routes->post('/recruitment/job-listing/add-comment/(:num)', [JobListingCommentsController::class, 'addComment/$1']);
$routes->match(['get', 'post'], '/recruitment/job-listing/comments/add-comment/(:num)', [JobListingCommentsController::class, 'addComment/$1']);
$routes->get('/recruitment/job-listing/comments/get-comments/(:num)', [JobListingCommentsController::class, 'getComments/$1']);
$routes->get('/recruitment/job-listing/comments/get-notifications', [JobListingCommentsController::class, 'getNotifications']);
$routes->post('/recruitment/job-listing/comments/mark-as-read', [JobListingCommentsController::class, 'markAsRead']);
$routes->get('/recruitment/job-listing/comments/unread-count', [JobListingCommentsController::class, 'getUnreadCount']);
$routes->post('/recruitment/job-listing/approve', [RecruitmentController::class, 'approve']);
$routes->post('/recruitment/job-listing/update-remarks/', [RecruitmentController::class, 'updateRemarks']);
$routes->get('/recruitment/job-listing/pending-notifications', [RecruitmentController::class, 'getJobListingNotifications']);
$routes->post('/recruitment/job-listing/mark-as-read', [RecruitmentController::class, 'markJobListingAsRead']);
$routes->get('/recruitment/job-listing/get-shift-timings', [RecruitmentController::class, 'getShiftTimings']);

// Job Closure Routes
$routes->post('/recruitment/job-listing/initiate-closure', [RecruitmentController::class, 'initiateJobClosure']);
$routes->post('/recruitment/job-listing/finalize-closure', [RecruitmentController::class, 'finalizeJobClosure']);
$routes->get('/recruitment/job-listing/closure-details/(:num)', [RecruitmentController::class, 'getClosureDetails/$1']);

// Recruitment Task Assignment Routes
$routes->get('/recruitment/job-listing/tasks/hr-employees', [RecruitmentController::class, 'getHrEmployees']);
$routes->post('/recruitment/job-listing/tasks/assign', [RecruitmentController::class, 'assignTask']);
$routes->get('/recruitment/job-listing/tasks/(:num)', [RecruitmentController::class, 'getJobTasks/$1']);
$routes->post('/recruitment/job-listing/tasks/update-status', [RecruitmentController::class, 'updateTaskStatus']);
$routes->post('/recruitment/job-listing/tasks/reassign', [RecruitmentController::class, 'reassignTask']);
$routes->post('/recruitment/job-listing/tasks/edit', [RecruitmentController::class, 'editTask']);
$routes->get('/recruitment/job-listing/tasks/revisions/(:num)', [RecruitmentController::class, 'getTaskRevisions/$1']);

// Task Dashboard Routes
$routes->get('/recruitment/task-dashboard', [RecruitmentController::class, 'taskDashboard']);
$routes->get('/recruitment/task-dashboard/tasks', [RecruitmentController::class, 'getDashboardTasks']);
$routes->get('/recruitment/task-dashboard/job-listings', [RecruitmentController::class, 'getApprovedJobListings']);

/*end::Job Recruitment Master*/