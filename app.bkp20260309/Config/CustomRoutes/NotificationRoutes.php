<?php

use App\Controllers\EmployeeNotificationController;
use App\Controllers\Cron\NotificationReminders;


$routes->group('backend/notifications', static function ($routes) {
    $routes->get('/', [EmployeeNotificationController::class, 'index']);
    $routes->get('create', [EmployeeNotificationController::class, 'create']);
    $routes->post('store', [EmployeeNotificationController::class, 'store']);
    $routes->get('edit/(:num)', [EmployeeNotificationController::class, 'edit/$1']);
    $routes->post('update/(:num)', [EmployeeNotificationController::class, 'update/$1']);
    $routes->post('delete/(:num)', [EmployeeNotificationController::class, 'delete/$1']);
});


$routes->group('ajax/notifications', function ($routes) {
    $routes->match(['get', 'post'], 'table', [EmployeeNotificationController::class, 'getAllNotifications']);
    $routes->match(['get', 'post'], 'dashboard', [EmployeeNotificationController::class, 'getDashboardNotifications']);
    $routes->post('mark-as-read', [EmployeeNotificationController::class, 'markAsRead']);
});


// Cron routes for notification reminders
$routes->group('cron/notifications', function ($routes) {
    $routes->match(['get', 'post'], 'send-reminders', [NotificationReminders::class, 'sendDailyReminders']);
    $routes->match(['get', 'post'], 'send-reminders-test', [NotificationReminders::class, 'sendTestReminder']);
});


