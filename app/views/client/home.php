<?php $titrePage = 'Accueil'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div id="preloader">
    <div id="progress-bar"></div>
    <div class="loader-content">
        <span class="font-title fs-4"><?= htmlspecialchars($params['nom_restaurant']) ?></span>
        <div class="loader-bar"></div>
    </div>
</div>

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

<section class="hero-carousel" id="hero-carousel">
    <div class="hero-slide actif" style="background-image: url('/assets/images/hero.jpg');"></div>
    <div class="hero-slide" style="background-image: url('/assets/images/hero2.jpg');"></div>
    <div class="hero-slide" style="background-image: url('/assets/images/hero3.jpg');"></div>

    <div class="hero-content" style="position: relative; z-index: 3; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center;">
        <div>
            <span class="text-uppercase" style="letter-spacing:3px; font-size:0.9rem;">Bienvenue</span>
            <h1 class="mb-3"><?= htmlspecialchars($params['nom_restaurant']) ?></h1>
            <p class="mb-4 fs-5">Une cuisine authentique, une expérience mémorable</p>
            <a href="/reserver" class="btn btn-accent btn-magnetic">Réserver une table</a>
        </div>
    </div>

    <div class="hero-dots">
        <span class="hero-dot actif" data-slide="0"></span>
        <span class="hero-dot" data-slide="1"></span>
        <span class="hero-dot" data-slide="2"></span>
    </div>
</section>

<section class="section" id="apropos">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 animate-on-scroll parallax-element img-float" data-speed="0.15">
                 <img src="/assets/images/interieur.jpg" class="img-fluid rounded" alt="Intérieur du restaurant">
            </div>
            <div class="col-lg-6 animate-on-scroll delay-1">
                <div class="section-title text-lg-start">
                    <span>Notre histoire</span>
                    <h2>Une passion transmise avec amour</h2>
                </div>
                <p style="color: var(--text-secondary);">
                    [Texte  sur l'histoire du restaurant]
                </p>
            </div>
        </div>
    </div>
</section>
<svg class="wave-divider" viewBox="0 0 1200 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0,30 C300,60 900,0 1200,30 L1200,60 L0,60 Z"></path>
</svg>
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
<?php if (!empty($params['nom_proprietaire'])): ?>
<section class="section" style="background-color: var(--bg-secondary);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 bloc-proprietaire animate-on-scroll">
                <?php if (!empty($params['photo_proprietaire'])): ?>
                    <img src="/assets/uploads/<?= htmlspecialchars($params['photo_proprietaire']) ?>" class="photo-proprietaire" alt="<?= htmlspecialchars($params['nom_proprietaire']) ?>">
                <?php else: ?>
                    <div class="photo-proprietaire img-placeholder-fallback" style="display:flex;align-items:center;justify-content:center;font-size:3rem;">👤</div>
                <?php endif; ?>

                <div class="titre-proprietaire"><?= htmlspecialchars($params['titre_proprietaire']) ?></div>
                <div class="nom-proprietaire"><?= htmlspecialchars($params['nom_proprietaire']) ?></div>
                <?php if (!empty($params['bio_proprietaire'])): ?>
                    <p style="color: var(--text-secondary);" class="mt-3"><?= htmlspecialchars($params['bio_proprietaire']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<svg class="wave-divider inverse" viewBox="0 0 1200 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0,30 C300,0 900,60 1200,30 L1200,60 L0,60 Z"></path>
</svg>

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
    </div>
    <div class="marquee-avis">
        <div class="marquee-track" id="avis-marquee-track"></div>
    </div>
    <div class="text-center mt-4">
        <a href="<?= htmlspecialchars($params['lien_avis_google']) ?>" target="_blank"
           class="btn btn-outline-dark" style="border-color: var(--accent); color: var(--accent);">
            Voir tous les avis sur Google
        </a>
    </div>
</section>

<section class="py-5 text-center" style="background-color: var(--accent); color: #fff;">
    <div class="container">
        <h2 class="font-title mb-3">Envie de vivre l'expérience ?</h2>
        <a href="/reserver" class="btn btn-light btn-magnetic">Réserver maintenant</a>
    </div>
</section>

<section class="section" id="contact">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <h3 class="font-title mb-3">Nous trouver</h3>
                <p style="color: var(--text-secondary);">
                    📍 Q. Nyamianda Av. <br>
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

<script src="/assets/js/hero-carousel.js"></script>
<script src="/assets/js/parallax.js"></script>
<script src="/assets/js/boutons-magnetiques.js"></script>
<script src="/assets/js/preloader.js"></script>
<script src="/assets/js/animations.js"></script>
<script>window.deviseActuelle = <?= json_encode($params['devise']) ?>;</script>
<script src="/assets/js/menu.js"></script>
<script src="/assets/js/avis.js"></script>
<script src="/assets/js/avis-internes.js"></script>
<?php require __DIR__ . '/../partials/footer-client.php'; ?>