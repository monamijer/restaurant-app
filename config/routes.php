<?php
// Routes publiques
$router->get('/', 'client/HomeController@index');
$router->get('/menu', 'client/MenuController@index');
$router->get('/api/menu/filtrer', 'client/MenuController@filtrer');
$router->get('/api/plats-signature', 'client/MenuController@filtrer');

$router->get('/reserver', 'client/ReservationController@index');
$router->post('/reserver', 'client/ReservationController@store');
$router->post('/reservation/creer-paiement', 'client/ReservationController@creerPaiement');
$router->post('/reservation/paiement-manuel', 'client/ReservationController@paiementManuel');
$router->get('/reservation/confirmation', 'client/ReservationController@confirmation');

$router->post('/panier/ajouter', 'client/CommandeController@ajouterPanier');
$router->post('/panier/modifier', 'client/CommandeController@modifierPanier');
$router->get('/panier', 'client/CommandeController@voirPanier');
$router->post('/commande/checkout', 'client/CommandeController@checkout');
$router->post('/commande/creer-paiement', 'client/CommandeController@creerPaiement');
$router->post('/commande/paiement-manuel', 'client/CommandeController@paiementManuel');
$router->get('/commande/suivi', 'client/CommandeController@suivi');
$router->get('/commande/statut-ajax', 'client/CommandeController@statutAjax');

$router->get('/avis', 'client/AvisController@index');
$router->post('/avis/ajouter', 'client/AvisController@store');

$router->post('/webhook/stripe', 'StripeWebhookController@handle');

$router->get('/connexion', 'AuthController@loginForm');
$router->post('/connexion', 'AuthController@login');
// $router->get('/inscription', 'AuthController@registerForm');
// $router->post('/inscription', 'AuthController@register');
$router->get('/deconnexion', 'AuthController@logout');

$router->get('/admin', 'admin/DashboardController@index');
$router->get('/admin/stats/refresh', 'admin/DashboardController@refreshStats');

$router->get('/admin/menu', 'admin/MenuAdminController@index');
$router->post('/admin/menu/store', 'admin/MenuAdminController@store');
$router->post('/admin/menu/update', 'admin/MenuAdminController@update');
$router->post('/admin/menu/delete', 'admin/MenuAdminController@delete');
$router->post('/admin/menu/toggle', 'admin/MenuAdminController@toggleDisponibilite');

$router->get('/admin/categories', 'admin/CategorieAdminController@index');
$router->post('/admin/categories/store', 'admin/CategorieAdminController@store');
$router->post('/admin/categories/update', 'admin/CategorieAdminController@update');
$router->post('/admin/categories/delete', 'admin/CategorieAdminController@delete');
$router->post('/admin/categories/reordonner', 'admin/CategorieAdminController@reordonner');

$router->get('/admin/reservations', 'admin/ReservationAdminController@index');
$router->post('/admin/reservations/statut', 'admin/ReservationAdminController@changerStatut');
$router->post('/admin/reservations/confirmer-paiement', 'admin/ReservationAdminController@confirmerPaiement');

$router->get('/admin/commandes', 'admin/CommandeAdminController@index');
$router->post('/admin/commandes/statut', 'admin/CommandeAdminController@changerStatut');
$router->post('/admin/commandes/confirmer-paiement', 'admin/CommandeAdminController@confirmerPaiement');
$router->get('/admin/commandes/liste-ajax', 'admin/CommandeAdminController@listeAjax');

$router->get('/admin/stocks', 'admin/StockController@index');
$router->post('/admin/stocks/store', 'admin/StockController@store');
$router->post('/admin/stocks/update', 'admin/StockController@update');
$router->post('/admin/stocks/ajuster', 'admin/StockController@ajusterStock');
$router->post('/admin/stocks/delete', 'admin/StockController@delete');

$router->get('/admin/clients', 'admin/ClientController@index');
$router->get('/admin/clients/detail', 'admin/ClientController@detail');
// $router->get('/admin/clients/rechercher-ajax', 'admin/ClientController@rechercherAjax');

$router->get('/admin/avis', 'admin/AvisAdminController@index');
$router->post('/admin/avis/repondre', 'admin/AvisAdminController@repondre');
$router->post('/admin/avis/delete', 'admin/AvisAdminController@delete');

$router->get('/admin/employes', 'admin/EmployeController@index');
$router->post('/admin/employes/store', 'admin/EmployeController@store');
$router->post('/admin/employes/update', 'admin/EmployeController@update');
$router->post('/admin/employes/delete', 'admin/EmployeController@delete');

$router->get('/admin/parametres', 'admin/ParametresController@index');
$router->post('/admin/parametres/update', 'admin/ParametresController@update');

$router->post('/verification/envoyer', 'VerificationController@envoyer');
$router->post('/verification/verifier', 'VerificationController@verifier');

$router->get('/admin/journal', 'admin/JournalController@index');
$router->post('/admin/journal/store', 'admin/JournalController@store');