<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

// Initialisation du contexte API PayPal avec tes identifiants sandbox
$paypal = new ApiContext(
    new OAuthTokenCredential(
        'Aat1Owvqt9YDQNPVMArNduDSGHe-c9eBpVGw4jpEi1iXfYeueT9DMak0fJh6QqvwrernGAEEQLwqir57',     // ← À remplacer
        'ED6xVocSrXBeOE47nWo3nzsAdTC7RLo52yzgmxWko9ksnrWV_TQdU8SuiD6FiICwCWF9opm64Meg2Gby'  // ← À remplacer
    )
);

$paypal->setConfig([
    'mode' => 'sandbox',                     // Mode test
    'http.ConnectionTimeOut' => 30,
    'log.LogEnabled' => false
]);
