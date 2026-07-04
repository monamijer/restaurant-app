<?php

class MenuAdminController extends Controller {

    public function index() {
        $this->requireRole('ADMIN');

        $platModel = new Plat();
        $categorieModel = new Categorie();

        $plats = $platModel->allWithCategorie();
        $categories = $categorieModel->allOrdered();

        $this->render('admin/menu', ['plats' => $plats, 'categories' => $categories]);
    }

    public function store() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $data = $this->validerDonnees();
        if (isset($data['erreur'])) {
            echo json_encode(['success' => false, 'message' => $data['erreur']]);
            return;
        }

        if (!empty($_FILES['image']['name'])) {
            $upload = $this->uploaderImage($_FILES['image']);
            if (isset($upload['erreur'])) {
                echo json_encode(['success' => false, 'message' => $upload['erreur']]);
                return;
            }
            $data['image'] = $upload['nom_fichier'];
        }

        $platModel = new Plat();
        $id = $platModel->create($data);

        echo json_encode(['success' => true, 'message' => 'Plat ajouté', 'id' => $id]);
    }

    public function update() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID manquant']);
            return;
        }

        $data = $this->validerDonnees();
        if (isset($data['erreur'])) {
            echo json_encode(['success' => false, 'message' => $data['erreur']]);
            return;
        }

        if (!empty($_FILES['image']['name'])) {
            $upload = $this->uploaderImage($_FILES['image']);
            if (isset($upload['erreur'])) {
                echo json_encode(['success' => false, 'message' => $upload['erreur']]);
                return;
            }
            $data['image'] = $upload['nom_fichier'];
        }

        $platModel = new Plat();
        $platModel->update($id, $data);

        echo json_encode(['success' => true, 'message' => 'Plat mis à jour']);
    }

    public function delete() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $platModel = new Plat();
        $plat = $platModel->find($id);

        if ($plat && $plat['image']) {
            $chemin = __DIR__ . '/../../../public/assets/uploads/' . $plat['image'];
            if (file_exists($chemin)) {
                unlink($chemin);
            }
        }

        $platModel->delete($id);
        echo json_encode(['success' => true, 'message' => 'Plat supprimé']);
    }

    public function toggleDisponibilite() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $disponible = (int) ($_POST['disponible'] ?? 0);

        $platModel = new Plat();
        $platModel->update($id, ['disponible' => $disponible]);

        echo json_encode(['success' => true]);
    }

    private function validerDonnees(): array {
        $nom = trim($_POST['nom'] ?? '');
        $prix = $_POST['prix'] ?? '';
        $categorieId = $_POST['categorie_id'] ?? '';

        if (!$nom || !$prix || !$categorieId) {
            return ['erreur' => 'Nom, prix et catégorie sont obligatoires.'];
        }
        if (!is_numeric($prix) || $prix <= 0) {
            return ['erreur' => 'Le prix doit être un nombre positif.'];
        }

        return [
            'nom' => htmlspecialchars($nom),
            'description' => htmlspecialchars(trim($_POST['description'] ?? '')),
            'prix' => (float) $prix,
            'categorie_id' => (int) $categorieId,
            'vegetarien' => isset($_POST['vegetarien']) ? 1 : 0,
            'sans_gluten' => isset($_POST['sans_gluten']) ? 1 : 0,
            'epice' => isset($_POST['epice']) ? 1 : 0,
            'disponible' => isset($_POST['disponible']) ? 1 : 0,
        ];
    }

    private function uploaderImage(array $fichier): array {
        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'webp'];
        $tailleMax = 3 * 1024 * 1024; // 3 Mo

        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionsAutorisees)) {
            return ['erreur' => 'Format d\'image non autorisé (jpg, png, webp uniquement).'];
        }
        if ($fichier['size'] > $tailleMax) {
            return ['erreur' => 'Image trop lourde (3 Mo maximum).'];
        }
        if (!getimagesize($fichier['tmp_name'])) {
            return ['erreur' => 'Le fichier n\'est pas une image valide.'];
        }

        $nomFichier = uniqid('plat_') . '.' . $extension;
        $destination = __DIR__ . '/../../../public/assets/uploads/' . $nomFichier;

        if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
            return ['erreur' => 'Échec de l\'upload de l\'image.'];
        }

        return ['nom_fichier' => $nomFichier];
    }
}