<link rel="stylesheet" href="Front/assets/CSS/navbar.css">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="logo.png" width="80" height="50" alt="Logo" class="d-inline-block align-text-top">
        </a>

        <!-- Bouton toggle mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Liens de navigation -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Menu gauche -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php?component=accueilUsers#reservation">Mes réservations</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?component=accueilUsers#historique">Historiques</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?component=reserver_place">Trouver une place</a></li>
            </ul>

            <!-- Dropdown utilisateur -->
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="index.php?component=mon_profil">Mon profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?deconnect">Se déconnecter</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
