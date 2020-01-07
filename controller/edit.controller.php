<?php

use UKMNorge\Arrangement\Arrangement;

require_once('UKM/Autoloader.php');

$arrangement = new Arrangement( intval( get_option('pl_id') ));
$innslag = $arrangement->getInnslag()->get($_GET['innslag']);

UKMplayback::addViewData([
    'arrangement' => $arrangement,
    'innslag' => $innslag,
    'playback' => $innslag->getPlayback()->get($_GET['id'])
]);
