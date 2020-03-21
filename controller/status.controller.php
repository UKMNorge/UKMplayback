<?php

use UKMNorge\File\Size;
use UKMNorge\Http\Curl;

$WARN = new stdClass;
$WARN->diskspace = 100; # GB

// Forslag struktur
$STATUS = new stdClass;
$STATUS->level = ''; // feks 'critical', 'warning', 'error'
$STATUS->status = false;
$STATUS->message = null;

require_once('UKM/Autoloader.php');

// KONTAKT SERVER
$curl_playback = new Curl();
$curl_playback->timeout(2);
$status_playback = $curl_playback->request('https://playback.' . UKM_HOSTNAME . '/api/status.php');

// FÅR IKKE KONTAKT
if (!$status_playback) {
    $STATUS->level = 'critical';
    $STATUS->status = true;
    $STATUS->message =
        'Filserveren har problemer, så det er ikke mulig å laste opp eller ned mediefiler og dokumenter akkurat nå.' .
        '<br />' .
        '<a href=mailto:support@ukm.no?subject=Playbackserver er nede">Kontakt UKM Norge</a>';
} else {
    if (!$status_playback->writeable_upload_folder or !$status_playback->writeable_data_folder) {
        $STATUS->level = 'critical';
        $STATUS->status = true;
        $STATUS->message =
            'Filserveren har litt problemer, så det er kun mulig å laste ned enkelt-filer akkurat nå. ' .
            'Opplasting av nye filer, eller nedlasting av zip-filer er dessverre ikke mulig. ' .
            '<a href=mailto:support@ukm.no?subject=Playbackserver er nede">Kontakt UKM Norge</a>';
    }
    if ($status_playback->diskspace < $WARN->diskspace * 1024 * 1024 * 1024) {
        $STATUS->level = 'warning';
        $STATUS->status = true;
        $STATUS->message = 'Filserveren har kun ' . Size::getHuman($status_playback->diskspace, 2) . 'GB ledig plass';
    }
}

UKMplayback::addViewData('STATUS', $STATUS);
