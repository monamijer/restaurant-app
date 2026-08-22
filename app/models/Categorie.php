<?php

class Categorie extends Model
{
    protected string $table = 'categories';

    public function allOrdered(): array
    {
        $stmt = $this->db->query('SELECT * FROM categories ORDER BY ordre ASC');
        return $stmt->fetchAll();
    }
    public function estUtilisee(int $id): bool
    {
        $sql = 'SELECT COUNT(*) AS total FROM plats WHERE categorie_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return (int) $stmt->fetch()['total'] > 0;
    }

    public function prochainOrdre(): int
    {
        $sql = 'SELECT COALESCE(MAX(ordre), 0) + 1 AS prochain FROM categories';
        return (int) $this->db->query($sql)->fetch()['prochain'];
    }
}
