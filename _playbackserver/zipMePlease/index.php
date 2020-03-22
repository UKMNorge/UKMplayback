<?php

use UKMNorge\File\Zip;

require_once('UKM/Autoloader.php');
require_once('UKMconfig.inc.php');

header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Headers: true');
header('Access-Control-Allow-Origin: https://' . UKM_HOSTNAME);
header('Access-Control-Request-Method: OPTIONS, HEAD, GET, POST, PUT, PATCH, DELETE');
header('Access-Control-Allow-Credentials: true');

if ( $_SERVER['REQUEST_METHOD'] != 'POST' ){
	error_log('RECIEVED NOT POST DATA. DIE');
	die( json_encode(
        [
            'success' => false,
            'message' => 'Fant ingen filer'
        ]
    ));
}

$data = $_POST['data'];

// Create zip (and overwrite existing file)
$zip = new Zip( $data['name'], true );
$zip->tryCatchAdd();

$errors = [];

foreach( $data['files'] as $fil) {
    #echo $fil['path'] ." => " . $fil['navn'] . "\r\n";
	try {
        $res = $zip->add( dirname( dirname( __FILE__ ) ) . '/'.  $fil['path'], $fil['navn'] );
        if( is_string($res)) {
            throw new Exception($res);
        }
    } catch( Exception $e ) {
        $errors[] = [
            'type' => 'fil',
            'innslag' => $fil['innslag'],
            'navn' => $fil['playback_navn'],
            'link' => $fil['playback_link'],
            'name' => $fil['navn'],
            'error' => $e->getMessage()
        ];
    }
}

try {
    $url = $zip->compress();
} catch( Exception $e ) {
    $errors[] = [
        'type' => 'zip',
        'name' => 'Lagring av zip-fil',
        'error' => $e->getMessage()
    ];
}

$url = str_replace('download.ukm.no/zip', 'playback.ukm.no/zipMePlease/data', $url);
$result = [
    'success' => true,
    'url' => $url,
    'data' => is_string($data),
    'hasErrors' => sizeof($errors) > 0,
    'errors' => $errors,
    'POST' => $_POST
];
die( json_encode( $result ) );
