<?php

class Depense extends Model {
    protected string $table = 'depenses';

    public function toutesAvecCategorie(?string $dateDebut = null, ?string $dateFin = null): array {
        $sql = "SELECT d.*, c.nom AS categorie_nom, u.nom AS saisie_par_nom
                FROM depenses d
                JOIN categories_depenses c ON d.categorie_id = c.id
                LEFT JOIN users u ON d.saisie_par_user_id = u.id";
        $params = [];
        if ($dateDebut && $dateFin) {
            $sql .= " WHERE d.date_depense BETWEEN ? AND ?";
            $params = [$dateDebut, $dateFin];
        }
        $sql .= " ORDER BY d.date_depense DESC, d.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function totalPeriode(string $dateDebut, string $dateFin): float {
        $sql = "SELECT COALESCE(SUM(montant), 0) AS total FROM depenses WHERE date_depense BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dateDebut, $dateFin]);
        return (float) $stmt->fetch()['total'];
    }

    public function totalParCategoriePeriode(string $dateDebut, string $dateFin): array {
        $sql = "SELECT c.nom, COALESCE(SUM(d.montant), 0) AS total
                FROM categories_depenses c
                LEFT JOIN depenses d ON d.categorie_id = c.id AND d.date_depense BETWEEN ? AND ?
                GROUP BY c.id, c.nom
                HAVING total > 0
                ORDER BY total DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dateDebut, $dateFin]);
        return $stmt->fetchAll();
    }

    public function totalParJour(int $nbJours = 7): array {
        $sql = "SELECT date_depense AS jour, COALESCE(SUM(montant), 0) AS total
                FROM depenses
                WHERE date_depense >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY date_depense
                ORDER BY jour ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $nbJours, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}