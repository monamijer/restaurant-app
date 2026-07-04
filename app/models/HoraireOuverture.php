<?php

class HoraireOuverture extends Model {
    protected string $table = 'horaires_ouverture';

    public function getAujourdhui(): ?array {
        $jour = (int) date('w'); // 0 = dimanche ... 6 = samedi
        $stmt = $this->db->prepare("SELECT * FROM horaires_ouverture WHERE jour_semaine = ?");
        $stmt->execute([$jour]);
        return $stmt->fetch() ?: null;
    }

    public function getSemaine(): array {
        return $this->db->query("SELECT * FROM horaires_ouverture ORDER BY jour_semaine")->fetchAll();
    }
}