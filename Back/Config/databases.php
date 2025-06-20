<?php
$errors = [];

$envPath = dirname(__DIR__, 2) . '/.env'; // chemin vers .env

if (!file_exists($envPath)) {
    $errors[] = "Fichier .env introuvable à l'emplacement attendu.";
} else {
    $env = parse_ini_file($envPath);
    if ($env === false) {
        $errors[] = "Impossible de lire le fichier .env.";
    } else {
        try {
            $pdo = new PDO(
                "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_NAME']};charset=utf8",
                $env['DB_USER'],
                $env['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            $errors[] = "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    }
}
