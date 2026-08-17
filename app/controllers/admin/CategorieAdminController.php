<?php

class CategorieAdminController extends Controller {

    public function index() {
        $this->requireRole('ADMIN');

        $categorieModel = new Categorie();
        $categories = $categorieModel->allOrdered();

        $this->render('admin/categories', ['categories' => $categories]);
    }

    public function store() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $nom = trim($_POST['nom'] ?? '');
        if (!$nom) {
            echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire.']);
            return;
        }

        $categorieModel = new Categorie();
        $categorieModel->create([
            'nom' => htmlspecialchars($nom),
            'ordre' => $categorieModel->prochainOrdre(),
        ]);

        echo json_encode(['success' => true, 'message' => 'Catégorie ajoutée.']);
    }

    public function update() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');

        if (!$id || !$nom) {
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            return;
        }

        $categorieModel = new Categorie();
        $categorieModel->update($id, ['nom' => htmlspecialchars($nom)]);

        echo json_encode(['success' => true, 'message' => 'Catégorie mise à jour.']);
    }

    public function delete() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $categorieModel = new Categorie();

        if ($categorieModel->estUtilisee($id)) {
            echo json_encode(['success' => false, 'message' => 'Impossible de supprimer : des plats utilisent encore cette catégorie.']);
            return;
        }

        $categorieModel->delete($id);
        echo json_encode(['success' => true, 'message' => 'Catégorie supprimée.']);
    }

    // Réordonnancement par glisser-déposer
    public function reordonner() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $ordres = $_POST['ordres'] ?? []; // ex: [3, 1, 2] = liste des IDs dans le nouvel ordre

        $categorieModel = new Categorie();
        foreach ($ordres as $index => $id) {
            $categorieModel->update((int) $id, ['ordre' => $index + 1]);
        }

        echo json_encode(['success' => true]);
    }
}