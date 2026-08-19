<?php $titrePage = 'Clients'; $page = 'clients'; ?>
<?php require __DIR__ . '/../partials/header-admin.php'; ?>

<h2 class="mb-4">👤 Clients</h2>

<div class="mb-4">
    <input type="text" class="form-control" id="recherche-client" placeholder="Rechercher par nom ou email...">
</div>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Réservations</th>
                <th>Commandes</th>
                <th>Total dépensé</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="clients-table-body">
            <?php foreach ($clients as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['nom']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td><?= htmlspecialchars($c['telephone'] ?? '—') ?></td>
                <td><?= $c['nb_reservations'] ?></td>
                <td><?= $c['nb_commandes'] ?></td>
                <td><strong><?= number_format($c['total_depense'], 0, ',', ' ') ?> <?= htmlspecialchars($devise) ?></strong></td>
                
                <td><a href="/admin/clients/detail?email=<?= urlencode($c['email']) ?>" class="btn btn-sm btn-outline-primary">Voir</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($clients)): ?>
        <p class="text-center py-4" style="color: var(--text-secondary);">Aucun client pour le moment.</p>
    <?php endif; ?>
</div>

<script>window.deviseActuelle = <?= json_encode($devise) ?>;</script>
<?php require __DIR__ . '/../partials/footer-admin.php'; ?>