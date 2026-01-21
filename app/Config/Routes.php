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

    // Candidate details modal (AJAX)
    $routes->get('candidate/(:num)', 'Admin::candidateDetails/$1');

   // $routes->get('resume/(:any)', 'Admin::viewResume/$1');

    // 🔥 ROLES ROUTES
    $routes->get('roles', 'Admin::roles');           // ← GET for page
    $routes->post('roles/create', 'Admin::createRole');  // POST create
    $routes->post('roles/toggle', 'Admin::toggleRole');  // POST toggle
    
    //STATUS LOGS
    $routes->get('status-history/(:num)', 'Admin::statusHistory/$1');
   
 // ----------------------------------------------------
// STAFF MANAGEMENT (ADMIN ONLY)
// ----------------------------------------------------
    $routes->get('staffs', 'Staff::index');
    $routes->post('staffs/create', 'Staff::create');
    $routes->post('staffs/block/(:num)', 'Staff::block/$1');
    $routes->post('staffs/unblock/(:num)', 'Staff::unblock/$1');
    // ✅ REQUIRED: Staff Assignment
    $routes->post('assign-staff', 'Admin::assignStaff');
});
 
// ----------------------------------------------------
// RESUME VIEW ROUTE (SECURE)
// ----------------------------------------------------
    $routes->get('resume/(:any)', 'Admin::viewResume/$1', ['filter' => 'auth']);
// ----------------------------------------------------
// ✅ SHARED STATUS UPDATE (ADMIN + STAFF)
// ----------------------------------------------------
    $routes->post('update-status', 'Admin::updateStatus', ['filter' => 'auth']);

// ----------------------------------------------------
// Staff Routes
// ----------------------------------------------------
$routes->group('staff', ['filter' => 'staff'], function ($routes) {
    $routes->get('dashboard', 'StaffDashboard::index');
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
