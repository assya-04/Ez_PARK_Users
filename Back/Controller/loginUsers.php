<?php

require "Back/Model/loginUsers.php";


/**
 * @var PDO $pdo
 */

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
) {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $errors = [];
    $debug = [];

    if (!$username || !$password) {
        $errors[] = "Identifiant et mot de passe vides.";
    } else {
        // Vérifie que $pdo est bien initialisé
        if (!isset($pdo) || !$pdo instanceof PDO) {
            $errors[] = "Erreur interne : connexion base de données.";
            $debug[] = "PDO non initialisé";
        } else {
            $connexion = connect($pdo, $username);

            if (!$connexion) {
                $errors[] = "Erreur d'identification : utilisateur inconnu.";
                $debug[] = "Aucun utilisateur trouvé pour '$username'";
            } elseif (!isset($connexion['password'])) {
                $errors[] = "Erreur d'identification : mot de passe non trouvé.";
                $debug[] = "Champ password absent dans la réponse SQL";
            } elseif (!password_verify($password, $connexion['password'])) {
                $errors[] = "Erreur d'identification : mot de passe incorrect.";
                $debug[] = "Password hash : " . $connexion['password'];
            }else {
                $_SESSION["auth"] = true;
                $_SESSION["username"] = $connexion['username'];
                $_SESSION["user_id"] = $connexion['id'];
                header("Content-Type: application/json");
                echo json_encode(['authentication' => true]);
                exit();
            }
        }
    }
    header("Content-Type: application/json");
    echo json_encode(['errors' => $errors, 'debug' => $debug]);
    exit();
}


require "Front/View/loginUsers.php";
