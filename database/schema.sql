CREATE DATABASE IF NOT EXISTS restaurant_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(30),
    role ENUM('CLIENT','SERVEUR','CUISINE','ADMIN') DEFAULT 'CLIENT',
    points_fidelite INT DEFAULT 0,
    allergies TEXT,
    nb_no_show INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ordre INT DEFAULT 0
);

CREATE TABLE plats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    disponible BOOLEAN DEFAULT TRUE,
    vegetarien BOOLEAN DEFAULT FALSE,
    sans_gluten BOOLEAN DEFAULT FALSE,
    epice BOOLEAN DEFAULT FALSE,
    en_promo_fin_journee BOOLEAN DEFAULT FALSE,
    prix_promo DECIMAL(10,2) NULL,
    categorie_id INT NOT NULL,
    FOREIGN KEY (categorie_id) REFERENCES categories(id)
);

CREATE TABLE ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    quantite_stock DECIMAL(10,2) NOT NULL,
    unite VARCHAR(20) NOT NULL,
    seuil_alerte DECIMAL(10,2) NOT NULL,
    date_peremption DATE NULL,
    fournisseur VARCHAR(150)
);

CREATE TABLE ingredient_plat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plat_id INT NOT NULL,
    ingredient_id INT NOT NULL,
    quantite DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (plat_id) REFERENCES plats(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE CASCADE
);

CREATE TABLE tables_resto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT UNIQUE NOT NULL,
    capacite INT NOT NULL
);

-- Les réglages configurables par ta sœur, sans toucher au code
CREATE TABLE parametres (
    cle VARCHAR(100) PRIMARY KEY,
    valeur TEXT NOT NULL,
    description VARCHAR(255)
);

INSERT INTO parametres (cle, valeur, description) VALUES
('nb_personnes_min_acompte', '1', 'Nombre de personnes à partir duquel un acompte est demandé'),
('montant_acompte_par_personne', '5000', 'Montant de l\'acompte par personne (en BIF ou devise locale)'),
('delai_annulation_gratuite_h', '24', 'Heures avant réservation pour annuler sans frais'),
('delai_grace_retard_min', '15', 'Minutes de tolérance avant libération de la table'),
('nom_restaurant', 'Mon Restaurant', 'Nom affiché sur le site'),
('email_contact', 'contact@monrestaurant.com', 'Email de contact affiché'),
('acompte_actif', '1', 'Activer/désactiver le système d\'acompte (1 ou 0)');

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    table_id INT NULL,
    date_reservation DATETIME NOT NULL,
    nb_personnes INT NOT NULL,
    statut ENUM('EN_ATTENTE_ACOMPTE','CONFIRMEE','ARRIVEE','NO_SHOW','ANNULEE_CLIENT','ANNULEE_RESTAURANT','TERMINEE') DEFAULT 'EN_ATTENTE_ACOMPTE',
    notes TEXT,
    montant_acompte DECIMAL(10,2) NULL,
    statut_acompte ENUM('EN_ATTENTE','PAYE','REMBOURSE','RETENU') DEFAULT 'EN_ATTENTE',
    stripe_payment_id VARCHAR(255) NULL,
    confirmee_par_client BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (table_id) REFERENCES tables_resto(id)
);

CREATE TABLE liste_attente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date_souhaitee DATETIME NOT NULL,
    nb_personnes INT NOT NULL,
    notifie BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE fermetures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    raison VARCHAR(255),
    notifier_reservations BOOLEAN DEFAULT TRUE
);

CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('SUR_PLACE','EMPORTER','LIVRAISON') NOT NULL,
    statut ENUM('EN_ATTENTE','EN_CUISINE','PRETE','SERVIE','ANNULEE') DEFAULT 'EN_ATTENTE',
    total DECIMAL(10,2) NOT NULL,
    stripe_id VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE lignes_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    plat_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (plat_id) REFERENCES plats(id)
);

CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    note TINYINT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT NOT NULL,
    reponse_admin TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);