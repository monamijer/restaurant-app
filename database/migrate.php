<?php

require_once __DIR__ . '/../app/core/Env.php';
Env::load(__DIR__ . '/../.env');

$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? 'restaurant_app';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Table qui garde la trace des migrations déjà appliquées
$pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_fichier VARCHAR(255) UNIQUE NOT NULL,
    applique_le DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$dejaAppliquees = $pdo->query("SELECT nom_fichier FROM migrations")->fetchAll(PDO::FETCH_COLUMN);

$dossierMigrations = __DIR__ . '/migrations';
$fichiers = glob($dossierMigrations . '/*.sql');
sort($fichiers); // ordre alphabétique = ordre numérique grâce au préfixe 001_, 002_...

$nbAppliquees = 0;

foreach ($fichiers as $fichier) {
    $nomFichier = basename($fichier);

    if (in_array($nomFichier, $dejaAppliquees)) {
        continue;
    }

    echo "Application de : $nomFichier\n";
    $sql = file_get_contents($fichier);

    try {
        $pdo->exec($sql);
        $stmt = $pdo->prepare("INSERT INTO migrations (nom_fichier) VALUES (?)");
        $stmt->execute([$nomFichier]);
        $nbAppliquees++;
        echo "  ✅ OK\n";
    } catch (PDOException $e) {
        echo "  ❌ ERREUR : " . $e->getMessage() . "\n";
        echo "Arrêt des migrations.\n";
        exit(1);
    }
}

if ($nbAppliquees === 0) {
    echo "Aucune nouvelle migration à appliquer. Base à jour.\n";
} else {
    echo "$nbAppliquees migration(s) appliquée(s) avec succès.\n";
}