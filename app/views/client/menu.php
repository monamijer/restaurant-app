<?php $titrePage = 'Menu'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div class="container py-5" style="margin-top: 60px;">
    <div class="section-title">
        <span>Notre carte</span>
        <h2>Le Menu</h2>
    </div>

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

    <div class="row g-4" id="plats-liste"></div>
</div>

<script src="/assets/js/menu-filtres.js"></script>
<?php require __DIR__ . '/../partials/footer-client.php'; ?>