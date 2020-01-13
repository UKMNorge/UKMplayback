<?php
$WARN = new stdClass;
$WARN->diskspace = 100; # GB

// Forslag struktur
$STATUS = new stdClass;
$STATUS->level = ''; // feks 'critical', 'warning', 'error'
$STATUS->status = false;
$STATUS->message = null;

require_once('UKM/curl.class.php');

// KONTAKT SERVER
$curl_playback = new UKMCURL();
$curl_playback->timeout(2);
$status_playback = $curl_playback->request('https://playback.'. UKM_HOSTNAME .'/api/status.php');

// FÅR IKKE KONTAKT
if(!$status_playback) {
    $STATUS->level = 'critical';
    $STATUS->status = true;
    $STATUS->message = 
        'Serveren har litt problemer, så det er ikke mulig å laste opp eller ned mediefiler og dokumenter akkurat nå.'. 
        '<a href=mailto:support@ukm.no?subject=Playbackserver er nede">Kontakt UKM Norge</a>';
    // $STATUS->critical->status = true;
// PLAYBACK MANGLER SKRIVERETTIGHETER
} else {
	if( !$status_playback->writeable_upload_folder or !$status_playback->writeable_data_folder ) {
        $STATUS->level = 'critical';
		$STATUS->status = true;
        $STATUS->message = 
            'Serveren har litt problemer, så det er kun mulig å laste ned enkelt-filer akkurat nå. '.
            'Opplasting av nye filer, eller nedlasting av zip-filer er dessverre ikke mulig. '.
            '<a href=mailto:support@ukm.no?subject=Playbackserver er nede">Kontakt UKM Norge</a>';
	
	} 
	if( $status_playback->diskspace < $WARN->diskspace*1024*1024*1024) {
        $disk_human = $status_playback->diskspace / (1024 * 1024 * 1024);
        $STATUS->level = 'warning';
	    $STATUS->status = true;
	    $STATUS->message = 'Filserveren har kun '. round($disk_human,2) .'GB ledig plass';
	}
}

UKMplayback::addViewData('STATUS', $STATUS);
