<?php
if ( $_SERVER['REQUEST_METHOD'] != 'POST' ){
	error_log('RECIEVED NOT POST DATA. DIE');
}
$data = json_decode( file_get_contents('php://input') );

require_once('UKM/zip.class.php');
define('ZIP_WRITE_PATH', __DIR__ .'/data/');
if( !file_exists( ZIP_WRITE_PATH ) ) {
	mkdir( ZIP_WRITE_PATH, 0777, true );
}

// Create zip (and overwrite existing file)
$zip = new zip( $data->filename, true );
$zip->debugMode();

foreach( $data->files as $path => $name ) {
	$zip->add( dirname( dirname( __FILE__ ) ) . '/'.  $path, $name );
}

$url = $zip->compress();
echo str_replace('download.ukm.no/zip', 'playback.ukm.no/zipMePlease/data', $url);
die();
