<?php

class AuthController extends Controller {

    public function loginForm() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        $this->render('auth/login', ['error' => $error]);
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
            header('Location: ' . (Auth::role() === 'ADMIN' ? '/admin' : '/'));
            exit;
        }

        $_SESSION['error'] = "Email ou mot de passe incorrect.";
        header('Location: /connexion');
        exit;
    }

    public function registerForm() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        $this->render('auth/register', ['error' => $error]);
    }

    public function register() {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $telephone = trim($_POST['telephone'] ?? '');

        if (!$nom || !$email || !$password) {
            $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
            header('Location: /inscription');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères.";
            header('Location: /inscription');
            exit;
        }

        $userModel = new User();
        if ($userModel->emailExists($email)) {
            $_SESSION['error'] = "Un compte existe déjà avec cet email.";
            header('Location: /inscription');
            exit;
        }

        $userId = $userModel->createUser([
            'nom' => $nom,
            'email' => $email,
            'password' => $password,
            'telephone' => $telephone,
            'role' => 'CLIENT',
        ]);

        Auth::login($userModel->find($userId));
        header('Location: /');
        exit;
    }

    public function logout() {
        Auth::logout();
        header('Location: /');
        exit;
    }
}