<?php

class AuthController extends Controller {

    public function loginForm() {
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);
        $this->render('auth/login', ['error' => $error, 'success' => $success]);
    }

    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['error'] = "Veuillez remplir tous les champs.";
            header('Location: /connexion');
            exit;
        }

        if (Auth::attempt($email, $password)) {
            $destinations = [
                'ADMIN' => '/admin',
                'SERVEUR' => '/admin/journal',
                'CUISINE' => '/admin/commandes',
            ];
            header('Location: ' . ($destinations[Auth::role()] ?? '/'));
            exit;
        }

        $_SESSION['error'] = "Email ou mot de passe incorrect.";
        header('Location: /connexion');
        exit;
    }

    public function logout() {
        Auth::logout();
        header('Location: /');
        exit;
    }

    public function motDePasseOublieForm() {
        $message = $_SESSION['reset_message'] ?? null;
        unset($_SESSION['reset_message']);
        $this->render('auth/mot-de-passe-oublie', ['message' => $message]);
    }

    public function motDePasseOublieEnvoyer() {
        $email = trim($_POST['email'] ?? '');

        // Message volontairement identique que le compte existe ou non,
        // pour ne pas révéler quels emails ont un compte (sécurité)
        $messageGenerique = "Si un compte existe avec cet email, un lien de réinitialisation vient d'être envoyé.";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['reset_message'] = $messageGenerique;
            header('Location: /mot-de-passe-oublie');
            exit;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        $resetModel = new PasswordReset();

        if ($user && !$resetModel->demandeRecenteExiste($email)) {
            $token = $resetModel->creerToken($email);
            $lien = 'http://restaurant.local/reinitialiser-mot-de-passe?token=' . $token;
            Mailer::envoyerLienReinitialisation($email, $lien);
        }

        $_SESSION['reset_message'] = $messageGenerique;
        header('Location: /mot-de-passe-oublie');
        exit;
    }

    public function reinitialiserForm() {
        $token = $_GET['token'] ?? '';

        $resetModel = new PasswordReset();
        $reset = $resetModel->trouverValide($token);

        if (!$reset) {
            $this->render('auth/reinitialiser-mot-de-passe', ['token' => null, 'erreur' => 'Ce lien est invalide ou a expiré. Veuillez refaire une demande.']);
            return;
        }

        $this->render('auth/reinitialiser-mot-de-passe', ['token' => $token, 'erreur' => null]);
    }

    public function reinitialiser() {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';

        $resetModel = new PasswordReset();
        $reset = $resetModel->trouverValide($token);

        if (!$reset) {
            $_SESSION['error'] = "Ce lien est invalide ou a expiré.";
            header('Location: /connexion');
            exit;
        }

        if (strlen($password) < 6) {
            $this->render('auth/reinitialiser-mot-de-passe', ['token' => $token, 'erreur' => 'Le mot de passe doit contenir au moins 6 caractères.']);
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($reset['email']);

        if ($user) {
            $userModel->update($user['id'], ['password' => password_hash($password, PASSWORD_BCRYPT)]);
        }

        $resetModel->marquerUtilise($reset['id']);

        $_SESSION['success'] = "Mot de passe réinitialisé. Vous pouvez vous connecter.";
        header('Location: /connexion');
        exit;
    }
}