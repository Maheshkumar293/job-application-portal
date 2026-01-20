<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ----------------------------------------------------
// Default
// ----------------------------------------------------
$routes->get('/', 'Auth::login');

// ----------------------------------------------------
// Auth (Web) — FIXED
// ----------------------------------------------------
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');

$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::store');

$routes->get('logout', 'Auth::logout');

// ----------------------------------------------------
// Candidate
// ----------------------------------------------------
$routes->group('candidate', ['filter' => 'auth'], function ($routes) {
    $routes->get('apply', 'Candidate::apply');
    $routes->post('submit', 'Candidate::submit');
});


// ----------------------------------------------------
// Admin Routes (SINGLE SOURCE OF TRUTH)
// ----------------------------------------------------
$routes->group('admin', ['filter' => 'admin'], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'Admin::index');

    // Inline status update (AJAX)
    $routes->post('update-status', 'Admin::updateStatus');

    // Candidate details modal (AJAX)
    $routes->get('candidate/(:num)', 'Admin::candidateDetails/$1');

    // Resume view (secure)
    $routes->get('resume/(:any)', 'Admin::viewResume/$1');
    // 🔥 ROLES ROUTES
    $routes->get('roles', 'Admin::roles');           // ← GET for page
    $routes->post('roles/create', 'Admin::createRole');  // POST create
    $routes->post('roles/toggle', 'Admin::toggleRole');  // POST toggle
    // ----------------------------------------------------
// STAFF MANAGEMENT (ADMIN ONLY)
// ----------------------------------------------------
    $routes->get('staffs', 'Staff::index');
    $routes->post('staffs/create', 'Staff::create');
    $routes->post('staffs/block/(:num)', 'Staff::block/$1');
    $routes->post('staffs/unblock/(:num)', 'Staff::unblock/$1');


});




// ----------------------------------------------------
// API (leave as-is for later)
// ----------------------------------------------------
$routes->group('api', function ($routes) {
    $routes->post('register', 'Api\AuthApi::register');
    $routes->post('login', 'Api\AuthApi::login');
});

// ----------------------------------------------------
// Utilities
// ----------------------------------------------------
$routes->get('test-db', 'TestDB::index');
// Add this line anywhere OUTSIDE admin group
$routes->get('seed-admin', 'SeedAdmin::index');
