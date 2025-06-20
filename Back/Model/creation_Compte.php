<?php
function createUser(
    PDO $pdo,
    string $email,
    string $telephone,
    string $username,
    string $password,
    string $Nom,
    string $Prenom,
    string $date_naissance,
    string $statut = 'actif' // Par défaut : actif
): bool|string {
    try {
        // Vérifie si l'email ou le username existe déjà
        $checkQuery = "SELECT COUNT(*) FROM users WHERE email = :email OR username = :username";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute([
            ':email' => $email,
            ':username' => $username
        ]);
        $exists = $checkStmt->fetchColumn();

        if ($exists > 0) {
            return "Un utilisateur avec cet email ou ce nom d'utilisateur existe déjà.";
        }

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertion de l'utilisateur
        $query = "INSERT INTO users 
            (email, telephone, username, password, statut, date_creation, Nom, Prenom, date_naissance)
            VALUES 
            (:email, :telephone, :username, :password, :statut, NOW(), :Nom, :Prenom, :date_naissance)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':email' => $email,
            ':telephone' => $telephone,
            ':username' => $username,
            ':password' => $hashedPassword,
            ':statut' => $statut,
            ':Nom' => $Nom,
            ':Prenom' => $Prenom,
            ':date_naissance' => $date_naissance
        ]);

        return true;

    } catch (PDOException $e) {
        return "Erreur lors de la création de l'utilisateur : " . $e->getMessage();
    }
}
