<?php
session_name("parking_session");
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "Back/Includes/databases.php";

// Déconnexion
if (isset($_GET['deconnect'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Composants publics accessibles sans connexion
$publicComponents = ['loginUsers', 'creation_Compte'];

// Requête AJAX
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
) {
    $componentName = !empty($_GET['component'])
        ? htmlspecialchars($_GET['component'], ENT_QUOTES, 'UTF-8')
        : 'loginUsers';

    if (!empty($_SESSION['auth']) || in_array($componentName, $publicComponents)) {
        $componentPath = "Back/Controller/$componentName.php";

        if (file_exists($componentPath)) {
            require $componentPath;
        } else {
            http_response_code(404);
            echo "Composant '$componentName' inexistant";
        }
    } else {
        require "Back/Controller/loginUsers.php";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ez_PARK</title>
    <link rel="icon" type="image/x-icon" href="logo.png">

    <!-- Bootstrap et Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<div class="container-fluid pt-1">
    <?php
    $componentName = !empty($_GET['component'])
        ? htmlspecialchars($_GET['component'], ENT_QUOTES, 'UTF-8')
        : (!empty($_SESSION['auth']) ? 'accueilUsers' : 'loginUsers');

    // Utilisateur connecté
    if (!empty($_SESSION['auth'])) {
        require "Front/_partials/navbar.php";

        $componentPath = "Back/Controller/$componentName.php";
        if (file_exists($componentPath)) {
            require $componentPath;
        } else {
            echo "<div class='alert alert-danger'>Composant '$componentName' inexistant.</div>";
        }

        // Utilisateur non connecté, mais autorisé
    } elseif (in_array($componentName, $publicComponents)) {
        $componentPath = "Back/Controller/$componentName.php";
        if (file_exists($componentPath)) {
            require $componentPath;
        } else {
            echo "<div class='alert alert-danger'>Composant '$componentName' inexistant.</div>";
        }

        // Sinon, on affiche le formulaire de connexion
    } else {
        require "Back/Controller/loginUsers.php";
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
