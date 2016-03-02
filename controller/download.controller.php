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


// ALLE INNSLAG PÅ MØNSTRINGEN
$files = array();
foreach( $alle_innslag as $inn ) {
	$innslag = new innslag( $inn['b_id'] );
	
	if( $innslag->har_playback() ) {
		$playback = $innslag->playback();
		foreach( $playback as $i => $pb ) {
			$path = $pb->relative_file();
			$name = $innslag->g('b_name') .' FIL '. ($i+1) . $pb->extension();
			$files[ $path ] = $name;
		}
	}
}

// Prepare filelist as JSON
$jsondata = new stdClass();
$jsondata->filename = 'UKM Playback '. $m->g('pl_name');
$jsondata->files = $files;

// Exchange filelist for zipfile
$curl = new UKMCURL();
$curl->timeout(60);
$INFOS['alle_filer'] = $curl->json( $jsondata )->process('http://playback.ukm.no/zipMePlease/');

#var_dump( $curl );

foreach( $hendelser as $con ) {
	$c = new forestilling( $con['c_id'] );
	$rekkefolge = $c->concertBands();
	echo '<p class="hideOnDocReady">Playbackfiler for '. $c->g('c_name') .'</p>';
	ob_flush();
	flush();

	// Sjekk filer for forestillingen
	$files = array();
	foreach( $rekkefolge as $int => $inn ) {
		$innslag = new innslag( $inn['b_id'] );
		
		if( $innslag->har_playback() ) {
			$playback = $innslag->playback();
			foreach( $playback as $i => $pb ) {
				$path = $pb->relative_file();
				$name = $innslag->g('b_name') .' FIL '. ($i+1) . $pb->extension();
				$files[ $path ] = $name;
			}
		}
	}
	if( sizeof( $files ) > 0 ) {
		// Prepare filelist as JSON
		$jsondata = new stdClass();
		$jsondata->filename = 'UKM Playback '. $m->g('pl_name') .' '. $c->g('c_name');
		$jsondata->files = $files;
		
		// Exchange filelist for zipfile
		$curl = new UKMCURL();
		$curl->timeout(60);
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