<?php $titrePage = 'Horaires';
$page = 'horaires'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">🕐 Horaires d'ouverture</h2>

<div class="stat-card mb-4">
    <form id="form-horaires">
        <table class="table align-middle">
            <thead>
                <tr><th>Jour</th><th>Fermé</th><th>Ouverture</th><th>Fermeture</th></tr>
            </thead>
            <tbody>
                <?php foreach ($joursNoms as $num => $nom): ?>
                <?php $h = $horaires[$num] ?? null; ?>
                <tr>
                    <td><?= $nom ?></td>
                    <td>
                        <input type="checkbox" class="form-check-input checkbox-ferme" name="ferme[<?= $num ?>]"
                               data-jour="<?= $num ?>" <?= (!$h || $h['ferme']) ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <input type="time" class="form-control input-heure" name="heure_ouverture[<?= $num ?>]" data-jour="<?= $num ?>"
                               value="<?= $h && $h['heure_ouverture'] ? substr($h['heure_ouverture'], 0, 5) : '11:30' ?>"
                               <?= (!$h || $h['ferme']) ? 'disabled' : '' ?>>
                    </td>
                    <td>
                        <input type="time" class="form-control input-heure" name="heure_fermeture[<?= $num ?>]" data-jour="<?= $num ?>"
                               value="<?= $h && $h['heure_fermeture'] ? substr($h['heure_fermeture'], 0, 5) : '22:00' ?>"
                               <?= (!$h || $h['ferme']) ? 'disabled' : '' ?>>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-accent">Enregistrer les horaires</button>
    </form>
</div>

<div class="stat-card">
    <h5 class="mb-3">Fermetures exceptionnelles</h5>

    <form id="form-fermeture" class="row g-2 mb-4">
        <div class="col-md-3">
            <label class="form-label">Du</label>
            <input type="datetime-local" class="form-control" name="date_debut" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Au</label>
            <input type="datetime-local" class="form-control" name="date_fin" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Raison (optionnel)</label>
            <input type="text" class="form-control" name="raison" placeholder="Ex: Jour férié, événement privé">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-accent w-100">Ajouter</button>
        </div>
    </form>

    <table class="table table-sm">
        <tbody id="liste-fermetures">
            <?php foreach ($fermetures as $f): ?>
            <tr data-id="<?= $f['id'] ?>">
                <td><?= date('d/m/Y H:i', strtotime($f['date_debut'])) ?> → <?= date('d/m/Y H:i', strtotime($f['date_fin'])) ?></td>
                <td><?= htmlspecialchars($f['raison'] ?: '—') ?></td>
                <td><button class="btn btn-sm btn-outline-danger btn-supprimer-fermeture" data-id="<?= $f['id'] ?>">Supprimer</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($fermetures)): ?>
        <p style="color: var(--text-secondary);">Aucune fermeture prévue.</p>
    <?php endif; ?>
</div>

<script src="/assets/js/horaires-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>