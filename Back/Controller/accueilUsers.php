<?php
/**
 * @var PDO $pdo
 * @var array $user_id
 */
require "Back/Model/accueilUsers.php";
require "Back/Includes/helpers.php";
$user_id = (int) $_SESSION['user_id'];

$currentReservations = getCurrentAndUpcomingReservations($pdo, $user_id);
$allReservations = getPastReservations($pdo, $user_id);



$actionName = $_GET['action'] ?? null;
if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
) {
    switch ($actionName){
        case 'annuler_reservation':
            $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
            if (!$id) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'ID invalide']);
                exit();
            }


            $res = annulerReservation($pdo, $id);
            header('Content-Type: application/json');
            echo json_encode($res === true
                ? ['success' => true]
                : ['error' => is_string($res) ? $res : 'Erreur inconnue']);
            exit();
    }
}




require "Front/View/accueilUsers.php";