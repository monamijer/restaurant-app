<?php
require_once __DIR__ . '/../core/Model.php';

class Parametre extends Model {
    protected string $table = 'parametres';

    // Récupère UNE valeur (avec valeur par défaut si absente)
    public function get(string $cle, $default = null) {
        $stmt = $this->db->prepare("SELECT valeur FROM parametres WHERE cle = ?");
        $stmt->execute([$cle]);
        $result = $stmt->fetch();
        return $result ? $result['valeur'] : $default;
    }

    // Récupère TOUS les paramètres sous forme de tableau clé => valeur
    public function getAll(): array {
        $stmt = $this->db->query("SELECT cle, valeur FROM parametres");
        $rows = $stmt->fetchAll();
        $params = [];
        foreach ($rows as $row) {
            $params[$row['cle']] = $row['valeur'];
        }
        return $params;
    }

    // Met à jour un ou plusieurs paramètres (depuis le formulaire admin)
    public function setMultiple(array $data): void {
        $stmt = $this->db->prepare("UPDATE parametres SET valeur = ? WHERE cle = ?");
        foreach ($data as $cle => $valeur) {
            $stmt->execute([$valeur, $cle]);
        }
    }
}