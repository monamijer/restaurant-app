<?php

class Ingredient extends Model {
    protected string $table = 'ingredients';

    public function stockBas(): array {
        $sql = "SELECT * FROM ingredients WHERE quantite_stock <= seuil_alerte ORDER BY quantite_stock ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function peremptionProche(int $jours = 3): array {
        $sql = "SELECT * FROM ingredients 
                WHERE date_peremption IS NOT NULL 
                AND date_peremption <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
                ORDER BY date_peremption ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$jours]);
        return $stmt->fetchAll();
    }
}