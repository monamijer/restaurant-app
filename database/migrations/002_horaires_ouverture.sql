CREATE TABLE IF NOT EXISTS horaires_ouverture (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour_semaine TINYINT NOT NULL,
    heure_ouverture TIME NULL,
    heure_fermeture TIME NULL,
    ferme BOOLEAN DEFAULT FALSE
);

INSERT IGNORE INTO horaires_ouverture (id, jour_semaine, heure_ouverture, heure_fermeture, ferme) VALUES
(1, 0, NULL, NULL, 1),
(2, 1, '11:30:00', '22:00:00', 0),
(3, 2, '11:30:00', '22:00:00', 0),
(4, 3, '11:30:00', '22:00:00', 0),
(5, 4, '11:30:00', '22:00:00', 0),
(6, 5, '11:30:00', '23:00:00', 0),
(7, 6, '11:30:00', '23:00:00', 0);