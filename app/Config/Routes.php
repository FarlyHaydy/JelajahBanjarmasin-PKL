<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'DashboardController::index');

// Home/Landing page routes - PERBAIKI INI
$routes->get('/', 'DashboardController::index'); // Beranda mengarah ke dashboard

// Auth Routes
$routes->get('/register', 'AuthController::register');
$routes->post('/registerAction', 'AuthController::registerAction');
$routes->get('/login', 'AuthController::login');
$routes->post('/loginAction', 'AuthController::loginAction');
$routes->get('/logout', 'AuthController::logout');

// User Routes  
$routes->get('/dashboard', 'DashboardController::index');
$routes->get('/profil', 'AuthController::profil');
$routes->post('/updateProfil', 'AuthController::updateProfil');

// User Wisata Routes
$routes->get('/rekreasi', 'UserController::rekreasi');
$routes->get('/kuliner', 'UserController::kuliner');
$routes->get('/religi', 'UserController::religi');

// Route untuk kategori dinamis
$routes->get('/kategori/(:segment)', 'UserController::kategoriWisata/$1');

// Detail Wisata Routes (Multiple URL formats supported)
$routes->get('/detail/(:num)', 'UserController::detailWisata/$1');
$routes->get('/wisata/detail/(:num)', 'UserController::detailWisata/$1');
$routes->get('/wisata/(:num)', 'UserController::detailWisata/$1');

// FAQ Routes (baru ditambahkan)
$routes->get('/faq', 'UserController::faq');

// Wishlist Routes - PINDAHKAN KE LUAR ADMIN GROUP
$routes->group('wishlist', function($routes) {
    $routes->post('toggle', 'WishlistController::toggle');
    $routes->get('check-status/(:num)', 'WishlistController::checkStatus/$1');
    $routes->get('user-bookmarks', 'WishlistController::getUserBookmarks');
    $routes->delete('remove/(:num)', 'WishlistController::remove/$1');
});

// Admin Routes (Protected)
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'AdminController::dashboard');

    //import routes
    $routes->post('wisata/import', 'AdminController::importWisata');
    
    // Wisata CRUD
    $routes->post('wisata/create', 'AdminController::createWisata');
    $routes->get('wisata/get/(:num)', 'AdminController::getWisata/$1');
    $routes->post('wisata/update/(:num)', 'AdminController::updateWisata/$1');
    $routes->get('wisata/delete/(:num)', 'AdminController::deleteWisata/$1');

     // Routes untuk User Management (tambahkan di dalam grup admin)
    // Routes untuk User Management - PERBAIKI INI
    $routes->get('user/get/(:num)', 'AdminController::getUser/$1');
    $routes->post('user/update/(:num)', 'AdminController::updateUser/$1');
    $routes->get('user/delete/(:num)', 'AdminController::deleteUser/$1');

    // Destinasi view for admin
    $routes->get('destinasi', 'AdminController::destinasi');

    
    // Master Data CRUD
    $routes->post('kategori/create', 'AdminController::createKategori');
    $routes->get('kategori/get/(:num)', 'AdminController::getKategori/$1');      // NEW
    $routes->post('kategori/update/(:num)', 'AdminController::updateKategori/$1');
    $routes->get('kategori/delete/(:num)', 'AdminController::deleteKategori/$1');
    
    $routes->post('kota/create', 'AdminController::createKota');
    $routes->get('kota/get/(:num)', 'AdminController::getKota/$1');              // NEW
    $routes->post('kota/update/(:num)', 'AdminController::updateKota/$1');
    $routes->get('kota/delete/(:num)', 'AdminController::deleteKota/$1');
    
    $routes->post('kecamatan/create', 'AdminController::createKecamatan');
    $routes->get('kecamatan/get/(:num)', 'AdminController::getKecamatan/$1');    // NEW
    $routes->post('kecamatan/update/(:num)', 'AdminController::updateKecamatan/$1');
    $routes->get('kecamatan/delete/(:num)', 'AdminController::deleteKecamatan/$1');

    
    // AJAX Routes
    $routes->get('kecamatan/by-kota/(:num)', 'AdminController::getKecamatanByKota/$1');
});