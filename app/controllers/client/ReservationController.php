<?php

class ReservationController extends Controller {

    public function index() {
        $parametreModel = new Parametre();
        $params = $parametreModel->getAll();

        $this->render('client/reservation', ['params' => $params]);
    }

    public function store() {
        header('Content-Type: application/json');

        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $date = $_POST['date'] ?? '';
        $heure = $_POST['heure'] ?? '';
        $nbPersonnes = (int) ($_POST['nb_personnes'] ?? 0);
        $notes = htmlspecialchars(trim($_POST['notes'] ?? ''));

        if (!$nom || !$email || !$date || !$heure || $nbPersonnes < 1) {
            echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email invalide.']);
            return;
        }

        // Sécurité serveur : l'email doit avoir été vérifié il y a moins de 30 minutes
        $verifModel = new VerificationEmail();
        if (!$verifModel->estRecemmentVerifie($email, 'RESERVATION')) {
            echo json_encode(['success' => false, 'message' => 'Veuillez vérifier votre email avant de continuer.']);
            return;
        }

        $dateReservation = $date . ' ' . $heure . ':00';

        if (strtotime($dateReservation) < time()) {
            echo json_encode(['success' => false, 'message' => 'La date sélectionnée est déjà passée.']);
            return;
        }

        $fermetureModel = new Fermeture();
        if ($fermetureModel->estFerme($dateReservation)) {
            echo json_encode(['success' => false, 'message' => 'Le restaurant est fermé à cette date.']);
            return;
        }

        $tableModel = new TableResto();
        $table = $tableModel->trouverTableDisponible($dateReservation, $nbPersonnes);

        if (!$table) {
            echo json_encode([
                'success' => false,
                'message' => 'Aucune table disponible pour ce créneau.',
                'liste_attente_possible' => true,
            ]);
            return;
        }

        $parametreModel = new Parametre();
        $acompteActif = (bool) $parametreModel->get('acompte_actif', '1');
        $seuilPersonnes = (int) $parametreModel->get('nb_personnes_min_acompte', 1);
        $montantParPersonne = (float) $parametreModel->get('montant_acompte_par_personne', 0);

        $montantAcompte = null;
        $statutInitial = 'CONFIRMEE';
        $statutAcompte = 'PAYE';

        if ($acompteActif && $nbPersonnes >= $seuilPersonnes) {
            $montantAcompte = $montantParPersonne * $nbPersonnes;
            $statutInitial = 'EN_ATTENTE_ACOMPTE';
            $statutAcompte = 'EN_ATTENTE';
        }

        $reservationModel = new Reservation();
        $reservationId = $reservationModel->create([
            'user_id' => null,
            'guest_nom' => htmlspecialchars($nom),
            'guest_email' => $email,
            'guest_telephone' => htmlspecialchars($telephone),
            'table_id' => $table['id'],
            'date_reservation' => $dateReservation,
            'nb_personnes' => $nbPersonnes,
            'statut' => $statutInitial,
            'notes' => $notes,
            'montant_acompte' => $montantAcompte,
            'statut_acompte' => $statutAcompte,
        ]);

        if (!$montantAcompte) {
            echo json_encode([
                'success' => true,
                'acompte_requis' => false,
                'message' => 'Réservation confirmée !',
                'reservation_id' => $reservationId,
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'acompte_requis' => true,
            'reservation_id' => $reservationId,
            'montant' => $montantAcompte,
        ]);
    }

    public function creerPaiement() {
        header('Content-Type: application/json');
        $config = require __DIR__ . '/../../../config/config.php';
        \Stripe\Stripe::setApiKey($config['stripe_secret_key']);

        $reservationId = (int) ($_POST['reservation_id'] ?? 0);
        $reservationModel = new Reservation();
        $reservation = $reservationModel->find($reservationId);

        if (!$reservation || $reservation['statut_acompte'] !== 'EN_ATTENTE') {
            echo json_encode(['success' => false, 'message' => 'Réservation invalide.']);
            return;
        }

        $montantCentimes = (int) round($reservation['montant_acompte'] * 100);

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Acompte réservation - ' . $reservation['nb_personnes'] . ' personnes'],
                        'unit_amount' => $montantCentimes,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://restaurant.local/reservation/confirmation?id=' . $reservationId,
                'cancel_url' => 'http://restaurant.local/reserver',
                'metadata' => ['type' => 'reservation', 'reservation_id' => $reservationId],
            ]);

            $reservationModel->update($reservationId, ['stripe_payment_id' => $session->id, 'mode_paiement' => 'STRIPE']);
            echo json_encode(['success' => true, 'checkout_url' => $session->url]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur Stripe : ' . $e->getMessage()]);
        }
    }

    public function paiementManuel() {
        header('Content-Type: application/json');

        $reservationId = (int) ($_POST['reservation_id'] ?? 0);
        $modePaiement = $_POST['mode_paiement'] ?? '';
        $reference = htmlspecialchars(trim($_POST['reference_paiement'] ?? ''));

        if (!in_array($modePaiement, ['AIRTEL_MONEY', 'ORANGE_MONEY', 'MPESA', 'CONTACT_RESTAURANT'])) {
            echo json_encode(['success' => false, 'message' => 'Mode de paiement invalide.']);
            return;
        }

        $reservationModel = new Reservation();
        $data = ['mode_paiement' => $modePaiement];

        if ($modePaiement === 'CONTACT_RESTAURANT') {
            $data['statut_acompte'] = 'EN_ATTENTE';
        } else {
            if (!$reference) {
                echo json_encode(['success' => false, 'message' => 'Référence de transaction requise.']);
                return;
            }
            $data['reference_paiement'] = $reference;
            $data['statut_acompte'] = 'VERIFICATION_MANUELLE';
        }

        $reservationModel->update($reservationId, $data);

        $reservation = $reservationModel->find($reservationId);
        $parametreModel = new Parametre();
        $emailAdmin = $parametreModel->get('email_contact');

        Mailer::notifierAdminPaiementManuel('Réservation #' . $reservationId, [
            'nom' => $reservation['guest_nom'],
            'telephone' => $reservation['guest_telephone'] ?? 'non renseigné',
            'mode_paiement' => $modePaiement,
            'reference' => $reference,
            'description' => $reservation['nb_personnes'] . ' personnes le ' . date('d/m/Y à H:i', strtotime($reservation['date_reservation'])),
        ], $emailAdmin);

        $messageClient = $modePaiement === 'CONTACT_RESTAURANT'
            ? 'Votre demande de réservation a été reçue. Nous allons vous contacter prochainement pour convenir du paiement.'
            : 'Votre réservation a été reçue et votre paiement est en cours de vérification. Vous recevrez une confirmation sous peu.';

        Mailer::confirmerDemandeClient($reservation['guest_email'], $reservation['guest_nom'], 'Votre demande de réservation', $messageClient);

        echo json_encode(['success' => true, 'message' => 'Enregistré.']);
    }

    public function confirmation() {
        $id = (int) ($_GET['id'] ?? 0);
        $reservationModel = new Reservation();
        $reservation = $reservationModel->find($id);

        $this->render('client/reservation-confirmation', ['reservation' => $reservation]);
    }
}