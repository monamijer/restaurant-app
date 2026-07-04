<?php $titrePage = 'Réservations'; $page = 'reservations'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">📅 Gestion des réservations</h2>

<div id="alert-zone"></div>

<div class="d-flex flex-wrap gap-2 mb-4" id="filtres-statut">
    <button class="btn btn-outline-dark btn-filtre-statut active" data-statut="">Toutes</button>
    <button class="btn btn-outline-dark btn-filtre-statut" data-statut="EN_ATTENTE_ACOMPTE">En attente d'acompte</button>
    <button class="btn btn-outline-dark btn-filtre-statut" data-statut="CONFIRMEE">Confirmées</button>
    <button class="btn btn-outline-dark btn-filtre-statut" data-statut="ARRIVEE">Arrivées</button>
    <button class="btn btn-outline-dark btn-filtre-statut" data-statut="NO_SHOW">No-show</button>
    <button class="btn btn-outline-dark btn-filtre-statut" data-statut="TERMINEE">Terminées</button>
</div>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Date & heure</th>
                <th>Client</th>
                <th>Personnes</th>
                <th>Acompte</th>
                <th>Statut</th>
                <th>No-show client</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="reservations-table-body">
            <?php foreach ($reservations as $r): ?>
            <tr data-id="<?= $r['id'] ?>" class="ligne-statut" data-statut-actuel="<?= $r['statut'] ?>">
                <td><?= date('d/m/Y à H:i', strtotime($r['date_reservation'])) ?></td>
                <td>
                    <?= htmlspecialchars($r['user_nom']) ?><br>
                    <small style="color: var(--text-secondary);"><?= htmlspecialchars($r['email']) ?><?= $r['telephone'] ? ' · ' . htmlspecialchars($r['telephone']) : '' ?></small>
                </td>
                <td><?= $r['nb_personnes'] ?></td>
                <td>
                    <?php if ($r['montant_acompte']): ?>
                        <?= number_format($r['montant_acompte'], 0, ',', ' ') ?> BIF
                        <br><span class="badge bg-<?= $r['statut_acompte'] === 'PAYE' ? 'success' : ($r['statut_acompte'] === 'RETENU' ? 'danger' : 'warning') ?>">
                            <?= $r['statut_acompte'] ?>
                        </span>
                    <?php else: ?>
                        <span style="color: var(--text-secondary);">Aucun</span>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge badge-statut badge-<?= strtolower($r['statut']) ?>"><?= str_replace('_', ' ', $r['statut']) ?></span>
                </td>
                <td>
                    <?php if ($r['nb_no_show'] > 0): ?>
                        <span class="badge bg-danger"><?= $r['nb_no_show'] ?> fois</span>
                    <?php else: ?>
                        <span style="color: var(--text-secondary);">0</span>
                    <?php endif; ?>
                </td>
                <td>
                    <select class="form-select form-select-sm select-statut" data-id="<?= $r['id'] ?>" style="width: auto; display: inline-block;">
                        <option value="">Changer le statut...</option>
                        <option value="CONFIRMEE">Confirmer</option>
                        <option value="ARRIVEE">Marquer arrivé</option>
                        <option value="NO_SHOW">Marquer no-show</option>
                        <option value="ANNULEE_RESTAURANT">Annuler (restaurant)</option>
                        <option value="TERMINEE">Terminer</option>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($reservations)): ?>
        <p class="text-center py-4" style="color: var(--text-secondary);">Aucune réservation pour le moment.</p>
    <?php endif; ?>
</div>

<script src="/assets/js/reservations-admin.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>