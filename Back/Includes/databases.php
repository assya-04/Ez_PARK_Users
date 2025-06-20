<?php

$errors = [];

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=gestion_parking;charset=utf8',
        'root',
        '@Assyaoumar1',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Gère les erreurs correctement
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Récupère les résultats en tableau associatif
        ]
    );
} catch (PDOException $e) {
    $errors[] = "Erreur de connexion à la base de données : " . $e->getMessage();
    // Tu peux aussi faire un `exit()` ici si la base est essentielle :
    // exit("Connexion échouée.");
}
