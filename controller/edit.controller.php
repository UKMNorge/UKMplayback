<?php

use UKMNorge\Arrangement\Arrangement;

require_once('UKM/Autoloader.php');

$arrangement = new Arrangement((int) get_option('pl_id'));
$innslag = $arrangement->getInnslag()->get($_GET['innslag']);

$INFOS['innslag'] = $innslag;
$INFOS['playback'] = $innslag->getPlayback()->get($_GET['id']);
