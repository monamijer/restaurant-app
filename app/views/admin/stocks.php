<?php $titrePage = 'Stocks'; $page = 'stocks'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">📦 Gestion des stocks</h2>

<div id="alert-zone"></div>

<button class="btn btn-accent mb-4" data-bs-toggle="modal" data-bs-target="#modalIngredient" id="btn-nouvel-ingredient">
    + Ajouter un ingrédient
</button>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Ingrédient</th>
                <th>Stock actuel</th>
                <th>Seuil d'alerte</th>
                <th>Péremption</th>
                <th>Fournisseur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="ingredients-table-body">
            <?php foreach ($ingredients as $ing): ?>
            <?php
                $stockBas = $ing['quantite_stock'] <= $ing['seuil_alerte'];
                $peremptionProche = $ing['date_peremption'] && strtotime($ing['date_peremption']) <= strtotime('+3 days');
            ?>
            <tr data-id="<?= $ing['id'] ?>" class="<?= $stockBas ? 'table-danger' : '' ?>">
                <td><?= htmlspecialchars($ing['nom']) ?></td>
                <td>
                    <span class="badge <?= $stockBas ? 'bg-danger' : 'bg-success' ?>">
                        <?= $ing['quantite_stock'] ?> <?= htmlspecialchars($ing['unite']) ?>
                    </span>
                </td>
                <td><?= $ing['seuil_alerte'] ?> <?= htmlspecialchars($ing['unite']) ?></td>
                <td>
                    <?php if ($ing['date_peremption']): ?>
                        <span class="<?= $peremptionProche ? 'text-danger fw-bold' : '' ?>">
                            <?= date('d/m/Y', strtotime($ing['date_peremption'])) ?>
                            <?= $peremptionProche ? ' ⚠️' : '' ?>
                        </span>
                    <?php else: ?>
                        <span style="color: var(--text-secondary);">—</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($ing['fournisseur'] ?? '—') ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary btn-ajuster-stock" data-id="<?= $ing['id'] ?>" data-nom="<?= htmlspecialchars($ing['nom']) ?>" data-stock="<?= $ing['quantite_stock'] ?>" data-unite="<?= htmlspecialchars($ing['unite']) ?>">
                        Ajuster stock
                    </button>
                    <button class="btn btn-sm btn-outline-primary btn-editer-ingredient"
                        data-id="<?= $ing['id'] ?>"
                        data-nom="<?= htmlspecialchars($ing['nom']) ?>"
                        data-stock="<?= $ing['quantite_stock'] ?>"
                        data-unite="<?= htmlspecialchars($ing['unite']) ?>"
                        data-seuil="<?= $ing['seuil_alerte'] ?>"
                        data-peremption="<?= $ing['date_peremption'] ?>"
                        data-fournisseur="<?= htmlspecialchars($ing['fournisseur'] ?? '') ?>">
                        Modifier
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-supprimer-ingredient" data-id="<?= $ing['id'] ?>">Supprimer</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($ingredients)): ?>
        <p class="text-center py-4" style="color: var(--text-secondary);">Aucun ingrédient enregistré.</p>
    <?php endif; ?>
</div>

<!-- Modal Ajout/Modification -->
<div class="modal fade" id="modalIngredient" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-ingredient">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-ingredient-titre">Ajouter un ingrédient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="ing-id">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="nom" id="ing-nom" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Stock initial</label>
                            <input type="number" step="0.01" class="form-control" name="quantite_stock" id="ing-stock" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Unité</label>
                            <select class="form-control" name="unite" id="ing-unite" required>
                                <option value="kg">kg</option>
                                <option value="g">g</option>
                                <option value="L">L</option>
                                <option value="ml">ml</option>
                                <option value="unité">unité</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Seuil d'alerte (stock bas)</label>
                        <input type="number" step="0.01" class="form-control" name="seuil_alerte" id="ing-seuil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date de péremption (optionnel)</label>
                        <input type="date" class="form-control" name="date_peremption" id="ing-peremption">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fournisseur (optionnel)</label>
                        <input type="text" class="form-control" name="fournisseur" id="ing-fournisseur">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-accent">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ajustement rapide du stock -->
<div class="modal fade" id="modalAjustStock" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="form-ajust-stock">
                <div class="modal-header">
                    <h5 class="modal-title">Ajuster le stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="ajust-id">
                    <p id="ajust-nom-affiche" class="fw-bold"></p>
                    <div class="mb-3">
                        <label class="form-label">Nouvelle quantité</label>
                        <input type="number" step="0.01" class="form-control" name="quantite_stock" id="ajust-quantite" required>
                        <small style="color: var(--text-secondary);" id="ajust-unite-affiche"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-accent">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/assets/js/stocks-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>