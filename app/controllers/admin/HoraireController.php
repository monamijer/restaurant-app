<?php

class HoraireController extends Controller
{
    private array $joursNoms = [0 => 'Dimanche', 1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];

    public function index()
    {
        $this->requireRole('ADMIN');

        $horaireModel = new HoraireOuverture();
        $fermetureModel = new Fermeture();

        $horairesBrut = $horaireModel->getSemaine();
        $horaires = [];
        foreach ($horairesBrut as $h) {
            $horaires[$h['jour_semaine']] = $h;
        }

        $this->render('admin/horaires', [
            'horaires' => $horaires,
            'joursNoms' => $this->joursNoms,
            'fermetures' => $fermetureModel->aVenir(),
        ]);
    }

    public function mettreAJour()
    {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $horaireModel = new HoraireOuverture();
        $donnees = [];

        foreach (range(0, 6) as $jour) {
            $donnees[$jour] = [
                'ferme' => isset($_POST['ferme'][$jour]) ? 1 : 0,
                'heure_ouverture' => $_POST['heure_ouverture'][$jour] ?? null,
                'heure_fermeture' => $_POST['heure_fermeture'][$jour] ?? null,
            ];
        }

        $horaireModel->mettreAJourSemaine($donnees);

        echo json_encode(['success' => true, 'message' => 'Horaires mis à jour.']);
    }

    public function ajouterFermeture()
    {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $dateDebut = $_POST['date_debut'] ?? '';
        $dateFin = $_POST['date_fin'] ?? '';
        $raison = htmlspecialchars(trim($_POST['raison'] ?? ''));

        if (!$dateDebut || !$dateFin) {
            echo json_encode(['success' => false, 'message' => 'Les deux dates sont obligatoires.']);
            return;
        }
        if (strtotime($dateFin) < strtotime($dateDebut)) {
            echo json_encode(['success' => false, 'message' => 'La date de fin doit être après la date de début.']);
            return;
        }

        $fermetureModel = new Fermeture();
        $fermetureModel->create([
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'raison' => $raison,
        ]);

        echo json_encode(['success' => true, 'message' => 'Fermeture ajoutée.']);
    }

    public function supprimerFermeture()
    {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $fermetureModel = new Fermeture();
        $fermetureModel->delete($id);

        echo json_encode(['success' => true, 'message' => 'Fermeture supprimée.']);
    }
}
