<?php


/**
 * @var PDO $pdo
 */

require_once "Back/Includes/databases.php"; // Connexion BDD
require "Back/Model/reservations.php"; // Fonction reservePlace()


function validerReservationPaypal()
{
    global $pdo;

    if (!isset($_GET['orderID'])) {
        echo "Erreur : orderID manquant.";
        return;
    }
    var_dump($_SESSION['paypal_place_id']);

    $orderID = $_GET['orderID'];

    $clientID = 'Aat1Owvqt9YDQNPVMArNduDSGHe-c9eBpVGw4jpEi1iXfYeueT9DMak0fJh6QqvwrernGAEEQLwqir57';
    $secret = 'ED6xVocSrXBeOE47nWo3nzsAdTC7RLo52yzgmxWko9ksnrWV_TQdU8SuiD6FiICwCWF9opm64Meg2Gby';
    $baseURL = 'https://api-m.sandbox.paypal.com';

    // 1. Obtenir le token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$baseURL/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, "$clientID:$secret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Accept-Language: en_US",
    ]);
    $tokenResult = curl_exec($ch);
    curl_close($ch);

    $tokenData = json_decode($tokenResult, true);
    if (!isset($tokenData['access_token'])) {
        echo "Erreur : token PayPal manquant.";
        return;
    }

    $accessToken = $tokenData['access_token'];

    // 2. Vérifier la commande
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$baseURL/v2/checkout/orders/$orderID");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
    ]);
    $orderResult = curl_exec($ch);
    curl_close($ch);

    $orderData = json_decode($orderResult, true);

    if (isset($orderData['status']) && $orderData['status'] === 'COMPLETED') {
        echo "<div class='alert alert-success'>Paiement validé ! Merci " . htmlspecialchars($orderData['payer']['name']['given_name']) . ".</div>";

        // Récupérer les infos de réservation depuis la session
        $user_id = $_SESSION['user_id'] ?? null;
        $place_id = $_SESSION['paypal_place_id'] ?? null;
        $date_debut = $_SESSION['paypal_date_debut'] ?? null;
        $date_fin = $_SESSION['paypal_date_fin'] ?? null;
        $montant = $_SESSION['paypal_montant'] ?? null;

        var_dump($user_id);

        /**
         * @var PDO $pdo
         */

        if ($user_id && $place_id && $date_debut && $date_fin && $montant) {
            $result = reservePlace($pdo, $user_id, $place_id, $date_debut, $date_fin, $montant);

            if ($result === true) {
                // Nettoyer la session
                unset($_SESSION['paypal_place_id'], $_SESSION['paypal_date_debut'], $_SESSION['paypal_date_fin'], $_SESSION['paypal_montant']);
                echo "<div class='alert alert-success'>Réservation enregistrée avec succès.</div>";
                header("refresh:3;url=index.php?component=accueilUsers");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Erreur lors de l'enregistrement : $result</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Données de réservation incomplètes.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Paiement non validé.</div>";
    }
}

validerReservationPaypal();
