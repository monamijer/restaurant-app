<?php $titrePage = 'Confirmation'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div class="container py-5 text-center" style="margin-top:80px;">
    <?php if ($reservation): ?>
        <?php if ($reservation['statut'] === 'CONFIRMEE' || $reservation['statut_acompte'] === 'PAYE'): ?>
            <h2 class="font-title">🎉 Réservation confirmée</h2>
            <p>Le <strong><?= date('d/m/Y à H:i', strtotime($reservation['date_reservation'])) ?></strong>
               pour <strong><?= $reservation['nb_personnes'] ?></strong> personne(s).</p>

        <?php elseif ($reservation['mode_paiement'] === 'CONTACT_RESTAURANT'): ?>
            <h2 class="font-title">📞 Demande enregistrée</h2>
            <p>Votre demande de réservation pour le <strong><?= date('d/m/Y à H:i', strtotime($reservation['date_reservation'])) ?></strong>
               (<?= $reservation['nb_personnes'] ?> personne(s)) a bien été reçue.</p>
            <p style="color: var(--text-secondary);">Le restaurant va vous contacter prochainement pour convenir du paiement et finaliser votre réservation.</p>

        <?php elseif ($reservation['statut_acompte'] === 'VERIFICATION_MANUELLE'): ?>
            <h2 class="font-title">⏳ En attente de vérification</h2>
            <p>Votre réservation du <strong><?= date('d/m/Y à H:i', strtotime($reservation['date_reservation'])) ?></strong>
               est enregistrée. Le restaurant vérifie votre paiement et confirmera sous peu.</p>

        <?php else: ?>
            <h2 class="font-title">📝 Réservation en cours de traitement</h2>
            <p>Le <strong><?= date('d/m/Y à H:i', strtotime($reservation['date_reservation'])) ?></strong>
               pour <strong><?= $reservation['nb_personnes'] ?></strong> personne(s).</p>
        <?php endif; ?>
    <?php else: ?>
        <h2 class="font-title">Réservation introuvable</h2>
    <?php endif; ?>

    <a href="/" class="btn btn-accent mt-3">Retour à l'accueil</a>
</div>

<?php require __DIR__ . '/../partials/footer-client.php'; ?>