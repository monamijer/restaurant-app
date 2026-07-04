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
$router->get('/deconnexion', 'AuthController@logout');

// Client - Menu
$router->get('/api/menu/filtrer', 'client/MenuController@filtrer');
$router->get('/api/plats-signature', 'client/MenuController@filtrer'); // remplace l'ancien endpoint utilisé sur home.php

// Admin - Menu
$router->get('/admin/menu', 'admin/MenuAdminController@index');
$router->post('/admin/menu/store', 'admin/MenuAdminController@store');
$router->post('/admin/menu/update', 'admin/MenuAdminController@update');
$router->post('/admin/menu/delete', 'admin/MenuAdminController@delete');
$router->post('/admin/menu/toggle', 'admin/MenuAdminController@toggleDisponibilite');
// Client - Réservation
$router->post('/reservation/creer-paiement', 'client/ReservationController@creerPaiement');
$router->get('/reservation/confirmation', 'client/ReservationController@confirmation');

// Webhook Stripe
$router->post('/webhook/stripe', 'StripeWebhookController@handle');

// Admin - Réservations
$router->get('/admin/reservations', 'admin/ReservationAdminController@index');
$router->post('/admin/reservations/statut', 'admin/ReservationAdminController@changerStatut');