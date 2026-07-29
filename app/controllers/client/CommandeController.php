<?php

class CommandeController extends Controller {

    // Ajout d'un plat au panier (AJAX)
    public function ajouterPanier() {
        header('Content-Type: application/json');

        $platId = (int) ($_POST['plat_id'] ?? 0);
        $quantite = max(1, (int) ($_POST['quantite'] ?? 1));

        $platModel = new Plat();
        $plat = $platModel->find($platId);

        if (!$plat || !$plat['disponible']) {
            echo json_encode(['success' => false, 'message' => 'Ce plat n\'est plus disponible.']);
            return;
        }

        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }

        if (isset($_SESSION['panier'][$platId])) {
            $_SESSION['panier'][$platId]['quantite'] += $quantite;
        } else {
            $_SESSION['panier'][$platId] = [
                'nom' => $plat['nom'],
                'prix' => $plat['prix'],
                'image' => $plat['image'],
                'quantite' => $quantite,
            ];
        }

        echo json_encode([
            'success' => true,
            'message' => $plat['nom'] . ' ajouté au panier',
            'nb_articles' => array_sum(array_column($_SESSION['panier'], 'quantite')),
        ]);
    }

    public function modifierPanier() {
        header('Content-Type: application/json');

        $platId = (int) ($_POST['plat_id'] ?? 0);
        $quantite = (int) ($_POST['quantite'] ?? 0);

        if ($quantite <= 0) {
            unset($_SESSION['panier'][$platId]);
        } elseif (isset($_SESSION['panier'][$platId])) {
            $_SESSION['panier'][$platId]['quantite'] = $quantite;
        }

        echo json_encode(['success' => true, 'panier' => $this->calculerPanier()]);
    }

    public function voirPanier() {
        if (!Auth::check()) {
            header('Location: /connexion');
            exit;
        }

        $panier = $this->calculerPanier();
        $this->render('client/panier', ['panier' => $panier]);
    }

    public function checkout() {
        if (!Auth::check()) {
            header('Location: /connexion');
            exit;
        }
        header('Content-Type: application/json');

        if (empty($_SESSION['panier'])) {
            echo json_encode(['success' => false, 'message' => 'Votre panier est vide.']);
            return;
        }

        $type = $_POST['type'] ?? '';
        $adresse = htmlspecialchars(trim($_POST['adresse_livraison'] ?? ''));
        $notes = htmlspecialchars(trim($_POST['notes'] ?? ''));

        if (!in_array($type, ['SUR_PLACE', 'EMPORTER', 'LIVRAISON'])) {
            echo json_encode(['success' => false, 'message' => 'Type de commande invalide.']);
            return;
        }
        if ($type === 'LIVRAISON' && !$adresse) {
            echo json_encode(['success' => false, 'message' => 'Adresse de livraison requise.']);
            return;
        }

        $total = 0;
        foreach ($_SESSION['panier'] as $item) {
            $total += $item['prix'] * $item['quantite'];
        }

        $commandeModel = new Commande();
        $commandeId = $commandeModel->creerAvecLignes([
            'user_id' => Auth::user()['id'],
            'type' => $type,
            'statut' => 'EN_ATTENTE',
            'total' => $total,
            'adresse_livraison' => $adresse ?: null,
            'notes' => $notes ?: null,
        ], $_SESSION['panier']);

        // Sur place : paiement à table, pas de Stripe nécessaire
        if ($type === 'SUR_PLACE') {
            unset($_SESSION['panier']);
            echo json_encode([
                'success' => true,
                'paiement_requis' => false,
                'commande_id' => $commandeId,
            ]);
            return;
        }

        // Emporter/Livraison : paiement en ligne obligatoire
        echo json_encode([
            'success' => true,
            'paiement_requis' => true,
            'commande_id' => $commandeId,
        ]);
    }

    public function creerPaiement() {
        header('Content-Type: application/json');
        $config = require __DIR__ . '/../../../config/config.php';
        \Stripe\Stripe::setApiKey($config['stripe_secret_key']);

        $commandeId = (int) ($_POST['commande_id'] ?? 0);
        $commandeModel = new Commande();
        $commande = $commandeModel->find($commandeId);

        if (!$commande || $commande['statut'] !== 'EN_ATTENTE') {
            echo json_encode(['success' => false, 'message' => 'Commande invalide.']);
            return;
        }

        $montantCentimes = (int) round($commande['total'] * 100);

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Commande #' . $commandeId],
                        'unit_amount' => $montantCentimes,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://restaurant.local/commande/suivi?id=' . $commandeId,
                'cancel_url' => 'http://restaurant.local/panier',
                'metadata' => ['type' => 'commande', 'commande_id' => $commandeId],
            ]);

            $commandeModel->update($commandeId, ['stripe_id' => $session->id]);
            echo json_encode(['success' => true, 'checkout_url' => $session->url]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur Stripe : ' . $e->getMessage()]);
        }
    }

    public function suivi() {
        $id = (int) ($_GET['id'] ?? 0);
        $commandeModel = new Commande();
        $commande = $commandeModel->find($id);

        if ($commande) {
            unset($_SESSION['panier']); // vidé une fois la commande finalisée
        }

        $this->render('client/suivi-commande', ['commande' => $commande]);
    }

    // Endpoint AJAX de polling pour le suivi en temps réel
    public function statutAjax() {
        header('Content-Type: application/json');
        $id = (int) ($_GET['id'] ?? 0);

        $commandeModel = new Commande();
        $commande = $commandeModel->find($id);

        echo json_encode(['statut' => $commande['statut'] ?? null]);
    }

    private function calculerPanier(): array {
        $panier = $_SESSION['panier'] ?? [];
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return ['items' => $panier, 'total' => $total];
    }
}