<?php $titrePage = 'Menu'; $page = 'menu'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">🍽️ Gestion du menu</h2>

<div id="alert-zone"></div>

<button class="btn btn-accent mb-4" data-bs-toggle="modal" data-bs-target="#modalPlat" id="btn-nouveau-plat">
    + Ajouter un plat
</button>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Image</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Disponible</th><th>Actions</th>
            </tr>
        </thead>
        <tbody id="plats-table-body">
            <?php foreach ($plats as $plat): ?>
            <tr data-id="<?= $plat['id'] ?>">
                <td><img src="/assets/uploads/<?= htmlspecialchars($plat['image'] ?? 'default.jpg') ?>" width="60" height="60" style="object-fit:cover; border-radius:4px;"></td>
                <td><?= htmlspecialchars($plat['nom']) ?></td>
                <td><?= htmlspecialchars($plat['categorie_nom']) ?></td>
                <td><?= number_format($plat['prix'], 0, ',', ' ') ?> BIF</td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-dispo" type="checkbox" data-id="<?= $plat['id'] ?>" <?= $plat['disponible'] ? 'checked' : '' ?>>
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary btn-editer"
                        data-id="<?= $plat['id'] ?>" data-nom="<?= htmlspecialchars($plat['nom']) ?>"
                        data-description="<?= htmlspecialchars($plat['description']) ?>" data-prix="<?= $plat['prix'] ?>"
                        data-categorie="<?= $plat['categorie_id'] ?>" data-vegetarien="<?= $plat['vegetarien'] ?>"
                        data-sansgluten="<?= $plat['sans_gluten'] ?>" data-epice="<?= $plat['epice'] ?>">
                        Modifier
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-supprimer" data-id="<?= $plat['id'] ?>">Supprimer</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalPlat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-plat" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-titre">Ajouter un plat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="plat-id">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="nom" id="plat-nom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="plat-description" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Prix</label>
                            <input type="number" step="0.01" class="form-control" name="prix" id="plat-prix" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Catégorie</label>
                            <select class="form-control" name="categorie_id" id="plat-categorie" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="vegetarien" id="plat-vegetarien">
                            <label class="form-check-label">🌱 Végétarien</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sans_gluten" id="plat-sansgluten">
                            <label class="form-check-label">🌾 Sans gluten</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="epice" id="plat-epice">
                            <label class="form-check-label">🌶️ Épicé</label>
                        </div>
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="disponible" id="plat-disponible" checked>
                        <label class="form-check-label">Disponible à la vente</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-accent">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/assets/js/menu-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>