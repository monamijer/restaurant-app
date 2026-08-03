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
$router->get('/admin', 'admin/DashboardController@index');
$router->get('/admin/stats/refresh', 'admin/DashboardController@refreshStats');
$router->get('/admin/clients', 'admin/PlaceholderController@clients');
$router->get('/admin/avis', 'admin/PlaceholderController@avis');
$router->get('/admin/employes', 'admin/PlaceholderController@employes');

// Client - Commande
$router->post('/panier/ajouter', 'client/CommandeController@ajouterPanier');
$router->post('/panier/modifier', 'client/CommandeController@modifierPanier');
$router->get('/panier', 'client/CommandeController@voirPanier');
$router->post('/commande/checkout', 'client/CommandeController@checkout');
$router->post('/commande/creer-paiement', 'client/CommandeController@creerPaiement');
$router->get('/commande/suivi', 'client/CommandeController@suivi');
$router->get('/commande/statut-ajax', 'client/CommandeController@statutAjax');

// Admin - Commandes ()
$router->get('/admin/commandes', 'admin/CommandeAdminController@index');
$router->post('/admin/commandes/statut', 'admin/CommandeAdminController@changerStatut');
$router->get('/admin/commandes/liste-ajax', 'admin/CommandeAdminController@listeAjax');

// Admin -Stocks
$router->get('/admin/stocks', 'admin/StockController@index');
$router->post('/admin/stocks/store', 'admin/StockController@store');
$router->post('/admin/stocks/update', 'admin/StockController@update');
$router->post('/admin/stocks/ajuster', 'admin/StockController@ajusterStock');
$router->post('/admin/stocks/delete', 'admin/StockController@delete');

// Admin -Clients
$router->get('/admin/clients', 'admin/ClientController@index');
$router->get('/admin/clients/detail', 'admin/ClientController@detail');
$router->get('/admin/clients/rechercher-ajax', 'admin/ClientController@rechercherAjax');

// Client
$router->get('/avis', 'client/AvisController@index');
$router->post('/avis/ajouter', 'client/AvisController@store');

// Admin
$router->get('/admin/avis', 'admin/AvisAdminController@index');
$router->post('/admin/avis/repondre', 'admin/AvisAdminController@repondre');
$router->post('/admin/avis/delete', 'admin/AvisAdminController@delete');