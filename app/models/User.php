<?php

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function createUser(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->create($data);
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }
    public function tousLesClients(): array
    {
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM reservations r WHERE r.user_id = u.id) AS nb_reservations,
                (SELECT COUNT(*) FROM commandes c WHERE c.user_id = u.id) AS nb_commandes,
                (SELECT COALESCE(SUM(total), 0) FROM commandes c WHERE c.user_id = u.id AND c.statut != 'ANNULEE') AS total_depense
            FROM users u
            WHERE u.role = 'CLIENT'
            ORDER BY total_depense DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function findAvecHistorique(int $id): ?array
    {
        $user = $this->find($id);
        if (!$user) {
            return null;
        }

        $sqlReservations = 'SELECT * FROM reservations WHERE user_id = ? ORDER BY date_reservation DESC LIMIT 10';
        $stmt = $this->db->prepare($sqlReservations);
        $stmt->execute([$id]);
        $user['reservations'] = $stmt->fetchAll();

        $sqlCommandes = 'SELECT * FROM commandes WHERE user_id = ? ORDER BY created_at DESC LIMIT 10';
        $stmt = $this->db->prepare($sqlCommandes);
        $stmt->execute([$id]);
        $user['commandes'] = $stmt->fetchAll();

        return $user;
    }

    public function rechercher(string $terme): array
    {
        $sql = "SELECT * FROM users WHERE role = 'CLIENT' AND (nom LIKE ? OR email LIKE ?) ORDER BY nom ASC";
        $stmt = $this->db->prepare($sql);
        $recherche = "%$terme%";
        $stmt->execute([$recherche, $recherche]);
        return $stmt->fetchAll();
    }
    public function tousLesEmployes(): array
    {
        $sql = "SELECT * FROM users WHERE role IN ('SERVEUR', 'CUISINE', 'ADMIN') ORDER BY role, nom ASC";
        return $this->db->query($sql)->fetchAll();
    }
}
