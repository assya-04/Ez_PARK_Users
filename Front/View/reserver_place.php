<?php
/**
 * @var array $places
 * @var string|null $type_place
 * @var int|null $place_id
 * @var string|null $date_debut
 * @var string|null $date_fin
 * @var array $errors
 */
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="Front/assets/CSS/reserver_place.css"> <!-- si tu as des styles spécifiques -->
<link rel="stylesheet" href="Front/assets/CSS/modalPlaces.css">

<div class="form-container">
    <h1 class="text-center fw-bold mb-4 text-primary">Réserver une place</h1>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="index.php?component=reservations" autocomplete="off">

        <label for="type_place" class="form-label">
            <i class="fa fa-car me-2 text-primary"></i>Type de place
        </label>
        <select id="type_place" name="type_place" class="form-select" required>
            <option value="" disabled <?= $type_place === null ? 'selected' : '' ?>>-- Sélectionnez --</option>
            <option value="normal" <?= ($type_place === 'normal') ? 'selected' : '' ?>>Normal</option>
            <option value="handicapee" <?= ($type_place === 'handicapee') ? 'selected' : '' ?>>Handicapée</option>
            <option value="2roues" <?= ($type_place === '2roues') ? 'selected' : '' ?>>2 Roues</option>
        </select>

        <label for="place_id" class="form-label" style="margin-top: 15px;">
            <i class="fa fa-square-parking me-2 text-primary"></i>Choisir une place
        </label>
        <select id="place_id" name="place_id" class="form-select" required>
            <option value="" disabled <?= $place_id === null ? 'selected' : '' ?>>-- Sélectionnez une place --</option>
            <?php foreach ($places as $place): ?>
                <option value="<?= htmlspecialchars($place['id']) ?>" <?= ($place_id == $place['id']) ? 'selected' : '' ?>>
                    <?= isset($place['numero_place']) ? htmlspecialchars($place['numero_place']) : 'Non spécifié' ?>
                    (<?= isset($place['type_place']) ? htmlspecialchars($place['type_place']) : 'Type inconnu' ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <!-- MODALE DU PLAN DE PARKING -->
        <div id="modalPlacePicker" class="modal" style="margin-top: 15px;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 class="text-center">Choisir une place</h2>
                <div class="parking-grid">
                    <?php foreach ($places as $place): ?>
                        <?php
                        $classStatut = 'free';
                        if ($place['statut'] === 'occupée') {
                            $classStatut = 'occupied';
                        } elseif ($place['statut'] === 'reservée') {
                            $classStatut = 'reserved';
                        }
                        ?>
                        <div class="parking-spot <?= $classStatut ?>"
                             data-id="<?= $place['id'] ?>"
                             title="Type : <?= htmlspecialchars($place['type_place']) ?>&#10;
                             Tarif jour : <?= htmlspecialchars($place['tarif_journee']) ?> €&#10;
                             Tarif nuit : <?= htmlspecialchars($place['tarif_nuit']) ?> €&#10;
                             Tarif weekend : <?= htmlspecialchars($place['tarif_weekend']) ?> €">
                            <?= htmlspecialchars($place['numero_place']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <label for="date_debut" class="form-label" style="margin-top: 15px;">
            <i class="fa fa-calendar-day me-2 text-primary"></i>Date et heure de début
        </label>
        <input type="datetime-local" id="date_debut" name="date_debut" class="form-control"
               min="<?= date('Y-m-d\TH:i') ?>" required
               value="<?= htmlspecialchars($date_debut ?? '') ?>">

        <label for="date_fin" class="form-label" style="margin-top: 15px;">
            <i class="fa fa-calendar-day me-2 text-primary"></i>Date et heure de fin
        </label>
        <input type="datetime-local" id="date_fin" name="date_fin" class="form-control"
               min="<?= date('Y-m-d\TH:i') ?>" required
               value="<?= htmlspecialchars($date_fin ?? '') ?>">

        <div class="form-actions" style="display: flex; gap: 10px; margin-top: 25px;">
            <button type="submit" name="reserve_button" class="btn-submit">
                <i class="fa fa-check me-2 text-white"></i>Valider
            </button>
            <a href="index.php?component=accueilUsers" class="btn-secondary" style="display: inline-flex; align-items: center;">
                <i class="fa fa-times me-2"></i>Annuler
            </a>
        </div>
    </form>
</div>

<script>
    const selectEl = document.getElementById('place_id');
    const modal = document.getElementById('modalPlacePicker');
    const closeBtn = modal.querySelector('.close');
    const allSpots = modal.querySelectorAll('.parking-spot');

    // Ouvrir modale au focus sur select
    selectEl.addEventListener('focus', (e) => {
        e.preventDefault();
        modal.style.display = 'block';
    });

    // Fermer modale
    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = (e) => { if (e.target === modal) modal.style.display = 'none'; }

    // Sélection d'une place libre via modale
    allSpots.forEach(spot => {
        if (spot.classList.contains('free')) {
            spot.addEventListener('click', () => {
                const placeId = spot.getAttribute('data-id');
                selectEl.value = placeId;

                // Mettre à jour la sélection visuelle dans la modale
                allSpots.forEach(s => s.classList.remove('selected'));
                spot.classList.add('selected');

                modal.style.display = 'none';
            });

            // Si place correspond à la sélection initiale, ajouter la classe selected
            if (spot.getAttribute('data-id') === selectEl.value) {
                spot.classList.add('selected');
            }
        }
    });
</script>
