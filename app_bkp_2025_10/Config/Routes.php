<?php

use App\Controllers\Additional\Contact;
use App\Controllers\Additional\FileController;
use App\Controllers\Additional\Unauthorised;
use CodeIgniter\Router\RouteCollection;


use App\Controllers\AddressConfirmationController;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->get('uploads/(:num)/(:num)/(:any)', [FileController::class, 'serve/$1/$2/$3']);
$routes->get('public/uploads/(:num)/(:num)/(:any)', [FileController::class, 'serve/$1/$2/$3']);
// $routes->get('public/uploads/(:num)/(:num)/(:any)', [FileController::class, 'serve/$1/$2/$3']);

$routes->get('/unauthorised', [Unauthorised::class, 'index']);

/*begin::Contact List*/
$routes->match(['get', 'post'], '/contacts', [Contact::class, 'index']);
$routes->match(['get', 'post'], '/ajax/get-all-contacts', [Contact::class, 'GetAllContacts']);


/*end::Contact List*/


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 */

$routes->group('address-confirmation', function ($routes) {
    $routes->get('check-popup', 'AddressConfirmationController::checkPopupRequired');
    $routes->post('submit', 'AddressConfirmationController::submitAddressConfirmation');
    $routes->post('snooze', 'AddressConfirmationController::snoozePopup');

    // HR routes
    $routes->get('hr/dashboard', 'AddressConfirmationController::hrDashboard');
    $routes->post('hr/review', 'AddressConfirmationController::reviewSubmission');
    $routes->post('hr/bulk-approve', 'AddressConfirmationController::bulkApprove');
});

foreach (glob(APPPATH . 'Config/CustomRoutes/*.php') as $routeFile) {
    require $routeFile;
}
