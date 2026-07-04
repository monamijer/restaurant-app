<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Etoile d'Or</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div id="theme-toggle" class="theme-toggle">🌙</div>

    <div class="auth-page d-flex align-items-center justify-content-center">
        <div class="auth-card">
            <h2 class="font-title text-center mb-4">Créer un compte</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/inscription">
                <div class="mb-3">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" name="telephone" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                </div>
                <button type="submit" class="btn btn-accent w-100">Créer mon compte</button>
            </form>

            <p class="text-center mt-3" style="color: var(--text-secondary);">
                Déjà un compte ? <a href="/connexion" style="color: var(--accent);">Se connecter</a>
            </p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/theme.js"></script>
</body>
</html>