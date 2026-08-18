<?php

class VerificationEmail extends Model {
    protected string $table = 'verifications_email';

    public function genererEtEnvoyer(string $email, string $type): string {
        $code = (string) random_int(100000, 999999);

        $this->create([
            'email' => $email,
            'code' => $code,
            'type' => $type,
            'verifie' => 0,
        ]);

        return $code;
    }

    public function verifier(string $email, string $code, string $type): bool {
        $sql = "SELECT * FROM verifications_email 
                WHERE email = ? AND code = ? AND type = ? AND verifie = 0
                AND created_at >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
                ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $code, $type]);
        $entree = $stmt->fetch();

        if (!$entree) {
            return false;
        }

        $this->update($entree['id'], ['verifie' => 1, 'verifie_le' => date('Y-m-d H:i:s')]);
        return true;
    }

    // Vérifie qu'un email a été confirmé récemment pour ce type précis (utilisé côté serveur à la soumission finale)
    public function estRecemmentVerifie(string $email, string $type, int $minutes = 30): bool {
        $sql = "SELECT COUNT(*) AS total FROM verifications_email
                WHERE email = ? AND type = ? AND verifie = 1
                AND verifie_le >= DATE_SUB(NOW(), INTERVAL ? MINUTE)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $type, $minutes]);
        return (int) $stmt->fetch()['total'] > 0;
    }
}