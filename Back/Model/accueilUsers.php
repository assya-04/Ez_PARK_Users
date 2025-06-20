<?php
function getPastReservations(PDO $pdo, int $user_id): array|string {
    try {
        $query = "SELECT r.id, r.date_debut, r.date_fin, p.numero_place, r.statut
                  FROM reservations r
                  JOIN users u ON r.user_id = u.id
                  JOIN places p ON r.place_id = p.id
                  WHERE u.id = :user_id
                    AND (r.date_fin < NOW() OR r.statut = 'annulée')";

        $prep = $pdo->prepare($query);
        $prep->execute(['user_id' => $user_id]);

        $reservations = $prep->fetchAll(PDO::FETCH_ASSOC);
        $prep->closeCursor();

        foreach ($reservations as &$res) {
            $res['statut_affiche'] = ($res['statut'] === 'annulée') ? 'Annulée' : 'Terminée';
        }

        return $reservations;
    } catch (PDOException $e) {
        return "Erreur lors de la récupération des réservations passées : " . $e->getMessage();
    }
}

function getCurrentAndUpcomingReservations(PDO $pdo, int $user_id): array|string {
    try {
        $query = "SELECT r.id, r.date_debut, r.date_fin, r.statut, p.numero_place
                  FROM reservations r
                  JOIN users u ON r.user_id = u.id
                  JOIN places p ON r.place_id = p.id
                  WHERE u.id = :user_id
                    AND r.date_fin >= NOW()
                    AND r.statut != 'annulée'";  // exclure les réservations annulées

        $prep = $pdo->prepare($query);
        $prep->execute(['user_id' => $user_id]);
        return $prep->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Erreur lors de la récupération des réservations : " . $e->getMessage();
    }
}
function annulerReservation(PDO $pdo, int $reservation_id): bool|string
{
    // Récupérer la réservation avec l'ID de la place
    $stmt = $pdo->prepare("SELECT statut, place_id FROM reservations WHERE id = ?");
    $stmt->execute([$reservation_id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        return "Réservation non trouvée.";
    }

    // Mettre à jour la réservation comme "annulée"
    $update = $pdo->prepare("UPDATE reservations SET statut = 'annulée' WHERE id = ?");
    try {
        $update->execute([$reservation_id]);

        // Mettre la place associée à "libre"
        return updatePlaceStatut($pdo, (int)$reservation['place_id'], 'libre');
    } catch (PDOException $e) {
        return "Erreur lors de l'annulation : " . $e->getMessage();
    }
}

function updatePlaceStatut(PDO $pdo, int $place_id, string $statut): bool|string {
    try {
        $stmt = $pdo->prepare("UPDATE places SET statut = :statut WHERE id = :id");
        $stmt->execute([
            'statut' => $statut,
            'id' => $place_id
        ]);
        return true;
    } catch (PDOException $e) {
        return "Erreur lors de la mise à jour de la place : " . $e->getMessage();
    }
}



