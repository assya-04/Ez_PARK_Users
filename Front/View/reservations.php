<?php
/**
 * @var array $placeInfos
 * @var string|null $error
 */
?>
<link rel="stylesheet" href="Front/assets/CSS/reservations.css">

<div class="form-container reservation-confirmation">
    <h1 class="text-center fw-bold mb-4 text-primary">Récapitulatif de votre réservation</h1>
    <hr class="separator-line">

    <?php if (!empty($error)): ?>
        <div class="errors"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="reservation-details">
        <p><strong>Numéro de place :</strong> <?= htmlspecialchars($placeInfos['numero_place']) ?></p>
        <p><strong>Type de place :</strong> <?= htmlspecialchars($_POST['type_place']) ?></p>
        <p><strong>Date de début :</strong> <?= htmlspecialchars($_POST['date_debut']) ?></p>
        <p><strong>Date de fin :</strong> <?= htmlspecialchars($_POST['date_fin']) ?></p>
        <p><strong>Montant :</strong> <?= isset($montant_total) ? htmlspecialchars($montant_total) . ' €' : 'Non calculé' ?></p>
    </div>

    <form method="post" action="" class="reservation-form">
        <input type="hidden" name="place_id" value="<?= htmlspecialchars($_POST['place_id']) ?>">
        <input type="hidden" name="type_place" value="<?= htmlspecialchars($_POST['type_place']) ?>">
        <input type="hidden" name="date_debut" value="<?= htmlspecialchars($_POST['date_debut']) ?>">
        <input type="hidden" name="date_fin" value="<?= htmlspecialchars($_POST['date_fin']) ?>">
        <input type="hidden" name="montant" value="<?= htmlspecialchars($montant_total ?? 0) ?>">

        <button type="submit" name="confirmer" class="btn-submit">Confirmer la réservation</button>
        <a href="index.php?component=reserver_place&place_id=<?= urlencode($_POST['place_id']) ?>&type_place=<?= urlencode($_POST['type_place']) ?>&date_debut=<?= urlencode($_POST['date_debut']) ?>&date_fin=<?= urlencode($_POST['date_fin']) ?>" class="btn-secondary">Annuler</a>
    </form>

    <div id="paypal-button-container" style="margin-top: 30px;"></div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=Aat1Owvqt9YDQNPVMArNduDSGHe-c9eBpVGw4jpEi1iXfYeueT9DMak0fJh6QqvwrernGAEEQLwqir57&currency=EUR"></script>
<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?= $montant_total ?? 0 ?>'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Transaction complétée par ' + details.payer.name.given_name);
                window.location.href = "index.php?component=valider_Paypal&orderID=" + data.orderID;
            });
        },
        onError: function(err) {
            console.error(err);
            alert('Une erreur est survenue lors du paiement.');
        }
    }).render('#paypal-button-container');
</script>
