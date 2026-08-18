<div class="mb-3" id="bloc-verification-email">
    <label class="form-label">Votre email</label>
    <div class="input-group">
        <input type="email" class="form-control" id="email-a-verifier" name="email" required>
        <button type="button" class="btn btn-outline-dark" id="btn-envoyer-code">Envoyer le code</button>
    </div>
    <small style="color: var(--text-secondary);" id="statut-email">Un code de vérification vous sera envoyé.</small>
</div>

<div class="mb-3 d-none" id="bloc-code-verification">
    <label class="form-label">Code reçu par email</label>
    <div class="input-group">
        <input type="text" class="form-control" id="code-verification" maxlength="6" placeholder="123456">
        <button type="button" class="btn btn-accent" id="btn-verifier-code">Vérifier</button>
    </div>
    <small style="color: var(--text-secondary);">
        <a href="#" id="lien-renvoyer-code">Renvoyer le code</a>
    </small>
</div>

<div class="alert alert-success d-none" id="email-verifie-confirme">
    ✅ Email vérifié
</div>