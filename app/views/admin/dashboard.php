<?php $titrePage = 'Dashboard'; $page = 'dashboard'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="font-title mb-4">📊 Tableau de bord</h2>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-label">CA aujourd'hui</div>
            <div class="stat-value" id="stat-ca-jour"><?= number_format($stats['ca_jour'], 0, ',', ' ') ?> BIF</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-label">CA ce mois</div>
            <div class="stat-value"><?= number_format($stats['ca_mois'], 0, ',', ' ') ?> BIF</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-label">Commandes aujourd'hui</div>
            <div class="stat-value" id="stat-nb-commandes"><?= $stats['nb_commandes_jour'] ?></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-label">Réservations aujourd'hui</div>
            <div class="stat-value" id="stat-reservations"><?= $stats['reservations_jour'] ?></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Taux de no-show (30j)</div>
            <div class="stat-value"><?= $stats['taux_no_show'] ?>%</div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="stat-card <?= count($stats['stock_bas']) > 0 ? 'alert-stock' : '' ?>">
            <div class="stat-label">⚠️ Alertes stock bas</div>
            <?php if (empty($stats['stock_bas'])): ?>
                <p class="mb-0 mt-2" style="color: var(--text-secondary);">Aucune alerte, tout va bien.</p>
            <?php else: ?>
                <ul class="mb-0 mt-2">
                    <?php foreach ($stats['stock_bas'] as $ing): ?>
                        <li><?= htmlspecialchars($ing['nom']) ?> — <?= $ing['quantite_stock'] ?> <?= $ing['unite'] ?> restant(s)</li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-label mb-3">CA des 7 derniers jours</div>
            <canvas id="chartCA"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-label mb-3">Répartition par heure</div>
            <canvas id="chartHeures"></canvas>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-label mb-3">🏆 Plats les plus vendus</div>
            <?php if (empty($stats['plats_populaires'])): ?>
                <p style="color: var(--text-secondary);">Pas encore de données.</p>
            <?php else: ?>
                <ol>
                    <?php foreach ($stats['plats_populaires'] as $plat): ?>
                        <li><?= htmlspecialchars($plat['nom']) ?> — <strong><?= $plat['total_vendu'] ?></strong> vendus</li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-label mb-3">📅 Prochaines réservations</div>
            <?php if (empty($stats['prochaines_reservations'])): ?>
                <p style="color: var(--text-secondary);">Aucune réservation à venir.</p>
            <?php else: ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($stats['prochaines_reservations'] as $r): ?>
                        <li class="mb-2">
                            <?= date('d/m H:i', strtotime($r['date_reservation'])) ?> —
                            <?= htmlspecialchars($r['user_nom']) ?> (<?= $r['nb_personnes'] ?> pers.)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dataCA = <?= json_encode($stats['ca_7_jours']) ?>;
    const dataHeures = <?= json_encode($stats['repartition_heures']) ?>;
</script>
<script src="/assets/js/dashboard.js"></script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>