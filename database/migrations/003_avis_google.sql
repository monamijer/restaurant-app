CREATE TABLE IF NOT EXISTS avis_google (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_client VARCHAR(150) NOT NULL,
    note TINYINT NOT NULL,
    commentaire TEXT NOT NULL,
    date_avis DATE NULL
);

INSERT IGNORE INTO parametres (cle, valeur, description) VALUES
('stat_annees_experience', '10', 'Années d\'expérience affichées'),
('stat_clients_servis', '15000', 'Nombre de clients servis affiché'),
('stat_plats_carte', '45', 'Nombre de plats à la carte'),
('stat_note_moyenne', '4.8', 'Note moyenne affichée (sur 5)'),
('lien_avis_google', 'https://g.page/r/votre-lien/review', 'Lien vers la page avis Google');