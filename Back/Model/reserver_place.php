<?php
function getPlaces(PDO $pdo): array|string {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM places";
        $prep = $pdo->prepare($query);
        $prep->execute();
        $places = $prep->fetchAll(PDO::FETCH_ASSOC);
        $prep->closeCursor();

        // Ajouter le statut dynamique
        $now = new DateTime();

        foreach ($places as &$place) {
            // Par défaut : libre
            $place['statut'] = 'libre';

            $queryReservation = "SELECT * FROM reservations 
                                 WHERE place_id = :place_id 
                                 AND statut != 'annulée'
                                 ORDER BY date_debut DESC
                                 LIMIT 1";
            $stmt = $pdo->prepare($queryReservation);
            $stmt->execute(['place_id' => $place['id']]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($reservation) {
                $debut = new DateTime($reservation['date_debut']);
                $fin = new DateTime($reservation['date_fin']);

                if ($now >= $debut && $now <= $fin) {
                    $place['statut'] = 'occupée';
                } elseif ($now < $debut) {
                    $place['statut'] = 'reservée';
                }
            }
        }

        return $places;

    } catch (PDOException $e) {
        return "Erreur lors de la récupération des places : " . $e->getMessage();
    }
}

function getReservationById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM reservations WHERE id = ?');
    $stmt->execute([$id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    return $reservation ?: null;
}

function getPlaceById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM places WHERE id = ?');
    $stmt->execute([$id]);
    $place = $stmt->fetch(PDO::FETCH_ASSOC);

    return $place ?: null;
}



function fetchReservation(PDO $pdo, int $place_id, string $date_debut, string $date_fin): string|bool
{
    $stmt = $pdo->prepare (
        "SELECT COUNT(*) FROM reservations WHERE place_id = :place_id AND (
                (date_debut <= :date_fin AND date_fin >= :date_debut)) "
    );
    $stmt->execute([
        ':place_id' => $place_id,
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin
    ]);
    if ($stmt->fetchColumn() > 0) {
        return "Cette place est déjà réservée sur la période choisie.";
    }
    return true;
}

function update_reservation(PDO $pdo, int $reservation_id, string $date_debut, string $date_fin): bool|string
{
    // Vérifier que la réservation existe
    $stmt = $pdo->prepare('SELECT * FROM reservations WHERE id = :id');
    $stmt->bindParam(':id', $reservation_id, PDO::PARAM_INT);
    $stmt->execute();
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        return "Réservation non trouvée.";
    }

    // Vérifier que la nouvelle période est valide
    if (new DateTime($date_debut) < new DateTime() || new DateTime($date_fin) <= new DateTime($date_debut)) {
        return "La période de réservation est invalide.";
    }

    // Mettre à jour la réservation
    $update = $pdo->prepare('UPDATE reservations SET date_debut = :date_debut, date_fin = :date_fin WHERE id = :id');
    $update->bindParam(':date_debut', $date_debut);
    $update->bindParam(':date_fin', $date_fin);
    $update->bindParam(':id', $reservation_id, PDO::PARAM_INT);

    try {
        $update->execute();
        return true;
    } catch (PDOException $e) {
        return "Erreur lors de la mise à jour de la réservation : " . $e->getMessage();
    }
}
