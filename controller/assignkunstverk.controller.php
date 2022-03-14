<?php

use UKMNorge\Arrangement\Arrangement;

require_once('UKM/Autoloader.php');
require_once('UKM/curl.class.php');

$arrangement = new Arrangement( intval( get_option('pl_id') ));
$innslag = $arrangement->getInnslag()->get($_GET['innslag']);
$playback = $innslag->getPlayback()->get($_GET['id']);

$imgPlaybackUrl = $playback->base_url . $playback->getPath() . $playback->fil;

foreach($innslag->getTitler()->getAll() as $tittel) {
    if($tittel->getPlayback() && $tittel->getPlayback()->getId() == $playback->getId()) {
        $tittelKunstverk = $tittel;
    }
}

UKMplayback::addViewData([
    'arrangement' => $arrangement,
    'innslag' => $innslag,
    'playback' => $playback,
    'imgPlaybackUrl' => $imgPlaybackUrl,
    'velgTittel' => $tittelKunstverk ? false : true
]);
