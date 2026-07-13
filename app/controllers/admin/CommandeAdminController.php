<?php

class CommandeAdminController extends Controller {

    public function index() {
        $this->requireRole('ADMIN');

        $commandeModel = new Commande();
        $ligneModel = new LigneCommande();

        $commandes = $commandeModel->toutesAvecUser();
        foreach ($commandes as &$commande) {
            $commande['lignes'] = $ligneModel->parCommande($commande['id']);
        }

        $this->render('admin/commandes', ['commandes' => $commandes]);
    }

    public function changerStatut() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $statut = $_POST['statut'] ?? '';

        $statutsValides = ['EN_ATTENTE', 'EN_CUISINE', 'PRETE', 'SERVIE', 'ANNULEE'];
        if (!in_array($statut, $statutsValides)) {
            echo json_encode(['success' => false, 'message' => 'Statut invalide.']);
            return;
        }

        $commandeModel = new Commande();
        $commandeModel->update($id, ['statut' => $statut]);

        echo json_encode(['success' => true, 'message' => 'Statut mis à jour.']);
    }

    // Endpoint AJAX pour rafraîchir l'écran cuisine sans recharger
    public function listeAjax() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $commandeModel = new Commande();
        $commandes = $commandeModel->toutesAvecUser();
        echo json_encode($commandes);
    }
}