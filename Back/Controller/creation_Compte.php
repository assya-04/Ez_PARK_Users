<?php
/**
 * @var PDO $pdo
 */

require "Back/Model/creation_Compte.php";
require "Back/Includes/helpers.php";

$errors = [];

if (isset($_POST['create_button'])) {
    $username = !empty($_POST['username']) ? cleanString($_POST['username']) : null;
    $password = !empty($_POST['pass']) ? cleanString($_POST['pass']) : null;
    $confirmation = !empty($_POST['confirmation']) ? cleanString($_POST['confirmation']) : null;
    $email = !empty($_POST['email']) ? cleanString($_POST['email']) : null;
    $telephone = !empty($_POST['telephone']) ? cleanString($_POST['telephone']) : null;
    $Nom = !empty($_POST['Nom']) ? cleanString($_POST['Nom']) : null;
    $Prenom = !empty($_POST['Prenom']) ? cleanString($_POST['Prenom']) : null;
    $date_naissance = !empty($_POST['date_naissance']) ? cleanString($_POST['date_naissance']) : null;
    $statut = !empty($_POST['statut']) ? cleanString($_POST['statut']) : 'actif';

    if ($password !== $confirmation) {
        $errors[] = "Le mot de passe et sa confirmation sont différents";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $result = createUser($pdo, $email, $telephone, $username, $hashedPassword, $Nom, $Prenom, $date_naissance, $statut);

        if ($result !== true) {
            $errors[] = $result;
        } else {
            // Récupérer l'utilisateur créé
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Créer la session d'authentification
                $_SESSION['auth'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                session_start();

                // Redirection vers la page d'accueil
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Erreur lors de la récupération des informations utilisateur.";
            }
        }
    }
}

require "Front/View/creation_Compte.php";
