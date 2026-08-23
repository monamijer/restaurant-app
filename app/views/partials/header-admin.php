<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titrePage) ? htmlspecialchars($titrePage) . ' - ' : '' ?>Admin - Etoile d'Or</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
    <link href="/assets/css/admin.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#b8894f">

    <!-- iOS spécifique : Apple ne lit pas toujours le manifest.json correctement -->
    <link rel="apple-touch-icon" href="/assets/icons/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Admin - Etoile d'Or">
    <meta name="csrf-token" content="<?= Csrf::token() ?>">
    <script src="/assets/js/csrf.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/toast.js" defer></script>
</head>
<body>
<div id="theme-toggle" class="theme-toggle">🌙</div>

<button class="admin-mobile-toggle" id="admin-mobile-toggle">☰</button>
<div class="admin-overlay" id="admin-overlay"></div>

<div class="admin-layout">
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="admin-sidebar-brand">
            <span class="font-title">Etoile d'Or</span>
            <small style="color: var(--text-secondary);">Espace admin</small>
        </div>
       <nav class="admin-nav">
    <?php if (Auth::role() === 'ADMIN'): ?>
        <a href="/admin" class="<?= ($page ?? '') === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="/admin/menu" class="<?= ($page ?? '') === 'menu' ? 'active' : '' ?>">🍽️ Menu</a>
        <a href="/admin/categories" class="<?= ($page ?? '') === 'categories' ? 'active' : '' ?>">🏷️ Catégories</a>
    <?php endif; ?>

    <?php if (in_array(Auth::role(), ['ADMIN', 'SERVEUR'])): ?>
    <a href="/admin/journal" class="<?= ($page ?? '') === 'journal' ? 'active' : '' ?>">📓 Journal</a>
    <a href="/admin/reservations" class="<?= ($page ?? '') === 'reservations' ? 'active' : '' ?>">📅 Réservations</a>
<?php endif; ?>

    <?php if (in_array(Auth::role(), ['ADMIN', 'SERVEUR', 'CUISINE'])): ?>
        <a href="/admin/commandes" class="<?= ($page ?? '') === 'commandes' ? 'active' : '' ?>">🧾 Commandes</a>
    <?php endif; ?>

    <?php if (Auth::role() === 'ADMIN'): ?>
        <a href="/admin/stocks" class="<?= ($page ?? '') === 'stocks' ? 'active' : '' ?>">📦 Stocks</a>
        <a href="/admin/depenses" class="<?= ($page ?? '') === 'depenses' ? 'active' : '' ?>">💸 Dépenses</a>
        <a href="/admin/clients" class="<?= ($page ?? '') === 'clients' ? 'active' : '' ?>">👤 Clients</a>
        <a href="/admin/avis" class="<?= ($page ?? '') === 'avis' ? 'active' : '' ?>">⭐ Avis</a>
        <a href="/admin/employes" class="<?= ($page ?? '') === 'employes' ? 'active' : '' ?>">👥 Employés</a>
        <a href="/admin/parametres" class="<?= ($page ?? '') === 'parametres' ? 'active' : '' ?>">⚙️ Paramètres</a>
    <?php endif; ?>

    <a href="/deconnexion" class="text-danger">🚪 Déconnexion</a>
</nav>
    </aside>

    <main class="admin-content">