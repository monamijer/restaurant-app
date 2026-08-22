<?php

class DepenseController extends Controller
{
    public function index()
    {
        $this->requireRole('ADMIN');

        $mois = $_GET['mois'] ?? date('Y-m');
        $dateDebut = $mois . '-01';
        $dateFin = date('Y-m-t', strtotime($dateDebut));

        $depenseModel = new Depense();
        $categorieModel = new CategorieDepense();
        $parametreModel = new Parametre();

        $depenses = $depenseModel->toutesAvecCategorie($dateDebut, $dateFin);
        $total = $depenseModel->totalPeriode($dateDebut, $dateFin);
        $parCategorie = $depenseModel->totalParCategoriePeriode($dateDebut, $dateFin);
        $categories = $categorieModel->allOrdered();

        $this->render('admin/depenses', [
            'depenses' => $depenses,
            'total' => $total,
            'parCategorie' => $parCategorie,
            'categories' => $categories,
            'moisActuel' => $mois,
            'devise' => $parametreModel->get('devise', 'BIF'),
        ]);
    }

    public function store()
    {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $data = $this->validerDonnees();
        if (isset($data['erreur'])) {
            echo json_encode(['success' => false, 'message' => $data['erreur']]);
            return;
        }

        $data['saisie_par_user_id'] = Auth::user()['id'];

        $depenseModel = new Depense();
        $depenseModel->create($data);

        echo json_encode(['success' => true, 'message' => 'Dépense enregistrée.']);
    }

    public function update()
    {
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

        $depenseModel = new Depense();
        $depenseModel->update($id, $data);

        echo json_encode(['success' => true, 'message' => 'Dépense mise à jour.']);
    }

    public function delete()
    {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $depenseModel = new Depense();
        $depenseModel->delete($id);

        echo json_encode(['success' => true, 'message' => 'Dépense supprimée.']);
    }

    private function validerDonnees(): array
    {
        $categorieId = (int) ($_POST['categorie_id'] ?? 0);
        $montant = $_POST['montant'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $dateDepense = $_POST['date_depense'] ?? '';

        if (!$categorieId || !$montant || !$description || !$dateDepense) {
            return ['erreur' => 'Catégorie, montant, description et date sont obligatoires.'];
        }
        if (!is_numeric($montant) || $montant <= 0) {
            return ['erreur' => 'Le montant doit être un nombre positif.'];
        }

        return [
            'categorie_id' => $categorieId,
            'montant' => (float) $montant,
            'description' => htmlspecialchars($description),
            'fournisseur' => htmlspecialchars(trim($_POST['fournisseur'] ?? '')),
            'date_depense' => $dateDepense,
        ];
    }
}
