<?php $titrePage = 'Mon profil'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">👤 Mon profil</h2>

<div class="row g-4">
    <div class="col-md-6">
        <div class="stat-card">
            <h5 class="mb-3">Informations générales</h5>
            <form id="form-infos">
                <div class="mb-3">
                    <label class="form-label">Nom complet</label>
                    <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-accent">Enregistrer</button>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card mb-4">
            <h5 class="mb-3">Changer d'email</h5>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Email actuel : <strong><?= htmlspecialchars($user['email']) ?></strong></p>
            <form id="form-email">
                <?php require __DIR__ . '/../partials/verification-email.php'; ?>
                <button type="submit" class="btn btn-accent" id="btn-changer-email" disabled>Vérifiez d'abord le nouvel email</button>
            </form>
        </div>

        <div class="stat-card">
            <h5 class="mb-3">Changer de mot de passe</h5>
            <form id="form-password">
                <div class="mb-3">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" class="form-control" name="ancien_mot_de_passe" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" name="nouveau_mot_de_passe" minlength="6" required>
                </div>
                <button type="submit" class="btn btn-accent">Changer le mot de passe</button>
            </form>
        </div>
    </div>
</div>

<script src="/assets/js/verification-email.js"></script>
<script src="/assets/js/profil-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>