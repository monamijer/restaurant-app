<?php

class Commande extends Model
{
    protected string $table = 'commandes';

    public function caPeriode(string $debut, string $fin): float
    {
        $sql = "SELECT COALESCE(SUM(total), 0) AS ca FROM commandes 
                WHERE created_at BETWEEN ? AND ? AND statut != 'ANNULEE'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$debut, $fin]);
        return (float) $stmt->fetch()['ca'];
    }

    public function nombrePeriode(string $debut, string $fin): int
    {
        $sql = "SELECT COUNT(*) AS total FROM commandes 
                WHERE created_at BETWEEN ? AND ? AND statut != 'ANNULEE'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$debut, $fin]);
        return (int) $stmt->fetch()['total'];
    }

    public function platsPopulaires(int $limite = 5): array
    {
        $sql = "SELECT p.nom, SUM(lc.quantite) AS total_vendu
                FROM lignes_commande lc
                JOIN plats p ON lc.plat_id = p.id
                JOIN commandes c ON lc.commande_id = c.id
                WHERE c.statut != 'ANNULEE'
                GROUP BY p.id, p.nom
                ORDER BY total_vendu DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function repartitionParHeure(): array
    {
        $sql = "SELECT HOUR(created_at) AS heure, COUNT(*) AS total
                FROM commandes
                WHERE statut != 'ANNULEE'
                GROUP BY HOUR(created_at)
                ORDER BY heure ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function caParJour(int $nbJours = 7): array
    {
        $sql = "SELECT DATE(created_at) AS jour, COALESCE(SUM(total), 0) AS ca
                FROM commandes
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                AND statut != 'ANNULEE'
                GROUP BY DATE(created_at)
                ORDER BY jour ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $nbJours, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function creerAvecLignes(array $donneesCommande, array $panier): int
    {
        $this->db->beginTransaction();
        try {
            $commandeId = $this->create($donneesCommande);

            $ligneModel = new LigneCommande();
            foreach ($panier as $platId => $item) {
                $ligneModel->create([
                    'commande_id' => $commandeId,
                    'plat_id' => $platId,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix'],
                ]);
            }

            $this->db->commit();
            return $commandeId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function findAvecUser(int $id): ?array
    {
        $sql = 'SELECT c.*, 
                COALESCE(u.nom, c.guest_nom) AS user_nom,
                COALESCE(u.email, c.guest_email) AS email,
                COALESCE(u.telephone, c.guest_telephone) AS telephone
            FROM commandes c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function toutesAvecUser(?string $statut = null): array
    {
        $sql = 'SELECT c.*, 
                COALESCE(u.nom, c.guest_nom) AS user_nom,
                COALESCE(u.email, c.guest_email) AS email,
                COALESCE(u.telephone, c.guest_telephone) AS telephone
            FROM commandes c
            LEFT JOIN users u ON c.user_id = u.id';
        $params = [];
        if ($statut) {
            $sql .= ' WHERE c.statut = ?';
            $params[] = $statut;
        }
        $sql .= ' ORDER BY c.created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    public function journalDuJour(?int $userId = null): array
    {
        $sql = 'SELECT c.*, u.nom AS saisie_par_nom
            FROM commandes c
            LEFT JOIN users u ON c.saisie_par_user_id = u.id
            WHERE c.saisie_par_user_id IS NOT NULL
            AND DATE(c.created_at) = CURDATE()';
        $params = [];
        if ($userId) {
            $sql .= ' AND c.saisie_par_user_id = ?';
            $params[] = $userId;
        }
        $sql .= ' ORDER BY c.created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function totalParModePaiementJour(?int $userId = null): array
    {
        $sql = 'SELECT mode_paiement, COALESCE(SUM(total), 0) AS total
            FROM commandes
            WHERE saisie_par_user_id IS NOT NULL
            AND DATE(created_at) = CURDATE()';
        $params = [];
        if ($userId) {
            $sql .= ' AND saisie_par_user_id = ?';
            $params[] = $userId;
        }
        $sql .= ' GROUP BY mode_paiement';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
