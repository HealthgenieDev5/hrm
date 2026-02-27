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

// Job Closure Routes
$routes->post('/recruitment/job-listing/initiate-closure', [RecruitmentController::class, 'initiateJobClosure']);
$routes->post('/recruitment/job-listing/finalize-closure', [RecruitmentController::class, 'finalizeJobClosure']);
$routes->get('/recruitment/job-listing/closure-details/(:num)', [RecruitmentController::class, 'getClosureDetails/$1']);


/*end::Job Recruitment Master*/