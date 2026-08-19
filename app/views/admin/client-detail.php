<?php $titrePage = 'Détail client'; $page = 'clients'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<a href="/admin/clients" class="btn btn-sm btn-outline-secondary mb-3">← Retour aux clients</a>

<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card">
            <h4 class="font-title mb-1"><?= htmlspecialchars($nom) ?></h4>
            <p style="color: var(--text-secondary);" class="mb-2"><?= htmlspecialchars($email) ?></p>
            <p style="color: var(--text-secondary);"><?= htmlspecialchars($telephone ?? 'Pas de téléphone') ?></p>

            <?php if ($nbNoShow > 0): ?>
                <div class="alert alert-danger mt-3 mb-0">🚫 <?= $nbNoShow ?> no-show enregistré(s)</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-8">
        <div class="stat-card mb-3">
            <div class="stat-label mb-3">📅 Réservations récentes</div>
            <?php if (empty($reservations)): ?>
                <p style="color: var(--text-secondary);">Aucune réservation.</p>
            <?php else: ?>
                <table class="table table-sm">
                    <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($r['date_reservation'])) ?></td>
                        <td><?= $r['nb_personnes'] ?> pers.</td>
                        <td><span class="badge badge-statut badge-<?= strtolower($r['statut']) ?>"><?= str_replace('_', ' ', $r['statut']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

        <div class="stat-card">
            <div class="stat-label mb-3">🧾 Commandes récentes</div>
            <?php if (empty($commandes)): ?>
                <p style="color: var(--text-secondary);">Aucune commande.</p>
            <?php else: ?>
                <table class="table table-sm">
                    <?php foreach ($commandes as $c): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
                        <td><?= $c['type'] ?></td>
                        <td><?= number_format($c['total'], 0, ',', ' ') ?> <?= htmlspecialchars($devise) ?></td>
                        <td><span class="badge badge-statut badge-<?= strtolower($c['statut']) ?>"><?= $c['statut'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer-admin.php'; ?>