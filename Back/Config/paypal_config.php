<?php

$envPath = dirname(__DIR__, 2) . '/.env';

if (!file_exists($envPath)) {
    throw new Exception("Fichier .env introuvable à $envPath");
}

$env = parse_ini_file($envPath);

if (!$env) {
    throw new Exception("Impossible de lire le fichier .env");
}

$PAYPAL_CLIENT_ID = $env['PAYPAL_CLIENT_ID'] ?? '';
$PAYPAL_SECRET = $env['PAYPAL_SECRET'] ?? '';
$PAYPAL_BASE_URL = $env['PAYPAL_BASE_URL'] ?? 'https://api-m.sandbox.paypal.com'; // valeur par défaut

return [
    'client_id' => $PAYPAL_CLIENT_ID,
    'secret' => $PAYPAL_SECRET,
    'base_url' => $PAYPAL_BASE_URL,
];
