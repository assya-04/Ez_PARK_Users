<?php

function connect(PDO $pdo, string $username): ?array {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM users WHERE username = :username";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':username', $username, PDO::PARAM_STR);

    try {
        $prep->execute();
        $res = $prep->fetch();
        $prep->closeCursor();
        return $res ?: null;
    } catch (PDOException $e) {
        error_log("Erreur PDO [{$e->getCode()}] : {$e->getMessage()}");
        return null;
    }
}

