<?php $titrePage = 'Connexion'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div class="auth-page d-flex align-items-center justify-content-center">
    <div class="auth-card">
        <h2 class="font-title text-center mb-4">Connexion</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/connexion">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-accent w-100">Se connecter</button>
        </form>

        <p class="text-center mt-3" style="color: var(--text-secondary);">
            Pas encore de compte ? <a href="/inscription" style="color: var(--accent);">Créer un compte</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer-client.php'; ?>