<?php $titrePage = 'Confirmation'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div class="container py-5 text-center" style="margin-top:80px;">
    <h2 class="font-title">🎉 Réservation confirmée</h2>
    <?php if ($reservation): ?>
        <p>Le <strong><?= date('d/m/Y à H:i', strtotime($reservation['date_reservation'])) ?></strong>
           pour <strong><?= $reservation['nb_personnes'] ?></strong> personne(s).</p>
    <?php endif; ?>
    <a href="/" class="btn btn-accent mt-3">Retour à l'accueil</a>
</div>

<?php require __DIR__ . '/../partials/footer-client.php'; ?>