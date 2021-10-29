<?php

use UKMNorge\Arrangement\Arrangement;

require_once('UKM/Autoloader.php');
require_once('UKM/curl.class.php');

$arrangement = new Arrangement( intval( get_option('pl_id') ));
$innslag = $arrangement->getInnslag()->get($_GET['innslag']);
$playback = $innslag->getPlayback()->get($_GET['id']);

$imgPlaybackUrl = $playback->base_url . $playback->getPath() . $playback->fil;

UKMplayback::addViewData([
    'arrangement' => $arrangement,
    'innslag' => $innslag,
    'playback' => $playback,
    'imgPlaybackUrl' => $imgPlaybackUrl
]);
