<?php $titrePage = 'Détail client'; $page = 'clients'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<a href="/admin/clients" class="btn btn-sm btn-outline-secondary mb-3">← Retour aux clients</a>

<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card">
            <h4 class="font-title mb-1"><?= htmlspecialchars($client['nom']) ?></h4>
            <p style="color: var(--text-secondary);" class="mb-2"><?= htmlspecialchars($client['email']) ?></p>
            <p style="color: var(--text-secondary);"><?= htmlspecialchars($client['telephone'] ?? 'Pas de téléphone') ?></p>

            <?php if ($client['allergies']): ?>
                <div class="alert alert-warning mt-3 mb-0">
                    ⚠️ Allergies : <?= htmlspecialchars($client['allergies']) ?>
                </div>
            <?php endif; ?>

            <?php if ($client['nb_no_show'] > 0): ?>
                <div class="alert alert-danger mt-3 mb-0">
                    🚫 <?= $client['nb_no_show'] ?> no-show enregistré(s)
                </div>
            <?php endif; ?>

            <p class="mt-3 mb-0" style="color: var(--text-secondary); font-size: 0.85rem;">
                Client depuis le <?= date('d/m/Y', strtotime($client['created_at'])) ?>
            </p>
        </div>
    </div>

    <div class="col-md-8">
        <div class="stat-card mb-3">
            <div class="stat-label mb-3">📅 Réservations récentes</div>
            <?php if (empty($client['reservations'])): ?>
                <p style="color: var(--text-secondary);">Aucune réservation.</p>
            <?php else: ?>
                <table class="table table-sm">
                    <?php foreach ($client['reservations'] as $r): ?>
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
            <?php if (empty($client['commandes'])): ?>
                <p style="color: var(--text-secondary);">Aucune commande.</p>
            <?php else: ?>
                <table class="table table-sm">
                    <?php foreach ($client['commandes'] as $c): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
                        <td><?= $c['type'] ?></td>
                        <td><?= number_format($c['total'], 0, ',', ' ') ?> BIF</td>
                        <td><span class="badge badge-statut badge-<?= strtolower($c['statut']) ?>"><?= $c['statut'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer-admin.php'; ?>