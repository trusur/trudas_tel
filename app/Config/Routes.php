<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home\Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// home
$routes->get('/', 'Home\Home::index');
$routes->get('/getsensorvalue', 'Home\Home::getSensorValues');

// data
$routes->get('/data-log', 'Data\Data::index');
$routes->add('/ajax/das-log', 'Data\Data::ajaxDasLog');

// configuration
$routes->get('/configuration', 'Configuration\Configuration::index');
$routes->post('/configuration/update', 'Configuration\Configuration::update');

// sensor
$routes->get('/sensor', 'Sensor\Sensor::index');
$routes->get('/sensor/add', 'Sensor\Sensor::add');
$routes->post('/sensor/save', 'Sensor\Sensor::save');
$routes->get('/sensor/edit/(:any)', 'Sensor\Sensor::edit/$1');
$routes->post('/sensor/update', 'Sensor\Sensor::update');
$routes->post('/sensor/delete', 'Sensor\Sensor::delete');

// reference
$routes->get('/reference', 'Reference\Reference::index');
$routes->get('/reference/add', 'Reference\Reference::add');
$routes->post('/reference/save', 'Reference\Reference::save');
$routes->get('/reference/edit/(:any)', 'Reference\Reference::edit/$1');
$routes->post('/reference/update', 'Reference\Reference::update');
$routes->post('/reference/delete', 'Reference\Reference::delete');

// unit
$routes->get('/unit', 'Unit\Unit::index');
$routes->get('/unit/add', 'Unit\Unit::add');
$routes->post('/unit/save', 'Unit\Unit::save');
$routes->get('/unit/edit/(:any)', 'Unit\Unit::edit/$1');
$routes->post('/unit/update', 'Unit\Unit::update');
$routes->post('/unit/delete', 'Unit\Unit::delete');


// user
$routes->get('/user', 'User\User::index');
$routes->post('/user/update', 'User\User::update');

// login
$routes->get('/login', 'Login\Login::form');
$routes->post('/login-session', 'Login\Login::processLogin');

// logout
$routes->add('/logout', 'Login\Login::logout');

// start mode rca
$routes->post('/start-mode-rca', 'Home\Home::modeRca');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
