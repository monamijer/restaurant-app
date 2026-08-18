<?php

class CommandeAdminController extends Controller {

    public function index() {
        $this->requireAnyRole(['ADMIN', 'SERVEUR', 'CUISINE']);

        $commandeModel = new Commande();
        $ligneModel = new LigneCommande();

        $commandes = $commandeModel->toutesAvecUser();
        foreach ($commandes as &$commande) {
            $commande['lignes'] = $ligneModel->parCommande($commande['id']);
        }

        $parametreModel = new Parametre();
        $this->render('admin/commandes', ['commandes' => $commandes, 'devise' => $parametreModel->get('devise', 'BIF')]);
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

    public function confirmerPaiement() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $commandeModel = new Commande();
        $commandeModel->update($id, ['statut_paiement' => 'PAYE']);

        echo json_encode(['success' => true, 'message' => 'Paiement confirmé.']);
    }

    public function listeAjax() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $commandeModel = new Commande();
        $commandes = $commandeModel->toutesAvecUser();
        echo json_encode($commandes);
    }
}