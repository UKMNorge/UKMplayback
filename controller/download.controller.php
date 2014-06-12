<?php
require_once('UKM/monstring.class.php');
require_once('UKM/forestilling.class.php');
require_once('UKM/innslag.class.php');
require_once('UKM/curl.class.php');

$m = new monstring( get_option('pl_id') );

$alle_innslag = $m->innslag();
$hendelser = $m->concerts();

$mediafiler = array('alle_innslag' => array(), 'forestilling' => array());

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
$jsondata = new stdClass();
$jsondata->filename = 'UKM Playback '. $m->g('pl_name');
$jsondata->bands = $mediafiler;
$INFOS['alle_filer'] = $curl->json( $jsondata )->process('http://playback.ukm.no/zipMePlease/');

foreach( $hendelser as $con ) {
	$c = new forestilling( $con['c_id'] );
	$rekkefolge = $c->concertBands();
	
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
		$jsondata = new stdClass();
		$jsondata->filename = 'UKM Playback '. $m->g('pl_name') .' '. $c->g('c_name');
		$jsondata->bands = $mediafiler;
		
		$viewdata = new stdClass();
		$viewdata->name = $c->g('c_name');
		$viewdata->url = $curl->json( $jsondata )->process('http://playback.ukm.no/zipMePlease/');
		$INFOS['forestillinger'][] = $viewdata;
	}
	
}