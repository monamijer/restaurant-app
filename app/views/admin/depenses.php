<?php $titrePage = 'Dépenses'; $page = 'depenses'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">💸 Dépenses du restaurant</h2>

<div id="alert-zone"></div>

<form method="GET" action="/admin/depenses" class="mb-4 d-flex gap-2 align-items-end">
    <div>
        <label class="form-label">Mois</label>
        <input type="month" class="form-control" name="mois" value="<?= htmlspecialchars($moisActuel) ?>" onchange="this.form.submit()">
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Total des dépenses ce mois</div>
            <div class="stat-value"><?= number_format($total, 0, ',', ' ') ?> <?= htmlspecialchars($devise) ?></div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="stat-card">
            <div class="stat-label mb-2">Répartition par catégorie</div>
            <?php if (empty($parCategorie)): ?>
                <p style="color: var(--text-secondary);">Aucune dépense enregistrée ce mois.</p>
            <?php else: ?>
                <ul class="mb-0">
                    <?php foreach ($parCategorie as $c): ?>
                        <li><?= htmlspecialchars($c['nom']) ?> : <strong><?= number_format($c['total'], 0, ',', ' ') ?> <?= htmlspecialchars($devise) ?></strong></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<button class="btn btn-accent mb-4" data-bs-toggle="modal" data-bs-target="#modalDepense" id="btn-nouvelle-depense">
    + Ajouter une dépense
</button>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Catégorie</th>
                <th>Description</th>
                <th>Fournisseur</th>
                <th>Montant</th>
                <th>Saisie par</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="depenses-table-body">
            <?php foreach ($depenses as $d): ?>
            <tr data-id="<?= $d['id'] ?>">
                <td><?= date('d/m/Y', strtotime($d['date_depense'])) ?></td>
                <td><?= htmlspecialchars($d['categorie_nom']) ?></td>
                <td><?= htmlspecialchars($d['description']) ?></td>
                <td><?= htmlspecialchars($d['fournisseur'] ?: '—') ?></td>
                <td><strong><?= number_format($d['montant'], 0, ',', ' ') ?> <?= htmlspecialchars($devise) ?></strong></td>
                <td><?= htmlspecialchars($d['saisie_par_nom'] ?? '—') ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary btn-editer-depense"
                        data-id="<?= $d['id'] ?>"
                        data-categorie="<?= $d['categorie_id'] ?>"
                        data-montant="<?= $d['montant'] ?>"
                        data-description="<?= htmlspecialchars($d['description']) ?>"
                        data-fournisseur="<?= htmlspecialchars($d['fournisseur'] ?? '') ?>"
                        data-date="<?= $d['date_depense'] ?>">
                        Modifier
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-supprimer-depense" data-id="<?= $d['id'] ?>">Supprimer</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($depenses)): ?>
        <p class="text-center py-4" style="color: var(--text-secondary);">Aucune dépense pour cette période.</p>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalDepense" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-depense">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-depense-titre">Ajouter une dépense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="dep-id">
                    <div class="mb-3">
                        <label class="form-label">Catégorie</label>
                        <select class="form-control" name="categorie_id" id="dep-categorie" required>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant</label>
                        <input type="number" step="0.01" class="form-control" name="montant" id="dep-montant" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="description" id="dep-description" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fournisseur (optionnel)</label>
                        <input type="text" class="form-control" name="fournisseur" id="dep-fournisseur">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date_depense" id="dep-date" required value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-accent">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/assets/js/depenses-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>