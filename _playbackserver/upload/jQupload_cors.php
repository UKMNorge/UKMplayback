<?php
################################################
## SET ALL HEADERS AND ACTUALLY PERFORM UPLOAD 
header('Access-Control-Allow-Headers: true');
header('Access-Control-Allow-Origin: https://ukm.no');
header('Access-Control-Request-Method: OPTIONS, HEAD, GET, POST, PUT, PATCH, DELETE');
header('Access-Control-Allow-Credentials: true');

die('true');
require('jUpload_handler.php');
$upload_handler = new UploadHandler();
die($upload_handler->get_body());
?>
