<?php
require_once('UKM/monstring.class.php');
require_once('UKM/forestilling.class.php');
require_once('UKM/innslag.class.php');
require_once('UKM/curl.class.php');

unset($INFOS['innslag']);

$m = new monstring( get_option('pl_id') );

$alle_innslag = $m->innslag();
$hendelser = $m->concerts();

ob_start();
echo '<h3 class="hideOnDocReady">Vennligst vent, komprimerer...</h3>
	<p class="lead hideOnDocReady">Dette kan ta flere minutter!</p>
		<p class="hideOnDocReady">Alle playbackfiler for mønstringen</p>';
ob_flush();
flush();
$mediafiler = array();

foreach( $alle_innslag as $inn ) {
	$i = new innslag( $inn['b_id'] );
	
	if( $i->har_playback() ) {
		$media = new stdClass();
		$media->navn = $i->g('b_name');
		$media->innslag = $i->g('b_id');
		$mediafiler[] = $media;
	}
}

// Exchange list of bands for zip-file
$curl = new UKMCURL();
$curl->timeout(60);
$jsondata = new stdClass();
$jsondata->filename = 'UKM Playback '. $m->g('pl_name');
$jsondata->bands = $mediafiler;
$INFOS['alle_filer'] = $curl->json( $jsondata )->process('http://playback.ukm.no/zipMePlease/');


foreach( $hendelser as $con ) {
	$c = new forestilling( $con['c_id'] );
	$rekkefolge = $c->concertBands();
	echo '<p class="hideOnDocReady">Playbackfiler for '. $c->g('c_name') .'</p>';
	ob_flush();
	flush();
	$mediafiler = array();
	foreach( $rekkefolge as $int => $inn ) {
		$i = new innslag( $inn['b_id'] );
		
		if( $i->har_playback() ) {
			$media = new stdClass();
			$media->navn = $int .'. '. $i->g('b_name');
			$media->innslag = $i->g('b_id');
			$mediafiler[] = $media;
		}
	}
	if( sizeof( $mediafiler ) > 0 ) {
		$curl = new UKMCURL();
		$curl->timeout(60);
		$jsondata = new stdClass();
		$jsondata->filename = 'UKM Playback '. $m->g('pl_name') .' '. $c->g('c_name');
		$jsondata->bands = $mediafiler;
		
		$viewdata = $curl->json( $jsondata )->process('http://playback.ukm.no/zipMePlease/');
		if( !$viewdata ) {
			$viewdata = new stdClass();
			$viewdata->success = false;
			$viewdata->error = 'Det tok for lang tid å generere filen. Kontakt UKM Norge';
		}
		$viewdata->name = $c->g('c_name');		
		$INFOS['forestillinger'][] = $viewdata;
	}
}


echo '<script>jQuery(document).on(\'ready\', function(){jQuery(\'.hideOnDocReady\').slideUp()});</script>';
ob_flush();
flush();
ob_end_clean();