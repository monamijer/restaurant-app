<?php

class AvisController extends Controller {

    public function index() {
        $avisModel = new Avis();
        $parametreModel = new Parametre();

        $avis = $avisModel->publiesAvecUser(20);
        $noteMoyenne = $avisModel->noteMoyenne();
        $params = $parametreModel->getAll();

        $this->render('client/avis', [
            'avis' => $avis,
            'noteMoyenne' => $noteMoyenne,
            'params' => $params,
        ]);
    }

    public function store() {
        if (!Auth::check()) {
            header('Location: /connexion');
            exit;
        }
        header('Content-Type: application/json');

        $note = (int) ($_POST['note'] ?? 0);
        $commentaire = trim($_POST['commentaire'] ?? '');

        if ($note < 1 || $note > 5) {
            echo json_encode(['success' => false, 'message' => 'Veuillez sélectionner une note entre 1 et 5.']);
            return;
        }
        if (strlen($commentaire) < 10) {
            echo json_encode(['success' => false, 'message' => 'Votre commentaire doit contenir au moins 10 caractères.']);
            return;
        }

        $avisModel = new Avis();
        $userId = Auth::user()['id'];

        // Limite anti-spam : un seul avis par jour par utilisateur
        if ($avisModel->utilisateurAvisAujourdhui($userId)) {
            echo json_encode(['success' => false, 'message' => 'Vous avez déjà laissé un avis aujourd\'hui.']);
            return;
        }

        $avisModel->create([
            'user_id' => $userId,
            'note' => $note,
            'commentaire' => htmlspecialchars($commentaire),
        ]);

        echo json_encode(['success' => true, 'message' => 'Merci pour votre avis !']);
    }
}