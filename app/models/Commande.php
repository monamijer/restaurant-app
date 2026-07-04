<?php

class Commande extends Model {
    protected string $table = 'commandes';

    public function caPeriode(string $debut, string $fin): float {
        $sql = "SELECT COALESCE(SUM(total), 0) AS ca FROM commandes 
                WHERE created_at BETWEEN ? AND ? AND statut != 'ANNULEE'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$debut, $fin]);
        return (float) $stmt->fetch()['ca'];
    }

    public function nombrePeriode(string $debut, string $fin): int {
        $sql = "SELECT COUNT(*) AS total FROM commandes 
                WHERE created_at BETWEEN ? AND ? AND statut != 'ANNULEE'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$debut, $fin]);
        return (int) $stmt->fetch()['total'];
    }

    public function platsPopulaires(int $limite = 5): array {
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

    public function repartitionParHeure(): array {
        $sql = "SELECT HOUR(created_at) AS heure, COUNT(*) AS total
                FROM commandes
                WHERE statut != 'ANNULEE'
                GROUP BY HOUR(created_at)
                ORDER BY heure ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function caParJour(int $nbJours = 7): array {
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
}