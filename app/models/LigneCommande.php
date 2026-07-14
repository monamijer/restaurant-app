<?php

class LigneCommande extends Model {
    protected string $table = 'lignes_commande';

    public function parCommande(int $commandeId): array {
        $sql = "SELECT lc.*, p.nom, p.image 
                FROM lignes_commande lc
                JOIN plats p ON lc.plat_id = p.id
                WHERE lc.commande_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$commandeId]);
        return $stmt->fetchAll();
    }
}