<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Réservation confirmée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5 text-center" style="margin-top:80px;">
        <h2 class="font-title">🎉 Réservation confirmée</h2>
        <?php if ($reservation): ?>
            <p>Le <strong><?= date('d/m/Y à H:i', strtotime($reservation['date_reservation'])) ?></strong>
               pour <strong><?= $reservation['nb_personnes'] ?></strong> personne(s).</p>
        <?php endif; ?>
        <a href="/" class="btn btn-accent mt-3">Retour à l'accueil</a>
    </div>
</body>
</html>