<?php

if( !isset( $_GET['file'] ) || !isset( $_GET['pl_id']) || empty( $_GET['file'] ) || empty( $_GET['pl_id'] ) ) {
	die('Mangler identifikator'); 
}

require_once('UKM/sql.class.php');

$sql = new SQL("SELECT * 
				FROM `ukm_playback`
				WHERE `pb_id` = '#id'
				AND `pl_id` = '#plid'",
			   array('id' => $_GET['file'], 'plid' => $_GET['pl_id'] )
			  );
$res = $sql->run();
$file = mysql_fetch_assoc( $res );

$file = dirname( __FILE__ ) .'/upload/data/'. $file['pb_season'] .'/'. $file['pl_id'] . '/'. $file['pb_file'];

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