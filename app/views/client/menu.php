<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - <?= htmlspecialchars($params['nom_restaurant']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div id="theme-toggle" class="theme-toggle">🌙</div>

    <div class="container py-5" style="margin-top: 60px;">
        <div class="section-title">
            <span>Notre carte</span>
            <h2>Le Menu</h2>
        </div>

        <!-- Filtres -->
        <div class="d-flex flex-wrap gap-2 justify-content-center mb-5" id="filtres-menu">
            <button class="btn btn-outline-dark btn-filtre active" data-categorie="">Tous</button>
            <?php foreach ($categories as $cat): ?>
                <button class="btn btn-outline-dark btn-filtre" data-categorie="<?= $cat['id'] ?>">
                    <?= htmlspecialchars($cat['nom']) ?>
                </button>
            <?php endforeach; ?>

            <div class="vr mx-2 d-none d-md-block"></div>

            <button class="btn btn-outline-dark btn-toggle-filtre" data-filtre="vegetarien">🌱 Végétarien</button>
            <button class="btn btn-outline-dark btn-toggle-filtre" data-filtre="sans_gluten">🌾 Sans gluten</button>
            <button class="btn btn-outline-dark btn-toggle-filtre" data-filtre="epice">🌶️ Épicé</button>
        </div>

        <div class="row g-4" id="plats-liste">
            <!-- Rempli en AJAX -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/theme.js"></script>
    <script src="/assets/js/menu-filtres.js"></script>
</body>
</html>