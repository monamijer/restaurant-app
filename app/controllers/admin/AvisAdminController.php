<?php

class AvisAdminController extends Controller {

    public function index() {
        $this->requireRole('ADMIN');

        $avisModel = new Avis();
        $avis = $avisModel->tousAvecUser();

        $this->render('admin/avis', ['avis' => $avis]);
    }

    public function repondre() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $reponse = htmlspecialchars(trim($_POST['reponse'] ?? ''));

        if (!$reponse) {
            echo json_encode(['success' => false, 'message' => 'La réponse ne peut pas être vide.']);
            return;
        }

        $avisModel = new Avis();
        $avisModel->update($id, ['reponse_admin' => $reponse]);

        echo json_encode(['success' => true, 'message' => 'Réponse enregistrée.']);
    }

    public function delete() {
        $this->requireRole('ADMIN');
        header('Content-Type: application/json');

        $id = (int) ($_POST['id'] ?? 0);
        $avisModel = new Avis();
        $avisModel->delete($id);

        echo json_encode(['success' => true, 'message' => 'Avis supprimé.']);
    }
}