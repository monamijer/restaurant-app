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
        header('Content-Type: application/json');

        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $note = (int) ($_POST['note'] ?? 0);
        $commentaire = trim($_POST['commentaire'] ?? '');

        if (!$nom || !$email) {
            echo json_encode(['success' => false, 'message' => 'Nom et email sont obligatoires.']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email invalide.']);
            return;
        }
        if ($note < 1 || $note > 5) {
            echo json_encode(['success' => false, 'message' => 'Veuillez sélectionner une note entre 1 et 5.']);
            return;
        }
        if (strlen($commentaire) < 10) {
            echo json_encode(['success' => false, 'message' => 'Votre commentaire doit contenir au moins 10 caractères.']);
            return;
        }

        $verifModel = new VerificationEmail();
        if (!$verifModel->estRecemmentVerifie($email, 'AVIS')) {
            echo json_encode(['success' => false, 'message' => 'Veuillez vérifier votre email avant de continuer.']);
            return;
        }

        $avisModel = new Avis();
        if ($avisModel->emailAvisAujourdhui($email)) {
            echo json_encode(['success' => false, 'message' => 'Vous avez déjà laissé un avis aujourd\'hui.']);
            return;
        }

        $avisModel->create([
            'user_id' => null,
            'guest_nom' => htmlspecialchars($nom),
            'guest_email' => $email,
            'note' => $note,
            'commentaire' => htmlspecialchars($commentaire),
        ]);

        echo json_encode(['success' => true, 'message' => 'Merci pour votre avis !']);
    }
    public function apiAvisRecents() {
    header('Content-Type: application/json');

    $avisModel = new Avis();
    $avis = $avisModel->publiesAvecUser(6);

    echo json_encode($avis);
}
}