<?php

class VerificationController extends Controller
{
    public function envoyer()
    {
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        $type = $_POST['type'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email invalide.']);
            return;
        }
        if (!in_array($type, ['RESERVATION', 'COMMANDE', 'AVIS', 'EMPLOYE', 'PROFIL'])) {
            echo json_encode(['success' => false, 'message' => 'Type invalide.']);
            return;
        }

        $verifModel = new VerificationEmail();
        $code = $verifModel->genererEtEnvoyer($email, $type);

        $envoye = Mailer::envoyerCodeVerification($email, $code);

        if (!$envoye) {
            echo json_encode(['success' => false, 'message' => 'Impossible d\'envoyer l\'email. Vérifiez l\'adresse saisie.']);
            return;
        }

        echo json_encode(['success' => true, 'message' => 'Code envoyé. Vérifiez votre boîte mail.']);

    }

    public function verifier()
    {
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $type = $_POST['type'] ?? '';

        $verifModel = new VerificationEmail();
        $ok = $verifModel->verifier($email, $code, $type);

        if (!$ok) {
            echo json_encode(['success' => false, 'message' => 'Code invalide ou expiré.']);
            return;
        }

        echo json_encode(['success' => true, 'message' => 'Email vérifié !']);
    }

}
