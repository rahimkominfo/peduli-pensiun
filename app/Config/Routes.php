<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public / Auth routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('auth', 'Auth::login');
$routes->post('auth', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Protected routes (require auth filter)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('data-persiapan', 'Persiapan::index');
    $routes->post('data-persiapan/create', 'Persiapan::create');
    $routes->post('data-persiapan/(:num)/update-progres', 'Persiapan::updateProgres/$1');

    $routes->get('data-verifikasi', 'Verifikasi::index');
    $routes->get('data-verifikasi/(:num)', 'Verifikasi::detail/$1');
    $routes->post('data-verifikasi/(:num)/update-contact', 'Verifikasi::updateContact/$1');
    $routes->post('data-verifikasi/(:num)/add-progres', 'Verifikasi::addProgres/$1');
    $routes->post('data-verifikasi/(:num)/upload-dokumen', 'Verifikasi::uploadDokumen/$1');
    $routes->post('data-verifikasi/(:num)/approve-dokumen', 'Verifikasi::approveDokumen/$1');
    $routes->match(['get', 'post'], 'data-verifikasi/(:num)/delete', 'Verifikasi::delete/$1');

    // Modul Dokumen & Konsep Akses
    $routes->get('dokumen', 'Dokumen::index');
    $routes->post('dokumen/edit_konsep', 'Dokumen::editKonsep');
    $routes->get('dokumen/konsep_akses', 'Dokumen::konsepAkses');
    $routes->post('dokumen/tambah_konsep', 'Dokumen::tambahKonsep');
    $routes->post('dokumen/ganti_akses', 'Dokumen::gantiAkses');
    $routes->match(['get', 'post'], 'dokumen/delete/(:num)', 'Dokumen::delete/$1');
});
