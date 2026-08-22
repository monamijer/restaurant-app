<?php

class ClientController extends Controller
{
    public function index()
    {
        $this->requireRole('ADMIN');

        $sql = "SELECT 
                    guest_email AS email,
                    MAX(guest_nom) AS nom,
                    MAX(guest_telephone) AS telephone,
                    COUNT(*) AS nb_reservations,
                    0 AS nb_commandes,
                    0 AS total_depense
                FROM reservations
                WHERE guest_email IS NOT NULL
                GROUP BY guest_email

                UNION ALL

                SELECT 
                    guest_email AS email,
                    MAX(guest_nom) AS nom,
                    MAX(guest_telephone) AS telephone,
                    0 AS nb_reservations,
                    COUNT(*) AS nb_commandes,
                    COALESCE(SUM(CASE WHEN statut != 'ANNULEE' THEN total ELSE 0 END), 0) AS total_depense
                FROM commandes
                WHERE guest_email IS NOT NULL
                GROUP BY guest_email";

        $db = Database::getInstance();
        $lignes = $db->query($sql)->fetchAll();

        // Fusionne les lignes par email (une personne peut apparaître dans les deux blocs)
        $clients = [];
        foreach ($lignes as $l) {
            $email = $l['email'];
            if (!isset($clients[$email])) {
                $clients[$email] = [
                    'email' => $email,
                    'nom' => $l['nom'],
                    'telephone' => $l['telephone'],
                    'nb_reservations' => 0,
                    'nb_commandes' => 0,
                    'total_depense' => 0,
                ];
            }
            $clients[$email]['nb_reservations'] += (int) $l['nb_reservations'];
            $clients[$email]['nb_commandes'] += (int) $l['nb_commandes'];
            $clients[$email]['total_depense'] += (float) $l['total_depense'];
            if (!$clients[$email]['nom']) {
                $clients[$email]['nom'] = $l['nom'];
            }
            if (!$clients[$email]['telephone']) {
                $clients[$email]['telephone'] = $l['telephone'];
            }
        }

        usort($clients, fn ($a, $b) => $b['total_depense'] <=> $a['total_depense']);

        $parametreModel = new Parametre();
        $this->render('admin/clients', ['clients' => array_values($clients), 'devise' => $parametreModel->get('devise', 'BIF')]);
    }

    public function detail()
    {
        $this->requireRole('ADMIN');

        $email = trim($_GET['email'] ?? '');
        if (!$email) {
            http_response_code(404);
            echo 'Client introuvable.';
            return;
        }

        $reservationModel = new Reservation();
        $commandeModel = new Commande();

        $db = Database::getInstance();

        $stmt = $db->prepare('SELECT * FROM reservations WHERE guest_email = ? ORDER BY date_reservation DESC LIMIT 10');
        $stmt->execute([$email]);
        $reservations = $stmt->fetchAll();

        $stmt = $db->prepare('SELECT * FROM commandes WHERE guest_email = ? ORDER BY created_at DESC LIMIT 10');
        $stmt->execute([$email]);
        $commandes = $stmt->fetchAll();

        $stmt = $db->prepare("SELECT SUM(CASE WHEN statut = 'NO_SHOW' THEN 1 ELSE 0 END) AS total FROM reservations WHERE guest_email = ?");
        $stmt->execute([$email]);
        $nbNoShow = (int) ($stmt->fetch()['total'] ?? 0);

        $nom = $reservations[0]['guest_nom'] ?? ($commandes[0]['guest_nom'] ?? $email);
        $telephone = $reservations[0]['guest_telephone'] ?? ($commandes[0]['guest_telephone'] ?? null);

        $parametreModel = new Parametre();

        $this->render('admin/client-detail', [
            'email' => $email,
            'nom' => $nom,
            'telephone' => $telephone,
            'nbNoShow' => $nbNoShow,
            'reservations' => $reservations,
            'commandes' => $commandes,
            'devise' => $parametreModel->get('devise', 'BIF'),
        ]);
    }
}
