<?php
$WARN = new stdClass;
$WARN->diskspace = 100; # GB

$STATUS = new stdClass;
$STATUS->critical = new stdClass;
$STATUS->critical->status = false;
$STATUS->critical->message = null;

$STATUS->warning = new stdClass;
$STATUS->warning->status = false;
$STATUS->warning->message = null;

$STATUS->info = new stdClass;
$STATUS->info->status = false;
$STATUS->info->message = null;

require_once('UKM/curl.class.php');

// KONTAKT SERVER
$curl_playback = new UKMCURL();
$curl_playback->timeout(2);
$status_playback = $curl_playback->request('https://playback.'. UKM_HOSTNAME .'/api/status.php');

// FÅR IKKE KONTAKT
if(!$status_playback) {
    $STATUS->critical->status = true;
    $STATUS->critical->message = "Webserveren får ikke kontakt med playback-serveren.
                                  Dette gjør at det ikke er mulig å laste opp eller ned playback-filer akkurat nå.
                                ";
// PLAYBACK MANGLER SKRIVERETTIGHETER
} else {
	if( !$status_playback->writeable_upload_folder or !$status_playback->writeable_data_folder ) {
		$STATUS->critical->status = true;
		$STATUS->critical->message = "Problemer med opplasting av playback og nedlasting av zip-filer.
									  Serveren har litt problemer, så det er kun mulig å laste ned enkelt-filer.";
	
	} 
	if( $status_playback->diskspace < $WARN->diskspace*1024*1024*1024) {
	    $disk_human = $status_playback->diskspace / (1024 * 1024 * 1024);
	    $STATUS->warning->status = true;
	    $STATUS->warning->message = 'Playbackserveren har kun '. round($disk_human,2) .'GB ledig plass';
	}
}
$INFOS['STATUS'] = $STATUS;
