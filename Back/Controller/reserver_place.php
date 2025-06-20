<?php
/**
 * @var PDO $pdo
 */
require_once "Back/Model/reserver_place.php";

$errors = [];


$places = getPlaces($pdo);

if (!isset($_SESSION['user_id'])) {
    $errors[] = "Vous devez être connecté pour réserver une place.";
}


$place_id = null;
$type_place = null;
$date_debut = null;
$date_fin = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Pré-remplir si données GET (ex: après annulation)
    if (!empty($_GET['place_id'])) {
        $place_id = intval($_GET['place_id']);
    }
    if (!empty($_GET['type_place'])) {
        $type_place = $_GET['type_place'];
    }
    if (!empty($_GET['date_debut'])) {
        $date_debut = $_GET['date_debut'];
    }
    if (!empty($_GET['date_fin'])) {
        $date_fin = $_GET['date_fin'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $place_id = !empty($_POST['place_id']) ? intval($_POST['place_id']) : null;
    $type_place = $_POST['type_place'] ?? null;
    $date_debut = $_POST['date_debut'] ?? null;
    $date_fin = $_POST['date_fin'] ?? null;

    $now = date('Y-m-d\TH:i');

    if (!$place_id || !$type_place || !$date_debut || !$date_fin) {
        $errors[] = "Tous les champs sont obligatoires.";
    } elseif ($date_debut < $now) {
        $errors[] = "La date de début doit être ultérieure ou égale à maintenant.";
    } elseif ($date_fin <= $date_debut) {
        $errors[] = "La date de fin doit être supérieure à la date de début.";
    }

    if (empty($errors)) {
        // On pourrait ici enregistrer en base ou afficher récapitulatif

        // Exemple récupération infos place choisie
        $placeInfos = getPlaceById($pdo, $place_id);

        // Charger une vue de confirmation, par exemple
        require "Front/View/reservations.php";
        exit();
    }
}

// Affichage du formulaire
require "Front/View/reserver_place.php";
