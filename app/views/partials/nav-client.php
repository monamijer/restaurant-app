<?php
if (!isset($params)) {
    $parametreModel = new Parametre();
    $params = $parametreModel->getAll();
}
$nbPanier = isset($_SESSION['panier']) ? array_sum(array_column($_SESSION['panier'], 'quantite')) : 0;
?>
<nav class="navbar navbar-expand-lg fixed-top" style="background-color: var(--bg-secondary); border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <a class="navbar-brand font-title" href="/"><?= htmlspecialchars($params['nom_restaurant']) ?></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavClient" aria-controls="offcanvasNavClient">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Version desktop : liens inline classiques -->
        <div class="collapse navbar-collapse d-none d-lg-flex justify-content-end">
            <ul class="navbar-nav gap-3 align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="/">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="/menu">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="/#apropos">À propos</a></li>
                <li class="nav-item"><a class="nav-link" href="/avis">Avis</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="/panier">
                        🛒 Panier<?php if ($nbPanier > 0): ?> <span class="badge bg-danger"><?= $nbPanier ?></span><?php endif; ?>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link btn btn-accent text-white px-3" href="/reserver">Réserver</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Version mobile : panneau qui glisse depuis la gauche -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavClient" style="background-color: var(--bg-secondary); color: var(--text-primary);">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title font-title"><?= htmlspecialchars($params['nom_restaurant']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav gap-2">
            <li class="nav-item"><a class="nav-link" href="/">🏠 Accueil</a></li>
            <li class="nav-item"><a class="nav-link" href="/menu">🍽️ Menu</a></li>
            <li class="nav-item"><a class="nav-link" href="/#apropos">ℹ️ À propos</a></li>
            <li class="nav-item"><a class="nav-link" href="/avis">⭐ Avis</a></li>
            <li class="nav-item">
                <a class="nav-link" href="/panier">
                    🛒 Panier<?php if ($nbPanier > 0): ?> <span class="badge bg-danger"><?= $nbPanier ?></span><?php endif; ?>
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="btn btn-accent text-white w-100" href="/reserver">Réserver une table</a>
            </li>
        </ul>
    </div>
</div>