<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
if( !isset( $_GET['file'] ) || !isset( $_GET['pl_id']) || empty( $_GET['file'] ) || empty( $_GET['pl_id'] ) ) {
	die('Mangler identifikator'); 
}

require_once('UKMconfig.inc.php');
require_once('UKM/curl.class.php');
$curl = new UKMCURL();
$curl->timeout(10);

$filedata = $curl->request('http://api.' . UKM_HOSTNAME . '/playback:file/'. $_GET['file'] .'/'. $_GET['pl_id'] .'/');

#var_dump( $curl );
#var_dump( $filedata );
$file = dirname( __FILE__ ) .'/upload/data/'. $filedata->pb_season .'/'. $filedata->pl_id . '/'. $filedata->pb_file;

var_dump( $file );
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}

die('Kunne ikke finne fil');
