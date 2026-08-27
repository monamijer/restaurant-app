<?php $titrePage = 'Réinitialiser le mot de passe'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div class="auth-page d-flex align-items-center justify-content-center">
    <div class="auth-card">
        <h2 class="font-title text-center mb-4">Nouveau mot de passe</h2>

        <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
            <p class="text-center mt-3">
                <a href="/mot-de-passe-oublie" style="color: var(--accent);">Faire une nouvelle demande</a>
            </p>
        <?php else: ?>
            <form method="POST" action="/reinitialiser-mot-de-passe">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="csrf_token" value="<?= Csrf::token() ?>">
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                </div>
                <button type="submit" class="btn btn-accent w-100">Réinitialiser</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer-client.php'; ?>