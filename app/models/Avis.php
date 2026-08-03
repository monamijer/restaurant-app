<?php

class Avis extends Model {
    protected string $table = 'avis';

    public function tousAvecUser(): array {
        $sql = "SELECT a.*, u.nom AS user_nom 
                FROM avis a
                JOIN users u ON a.user_id = u.id
                ORDER BY a.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function publiesAvecUser(int $limite = 10): array {
        $sql = "SELECT a.*, u.nom AS user_nom 
                FROM avis a
                JOIN users u ON a.user_id = u.id
                ORDER BY a.created_at DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function noteMoyenne(): float {
        $sql = "SELECT AVG(note) AS moyenne FROM avis";
        $result = $this->db->query($sql)->fetch();
        return $result['moyenne'] ? round($result['moyenne'], 1) : 0;
    }

    public function utilisateurAvisAujourdhui(int $userId): bool {
        $sql = "SELECT COUNT(*) AS total FROM avis WHERE user_id = ? AND DATE(created_at) = CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return (int) $stmt->fetch()['total'] > 0;
    }
}