<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Parametre.php';

class ParametresController extends Controller {

    public function index() {
        $this->requireRole('ADMIN'); // sécurité : admin uniquement

        $parametreModel = new Parametre();
        $params = $parametreModel->getAll();

        $this->render('admin/parametres', ['params' => $params]);
    }

    public function update() {
        $this->requireRole('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $parametreModel = new Parametre();

        // On ne prend que les champs attendus (sécurité)
        $champsAutorises = [
            'nb_personnes_min_acompte',
            'montant_acompte_par_personne',
            'delai_annulation_gratuite_h',
            'delai_grace_retard_min',
            'nom_restaurant',
            'email_contact',
            'acompte_actif',
        ];

        $data = [];
        foreach ($champsAutorises as $champ) {
            if (isset($_POST[$champ])) {
                $data[$champ] = htmlspecialchars(trim($_POST[$champ]));
            }
        }

        $parametreModel->setMultiple($data);

        // Réponse AJAX en JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Paramètres enregistrés']);
    }
}