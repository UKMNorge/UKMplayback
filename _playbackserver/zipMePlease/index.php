<?php
if ( $_SERVER['REQUEST_METHOD'] != 'POST' ){
	error_log('RECIEVED NOT POST DATA. DIE');
}
$data = json_decode( file_get_contents('php://input') );

require_once('UKM/zip.class.php');

// Create zip (and overwrite existing file)
$zip = new zip( $data->filename, true );
$zip->debugMode();

foreach( $data->files as $path => $name ) {
	$zip->add( dirname( dirname( __FILE__ ) ) . '/'.  $path, $name );
}

$url = $zip->compress();

$url = str_replace('download.ukm.no/zip', 'playback.ukm.no/zipMePlease/data', $url);
$result = array('success' => true, 'url' => $url);
die( json_encode( $result ) );
