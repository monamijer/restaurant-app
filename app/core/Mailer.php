<?php

class Mailer
{
    public static $derniereErreur = '';

    private static function envoyer(string $destinataire, string $nomDestinataire, string $sujet, string $htmlContenu): bool
    {
        $config = require __DIR__ . '/../../config/config.php';
        $apiKey = $config['brevo_api_key'];

        if (!$apiKey) {
            self::$derniereErreur = 'Clé API manquante';
            return false;
        }
        if (empty($config['brevo_sender_email'])) {
            self::$derniereErreur = 'Sender email manquant';
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
            'sender' => ['name' => $config['brevo_sender_name'], 'email' => $config['brevo_sender_email']],
            'to' => [['email' => $destinataire, 'name' => $nomDestinataire]],
            'subject' => $sujet,
            'htmlContent' => $htmlContenu,
        ]));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            self::$derniereErreur = "HTTP $httpCode : $response";
            return false;
        }
        self::$derniereErreur = '';
        return true;
    }

    public static function notifierAdminPaiementManuel(string $type, array $details, string $emailAdmin): void
    {
        $sujet = "Nouvelle demande de paiement à traiter — $type";
        $html = "
            <h3>Nouvelle demande nécessitant votre attention</h3>
            <p><strong>Type :</strong> $type</p>
            <p><strong>Client :</strong> {$details['nom']}</p>
            <p><strong>Téléphone :</strong> {$details['telephone']}</p>
            <p><strong>Méthode :</strong> {$details['mode_paiement']}</p>
            " . (!empty($details['reference']) ? "<p><strong>Référence :</strong> {$details['reference']}</p>" : '') . "
            <p><strong>Détail :</strong> {$details['description']}</p>
            <p>Connectez-vous à l'espace admin pour confirmer.</p>
        ";
        self::envoyer($emailAdmin, 'Admin', $sujet, $html);
    }

    public static function confirmerDemandeClient(string $emailClient, string $nomClient, string $sujet, string $message): void
    {
        $html = "<p>Bonjour $nomClient,</p><p>$message</p>";
        self::envoyer($emailClient, $nomClient, $sujet, $html);
    }

    public static function envoyerCodeVerification(string $email, string $code): bool
    {
        $html = "
        <p>Voici votre code de vérification :</p>
        <h2 style='letter-spacing: 4px;'>$code</h2>
        <p>Ce code est valable 15 minutes. Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.</p>
    ";
        return self::envoyer($email, '', 'Votre code de vérification', $html);
    }

    public static function envoyerLienReinitialisation(string $email, string $lien): bool
    {
        $html = "
        <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
        <p><a href='$lien' style='background:#b8894f;color:#fff;padding:10px 20px;text-decoration:none;border-radius:4px;'>Réinitialiser mon mot de passe</a></p>
        <p>Ce lien est valable 30 minutes et ne peut être utilisé qu'une seule fois. Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.</p>
    ";
        return self::envoyer($email, '', 'Réinitialisation de votre mot de passe', $html);
    }
}
