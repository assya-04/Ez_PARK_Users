<?php

function updateProfile(
    PDO $pdo,
    int $id,
    ?string $Nom,
    ?string $Prenom,
    string $username,
    string $email,
    string $telephone
): bool|string {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "UPDATE users SET
                Nom = :Nom, 
                Prenom = :Prenom,
                username = :username,
                email = :email,
                telephone = :telephone
              WHERE id = :id";

    $prep = $pdo->prepare($query);

    $prep->bindValue(':id', $id, PDO::PARAM_INT);
    $prep->bindValue(':Nom', $Nom);
    $prep->bindValue(':Prenom', $Prenom);
    $prep->bindValue(':username', $username);
    $prep->bindValue(':email', $email);
    $prep->bindValue(':telephone', $telephone);

    try {
        $prep->execute();
    } catch (PDOException $e) {
        return "Erreur : " . $e->getCode() . " - " . $e->getMessage();
    }

    return true;
}

function getUser(PDO $pdo, int $id): array|string {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM users WHERE id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':id', $id, PDO::PARAM_INT);

    try {
        $prep->execute();
    } catch (PDOException $e) {
        return "Erreur : " . $e->getCode() . " - " . $e->getMessage();
    }

    $res = $prep->fetch(PDO::FETCH_ASSOC);
    $prep->closeCursor();

    return $res;
}