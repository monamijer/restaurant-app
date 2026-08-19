<div class="modal fade" id="modalChoixPaiement" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mode de paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3" id="etape-choix-methode">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-dark text-start btn-methode-paiement" data-methode="STRIPE">
                            💳 Carte bancaire (paiement en ligne sécurisé)
                        </button>
                        <button class="btn btn-outline-dark text-start btn-methode-paiement" data-methode="AIRTEL_MONEY">
                            📱 Airtel Money
                        </button>
                        <button class="btn btn-outline-dark text-start btn-methode-paiement" data-methode="ORANGE_MONEY">
                            📱 Orange Money
                        </button>
                        <button class="btn btn-outline-dark text-start btn-methode-paiement" data-methode="MPESA">
                            📱 M-Pesa
                        </button>
                        <button class="btn btn-outline-dark text-start btn-methode-paiement" data-methode="CONTACT_RESTAURANT">
                            📞 Contacter le restaurant directement
                        </button>
                    </div>
                </div>

                <div class="mb-3 d-none" id="etape-mobile-money">
                    <div class="alert alert-info" id="instructions-mobile-money"></div>
                    <div class="mb-3">
                        <label class="form-label">Référence de transaction reçue par SMS</label>
                        <input type="text" class="form-control" id="reference-transaction" placeholder="Ex: MP240815.1234.A56789">
                    </div>
                    <button class="btn btn-accent w-100" id="btn-confirmer-mobile-money">J'ai effectué le paiement</button>
                    <button class="btn btn-link w-100" id="btn-retour-choix">← Choisir une autre méthode</button>
                </div>

                <div class="mb-3 d-none text-center" id="etape-contact-restaurant">
                    <p>📞 Contactez-nous au <strong id="tel-contact-affiche"></strong> pour convenir du paiement.</p>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Votre réservation/commande est enregistrée et sera confirmée après notre échange.</p>
                    <button class="btn btn-accent w-100" id="btn-confirmer-contact">J'ai compris</button>
                </div>

                <div class="mb-3 d-none text-center" id="etape-confirmation-envoyee">
                <p id="texte-confirmation-envoyee"></p>
                </div>

            </div>
        </div>
    </div>
</div>
