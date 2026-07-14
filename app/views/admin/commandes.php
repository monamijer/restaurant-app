<?php $titrePage = 'Commandes'; $page = 'commandes'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">🧾 Commandes</h2>

<div id="alert-zone"></div>

<div class="row g-3" id="commandes-container">
    <?php foreach ($commandes as $c): ?>
    <div class="col-md-4">
        <div class="stat-card" data-id="<?= $c['id'] ?>">
            <div class="d-flex justify-content-between mb-2">
                <strong>#<?= $c['id'] ?> — <?= $c['type'] ?></strong>
                <span class="badge badge-statut badge-<?= strtolower($c['statut']) ?>"><?= $c['statut'] ?></span>
            </div>
            <p class="mb-1" style="color: var(--text-secondary);"><?= htmlspecialchars($c['user_nom']) ?></p>
            <ul class="mb-2">
                <?php foreach ($c['lignes'] as $ligne): ?>
                    <li><?= $ligne['quantite'] ?>x <?= htmlspecialchars($ligne['nom']) ?></li>
                <?php endforeach; ?>
            </ul>
            <strong><?= number_format($c['total'], 0, ',', ' ') ?> BIF</strong>

            <select class="form-select form-select-sm mt-2 select-statut-commande" data-id="<?= $c['id'] ?>">
                <option value="EN_ATTENTE" <?= $c['statut'] === 'EN_ATTENTE' ? 'selected' : '' ?>>En attente</option>
                <option value="EN_CUISINE" <?= $c['statut'] === 'EN_CUISINE' ? 'selected' : '' ?>>En cuisine</option>
                <option value="PRETE" <?= $c['statut'] === 'PRETE' ? 'selected' : '' ?>>Prête</option>
                <option value="SERVIE" <?= $c['statut'] === 'SERVIE' ? 'selected' : '' ?>>Servie</option>
                <option value="ANNULEE" <?= $c['statut'] === 'ANNULEE' ? 'selected' : '' ?>>Annulée</option>
            </select>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($commandes)): ?>
        <p class="text-center py-5" style="color: var(--text-secondary);">Aucune commande pour le moment.</p>
    <?php endif; ?>
</div>

<script src="/assets/js/commandes-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>