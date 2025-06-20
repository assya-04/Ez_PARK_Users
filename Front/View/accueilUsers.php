<?php
/**
 * @var PDO $pdo
 * @var array $allReservations
 * @var array $currentReservations
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Page Web - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="Front/assets/CSS/accueilUsers.css" />


</head>
<body>
<!-- En-tête -->


<!-- Bouton Menu -->
<aside id="web_menu_button">
    <ul id="web_menu">
        <li><a href="#">Accueil</a></li>
        <li><a href="#reservation">Mes réservations</a></li>
        <li><a href="#historique">Historiques</a></li>
        <li><a href="index.php?component=mon_profil">Mon profil</a></li>
        <li><a href="?deconnect">Se déconnecter</a></li>
    </ul>
</aside>

<!-- Contenu principal -->
<div class="container text-center mt-4" id="contenu">
    <h2 class="mt-3">Bienvenue
        <?php echo htmlspecialchars($_SESSION['username']); ?>
    </h2>
    <hr class="separator-line" />
</div>

<div>
    <div id="reservation" class="section-block">
        <h2 class="text-primary fw-bold mb-4 text-center">Mes réservations en cours</h2>
        <div class="table-container">
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                    <tr>
                        <th>Heure de début</th>
                        <th>Heure de fin</th>
                        <th>Numéro de place</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (is_string($currentReservations)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="alert alert-danger mb-0">
                                    <?= htmlspecialchars($currentReservations) ?>
                                </div>
                            </td>
                        </tr>
                    <?php elseif (!empty($currentReservations)): ?>
                        <?php foreach ($currentReservations as $res): ?>
                            <?php
                            $now = new DateTime();
                            $dateDebut = new DateTime($res['date_debut']);
                            $dateFin = new DateTime($res['date_fin']);

                            if ($res['statut'] === 'annulée') {
                                $statut = "Annulée";
                            } elseif ($dateDebut <= $now && $dateFin >= $now) {
                                $statut = "En cours";
                            } elseif ($dateDebut > $now) {
                                $statut = "À venir";
                            } else {
                                $statut = "Terminée";
                            }

                            switch ($statut) {
                                case 'Annulée': $badgeClass = 'bg-danger'; break;
                                case 'À venir': $badgeClass = 'bg-info'; break;
                                case 'En cours': $badgeClass = 'bg-success'; break;
                                case 'Terminée': $badgeClass = 'bg-secondary'; break;
                                default: $badgeClass = 'bg-secondary'; break;
                            }
                            ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($res['date_debut']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($res['date_fin']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($res['numero_place']) ?></td>
                                <td class="text-center"><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($statut) ?></span></td>
                                <td class="text-center">
                                    <?php if ($statut === "En cours"): ?>
                                        <button class="btn btn-sm btn-outline-danger" disabled>Annuler</button>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-sm btn-outline-danger delete-link" data-id="<?= htmlspecialchars($res['id']) ?>">Annuler</a>
                                        <a href="reserver_place.php?action=edit&id=<?= htmlspecialchars($res['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted fst-italic">Aucune réservation en cours.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bloc 2 : Bouton pour réserver -->
    <div class="text-center mt-4">
        <a href="index.php?component=reserver_place" class="btn btn-primary">Réserver une place</a>
    </div>

    <div id="historique" class="section-block">
        <h1 class="text-center fw-bold mb-4 text-primary">Mon historique</h1>
        <div class="table-container">
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                    <tr>
                        <th>Heure de début</th>
                        <th>Heure de fin</th>
                        <th>Numéro de place</th>
                        <th>Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (is_string($allReservations)): ?>
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-danger mb-0">
                                    <?= htmlspecialchars($allReservations) ?>
                                </div>
                            </td>
                        </tr>
                    <?php elseif (!empty($allReservations)): ?>
                        <?php foreach ($allReservations as $res): ?>
                            <?php
                            $statut = $res['statut_affiche'];
                            switch ($statut) {
                                case 'Annulée':
                                    $badgeClass = "bg-danger";
                                    break;
                                case 'À venir':
                                    $badgeClass = "bg-info";
                                    break;
                                case 'En cours':
                                    $badgeClass = "bg-success";
                                    break;
                                case 'Terminée':
                                    $badgeClass = "bg-secondary";
                                    break;
                                default:
                                    $badgeClass = "bg-secondary";
                            }

                            $dateDebutFormatted = (new DateTime($res['date_debut']))->format('d/m/Y H:i');
                            $dateFinFormatted = (new DateTime($res['date_fin']))->format('d/m/Y H:i');
                            ?>
                            <tr>
                                <td class="text-center"><?= $dateDebutFormatted ?></td>
                                <td class="text-center"><?= $dateFinFormatted ?></td>
                                <td class="text-center"><?= htmlspecialchars($res['numero_place']) ?></td>
                                <td class="text-center"><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($statut) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted fst-italic">Aucune réservation trouvée.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container text-center mt-4">
    <button class="btn btn-color" id="change_color_button">Ez_Park</button>
</div>

<!-- Pied de page -->
<div class="footer">&copy; 2025 <?php echo htmlspecialchars($_SESSION['username']); ?></div>

<script src="Front/assets/JS/Component/accueilUsers.js" type="module"></script>
<script type="module">
    import { handleDeleteReservation } from "./Front/assets/JS/Component/accueilUsers.js";

    document.addEventListener('DOMContentLoaded', () => {
        handleDeleteReservation();
    });
</script>

</body>
</html>
