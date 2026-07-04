<?php

class ReservationAdminController extends Controller {

    public function index() {
        $this->requireRole('ADMIN');

        $reservationModel = new Reservation();
        $reservations = $reservationModel->toutesAvecDetails();

        $this->render('admin/reservations', ['reservations' => $reservations]);
    }

    public function changerStatut() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $statut = $_POST['statut'] ?? '';

        $statutsValides = ['CONFIRMEE', 'ARRIVEE', 'NO_SHOW', 'ANNULEE_RESTAURANT', 'TERMINEE'];
        if (!in_array($statut, $statutsValides)) {
            echo json_encode(['success' => false, 'message' => 'Statut invalide.']);
            return;
        }

        $reservationModel = new Reservation();

        if ($statut === 'NO_SHOW') {
            $reservationModel->marquerNoShow($id);
        } else {
            $reservationModel->update($id, ['statut' => $statut]);
        }

        echo json_encode(['success' => true, 'message' => 'Statut mis à jour.']);
    }
}