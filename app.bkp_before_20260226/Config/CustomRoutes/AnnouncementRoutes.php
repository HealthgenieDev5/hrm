<?php

/**
 * Announcement Routes
 * Routes for announcement management and acknowledgment system
 */

// AJAX endpoints for users
$routes->get('announcement/get-pending', 'AnnouncementController::getPendingAnnouncements');
$routes->post('announcement/acknowledge', 'AnnouncementController::acknowledgeAnnouncement');

// Admin routes for announcement management
$routes->group('announcements', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'AnnouncementController::index');
    $routes->get('create', 'AnnouncementController::create');
    $routes->post('store', 'AnnouncementController::store');
    $routes->get('edit/(:num)', 'AnnouncementController::edit/$1');
    $routes->post('update/(:num)', 'AnnouncementController::update/$1');
    $routes->get('delete/(:num)', 'AnnouncementController::delete/$1');
    $routes->get('statistics/(:num)', 'AnnouncementController::statistics/$1');
});
