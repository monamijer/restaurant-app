<?php

class DashboardController extends Controller {

    public function index() {
        $this->requireRole('ADMIN');

        $commandeModel = new Commande();
        $reservationModel = new Reservation();
        $ingredientModel = new Ingredient();

        $debutJour = date('Y-m-d 00:00:00');
        $finJour = date('Y-m-d 23:59:59');
        $debutMois = date('Y-m-01 00:00:00');
        $finMois = date('Y-m-t 23:59:59');

        $stats = [
            'ca_jour' => $commandeModel->caPeriode($debutJour, $finJour),
            'ca_mois' => $commandeModel->caPeriode($debutMois, $finMois),
            'nb_commandes_jour' => $commandeModel->nombrePeriode($debutJour, $finJour),
            'reservations_jour' => $reservationModel->compterAujourdhui(),
            'taux_no_show' => $reservationModel->tauxNoShow(),
            'plats_populaires' => $commandeModel->platsPopulaires(5),
            'repartition_heures' => $commandeModel->repartitionParHeure(),
            'ca_7_jours' => $commandeModel->caParJour(7),
            'prochaines_reservations' => $reservationModel->prochaines(5),
            'stock_bas' => $ingredientModel->stockBas(),
        ];

        $this->render('admin/dashboard', ['stats' => $stats]);
    }

    // Endpoint AJAX pour rafraîchir les stats sans recharger la page
    public function refreshStats() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $commandeModel = new Commande();
        $reservationModel = new Reservation();

        $debutJour = date('Y-m-d 00:00:00');
        $finJour = date('Y-m-d 23:59:59');

        echo json_encode([
            'ca_jour' => $commandeModel->caPeriode($debutJour, $finJour),
            'nb_commandes_jour' => $commandeModel->nombrePeriode($debutJour, $finJour),
            'reservations_jour' => $reservationModel->compterAujourdhui(),
        ]);
    }
}