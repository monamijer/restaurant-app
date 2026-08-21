<?php $titrePage = 'Mot de passe oublié'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div class="auth-page d-flex align-items-center justify-content-center">
    <div class="auth-card">
        <h2 class="font-title text-center mb-4">Mot de passe oublié</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php else: ?>
            <p style="color: var(--text-secondary);" class="mb-3">Entrez votre email, un lien de réinitialisation vous sera envoyé.</p>
            <form method="POST" action="/mot-de-passe-oublie">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-accent w-100">Envoyer le lien</button>
            </form>
        <?php endif; ?>

        <p class="text-center mt-3">
            <a href="/connexion" style="color: var(--accent);">← Retour à la connexion</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer-client.php'; ?>