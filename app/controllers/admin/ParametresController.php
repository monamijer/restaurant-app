<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Parametre.php';

class ParametresController extends Controller
{
    public function index()
    {
        $this->requireRole('ADMIN');

        $parametreModel = new Parametre();
        $params = $parametreModel->getAll();

        $this->render('admin/parametres', ['params' => $params]);
    }

    public function update()
    {
        $this->requireRole('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $parametreModel = new Parametre();

        $champsAutorises = [
            'nb_personnes_min_acompte',
            'montant_acompte_par_personne',
            'delai_annulation_gratuite_h',
            'delai_grace_retard_min',
            'nom_restaurant',
            'email_contact',
            'acompte_actif',
            'telephone_contact',
            'numero_airtel_money',
            'numero_orange_money',
            'numero_mpesa',
            'devise',
            'devise_stripe',
            'nom_proprietaire',
            'titre_proprietaire',
            'bio_proprietaire',
        ];

        $data = [];
        foreach ($champsAutorises as $champ) {
            if (isset($_POST[$champ])) {
                $data[$champ] = htmlspecialchars(trim($_POST[$champ]));
            } elseif ($champ === 'acompte_actif') {
                $data[$champ] = '0';
            }
        }

        // Upload de la photo du propriétaire si fournie
        if (!empty($_FILES['photo_proprietaire']['name'])) {

            $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'webp'];
            $extension = strtolower(pathinfo($_FILES['photo_proprietaire']['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, $extensionsAutorisees)) {
            } elseif (!getimagesize($_FILES['photo_proprietaire']['tmp_name'])) {
            } else {
                $nomFichier = uniqid('proprio_') . '.' . $extension;
                $destination = __DIR__ . '/../../../public/assets/uploads/' . $nomFichier;

                if (move_uploaded_file($_FILES['photo_proprietaire']['tmp_name'], $destination)) {
                    $data['photo_proprietaire'] = $nomFichier;
                } else {
                    
                }
            }
        }

        $parametreModel->setMultiple($data);

        header('Content-Type: application/json');

        if (!empty($_FILES['photo_proprietaire']['name']) && empty($data['photo_proprietaire'])) {
            echo json_encode(['success' => false, 'message' => 'Les paramètres ont été enregistrés, mais l\'upload de la photo a échoué. Vérifiez le format ou réessayez.']);
            return;
        }

        echo json_encode(['success' => true, 'message' => 'Paramètres enregistrés']);
    }
}
