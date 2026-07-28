<?php

class StockController extends Controller {

    public function index() {
        $this->requireRole('ADMIN');

        $ingredientModel = new Ingredient();
        $ingredients = $ingredientModel->all();

        $this->render('admin/stocks', ['ingredients' => $ingredients]);
    }

    public function store() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $data = $this->validerDonnees();
        if (isset($data['erreur'])) {
            echo json_encode(['success' => false, 'message' => $data['erreur']]);
            return;
        }

        $ingredientModel = new Ingredient();
        $ingredientModel->create($data);

        echo json_encode(['success' => true, 'message' => 'Ingrédient ajouté.']);
    }

    public function update() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID manquant.']);
            return;
        }

        $data = $this->validerDonnees();
        if (isset($data['erreur'])) {
            echo json_encode(['success' => false, 'message' => $data['erreur']]);
            return;
        }

        $ingredientModel = new Ingredient();
        $ingredientModel->update($id, $data);

        echo json_encode(['success' => true, 'message' => 'Ingrédient mis à jour.']);
    }

    // Ajustement rapide de la quantité uniquement (sans passer par le formulaire complet)
    public function ajusterStock() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $quantite = $_POST['quantite_stock'] ?? '';

        if (!is_numeric($quantite) || $quantite < 0) {
            echo json_encode(['success' => false, 'message' => 'Quantité invalide.']);
            return;
        }

        $ingredientModel = new Ingredient();
        $ingredientModel->update($id, ['quantite_stock' => (float) $quantite]);

        echo json_encode(['success' => true, 'message' => 'Stock ajusté.']);
    }

    public function delete() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $ingredientModel = new Ingredient();
        $ingredientModel->delete($id);

        echo json_encode(['success' => true, 'message' => 'Ingrédient supprimé.']);
    }

    private function validerDonnees(): array {
        $nom = trim($_POST['nom'] ?? '');
        $stock = $_POST['quantite_stock'] ?? '';
        $unite = trim($_POST['unite'] ?? '');
        $seuil = $_POST['seuil_alerte'] ?? '';

        if (!$nom || $stock === '' || !$unite || $seuil === '') {
            return ['erreur' => 'Nom, stock, unité et seuil sont obligatoires.'];
        }
        if (!is_numeric($stock) || $stock < 0 || !is_numeric($seuil) || $seuil < 0) {
            return ['erreur' => 'Le stock et le seuil doivent être des nombres positifs.'];
        }

        return [
            'nom' => htmlspecialchars($nom),
            'quantite_stock' => (float) $stock,
            'unite' => htmlspecialchars($unite),
            'seuil_alerte' => (float) $seuil,
            'date_peremption' => !empty($_POST['date_peremption']) ? $_POST['date_peremption'] : null,
            'fournisseur' => htmlspecialchars(trim($_POST['fournisseur'] ?? '')),
        ];
    }
}