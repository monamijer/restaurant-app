CREATE TABLE IF NOT EXISTS categories_depenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

INSERT IGNORE INTO categories_depenses (id, nom) VALUES
(1, 'Achats ingrédients'),
(2, 'Salaires'),
(3, 'Loyer'),
(4, 'Électricité / Eau'),
(5, 'Équipement'),
(6, 'Maintenance'),
(7, 'Marketing'),
(8, 'Autre');

CREATE TABLE IF NOT EXISTS depenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categorie_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    description VARCHAR(255) NOT NULL,
    fournisseur VARCHAR(150) NULL,
    date_depense DATE NOT NULL,
    saisie_par_user_id INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories_depenses(id)
);