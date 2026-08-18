ALTER TABLE reservations 
    ADD COLUMN IF NOT EXISTS mode_paiement ENUM('STRIPE','AIRTEL_MONEY','ORANGE_MONEY','MPESA','CONTACT_RESTAURANT') NULL AFTER statut_acompte,
    ADD COLUMN IF NOT EXISTS reference_paiement VARCHAR(100) NULL AFTER mode_paiement;

ALTER TABLE reservations 
    MODIFY COLUMN statut_acompte ENUM('EN_ATTENTE','VERIFICATION_MANUELLE','PAYE','REMBOURSE','RETENU') DEFAULT 'EN_ATTENTE';

ALTER TABLE commandes 
    ADD COLUMN IF NOT EXISTS mode_paiement ENUM('STRIPE','AIRTEL_MONEY','ORANGE_MONEY','MPESA','CONTACT_RESTAURANT') NULL AFTER stripe_id,
    ADD COLUMN IF NOT EXISTS reference_paiement VARCHAR(100) NULL AFTER mode_paiement,
    ADD COLUMN IF NOT EXISTS statut_paiement ENUM('NON_REQUIS','EN_ATTENTE','VERIFICATION_MANUELLE','PAYE') DEFAULT 'NON_REQUIS' AFTER reference_paiement;

INSERT IGNORE INTO parametres (cle, valeur, description) VALUES
('telephone_contact', '', 'Numéro de téléphone du restaurant pour contact direct'),
('numero_airtel_money', '', 'Numéro Airtel Money du restaurant'),
('numero_orange_money', '', 'Numéro Orange Money du restaurant'),
('numero_mpesa', '', 'Numéro M-Pesa du restaurant');