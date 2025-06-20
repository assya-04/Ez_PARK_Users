<?php
/**
 * @var array $user
 * @var array $errors
 * @var bool $success
 */
?>
<link rel="stylesheet" href="Front/assets/CSS/mon_profil.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="form-container">
    <h1 class="text-center fw-bold mb-4 text-primary">Modifier mon profil</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Profil mis à jour avec succès.</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" autocomplete="off">

        <!-- Nom et Prénom -->
        <div class="row">
            <div class="col-md-6">
                <label for="nom" class="form-label"><i class="fa fa-user me-2 text-primary"></i>Nom</label>
                <input type="text" id="nom" name="nom" required class="form-control"
                       value="<?= htmlspecialchars($_POST['nom'] ?? $user['Nom'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label for="prenom" class="form-label"><i class="fa fa-user me-2 text-primary"></i>Prénom</label>
                <input type="text" id="prenom" name="prenom" required class="form-control"
                       value="<?= htmlspecialchars($_POST['prenom'] ?? $user['Prenom'] ?? '') ?>">
            </div>
        </div>

        <!-- Identifiant -->
        <div class="mt-3">
            <label for="username" class="form-label"><i class="fa fa-id-badge me-2 text-primary"></i>Identifiant</label>
            <input type="text" id="username" name="username" required class="form-control"
                   value="<?= htmlspecialchars($_POST['username'] ?? $user['username'] ?? '') ?>">
        </div>

        <!-- Email et Téléphone -->
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="email" class="form-label"><i class="fa fa-envelope me-2 text-primary"></i>Email</label>
                <input type="email" id="email" name="email" required class="form-control"
                       value="<?= htmlspecialchars($_POST['email'] ?? $user['email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label for="telephone" class="form-label"><i class="fa fa-phone me-2 text-primary"></i>Téléphone</label>
                <input type="tel" id="telephone" name="telephone" required class="form-control"
                       value="<?= htmlspecialchars($_POST['telephone'] ?? $user['telephone'] ?? '') ?>">
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions mt-4 text-center">
            <button type="submit" name="update_profile" class="btn btn-primary px-4">
                <i class="fa fa-check me-2"></i>Enregistrer les modifications
            </button>
            <a href="index.php" class="btn btn-outline-secondary ms-3">
                <i class="fa fa-arrow-left me-2"></i>Retour à l'accueil
            </a>
        </div>

    </form>
</div>
