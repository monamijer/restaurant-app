<?php
// Routes publiques
$router->get('/', 'client/HomeController@index');
$router->get('/menu', 'client/MenuController@index');
$router->get('/reserver', 'client/ReservationController@index');
$router->post('/reserver', 'client/ReservationController@store');

// Auth
$router->get('/connexion', 'AuthController@loginForm');
$router->post('/connexion', 'AuthController@login');
$router->get('/inscription', 'AuthController@registerForm');
$router->post('/inscription', 'AuthController@register');

// Admin
$router->get('/admin', 'admin/DashboardController@index');
$router->get('/admin/parametres', 'admin/ParametresController@index');
$router->post('/admin/parametres/update', 'admin/ParametresController@update');