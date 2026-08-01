<?php $titrePage = 'Avis clients'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div class="container py-5" style="margin-top: 60px; max-width: 700px;">
    <div class="section-title">
        <span>Témoignages</span>
        <h2>Avis clients</h2>
        <p><?= $noteMoyenne ?> ⭐ sur <?= count($avis) ?> avis</p>
    </div>

    <?php if (Auth::check()): ?>
    <div class="stat-card mb-4 p-4" id="form-avis-container">
        <h5 class="mb-3">Laisser un avis</h5>
        <div id="alert-zone"></div>
        <form id="form-avis">
            <div class="mb-3">
                <label class="form-label">Votre note</label>
                <div class="etoiles-select" id="etoiles-select">
                    <span data-valeur="1">⭐</span>
                    <span data-valeur="2">⭐</span>
                    <span data-valeur="3">⭐</span>
                    <span data-valeur="4">⭐</span>
                    <span data-valeur="5">⭐</span>
                </div>
                <input type="hidden" name="note" id="note-selectionnee" value="0">
            </div>
            <div class="mb-3">
                <label class="form-label">Votre commentaire</label>
                <textarea class="form-control" name="commentaire" rows="3" minlength="10" required></textarea>
            </div>
            <button type="submit" class="btn btn-accent">Publier mon avis</button>
        </form>
    </div>
    <?php else: ?>
        <div class="alert alert-info">
            <a href="/connexion">Connectez-vous</a> pour laisser un avis.
        </div>
    <?php endif; ?>

    <div id="avis-liste">
        <?php foreach ($avis as $a): ?>
        <div class="border-bottom py-3">
            <div class="d-flex justify-content-between">
                <strong><?= htmlspecialchars($a['user_nom']) ?></strong>
                <span><?= str_repeat('⭐', $a['note']) ?></span>
            </div>
            <p class="mb-1" style="color: var(--text-secondary);"><?= htmlspecialchars($a['commentaire']) ?></p>
            <small style="color: var(--text-secondary);"><?= date('d/m/Y', strtotime($a['created_at'])) ?></small>

            <?php if ($a['reponse_admin']): ?>
                <div class="mt-2 p-2" style="background: var(--bg-secondary); border-radius: 4px; border-left: 3px solid var(--accent);">
                    <small><strong>Réponse du restaurant :</strong> <?= htmlspecialchars($a['reponse_admin']) ?></small>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <?php if (empty($avis)): ?>
            <p class="text-center" style="color: var(--text-secondary);">Aucun avis pour le moment. Soyez le premier !</p>
        <?php endif; ?>
    </div>
</div>

<style>
.etoiles-select { font-size: 1.8rem; cursor: pointer; }
.etoiles-select span { opacity: 0.3; transition: opacity 0.2s; }
.etoiles-select span.active { opacity: 1; }
</style>

<script src="/assets/js/avis-client.js"></script>
<?php require __DIR__ . '/../partials/footer-client.php'; ?>