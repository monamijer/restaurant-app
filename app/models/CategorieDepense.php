<?php

class CategorieDepense extends Model {
    protected string $table = 'categories_depenses';

    public function allOrdered(): array {
        return $this->db->query("SELECT * FROM categories_depenses ORDER BY nom ASC")->fetchAll();
    }
}