<?php
/*begin::AppraisalRoutes */

use App\Controllers\AppraisalsController;


// $routes->get('/backend/master/appraisals', [AppraisalsController::class, 'index']);
// $routes->get('/backend/master/appraisals/add/(:num)', [AppraisalsController::class, 'create/$1']);
// $routes->post('/ajax/master/appraisals/validate', [AppraisalsController::class, 'store']);

// $routes->get('/backend/master/appraisals/edit/(:num)', [AppraisalsController::class, 'edit/$1']);
// $routes->post('/ajax/master/appraisals/update/(:id)', [AppraisalsController::class, 'update/$1']);

// $routes->post('/ajax/master/appraisals/delete/(:id)', [AppraisalsController::class, 'delete/$1']);

// $routes->match(['get', 'post'], '/ajax/get-appraisals_table', [AppraisalsController::class, 'showTable']);
// // $routes->match(['get', 'post'], '/ajax/get-appraisals-table-by-empid', [AppraisalsController::class, 'showTableByEmpId']);
// $routes->match(['get', 'post'], '/ajax/get-appraisals-table-by-empid/(:num)', [AppraisalsController::class, 'showTableByEmpId']);

// $routes->post('/ajax/master/appraisals/getAppraisalDetails', [AppraisalsController::class, 'getAppraisalDetails']);

// $routes->get('/backend/master/appraisals/pdf/(:num)?', [AppraisalsController::class, 'downloadPDF/$1']);


$routes->group('backend/master/appraisals', static function ($routes) {
    $routes->get('/',               [AppraisalsController::class, 'index']);       // list
    $routes->get('employee/(:num)/create', [AppraisalsController::class, 'create']);         // create form
    $routes->post('/',              [AppraisalsController::class, 'store']);      // save new
    $routes->get('edit/(:num)',     [AppraisalsController::class, 'edit/$1']);     // edit form
    $routes->put('(:num)',          [AppraisalsController::class, 'update/$1']);   // update
    $routes->delete('(:num)',       [AppraisalsController::class, 'delete']);   // delete
    $routes->get('pdf/(:num)',      [AppraisalsController::class, 'downloadPDF/$1']); // PDF
});

// Ajax routes (dataTables, async actions)
$routes->group('ajax/master/appraisals', function ($routes) {
    $routes->get('table', [AppraisalsController::class, 'showTable']);
    $routes->get('table-by-empid/(:num)', [AppraisalsController::class, 'showTableByEmpId']);
    $routes->match(['get', 'post'], 'details/(:num)', [AppraisalsController::class, 'getAppraisalDetails']);
});
