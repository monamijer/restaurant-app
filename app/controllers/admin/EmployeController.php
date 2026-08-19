<?php

class EmployeController extends Controller {

    public function index() {
        $this->requireRole('ADMIN');

        $userModel = new User();
        $employes = $userModel->tousLesEmployes();

        $this->render('admin/employes', ['employes' => $employes]);
    }

    public function store() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $data = $this->validerDonnees();
        if (isset($data['erreur'])) {
            echo json_encode(['success' => false, 'message' => $data['erreur']]);
            return;
        }

        $userModel = new User();
        if ($userModel->emailExists($data['email'])) {
            echo json_encode(['success' => false, 'message' => 'Un compte existe déjà avec cet email.']);
            return;
        }

        $userModel->createUser($data);
        echo json_encode(['success' => true, 'message' => 'Employé ajouté.']);
    }

    public function update() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID manquant.']);
            return;
        }

        if ($id === Auth::user()['id'] && ($_POST['role'] ?? '') !== 'ADMIN') {
            echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas modifier votre propre rôle.']);
            return;
        }

        $nom = trim($_POST['nom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $role = $_POST['role'] ?? '';

        if (!$nom || !in_array($role, ['SERVEUR', 'CUISINE', 'ADMIN'])) {
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            return;
        }

        $userModel = new User();
        $updateData = [
            'nom' => htmlspecialchars($nom),
            'telephone' => htmlspecialchars($telephone),
            'role' => $role,
        ];

        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 6) {
                echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères.']);
                return;
            }
            $updateData['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }

        $userModel->update($id, $updateData);
        echo json_encode(['success' => true, 'message' => 'Employé mis à jour.']);
    }

    public function delete() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);

        if ($id === Auth::user()['id']) {
            echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas supprimer votre propre compte.']);
            return;
        }

        $userModel = new User();
        $userModel->delete($id);

        echo json_encode(['success' => true, 'message' => 'Employé supprimé.']);
    }

    private function validerDonnees(): array {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $telephone = trim($_POST['telephone'] ?? '');
        $role = $_POST['role'] ?? '';

        if (!$nom || !$email || !$password || !in_array($role, ['SERVEUR', 'CUISINE', 'ADMIN'])) {
            return ['erreur' => 'Nom, email, mot de passe et rôle sont obligatoires.'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['erreur' => 'Email invalide.'];
        }
        if (strlen($password) < 6) {
            return ['erreur' => 'Le mot de passe doit contenir au moins 6 caractères.'];
        }

        return [
            'nom' => htmlspecialchars($nom),
            'email' => $email,
            'password' => $password,
            'telephone' => htmlspecialchars($telephone),
            'role' => $role,
        ];
    }
}