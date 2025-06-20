<?php
// Fichier : Back/Config/pusher.php

require_once __DIR__ . '/../../vendor/autoload.php'; // Remonte 2 niveaux pour accéder à /vendor

$options = [
    'cluster' => 'eu', // remplace par ton cluster réel (ex: 'mt1' ou 'eu')
    'useTLS' => true
];

$pusher = new Pusher\Pusher(
    '38211250606ceba37891',     // à remplacer
    'fe1ea2de7dc3935b508b',  // à remplacer
    '2010829',      // à remplacer
    $options
);
