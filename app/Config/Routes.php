<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Beranda Routes
$routes->get('/', 'DashboardController::index'); 

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

// Detail Wisata Routes
$routes->get('/detail/(:num)', 'UserController::detailWisata/$1');


// Bookmark Routes
$routes->group('bookmark', function($routes) {
    $routes->post('toggle', 'BookmarkController::toggle');
    $routes->get('check-status/(:num)', 'BookmarkController::checkStatus/$1');
    $routes->get('user-bookmarks', 'BookmarkController::getUserBookmarks');
    $routes->delete('remove/(:num)', 'BookmarkController::remove/$1');
});

// Admin Routes (Protected)
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'AdminController::dashboard');

    //import routes
    $routes->post('wisata/import', 'AdminController::importWisata');
    $routes->get('wisata/download-template', 'AdminController::downloadTemplate');
    
    // Wisata CRUD
    $routes->post('wisata/create', 'AdminController::createWisata');
    $routes->get('wisata/get/(:num)', 'AdminController::getWisata/$1');
    $routes->post('wisata/update/(:num)', 'AdminController::updateWisata/$1');
    $routes->get('wisata/delete/(:num)', 'AdminController::deleteWisata/$1');

    // Routes untuk Gallery Management 
    $routes->group('wisata/(:num)/gallery', function($routes) {
        $routes->get('', 'GaleriController::getByWisata/$1');
        $routes->post('upload', 'GaleriController::upload/$1');
        $routes->post('set-primary', 'GaleriController::setPrimary/$1');
        $routes->post('delete', 'GaleriController::delete/$1');
    });

    // Routes untuk User Management
    $routes->get('user/get/(:num)', 'AdminController::getUser/$1');
    

    // Destinasi view for admin
    $routes->get('destinasi', 'AdminController::destinasi');

    
    // Master Data CRUD
    $routes->post('kategori/create', 'AdminController::createKategori');
    $routes->get('kategori/get/(:num)', 'AdminController::getKategori/$1');      
    $routes->post('kategori/update/(:num)', 'AdminController::updateKategori/$1');
    $routes->get('kategori/delete/(:num)', 'AdminController::deleteKategori/$1');
    
    $routes->post('kota/create', 'AdminController::createKota');
    $routes->get('kota/get/(:num)', 'AdminController::getKota/$1');              
    $routes->post('kota/update/(:num)', 'AdminController::updateKota/$1');
    $routes->get('kota/delete/(:num)', 'AdminController::deleteKota/$1');
    
    $routes->post('kecamatan/create', 'AdminController::createKecamatan');
    $routes->get('kecamatan/get/(:num)', 'AdminController::getKecamatan/$1');    
    $routes->post('kecamatan/update/(:num)', 'AdminController::updateKecamatan/$1');
    $routes->get('kecamatan/delete/(:num)', 'AdminController::deleteKecamatan/$1');

    
    // AJAX Routes
    $routes->get('kecamatan/by-kota/(:num)', 'AdminController::getKecamatanByKota/$1');
});