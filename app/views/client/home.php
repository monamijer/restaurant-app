<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($params['nom_restaurant']) ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
</head>
<body>

    <div id="theme-toggle" class="theme-toggle">🌙</div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: var(--bg-secondary); border-bottom: 1px solid var(--border-color);">
        <div class="container">
            <a class="navbar-brand font-title" href="/"><?= htmlspecialchars($params['nom_restaurant']) ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navMenu">
                <ul class="navbar-nav gap-3">
                    <li class="nav-item"><a class="nav-link" href="#apropos">À propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#menu">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="#galerie">Galerie</a></li>
                    <li class="nav-item"><a class="nav-link" href="#avis">Avis</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-accent text-white px-3" href="/reserver">Réserver</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero" style="background-image: url('/assets/images/hero.jpg');">
        <div class="hero-content">
            <span class="text-uppercase" style="letter-spacing:3px; font-size:0.9rem;">Bienvenue</span>
            <h1 class="mb-3"><?= htmlspecialchars($params['nom_restaurant']) ?></h1>
            <p class="mb-4 fs-5">Une cuisine authentique, une expérience mémorable</p>
            <a href="/reserver" class="btn btn-accent">Réserver une table</a>
        </div>
    </section>

    <!-- À propos -->
    <section class="section" id="apropos">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 animate-on-scroll">
                    <img src="/assets/images/interieur.jpg" class="img-fluid rounded" alt="Intérieur du restaurant">
                </div>
                <div class="col-lg-6 animate-on-scroll delay-1">
                    <div class="section-title text-lg-start">
                        <span>Notre histoire</span>
                        <h2>Une passion transmise avec amour</h2>
                    </div>
                    <p style="color: var(--text-secondary);">
                        [Texte à personnaliser avec ta sœur sur l'histoire du restaurant, 
                        les valeurs, l'inspiration culinaire...]
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Plats signature (chargés dynamiquement en AJAX) -->
    <section class="section" id="menu" style="background-color: var(--bg-secondary);">
        <div class="container">
            <div class="section-title">
                <span>Notre carte</span>
                <h2>Plats signature</h2>
            </div>
            <div class="row g-4" id="plats-container">
                <!-- Rempli en AJAX via /assets/js/menu.js -->
            </div>
            <div class="text-center mt-5">
                <a href="/menu" class="btn btn-outline-dark" style="border-color: var(--accent); color: var(--accent);">Voir toute la carte</a>
            </div>
        </div>
    </section>

    <!-- Galerie -->
    <section class="section" id="galerie">
        <div class="container">
            <div class="section-title">
                <span>Ambiance</span>
                <h2>Notre galerie</h2>
            </div>
            <div class="row g-3">
                <div class="col-md-4 gallery-item"><img src="/assets/images/galerie1.jpg" alt=""></div>
                <div class="col-md-4 gallery-item"><img src="/assets/images/galerie2.jpg" alt=""></div>
                <div class="col-md-4 gallery-item"><img src="/assets/images/galerie3.jpg" alt=""></div>
            </div>
        </div>
    </section>

    <!-- Avis clients (chargés en AJAX) -->
    <section class="section" id="avis" style="background-color: var(--bg-secondary);">
        <div class="container">
            <div class="section-title">
                <span>Témoignages</span>
                <h2>Ce que disent nos clients</h2>
            </div>
            <div class="row g-4" id="avis-container">
                <!-- Rempli en AJAX -->
            </div>
        </div>
    </section>

    <!-- CTA Réservation -->
    <section class="py-5 text-center" style="background-color: var(--accent); color: #fff;">
        <div class="container">
            <h2 class="font-title mb-3">Envie de vivre l'expérience ?</h2>
            <a href="/reserver" class="btn btn-light">Réserver maintenant</a>
        </div>
    </section>

    <!-- Contact -->
    <section class="section" id="contact">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <h3 class="font-title mb-3">Nous trouver</h3>
                    <p style="color: var(--text-secondary);">
                        📍 [Adresse]<br>
                        📞 [Téléphone]<br>
                        ✉️ <?= htmlspecialchars($params['email_contact']) ?>
                    </p>
                </div>
                <div class="col-lg-6">
                    <!-- Intégrer Google Maps iframe ici -->
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-4" style="background-color: var(--bg-secondary); border-top: 1px solid var(--border-color); color: var(--text-secondary);">
        <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($params['nom_restaurant']) ?>. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/theme.js"></script>
    <script src="/assets/js/animations.js"></script>
    <script src="/assets/js/menu.js"></script>
</body>
</html>