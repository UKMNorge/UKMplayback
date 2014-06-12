<?php
if ( $_SERVER['REQUEST_METHOD'] != 'POST' ){
	error_log('RECIEVED NOT POST DATA. DIE');
}
$data = json_decode( file_get_contents('php://input') );

require_once('UKM/monstring.class.php');
require_once('UKM/forestilling.class.php');
require_once('UKM/innslag.class.php');
require_once('UKM/zip.class.php');

define('ZIP_WRITE_PATH', '/home/ukmplayb/public_html/zipMePlease/data/');


// Create zip (and overwrite existing file)
$zip = new zip( $data->filename, true );
$zip->debugMode();


foreach( $data->bands as $key => $pbdata ) {
	$innslag = new innslag( $pbdata->innslag );

	$playback = $innslag->playback();
	foreach( $playback as $i => $pb ) {
		$path = $pb->local_file();
		$name = $pbdata->navn .' FIL '. ($i+1) . $pb->extension();
		$zip->add( $path, $name );
		#echo 'ADD '. $path .' AS '. $name .'<br />';
	}
}
$url = $zip->compress();
echo str_replace('download.ukm.no/zip', 'playback.ukm.no/zipMePlease/data', $url);
die();