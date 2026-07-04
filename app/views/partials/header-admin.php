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
</head>
<body>
<div id="theme-toggle" class="theme-toggle">🌙</div>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">
            <span class="font-title">Etoile d'Or</span>
            <small style="color: var(--text-secondary);">Espace admin</small>
        </div>
        <nav class="admin-nav">
            <a href="/admin" class="<?= ($page ?? '') === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
            <a href="/admin/menu" class="<?= ($page ?? '') === 'menu' ? 'active' : '' ?>">🍽️ Menu</a>
            <a href="/admin/reservations" class="<?= ($page ?? '') === 'reservations' ? 'active' : '' ?>">📅 Réservations</a>
            <a href="/admin/commandes" class="<?= ($page ?? '') === 'commandes' ? 'active' : '' ?>">🧾 Commandes</a>
            <a href="/admin/stocks" class="<?= ($page ?? '') === 'stocks' ? 'active' : '' ?>">📦 Stocks</a>
            <a href="/admin/clients" class="<?= ($page ?? '') === 'clients' ? 'active' : '' ?>">👤 Clients</a>
            <a href="/admin/avis" class="<?= ($page ?? '') === 'avis' ? 'active' : '' ?>">⭐ Avis</a>
            <a href="/admin/employes" class="<?= ($page ?? '') === 'employes' ? 'active' : '' ?>">👥 Employés</a>
            <a href="/admin/parametres" class="<?= ($page ?? '') === 'parametres' ? 'active' : '' ?>">⚙️ Paramètres</a>
            <a href="/deconnexion" class="text-danger">🚪 Déconnexion</a>
        </nav>
    </aside>

    <main class="admin-content">