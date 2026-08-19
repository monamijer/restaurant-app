<?php

class Mailer {
    private static function envoyer(string $destinataire, string $nomDestinataire, string $sujet, string $htmlContenu): bool {
        $config = require __DIR__ . '/../../config/config.php';
        $apiKey = $config['brevo_api_key'];

        if (!$apiKey) {
            error_log("Brevo API key manquante — email non envoyé à $destinataire");
            return false;
        }

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'sender' => ['name' => 'Etoile d\'Or', 'email' => 'noreply@etoiledor.com'],
            'to' => [['email' => $destinataire, 'name' => $nomDestinataire]],
            'subject' => $sujet,
            'htmlContent' => $htmlContenu,
        ]));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            error_log("Erreur Brevo ($httpCode) : $response");
            return false;
        }
        return true;
    }

    public static function notifierAdminPaiementManuel(string $type, array $details, string $emailAdmin): void {
        $sujet = "Nouvelle demande de paiement à traiter — $type";
        $html = "
            <h3>Nouvelle demande nécessitant votre attention</h3>
            <p><strong>Type :</strong> $type</p>
            <p><strong>Client :</strong> {$details['nom']}</p>
            <p><strong>Téléphone :</strong> {$details['telephone']}</p>
            <p><strong>Méthode :</strong> {$details['mode_paiement']}</p>
            " . (!empty($details['reference']) ? "<p><strong>Référence :</strong> {$details['reference']}</p>" : "") . "
            <p><strong>Détail :</strong> {$details['description']}</p>
            <p>Connectez-vous à l'espace admin pour confirmer.</p>
        ";
        self::envoyer($emailAdmin, 'Admin', $sujet, $html);
    }

    public static function confirmerDemandeClient(string $emailClient, string $nomClient, string $sujet, string $message): void {
        $html = "<p>Bonjour $nomClient,</p><p>$message</p>";
        self::envoyer($emailClient, $nomClient, $sujet, $html);
    }
    // ⚠️ MODE_SIMULATION_TEMPORAIRE — À RETIRER dès que Brevo est configuré (clé API + expéditeur vérifié)
public static function envoyerCodeVerification(string $email, string $code): bool {
    $config = require __DIR__ . '/../../config/config.php';

    if (empty($config['brevo_api_key'])) {
        error_log("⚠️ SIMULATION — Code de vérification pour $email : $code");
        return true; // on fait comme si l'envoi avait réussi
    }
    // ⚠️ FIN MODE_SIMULATION_TEMPORAIRE

    $html = "
        <p>Voici votre code de vérification :</p>
        <h2 style='letter-spacing: 4px;'>$code</h2>
        <p>Ce code est valable 15 minutes. Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.</p>
    ";
    return self::envoyer($email, '', 'Votre code de vérification', $html);
}
}