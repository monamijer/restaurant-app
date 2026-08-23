<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titrePage) ? htmlspecialchars($titrePage) . ' - ' : '' ?>Etoile d'Or</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
    
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#b8894f">

    <!-- iOS spécifique : Apple ne lit pas toujours le manifest.json correctement -->
    <link rel="apple-touch-icon" href="/assets/icons/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Etoile d'Or">
    <meta name="csrf-token" content="<?= Csrf::token() ?>">
    <script src="/assets/js/csrf.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/image-fallback.js" defer></script>
</head>
<body>
    <div id="theme-toggle" class="theme-toggle">🌙</div>
    <?php require __DIR__ . '/nav-client.php'; ?>