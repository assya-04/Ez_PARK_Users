<?php
// Back/Controller/reservations.php

/**
 * @var PDO $pdo
 */
require_once "Back/Model/reservations.php";

updatePlaceStatuses($pdo); // Met à jour les statuts avant affichage

$error = null;
$montant_total = 0;
$placeInfos = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $type_place = $_POST['type_place'] ?? null;
        $place_id = $_POST['place_id'] ?? null;
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;

    //var_dump($type_place);
   // var_dump($place_id);
   // var_dump($date_debut);
    //var_dump($date_fin);

        // Validation des données


    $errors = [];

        if (!$type_place) {
            $errors[] = "Le type de place est requis.";
        }
        if (!$place_id) {
            $errors[] = "La place est requise.";
        }
        if (!$date_debut) {
            $errors[] = "La date de début est requise.";
        }
        if (!$date_fin) {
            $errors[] = "La date de fin est requise.";
        }


    if (!empty($errors)) {
            exit;
        } else {
        // Récupérer les infos de la place
        $placeInfos = getPlaceById($pdo, $place_id);
        if (!$placeInfos) {
            $error = "Place non trouvée.";
        } else {
            // Récupérer le tarif horaire
            $tarif_horaire = getTarifParHeureByPlaceId($pdo, $place_id);
            if ($tarif_horaire === null) {
                $error = "Impossible de récupérer le tarif.";
            } else {
                // Calcul durée et montant total
                $date1 = new DateTime($date_debut);
                $date2 = new DateTime($date_fin);
                $diff = $date1->diff($date2);
                $duree_heures = ($diff->days * 24) + $diff->h + ($diff->i / 60);
                $montant_total = round($duree_heures * $tarif_horaire, 2);

// Stockage des infos nécessaires pour PayPal
                $_SESSION['paypal_place_id'] = $place_id;
                $_SESSION['paypal_date_debut'] = $date_debut;
                $_SESSION['paypal_date_fin'] = $date_fin;
                $_SESSION['paypal_montant'] = $montant_total;



                if (isset($_POST['confirmer'])) {
                    $user_id = $_SESSION['user_id'] ?? null;
                    if (!$user_id) {
                        exit("Utilisateur non connecté.");
                    }

               // Appel de la fonction reservePlace avec montant
                    $result = reservePlace($pdo, $user_id, (int)$place_id, $date_debut, $date_fin, $montant_total);

                    if ($result === true) {
                        header("Location: index.php?component=accueilUsers");
                        exit();
                    } else {
                        $error = $result;
                    }
                }

            }
        }
    }

    require "Front/View/reservations.php";

} else {
    // Redirection si accès direct sans POST
    header("Location: index.php?component=reserver_place");
    exit();
}

