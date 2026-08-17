<?php $titrePage = 'Catégories'; $page = 'categories'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">🏷️ Catégories du menu</h2>

<div id="alert-zone"></div>

<div class="stat-card mb-4 p-3">
    <form id="form-nouvelle-categorie" class="d-flex gap-2">
        <input type="text" class="form-control" name="nom" placeholder="Nom de la nouvelle catégorie (ex: Grillades)" required>
        <button type="submit" class="btn btn-accent">Ajouter</button>
    </form>
</div>

<ul class="list-group" id="liste-categories">
    <?php foreach ($categories as $cat): ?>
    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $cat['id'] ?>" style="background: var(--bg-secondary); color: var(--text-primary); border-color: var(--border-color);">
        <span class="categorie-nom" data-id="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></span>
        <div>
            <button class="btn btn-sm btn-outline-primary btn-renommer" data-id="<?= $cat['id'] ?>" data-nom="<?= htmlspecialchars($cat['nom']) ?>">Renommer</button>
            <button class="btn btn-sm btn-outline-danger btn-supprimer-categorie" data-id="<?= $cat['id'] ?>">Supprimer</button>
        </div>
    </li>
    <?php endforeach; ?>
</ul>

<?php if (empty($categories)): ?>
    <p class="text-center py-4" style="color: var(--text-secondary);">Aucune catégorie pour le moment.</p>
<?php endif; ?>

<script src="/assets/js/categories-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>