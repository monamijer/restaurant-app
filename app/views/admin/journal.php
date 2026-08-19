<?php $titrePage = 'Journal'; $page = 'journal'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">📓 Journal des ventes</h2>

<div id="alert-zone"></div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="stat-card">
            <h5 class="mb-3">Nouvelle entrée</h5>
            <form id="form-journal">
                <div class="d-flex gap-2 mb-3">
                    <select class="form-control" id="select-plat">
                        <option value="">Choisir un plat...</option>
                        <?php foreach ($plats as $p): ?>
                            <option value="<?= $p['id'] ?>" data-nom="<?= htmlspecialchars($p['nom']) ?>" data-prix="<?= $p['prix'] ?>">
                                <?= htmlspecialchars($p['nom']) ?> — <?= number_format($p['prix'], 0, ',', ' ') ?> <?= htmlspecialchars($devise) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control" id="input-quantite-ligne" value="1" min="1" style="width: 80px;">
                    <button type="button" class="btn btn-outline-dark" id="btn-ajouter-ligne">+</button>
                </div>

                <table class="table table-sm">
                    <tbody id="lignes-entree"></tbody>
                </table>

                <div class="d-flex justify-content-between mb-3">
                    <strong>Total</strong>
                    <strong id="total-entree-courante">0 <?= htmlspecialchars($devise) ?></strong>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mode de paiement reçu</label>
                    <select class="form-control" id="mode-paiement-journal" required>
                        <option value="ESPECES">Espèces</option>
                        <option value="AIRTEL_MONEY">Airtel Money</option>
                        <option value="ORANGE_MONEY">Orange Money</option>
                        <option value="MPESA">M-Pesa</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-accent w-100" id="btn-enregistrer-entree" disabled>Enregistrer l'entrée</button>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card mb-3">
            <div class="stat-label mb-2">Total du jour</div>
            <div class="stat-value"><?= number_format($totalJour, 0, ',', ' ') ?> <?= htmlspecialchars($devise) ?></div>
        </div>

        <div class="stat-card mb-3">
            <div class="stat-label mb-2">Répartition par mode de paiement</div>
            <?php if (empty($parModePaiement)): ?>
                <p style="color: var(--text-secondary);">Aucune entrée aujourd'hui.</p>
            <?php else: ?>
                <ul class="mb-0">
                    <?php foreach ($parModePaiement as $m): ?>
                        <li><?= $m['mode_paiement'] ?> : <strong><?= number_format($m['total'], 0, ',', ' ') ?> <?= htmlspecialchars($devise) ?></strong></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="stat-card">
            <div class="stat-label mb-2">Entrées du jour</div>
            <?php if (empty($entrees)): ?>
                <p style="color: var(--text-secondary);">Aucune entrée pour le moment.</p>
            <?php else: ?>
                <table class="table table-sm">
                    <?php foreach ($entrees as $e): ?>
                    <tr>
                        <td><?= date('H:i', strtotime($e['created_at'])) ?></td>
                        <td><?= $e['mode_paiement'] ?></td>
                        <td><?= number_format($e['total'], 0, ',', ' ') ?> <?= htmlspecialchars($devise) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>window.deviseActuelle = <?= json_encode($devise) ?>;</script>
<script src="/assets/js/journal-serveur.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>