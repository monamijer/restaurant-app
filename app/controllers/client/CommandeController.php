<?php

class CommandeController extends Controller
{
    public function ajouterPanier()
    {
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

    public function modifierPanier()
    {
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

    public function voirPanier()
    {
        $panier = $this->calculerPanier();
        $parametreModel = new Parametre();
        $params = $parametreModel->getAll();

        $this->render('client/panier', ['panier' => $panier, 'params' => $params]);
    }

    public function checkout()
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['panier'])) {
            echo json_encode(['success' => false, 'message' => 'Votre panier est vide.']);
            return;
        }

        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $type = $_POST['type'] ?? '';
        $adresse = htmlspecialchars(trim($_POST['adresse_livraison'] ?? ''));
        $notes = htmlspecialchars(trim($_POST['notes'] ?? ''));

        if (!$nom || !$email) {
            echo json_encode(['success' => false, 'message' => 'Nom et email sont obligatoires.']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email invalide.']);
            return;
        }

        $verifModel = new VerificationEmail();
        if (!$verifModel->estRecemmentVerifie($email, 'COMMANDE')) {
            echo json_encode(['success' => false, 'message' => 'Veuillez vérifier votre email avant de continuer.']);
            return;
        }

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
            'user_id' => null,
            'guest_nom' => htmlspecialchars($nom),
            'guest_email' => $email,
            'guest_telephone' => htmlspecialchars($telephone),
            'type' => $type,
            'statut' => 'EN_ATTENTE',
            'total' => $total,
            'adresse_livraison' => $adresse ?: null,
            'notes' => $notes ?: null,
            'statut_paiement' => $type === 'SUR_PLACE' ? 'NON_REQUIS' : 'EN_ATTENTE',
        ], $_SESSION['panier']);

        unset($_SESSION['panier']);

        if ($type === 'SUR_PLACE') {
            echo json_encode(['success' => true, 'paiement_requis' => false, 'commande_id' => $commandeId]);
            return;
        }

        echo json_encode(['success' => true, 'paiement_requis' => true, 'commande_id' => $commandeId]);
    }

    public function creerPaiement()
    {
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
            $parametreModel = new Parametre();
            $config['stripe_devise'] = $parametreModel->get('devise_stripe', 'usd');

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $config['stripe_devise'] ?? 'usd',
                        'product_data' => ['name' => 'Commande #' . $commandeId],
                        'unit_amount' => $montantCentimes,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $config['url_site'] . '/commande/suivi?id=' . $commandeId,
                'cancel_url' => $config['url_site'] . '/panier',
                'metadata' => ['type' => 'commande', 'commande_id' => $commandeId],
            ]);

            $commandeModel->update($commandeId, ['stripe_id' => $session->id, 'mode_paiement' => 'STRIPE', 'statut_paiement' => 'EN_ATTENTE']);
            echo json_encode(['success' => true, 'checkout_url' => $session->url]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur Stripe : ' . $e->getMessage()]);
        }
    }

    public function paiementManuel()
    {
        header('Content-Type: application/json');

        $commandeId = (int) ($_POST['commande_id'] ?? 0);
        $modePaiement = $_POST['mode_paiement'] ?? '';
        $reference = htmlspecialchars(trim($_POST['reference_paiement'] ?? ''));

        if (!in_array($modePaiement, ['AIRTEL_MONEY', 'ORANGE_MONEY', 'MPESA', 'CONTACT_RESTAURANT'])) {
            echo json_encode(['success' => false, 'message' => 'Mode de paiement invalide.']);
            return;
        }

        $commandeModel = new Commande();
        $data = ['mode_paiement' => $modePaiement];

        if ($modePaiement === 'CONTACT_RESTAURANT') {
            $data['statut_paiement'] = 'EN_ATTENTE';
        } else {
            if (!$reference) {
                echo json_encode(['success' => false, 'message' => 'Référence de transaction requise.']);
                return;
            }
            $data['reference_paiement'] = $reference;
            $data['statut_paiement'] = 'VERIFICATION_MANUELLE';
        }

        $commandeModel->update($commandeId, $data);

        $commande = $commandeModel->findAvecUser($commandeId);
        $parametreModel = new Parametre();
        $emailAdmin = $parametreModel->get('email_contact');

        Mailer::notifierAdminPaiementManuel('Commande #' . $commandeId, [
            'nom' => $commande['user_nom'],
            'telephone' => $commande['telephone'] ?? 'non renseigné',
            'mode_paiement' => $modePaiement,
            'reference' => $reference,
            'description' => $commande['type'] . ' — ' . number_format($commande['total'], 0, ',', ' ') . ' ' . $parametreModel->get('devise', 'BIF'),
        ], $emailAdmin);

        $messageClient = $modePaiement === 'CONTACT_RESTAURANT'
            ? 'Votre commande a été reçue. Nous allons vous contacter pour convenir du paiement.'
            : 'Votre commande a été reçue et votre paiement est en cours de vérification.';

        Mailer::confirmerDemandeClient($commande['email'], $commande['user_nom'], 'Votre commande', $messageClient);

        echo json_encode(['success' => true, 'message' => 'Enregistré.']);
    }

    public function suivi()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $commandeModel = new Commande();
        $commande = $commandeModel->find($id);

        $this->render('client/suivi-commande', ['commande' => $commande]);
    }

    public function statutAjax()
    {
        header('Content-Type: application/json');
        $id = (int) ($_GET['id'] ?? 0);

        $commandeModel = new Commande();
        $commande = $commandeModel->find($id);

        echo json_encode(['statut' => $commande['statut'] ?? null]);
    }

    private function calculerPanier(): array
    {
        $panier = $_SESSION['panier'] ?? [];
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return ['items' => $panier, 'total' => $total];
    }
}
