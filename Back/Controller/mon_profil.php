<?php

/**
 * @var PDO $pdo
 */

require "Back/Model/mon_profil.php";
require "Back/Includes/helpers.php";

$errors = [];
$success = false;

// Supposons que l'ID de l'utilisateur connecté est dans la session
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    // Rediriger vers la connexion si non connecté
    header('Location: login.php');
    exit;
}

// Récupérer les données utilisateur
$user = getUser($pdo, $userId);
if (!is_array($user)) {
    $errors[] = "Utilisateur introuvable.";
}

if (isset($_POST['update_profile'])) {
    $Nom = !empty($_POST['nom']) ? cleanString($_POST['nom']) : null;
    $Prenom = !empty($_POST['prenom']) ? cleanString($_POST['prenom']) : null;
    $username = !empty($_POST['username']) ? cleanString($_POST['username']) : null;
    $email = !empty($_POST['email']) ? cleanString($_POST['email']) : null;
    $telephone = !empty($_POST['telephone']) ? cleanString($_POST['telephone']) : null;

    if (empty($Nom) || empty($Prenom) || empty($username) || empty($email) || empty($telephone)) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (empty($errors)) {
        $updated = updateProfile($pdo, $userId, $Nom, $Prenom, $username, $email, $telephone);
        if ($updated === true) {
            $success = true;
            $user = getUser($pdo, $userId);
        } else {
            $errors[] = $updated;
        }
    }
}

require "Front/View/mon_profil.php";