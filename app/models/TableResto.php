<?php

class TableResto extends Model {
    protected string $table = 'tables_resto';

    // Trouve une table libre avec assez de capacité pour ce créneau
    public function trouverTableDisponible(string $dateReservation, int $nbPersonnes): ?array {
        $sql = "SELECT t.* FROM tables_resto t
                WHERE t.capacite >= ?
                AND t.id NOT IN (
                    SELECT table_id FROM reservations
                    WHERE table_id IS NOT NULL
                    AND statut NOT IN ('ANNULEE_CLIENT', 'ANNULEE_RESTAURANT', 'NO_SHOW', 'TERMINEE')
                    AND ABS(TIMESTAMPDIFF(MINUTE, date_reservation, ?)) < 120
                )
                ORDER BY t.capacite ASC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nbPersonnes, $dateReservation]);
        return $stmt->fetch() ?: null;
    }
}