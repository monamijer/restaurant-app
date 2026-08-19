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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navMenu">
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