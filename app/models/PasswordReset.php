<?php

class PasswordReset extends Model
{
    protected string $table = 'password_resets';

    public function creerToken(string $email): string
    {
        $token = bin2hex(random_bytes(32));

        $this->create([
            'email' => $email,
            'token' => $token,
            'expire_le' => date('Y-m-d H:i:s', strtotime('+30 minutes')),
        ]);

        return $token;
    }

    public function trouverValide(string $token): ?array
    {
        $sql = 'SELECT * FROM password_resets 
                WHERE token = ? AND utilise = 0 AND expire_le > NOW()
                ORDER BY created_at DESC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch() ?: null;
    }

    public function marquerUtilise(int $id): void
    {
        $this->update($id, ['utilise' => 1]);
    }

    // Anti-abus : empêche de spammer les demandes de réinitialisation
    public function demandeRecenteExiste(string $email, int $minutes = 5): bool
    {
        $sql = 'SELECT COUNT(*) AS total FROM password_resets 
                WHERE email = ? AND created_at >= DATE_SUB(NOW(), INTERVAL ? MINUTE)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $minutes]);
        return (int) $stmt->fetch()['total'] > 0;
    }
}
