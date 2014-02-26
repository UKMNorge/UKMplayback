<?php
################################################
## SET ALL HEADERS AND ACTUALLY PERFORM UPLOAD 
header('Access-Control-Allow-Headers: true');
header('Access-Control-Allow-Origin: ukm.local');
header('Access-Control-Request-Method: OPTIONS, HEAD, GET, POST, PUT, PATCH, DELETE');
header('Access-Control-Allow-Credentials: true');
die();
/*
require('UploadHandler.php');
$upload_handler = new UploadHandler();
die($upload_handler->get_body());
*/
?>