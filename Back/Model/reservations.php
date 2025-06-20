<?php

function getPlaceById(PDO $pdo, int $id): array|false {
    $stmt = $pdo->prepare("SELECT * FROM places WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function reservePlace(PDO $pdo, int $user_id, int $place_id, string $date_debut, string $date_fin, float $montant): bool|string
{
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $now = new DateTime();
        $debut = new DateTime($date_debut);
        $fin = new DateTime($date_fin);

        if ($debut < $now) {
            return "La date de début ne peut pas être dans le passé.";
        }

        if ($fin <= $debut) {
            return "La date de fin doit être après la date de début.";
        }

        // Vérifier que la place existe
        $stmt = $pdo->prepare("SELECT statut FROM places WHERE id = :place_id");
        $stmt->execute([':place_id' => $place_id]);
        $place = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$place) {
            return "Place introuvable.";
        }

        // Vérifier les conflits
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM reservations 
            WHERE place_id = :place_id
              AND NOT (date_fin <= :date_debut OR date_debut >= :date_fin)
        ");
        $stmt->execute([
            ':place_id' => $place_id,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin,
        ]);

        if ($stmt->fetchColumn() > 0) {
            return "Cette place est déjà réservée pendant la période choisie.";
        }

        // Début transaction
        $pdo->beginTransaction();

        // Insertion réservation
        $stmt = $pdo->prepare("
            INSERT INTO reservations (
                user_id, place_id, date_debut, date_fin, montant, confirmation_envoyee, date_creation
            ) VALUES (
                :user_id, :place_id, :date_debut, :date_fin, :montant, 0, :date_creation
            )
        ");
        $stmt->execute([
            ':user_id' => $user_id,
            ':place_id' => $place_id,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin,
            ':montant' => $montant,
            ':date_creation' => $now->format('Y-m-d H:i:s'),
        ]);
        $reservation_id = $pdo->lastInsertId();

        if (!$reservation_id) {
            $pdo->rollBack();
            return "Erreur lors de la création de la réservation.";
        }

        // Insertion paiement
        $paiement = insertPaiement($pdo, $reservation_id, $montant, 'PayPal', 'effectué');
        if ($paiement !== true) {
            $pdo->rollBack();
            return "Erreur lors de l'enregistrement du paiement : $paiement";
        }

        // Mise à jour statut place
       // $update = $pdo->prepare("UPDATE places SET statut = 'reservée' WHERE id = :place_id");
       // $update->execute([':place_id' => $place_id]);
        updatePlaceStatuses($pdo);

        $pdo->commit();

        return true;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return "Erreur PDO : " . $e->getMessage();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return "Erreur : " . $e->getMessage();
    }
}


function insertPaiement(PDO $pdo, int $reservation_id, float $montant, string $moyen_paiement, string $statut_paiement = 'effectué'): bool|string
{
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO paiements (reservation_id, montant, moyen_paiement, statut_paiement, date_paiement)
                VALUES (:reservation_id, :montant, :moyen_paiement, :statut_paiement, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':reservation_id' => $reservation_id,
            ':montant' => $montant,
            ':moyen_paiement' => $moyen_paiement,
            ':statut_paiement' => $statut_paiement,
        ]);
        return true;
    } catch (PDOException $e) {
        return "Erreur insertion paiement : " . $e->getMessage();
    }
}



function updatePlaceStatuses(PDO $pdo) {
    $now = (new DateTime())->format('Y-m-d H:i:s');

    // 1. Tout remettre à 'libre' sauf les annulées
    $pdo->exec("UPDATE places SET statut = 'libre' WHERE statut != 'annulée'");

    // 2. Mettre à 'occupée' les places avec réservation en cours
    $sqlOccupied = "
        UPDATE places p
        JOIN reservations r ON p.id = r.place_id
        SET p.statut = 'occupée'
        WHERE r.date_debut <= :now
          AND r.date_fin >= :now
    ";
    $stmt = $pdo->prepare($sqlOccupied);
    $stmt->execute([':now' => $now]);

    // 3. Mettre à 'reservée' les places avec réservation à venir (et pas déjà marquées occupées)
    $sqlReserved = "
        UPDATE places p
        JOIN reservations r ON p.id = r.place_id
        SET p.statut = 'reservée'
        WHERE r.date_debut > :now
          AND p.statut NOT IN ('occupée', 'annulée')
    ";
    $stmt = $pdo->prepare($sqlReserved);
    $stmt->execute([':now' => $now]);
}


function getTarifParHeureByPlaceId(PDO $pdo, int $place_id): ?float {
    $stmt = $pdo->prepare("SELECT tarif_journee FROM places WHERE id = :id");
    $stmt->execute(['id' => $place_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? (float) $result['tarif_journee'] : null;
}



