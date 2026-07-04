<?php

class Reservation extends Model {
    protected string $table = 'reservations';

    public function creerAvecUtilisateur(array $data): int {
        return $this->create($data);
    }

    public function findAvecDetails(int $id): ?array {
        $sql = "SELECT r.*, u.nom AS user_nom, u.email, u.telephone, u.nb_no_show
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                WHERE r.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function toutesAvecDetails(?string $statut = null): array {
        $sql = "SELECT r.*, u.nom AS user_nom, u.email, u.telephone, u.nb_no_show
                FROM reservations r
                JOIN users u ON r.user_id = u.id";
        $params = [];
        if ($statut) {
            $sql .= " WHERE r.statut = ?";
            $params[] = $statut;
        }
        $sql .= " ORDER BY r.date_reservation ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function marquerNoShow(int $id): void {
        $this->update($id, ['statut' => 'NO_SHOW', 'statut_acompte' => 'RETENU']);
        $reservation = $this->find($id);
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE users SET nb_no_show = nb_no_show + 1 WHERE id = ?");
        $stmt->execute([$reservation['user_id']]);
    }

    public function compterAujourdhui(): int {
        $sql = "SELECT COUNT(*) AS total FROM reservations 
                WHERE DATE(date_reservation) = CURDATE() 
                AND statut NOT IN ('ANNULEE_CLIENT', 'ANNULEE_RESTAURANT')";
        return (int) $this->db->query($sql)->fetch()['total'];
    }

    public function tauxNoShow(int $nbJours = 30): float {
        $sql = "SELECT 
                    SUM(CASE WHEN statut = 'NO_SHOW' THEN 1 ELSE 0 END) AS no_shows,
                    COUNT(*) AS total
                FROM reservations
                WHERE date_reservation >= DATE_SUB(NOW(), INTERVAL ? DAY)
                AND statut IN ('NO_SHOW', 'TERMINEE', 'ARRIVEE')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nbJours]);
        $result = $stmt->fetch();
        if ($result['total'] == 0) return 0;
        return round(($result['no_shows'] / $result['total']) * 100, 1);
    }

    public function prochaines(int $limite = 5): array {
        $sql = "SELECT r.*, u.nom AS user_nom 
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                WHERE r.date_reservation >= NOW()
                AND r.statut NOT IN ('ANNULEE_CLIENT', 'ANNULEE_RESTAURANT', 'NO_SHOW')
                ORDER BY r.date_reservation ASC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}