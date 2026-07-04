<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver une table - <?= htmlspecialchars($params['nom_restaurant']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div id="theme-toggle" class="theme-toggle">🌙</div>

    <div class="container py-5" style="margin-top: 60px; max-width: 600px;">
        <div class="section-title">
            <span>Réservation</span>
            <h2>Réserver une table</h2>
        </div>

        <?php if ($params['acompte_actif'] == '1'): ?>
            <div class="alert alert-info">
                ℹ️ Un acompte de <?= number_format($params['montant_acompte_par_personne'], 0, ',', ' ') ?> BIF/personne
                est demandé à partir de <?= $params['nb_personnes_min_acompte'] ?> personne(s),
                remboursable en cas d'annulation plus de <?= $params['delai_annulation_gratuite_h'] ?>h à l'avance.
            </div>
        <?php endif; ?>

        <div id="alert-zone"></div>

        <form id="form-reservation" class="auth-card">
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" id="resa-date" required min="<?= date('Y-m-d') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Heure</label>
                <select class="form-control" name="heure" required>
                    <?php foreach (['12:00','12:30','13:00','13:30','19:00','19:30','20:00','20:30','21:00'] as $h): ?>
                        <option value="<?= $h ?>"><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre de personnes</label>
                <input type="number" class="form-control" name="nb_personnes" min="1" max="20" value="2" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes (allergies, occasion spéciale...)</label>
                <textarea class="form-control" name="notes" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-accent w-100">Réserver</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/theme.js"></script>
    <script src="/assets/js/reservation.js"></script>
</body>
</html>