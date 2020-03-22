<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Innslag\Samling;

require_once('UKM/Autoloader.php');
require_once('UKM/curl.class.php');

unset($INFOS['innslag']);

$arrangement = new Arrangement( (Int) get_option('pl_id') );


// $hendelser = $m->concerts();

ob_start();
echo '<h3 class="hideOnDocReady">Vennligst vent, komprimerer...</h3>
	<p class="lead hideOnDocReady">Dette kan ta flere minutter!</p>
		<p class="hideOnDocReady">Alle filer for mønstringen</p>';
ob_flush();
flush();




// Prepare filelist as JSON
$jsondata = new stdClass();
$jsondata->filename = 'UKM Playback '. $arrangement->getNavn(); // $m->g('pl_name');
$jsondata->files = $files;


UKMplayback::addViewData('har_filer', !empty($files));
// Exchange filelist for zipfile
$curl = new UKMCURL();
$curl->timeout(5); //TODO: CHANGE BACK TO 60
UKMplayback::addViewData('alle_filer', $curl->json( $jsondata )->process('https://playback.' . UKM_HOSTNAME . '/zipMePlease/') );

#var_dump( $curl );

// foreach( $arrangement->getProgram()->getAbsoluteAll() as $hendelse ) {
// 	/** @var UKMNorge\Arrangement\Program\Hendelse $hendelse  */

// 	$rekkefolge = $hendelse->getInnslag()->getAll();



// 	echo '<p class="hideOnDocReady">Playbackfiler for '. $hendelse->getNavn() .'</p>';
// 	ob_flush();
// 	flush();

// 	// Sjekk filer for forestillingen
// 	$files = array();
// 	foreach( $rekkefolge as $int => $innslag ) {
// 		/** @var UKMNorge\Innslag\Innslag $innslag */
		

// 		$playbacks = $innslag->getPlayback();
		
// 		if( $playbacks->getAntall() > 0 ) {
// 			foreach( $playbacks->getAll() as $i => $pb ) {
// 				/** @var UKMNorge\Innslag\Playback\Playback $pb */
// 				$path = $pb->getPath();
// 				$name = $innslag->getNavn() .' FIL '. ($i+1) . $pb->getExtension();
// 				$files[ $path ] = $name;
// 			}
// 		}
// 	}
// 	if( sizeof( $files ) > 0 ) {
// 		// Prepare filelist as JSON
// 		$jsondata = new stdClass();
// 		$jsondata->filename = 'UKM Playback '. $arrangement->getNavn() .' '. $hendelse->getNavn();
// 		$jsondata->files = $files;
		
// 		// Exchange filelist for zipfile
// 		$curl = new UKMCURL();
// 		$curl->timeout(5); //TODO: CHANGE BACK TO 60
// 		$viewdata = $curl->json( $jsondata )->process('https://playback.' . UKM_HOSTNAME . '/zipMePlease/');
// 		if( !$viewdata ) {
// 			$viewdata = new stdClass();
// 			$viewdata->success = false;
// 			$viewdata->error = 'Det tok for lang tid å generere filen. Kontakt UKM Norge';
// 		}
// 		$viewdata->name = $hendelse->getNavn();		
// 		$INFOS['forestillinger'][] = $viewdata;		
// 	}
// }


echo '<script>jQuery(document).on(\'ready\', function(){jQuery(\'.hideOnDocReady\').slideUp()});</script>';
ob_flush();
flush();
ob_end_clean();