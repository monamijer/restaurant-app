<?php $titrePage = 'Accueil'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div id="preloader">
    <div class="loader-content">
        <span class="font-title fs-4"><?= htmlspecialchars($params['nom_restaurant']) ?></span>
        <div class="loader-bar"></div>
    </div>
</div>

<nav class="navbar navbar-expand-lg fixed-top" style="background-color: var(--bg-secondary); border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <a class="navbar-brand font-title" href="/"><?= htmlspecialchars($params['nom_restaurant']) ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navMenu">
            <ul class="navbar-nav gap-3">
                <li class="nav-item"><a class="nav-link" href="#apropos">À propos</a></li>
                <li class="nav-item"><a class="nav-link" href="/menu">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="#galerie">Galerie</a></li>
                <li class="nav-item"><a class="nav-link" href="#avis">Avis</a></li>
                <li class="nav-item"><a class="nav-link btn btn-accent text-white px-3" href="/reserver">Réserver</a></li>
            </ul>
        </div>
    </div>
</nav>

<?php
$horaireModel = new HoraireOuverture();
$horaireAujourdhui = $horaireModel->getAujourdhui();
$estOuvert = false;
$texteHoraire = "Fermé aujourd'hui";
if ($horaireAujourdhui && !$horaireAujourdhui['ferme']) {
    $now = date('H:i:s');
    if ($now >= $horaireAujourdhui['heure_ouverture'] && $now <= $horaireAujourdhui['heure_fermeture']) {
        $estOuvert = true;
        $texteHoraire = "Ouvert aujourd'hui jusqu'à " . substr($horaireAujourdhui['heure_fermeture'], 0, 5);
    } else {
        $texteHoraire = "Ouvre aujourd'hui à " . substr($horaireAujourdhui['heure_ouverture'], 0, 5);
    }
}
?>
<div class="horaires-banner">
    <span class="statut-dot <?= $estOuvert ? 'ouvert' : 'ferme' ?>"></span>
    <?= htmlspecialchars($texteHoraire) ?>
</div>

<section class="hero" style="background-image: url('/assets/images/hero.jpg');">
    <div class="hero-content">
        <span class="text-uppercase" style="letter-spacing:3px; font-size:0.9rem;">Bienvenue</span>
        <h1 class="mb-3"><?= htmlspecialchars($params['nom_restaurant']) ?></h1>
        <p class="mb-4 fs-5">Une cuisine authentique, une expérience mémorable</p>
        <a href="/reserver" class="btn btn-accent">Réserver une table</a>
    </div>
</section>

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
                    [Texte à personnaliser avec ta sœur sur l'histoire du restaurant]
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section" id="menu" style="background-color: var(--bg-secondary);">
    <div class="container">
        <div class="section-title">
            <span>Notre carte</span>
            <h2>Plats signature</h2>
        </div>
        <div class="row g-4" id="plats-container"></div>
        <div class="text-center mt-5">
            <a href="/menu" class="btn btn-outline-dark" style="border-color: var(--accent); color: var(--accent);">Voir toute la carte</a>
        </div>
    </div>
</section>

<section class="stats-section py-5" style="background-color: var(--accent); color: #fff;">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <div class="stat-number" data-target="<?= (int)$params['stat_annees_experience'] ?>">0</div>
                <div class="stat-label">Années d'expérience</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number" data-target="<?= (int)$params['stat_clients_servis'] ?>">0</div>
                <div class="stat-label">Clients servis</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number" data-target="<?= (int)$params['stat_plats_carte'] ?>">0</div>
                <div class="stat-label">Plats à la carte</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number" data-target="<?= (float)$params['stat_note_moyenne'] * 10 ?>" data-decimal="true">0</div>
                <div class="stat-label">Note moyenne /5</div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="galerie">
    <div class="container">
        <div class="section-title">
            <span>Ambiance</span>
            <h2>Notre galerie</h2>
        </div>
        <div class="row g-3">
            <div class="col-md-4 gallery-item animate-on-scroll"><img src="/assets/images/galerie1.jpg" alt=""></div>
            <div class="col-md-4 gallery-item animate-on-scroll delay-1"><img src="/assets/images/galerie2.jpg" alt=""></div>
            <div class="col-md-4 gallery-item animate-on-scroll delay-2"><img src="/assets/images/galerie3.jpg" alt=""></div>
        </div>
    </div>
</section>

<section class="section" id="avis" style="background-color: var(--bg-secondary);">
    <div class="container">
        <div class="section-title">
            <span>Témoignages</span>
            <h2>Ce que disent nos clients</h2>
        </div>
        <div class="row g-4" id="avis-container"></div>
    </div>
</section>

<section class="section" id="avis-google" style="background-color: var(--bg-secondary);">
    <div class="container">
        <div class="section-title animate-on-scroll">
            <span>Avis vérifiés</span>
            <h2>Ce que dit Google</h2>
            <p><strong><?= htmlspecialchars($params['stat_note_moyenne']) ?></strong> ⭐⭐⭐⭐⭐</p>
        </div>
        <div class="row g-4" id="avis-google-container"></div>
        <div class="text-center mt-4">
            <a href="<?= htmlspecialchars($params['lien_avis_google']) ?>" target="_blank"
               class="btn btn-outline-dark" style="border-color: var(--accent); color: var(--accent);">
                Voir tous les avis sur Google
            </a>
        </div>
    </div>
</section>

<section class="py-5 text-center" style="background-color: var(--accent); color: #fff;">
    <div class="container">
        <h2 class="font-title mb-3">Envie de vivre l'expérience ?</h2>
        <a href="/reserver" class="btn btn-light">Réserver maintenant</a>
    </div>
</section>

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
            <div class="col-lg-6"></div>
        </div>
    </div>
</section>

<footer class="text-center py-4" style="background-color: var(--bg-secondary); border-top: 1px solid var(--border-color); color: var(--text-secondary);">
    <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($params['nom_restaurant']) ?>. Tous droits réservés.</p>
</footer>

<a href="/reserver" class="floating-reserve-btn d-lg-none">📅 Réserver</a>

<script src="/assets/js/preloader.js"></script>
<script src="/assets/js/animations.js"></script>
<script src="/assets/js/menu.js"></script>
<script src="/assets/js/avis.js"></script>
<?php require __DIR__ . '/../partials/footer-client.php'; ?>