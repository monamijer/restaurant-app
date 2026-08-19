<?php

class ClientController extends Controller {

    public function index() {
        $this->requireRole('ADMIN');

        $userModel = new User();
        $clients = $userModel->tousLesClients();

        $parametreModel = new Parametre();
        $this->render('admin/clients', ['clients' => $clients, 'devise' => $parametreModel->get('devise', 'BIF')]);
    }

    public function detail() {
        $this->requireRole('ADMIN');

        $id = (int) ($_GET['id'] ?? 0);
        $userModel = new User();
        $client = $userModel->findAvecHistorique($id);

        if (!$client) {
            http_response_code(404);
            echo "Client introuvable.";
            return;
        }

        $this->render('admin/client-detail', ['client' => $client]);
    }

    public function rechercherAjax() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $terme = trim($_GET['q'] ?? '');
        $userModel = new User();
        $resultats = $terme ? $userModel->rechercher($terme) : $userModel->tousLesClients();

        echo json_encode($resultats);
    }
}