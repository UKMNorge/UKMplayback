<?php
require_once('UKM/monstring.class.php');
require_once('UKM/forestilling.class.php');
require_once('UKM/innslag.class.php');
require_once('UKM/curl.class.php');

$m = new monstring( get_option('pl_id') );

$alle_innslag = $m->innslag();

$mediafiler = array();

foreach( $alle_innslag as $inn ) {
	$i = new innslag( $inn['b_id'] );
	
	if( $i->har_playback() ) {
		$mediafiler['alle_innslag'][] = $i->g('b_id');
	}
}

// Exchange list of bands for zip-file
$curl = new UKMCURL();
$jsondata = new stdClass();
$jsondata->filename = 'UKM Playback '. $m->g('pl_name');
$jsondata->bands = $mediafiler['alle_innslag'];
$INFOS['alle_filer'] = $curl->json( $jsondata )->process('http://playback.ukm.no/zipMePlease/');


/*
	$zipname = 'UKM Playback '. $m->g('pl_name');
$zip = new zip( $zipname .' ALLE FILER' , true );
$zip->debugMode();
$zip->add( '/home/ukmno/public_html/wp-content/plugins/UKMplayback/twig/upload.twig.html', 'TwigTest.html' );

// LOOP ALLE INNSLAG
foreach( $mediafiler['alle_innslag'] as $data ) {
	// LOOP ALLE PLAYBACKFILER TILKNYTTET INNSLAGET OG LEGG TIL ZIP
	foreach( $data->playback as $i => $playback ) {
		$path = $playback->local_file;
		$name = $data->name .' FIL '. $i . $playback->extension;
		echo $path .' => '. $name .'<br />';
		$zip->add( $path, $name );
	}
}
$INFOS['alle_filer'] = $zip->compress();

	
*/