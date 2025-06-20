<link rel="stylesheet" href="Front/assets/CSS/creation_Compte.css">

<div class="container">
    <div class="logo">
        <img src="logo.png" height="250px" width="250" alt="Logo">
    </div>

    <div class="form-wrapper">
        <div id="errors"></div>
        <div>
            <h1>Inscription</h1>
        </div>
        <div class="form">
            <form method="POST" id="register-form" autocomplete="off">
                <div class="form-group">
                    <label for="Nom">
                        <i class="fa fa-user me-2"></i>Nom
                    </label>
                    <input type="text" id="Nom" name="Nom" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="Prenom">
                        <i class="fa fa-user me-2"></i>Prénom
                    </label>
                    <input type="text" id="Prenom" name="Prenom" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="date_naissance">
                        <i class="fa fa-calendar me-2"></i>Date de naissance
                    </label>
                    <input type="date" id="date_naissance" name="date_naissance" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fa fa-envelope me-2"></i>Email
                    </label>
                    <input type="email" id="email" name="email" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="telephone">
                        <i class="fa fa-phone me-2"></i>Téléphone
                    </label>
                    <input type="tel" id="telephone" name="telephone" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="username">
                        <i class="fa fa-user me-2"></i>Identifiant
                    </label>
                    <input type="text" id="username" name="username" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fa fa-lock me-2"></i>Mot de passe
                    </label>
                    <input type="password" id="password" name="pass" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="confirmation">
                        <i class="fa fa-lock me-2"></i>Confirmation du mot de passe
                    </label>
                    <input type="password" id="confirmation" name="confirmation" required class="form-control">
                </div>


                <!-- Optionnel si tu veux laisser l'admin choisir un statut -->
                <div class="form-group">
                    <label for="statut">
                        <i class="fa fa-user-tag me-2"></i>Statut
                    </label>
                    <select id="statut" name="statut" class="form-control">
                        <option value="actif" selected>actif</option>
                        <option value="inactif">inactif</option>
                    </select>
                </div>

                <div class="button-group mt-3">
                    <button type="submit" class="btn btn-primary" name="create_button">
                        <i class="fa fa-check me-2"></i>Créer le compte
                    </button>
                </div>
            </form>
        </div>


        <div class="mt-3 text-center">
            <p>Vous avez déjà un compte ? <a href="index.php?component=loginUsers">Connexion</a></p>
        </div>
    </div>
</div>