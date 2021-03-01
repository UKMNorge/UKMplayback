<?php
require_once('UKMconfig.inc.php');

################################################
## SET ALL HEADERS AND ACTUALLY PERFORM UPLOAD 

$origin = $_SERVER['HTTP_ORIGIN'];

$acceptedOrigins = [
    'https://delta.' . UKM_HOSTNAME, // delta.ukm.no eller delta.ukm.dev
    'https://' . UKM_HOSTNAME        
];

$allowOrigin = null;
if(in_array($origin, $acceptedOrigins)) {
    $allowOrigin = $origin;
}

header('Access-Control-Allow-Headers: true');
header('Access-Control-Allow-Origin: ' . $allowOrigin);
header('Access-Control-Request-Method: OPTIONS, HEAD, GET, POST, PUT, PATCH, DELETE');
header('Access-Control-Allow-Credentials: true');

die('true');
require('jUpload_handler.php');
$upload_handler = new UploadHandler();
die($upload_handler->get_body());
?>
