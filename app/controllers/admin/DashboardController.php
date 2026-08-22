<?php

class DashboardController extends Controller
{
    public function index()
    {
        $this->requireRole('ADMIN');

        $commandeModel = new Commande();
        $reservationModel = new Reservation();
        $ingredientModel = new Ingredient();
        $depenseModel = new Depense();
        $parametreModel = new Parametre();

        $debutJour = date('Y-m-d 00:00:00');
        $finJour = date('Y-m-d 23:59:59');
        $debutMois = date('Y-m-01 00:00:00');
        $finMois = date('Y-m-t 23:59:59');

        $caMois = $commandeModel->caPeriode($debutMois, $finMois);
        $depensesMois = $depenseModel->totalPeriode($debutMois, $finMois);

        $stats = [
            'ca_jour' => $commandeModel->caPeriode($debutJour, $finJour),
            'ca_mois' => $caMois,
            'nb_commandes_jour' => $commandeModel->nombrePeriode($debutJour, $finJour),
            'reservations_jour' => $reservationModel->compterAujourdhui(),
            'taux_no_show' => $reservationModel->tauxNoShow(),
            'plats_populaires' => $commandeModel->platsPopulaires(5),
            'repartition_heures' => $commandeModel->repartitionParHeure(),
            'ca_7_jours' => $commandeModel->caParJour(7),
            'prochaines_reservations' => $reservationModel->prochaines(5),
            'stock_bas' => $ingredientModel->stockBas(),
            'depenses_mois' => $depensesMois,
            'benefice_mois' => $caMois - $depensesMois,
        ];

        $this->render('admin/dashboard', ['stats' => $stats, 'devise' => $parametreModel->get('devise', 'BIF')]);
    }

    public function refreshStats()
    {
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
