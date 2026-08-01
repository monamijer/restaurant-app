<?php $titrePage = 'Avis'; $page = 'avis'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">⭐ Modération des avis</h2>

<div id="alert-zone"></div>

<div class="row g-3">
    <?php foreach ($avis as $a): ?>
    <div class="col-md-6">
        <div class="stat-card" data-id="<?= $a['id'] ?>">
            <div class="d-flex justify-content-between">
                <strong><?= htmlspecialchars($a['user_nom']) ?></strong>
                <span><?= str_repeat('⭐', $a['note']) ?></span>
            </div>
            <p style="color: var(--text-secondary);" class="my-2"><?= htmlspecialchars($a['commentaire']) ?></p>
            <small style="color: var(--text-secondary);"><?= date('d/m/Y H:i', strtotime($a['created_at'])) ?></small>

            <?php if ($a['reponse_admin']): ?>
                <div class="mt-2 p-2" style="background: var(--bg-primary); border-radius: 4px; border-left: 3px solid var(--accent);">
                    <small><strong>Votre réponse :</strong> <?= htmlspecialchars($a['reponse_admin']) ?></small>
                </div>
            <?php else: ?>
                <form class="form-reponse mt-2" data-id="<?= $a['id'] ?>">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="reponse" placeholder="Répondre à cet avis..." required>
                        <button class="btn btn-accent" type="submit">Envoyer</button>
                    </div>
                </form>
            <?php endif; ?>

            <button class="btn btn-sm btn-outline-danger mt-2 btn-supprimer-avis" data-id="<?= $a['id'] ?>">Supprimer</button>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($avis)): ?>
        <p class="text-center py-4" style="color: var(--text-secondary);">Aucun avis pour le moment.</p>
    <?php endif; ?>
</div>

<script src="/assets/js/avis-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>