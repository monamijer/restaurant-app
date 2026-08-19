<?php $titrePage = 'Suivi de commande'; ?>
<?php require __DIR__ . '/../partials/header-client.php'; ?>

<div class="container py-5 text-center" style="margin-top: 80px; max-width: 500px;">
    <h2 class="font-title mb-4">🧾 Commande #<?= $commande['id'] ?? '' ?></h2>
    <?php if (!empty($commande['mode_paiement']) && $commande['mode_paiement'] === 'CONTACT_RESTAURANT'): ?>
    <div class="alert alert-info">📞 Le restaurant va vous contacter pour convenir du paiement.</div>
    <?php elseif (!empty($commande['statut_paiement']) && $commande['statut_paiement'] === 'VERIFICATION_MANUELLE'): ?>
    <div class="alert alert-warning">⏳ Votre paiement est en cours de vérification par le restaurant.</div>
    <?php endif; ?>

    <div class="suivi-etapes mb-4" data-commande-id="<?= $commande['id'] ?? '' ?>">
        <div class="etape" data-statut="EN_ATTENTE">📝 Reçue</div>
        <div class="etape" data-statut="EN_CUISINE">👨‍🍳 En cuisine</div>
        <div class="etape" data-statut="PRETE">✅ Prête</div>
        <div class="etape" data-statut="SERVIE">🎉 Servie</div>
    </div>

    <a href="/" class="btn btn-accent">Retour à l'accueil</a>
</div>

<style>
.suivi-etapes { display: flex; flex-direction: column; gap: 0.75rem; }
.etape { padding: 1rem; border-radius: 6px; background: var(--bg-secondary); border: 1px solid var(--border-color); opacity: 0.4; }
.etape.actif { opacity: 1; border-color: var(--accent); background: var(--accent); color: #fff; font-weight: 600; }
</style>

<script src="/assets/js/suivi-commande.js"></script>
<?php require __DIR__ . '/../partials/footer-client.php'; ?>