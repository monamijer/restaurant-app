<?php

class Plat extends Model {
    protected string $table = 'plats';

    public function allWithCategorie(): array {
        $sql = "SELECT p.*, c.nom AS categorie_nom 
                FROM plats p 
                JOIN categories c ON p.categorie_id = c.id 
                ORDER BY c.ordre ASC, p.nom ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function filtrer(array $filtres): array {
        $sql = "SELECT p.*, c.nom AS categorie_nom 
                FROM plats p 
                JOIN categories c ON p.categorie_id = c.id 
                WHERE p.disponible = 1";
        $params = [];

        if (!empty($filtres['categorie_id'])) {
            $sql .= " AND p.categorie_id = ?";
            $params[] = $filtres['categorie_id'];
        }
        if (!empty($filtres['vegetarien'])) {
            $sql .= " AND p.vegetarien = 1";
        }
        if (!empty($filtres['sans_gluten'])) {
            $sql .= " AND p.sans_gluten = 1";
        }
        if (!empty($filtres['epice'])) {
            $sql .= " AND p.epice = 1";
        }

        $sql .= " ORDER BY c.ordre ASC, p.nom ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function signature(int $limite = 3): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM plats WHERE disponible = 1 ORDER BY RAND() LIMIT ?"
        );
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}