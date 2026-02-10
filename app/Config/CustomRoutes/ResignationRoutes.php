<?php

use App\Controllers\ResignationController;

$routes->match(['get', 'post'], 'resignation', [ResignationController::class, 'dashboard']);
// Company filter route - matches numbers or "all_companies"
$routes->match(['get', 'post'], 'resignation/all_companies', [ResignationController::class, 'dashboard/$1']);
$routes->match(['get', 'post'], 'resignation/(:num)', [ResignationController::class, 'dashboard/$1']);

$routes->match(['get', 'post'], '/ajax/resignation/stats', [ResignationController::class, 'getDashboardStats']);
$routes->match(['get', 'post'], '/ajax/resignation/reports', [ResignationController::class, 'getResignationReports']);
$routes->match(['get', 'post'], '/ajax/resignation/alerts', [ResignationController::class, 'getResignationAlerts']);
$routes->match(['get', 'post'], '/ajax/resignation/completed', [ResignationController::class, 'getCompletedResignations']);
$routes->match(['get', 'post'], '/ajax/resignation/history/(:num)', [ResignationController::class, 'getRevisionHistory/$1']);
// Resignation HOD acknowledgment routes
$routes->match(['get', 'post'], '/ajax/resignation/save-hod-response', [ResignationController::class, 'saveResignationResponseOfHod']);
$routes->match(['get', 'post'], '/ajax/resignation/manager-notifications', [ResignationController::class, 'getManagerResignationNotifications']);
$routes->post('/ajax/resignation/manager-notification-action', [ResignationController::class, 'handleManagerResignationNotificationAction']);

// Reporting manager notification routes (notified at resignation creation)
$routes->match(['get', 'post'], '/ajax/resignation/reporting-manager-notifications', [ResignationController::class, 'getReportingManagerResignationNotifications']);
$routes->post('/ajax/resignation/reporting-manager-notification-action', [ResignationController::class, 'handleReportingManagerNotificationAction']);

$routes->group('resignation', ['filter' => 'authfilter'], function ($routes) {
    $routes->get('/', [ResignationController::class, 'index']);
    $routes->get('create', [ResignationController::class, 'create']);
    $routes->post('store', [ResignationController::class, 'store']);
    $routes->get('edit/(:num)', [ResignationController::class, 'edit/$1']);
    $routes->post('update/(:num)', [ResignationController::class, 'update/$1']);
    $routes->post('withdraw/(:num)', [ResignationController::class, 'withdraw/$1']);
    $routes->post('complete/(:num)', [ResignationController::class, 'complete/$1']);
    $routes->post('change-status/(:num)', [ResignationController::class, 'changeStatus/$1']);
    $routes->post('calculate-date', [ResignationController::class, 'calculateLastWorkingDay']);
    $routes->get('employees/(:num)', [ResignationController::class, 'getEmployeesByCompany/$1']);
    $routes->get('export', [ResignationController::class, 'exportResignations']);
});
