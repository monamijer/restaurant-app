<?php

class Fermeture extends Model {
    protected string $table = 'fermetures';

    public function estFerme(string $date): bool {
        $sql = "SELECT COUNT(*) AS total FROM fermetures WHERE ? BETWEEN date_debut AND date_fin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        return (int) $stmt->fetch()['total'] > 0;
    }
}