<?php $titrePage = 'Employés'; $page = 'employes'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">👥 Gestion des employés</h2>

<div id="alert-zone"></div>

<button class="btn btn-accent mb-4" data-bs-toggle="modal" data-bs-target="#modalEmploye" id="btn-nouvel-employe">
    + Ajouter un employé
</button>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôle</th>
                <th>Membre depuis</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="employes-table-body">
            <?php foreach ($employes as $e): ?>
            <tr data-id="<?= $e['id'] ?>">
                <td><?= htmlspecialchars($e['nom']) ?></td>
                <td><?= htmlspecialchars($e['email']) ?></td>
                <td><?= htmlspecialchars($e['telephone'] ?? '—') ?></td>
                <td>
                    <span class="badge bg-<?= $e['role'] === 'ADMIN' ? 'dark' : ($e['role'] === 'CUISINE' ? 'warning' : 'info') ?>">
                        <?= $e['role'] ?>
                    </span>
                </td>
                <td><?= date('d/m/Y', strtotime($e['created_at'])) ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary btn-editer-employe"
                        data-id="<?= $e['id'] ?>"
                        data-nom="<?= htmlspecialchars($e['nom']) ?>"
                        data-telephone="<?= htmlspecialchars($e['telephone'] ?? '') ?>"
                        data-role="<?= $e['role'] ?>">
                        Modifier
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-supprimer-employe" data-id="<?= $e['id'] ?>">Supprimer</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($employes)): ?>
        <p class="text-center py-4" style="color: var(--text-secondary);">Aucun employé pour le moment.</p>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalEmploye" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-employe">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-employe-titre">Ajouter un employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="emp-id">
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" class="form-control" name="nom" id="emp-nom" required>
                    </div>
                    <div class="mb-3" id="champ-email">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="emp-email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" name="telephone" id="emp-telephone" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select class="form-control" name="role" id="emp-role" required>
                            <option value="SERVEUR">Serveur</option>
                            <option value="CUISINE">Cuisine</option>
                            <option value="ADMIN">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" id="label-password">Mot de passe</label>
                        <input type="password" class="form-control" name="password" id="emp-password" minlength="6">
                        <small style="color: var(--text-secondary);" id="hint-password"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-accent">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/assets/js/employes-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>