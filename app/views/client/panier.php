<?php $titrePage = 'Mon panier'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div class="container py-5" style="margin-top: 60px; max-width: 700px;">
    <div class="section-title">
        <span>Commande</span>
        <h2>Mon panier</h2>
    </div>

    <div id="alert-zone"></div>

    <div id="panier-liste">
        <?php if (empty($panier['items'])): ?>
            <p class="text-center" style="color: var(--text-secondary);">Votre panier est vide.</p>
        <?php else: ?>
            <?php foreach ($panier['items'] as $platId => $item): ?>
                <div class="d-flex align-items-center justify-content-between border-bottom py-3" data-plat-id="<?= $platId ?>">
                    <div>
                        <strong><?= htmlspecialchars($item['nom']) ?></strong><br>
                        <span style="color: var(--text-secondary);"><?= number_format($item['prix'], 0, ',', ' ') ?> BIF</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" class="form-control form-control-sm input-quantite" style="width: 70px;" value="<?= $item['quantite'] ?>" min="0" data-plat-id="<?= $platId ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <h4>Total</h4>
        <h4 id="panier-total" style="color: var(--accent);"><?= number_format($panier['total'], 0, ',', ' ') ?> BIF</h4>
    </div>

    <?php if (!empty($panier['items'])): ?>
    <form id="form-checkout" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Type de commande</label>
            <select class="form-control" name="type" id="type-commande" required>
                <option value="SUR_PLACE">Sur place</option>
                <option value="EMPORTER">À emporter</option>
                <option value="LIVRAISON">Livraison</option>
            </select>
        </div>
        <div class="mb-3 d-none" id="champ-adresse">
            <label class="form-label">Adresse de livraison</label>
            <input type="text" class="form-control" name="adresse_livraison">
        </div>
        <div class="mb-3">
            <label class="form-label">Notes (optionnel)</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-accent w-100">Valider la commande</button>
    </form>
    <?php endif; ?>
</div>

<script src="/assets/js/panier.js"></script>
<?php require __DIR__ . '/../partials/footer-client.php'; ?>