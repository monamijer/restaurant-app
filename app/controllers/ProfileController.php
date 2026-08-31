<?php

class ProfileController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            header('Location: /connexion');
            exit;
        }

        $userModel = new User();
        $user = $userModel->find(Auth::user()['id']);

        $this->render('admin/profil', ['user' => $user, 'page' => 'profil']);
    }

    public function mettreAJourInfos()
    {
        if (!Auth::check()) {
            header('Location: /connexion');
            exit;
        }
        header('Content-Type: application/json');

        $userId = Auth::user()['id'];
        $nom = trim($_POST['nom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');

        if (!$nom) {
            echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire.']);
            return;
        }

        $userModel = new User();
        $userModel->update($userId, [
            'nom' => $nom,
            'telephone' => $telephone,
        ]);

        // Met à jour le nom affiché en session immédiatement
        $_SESSION['nom'] = $nom;

        echo json_encode(['success' => true, 'message' => 'Informations mises à jour.']);
    }

    public function changerEmail()
    {
        if (!Auth::check()) {
            header('Location: /connexion');
            exit;
        }
        header('Content-Type: application/json');

        $nouvelEmail = trim($_POST['email'] ?? '');

        if (!filter_var($nouvelEmail, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email invalide.']);
            return;
        }

        $verifModel = new VerificationEmail();
        if (!$verifModel->estRecemmentVerifie($nouvelEmail, 'PROFIL')) {
            echo json_encode(['success' => false, 'message' => 'Veuillez vérifier ce nouvel email avant de continuer.']);
            return;
        }

        $userModel = new User();
        $userId = Auth::user()['id'];

        $existant = $userModel->findByEmail($nouvelEmail);
        if ($existant && $existant['id'] != $userId) {
            echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé par un autre compte.']);
            return;
        }

        $userModel->update($userId, ['email' => $nouvelEmail]);

        echo json_encode(['success' => true, 'message' => 'Email mis à jour.']);
    }

    public function changerMotDePasse()
    {
        if (!Auth::check()) {
            header('Location: /connexion');
            exit;
        }
        header('Content-Type: application/json');

        $ancienMotDePasse = $_POST['ancien_mot_de_passe'] ?? '';
        $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'] ?? '';

        if (strlen($nouveauMotDePasse) < 6) {
            echo json_encode(['success' => false, 'message' => 'Le nouveau mot de passe doit contenir au moins 6 caractères.']);
            return;
        }

        $userModel = new User();
        $user = $userModel->find(Auth::user()['id']);

        if (!password_verify($ancienMotDePasse, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect.']);
            return;
        }

        $userModel->update($user['id'], [
            'password' => password_hash($nouveauMotDePasse, PASSWORD_BCRYPT),
        ]);

        echo json_encode(['success' => true, 'message' => 'Mot de passe changé avec succès.']);
    }
}
