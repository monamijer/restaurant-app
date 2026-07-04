<?php

class Categorie extends Model {
    protected string $table = 'categories';

    public function allOrdered(): array {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY ordre ASC");
        return $stmt->fetchAll();
    }
}