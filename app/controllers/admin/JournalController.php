<?php

class JournalController extends Controller {

    public function index() {
        $this->requireAnyRole(['ADMIN', 'SERVEUR']);

        $commandeModel = new Commande();
        $platModel = new Plat();
        $parametreModel = new Parametre();

        // Un serveur ne voit que ses propres entrées ; l'admin voit tout le monde
        $userId = Auth::role() === 'SERVEUR' ? Auth::user()['id'] : null;

        $entrees = $commandeModel->journalDuJour($userId);
        $totalJour = array_sum(array_column($entrees, 'total'));
        $parModePaiement = $commandeModel->totalParModePaiementJour($userId);
        $plats = $platModel->filtrer([]);

        $this->render('admin/journal', [
            'entrees' => $entrees,
            'totalJour' => $totalJour,
            'parModePaiement' => $parModePaiement,
            'plats' => $plats,
            'devise' => $parametreModel->get('devise', 'BIF'),
        ]);
    }

    public function store() {
        $this->requireAnyRole(['ADMIN', 'SERVEUR']);
        header('Content-Type: application/json');

        $platIds = $_POST['plat_id'] ?? [];
        $quantites = $_POST['quantite'] ?? [];
        $modePaiement = $_POST['mode_paiement'] ?? '';

        if (empty($platIds) || count($platIds) !== count($quantites)) {
            echo json_encode(['success' => false, 'message' => 'Aucun plat sélectionné.']);
            return;
        }
        if (!in_array($modePaiement, ['ESPECES', 'AIRTEL_MONEY', 'ORANGE_MONEY', 'MPESA'])) {
            echo json_encode(['success' => false, 'message' => 'Mode de paiement invalide.']);
            return;
        }

        // Le prix de chaque plat est relu en base — jamais fait confiance à ce que le formulaire envoie
        $platModel = new Plat();
        $lignes = [];
        $total = 0;

        foreach ($platIds as $i => $platId) {
            $platId = (int) $platId;
            $quantite = max(1, (int) ($quantites[$i] ?? 1));
            $plat = $platModel->find($platId);
            if (!$plat) continue;

            $lignes[$platId] = ['nom' => $plat['nom'], 'prix' => $plat['prix'], 'quantite' => $quantite];
            $total += $plat['prix'] * $quantite;
        }

        if (empty($lignes)) {
            echo json_encode(['success' => false, 'message' => 'Aucun plat valide.']);
            return;
        }

        $commandeModel = new Commande();
        $commandeModel->creerAvecLignes([
            'user_id' => null,
            'guest_nom' => 'Client sur place',
            'guest_email' => null,
            'guest_telephone' => null,
            'saisie_par_user_id' => Auth::user()['id'],
            'type' => 'SUR_PLACE',
            'statut' => 'SERVIE',
            'total' => $total,
            'mode_paiement' => $modePaiement,
            'statut_paiement' => 'PAYE',
        ], $lignes);

        echo json_encode(['success' => true, 'message' => 'Entrée enregistrée.', 'total' => $total]);
    }
}