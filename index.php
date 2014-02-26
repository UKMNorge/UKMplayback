<?php
// TO BE PLACED @ PLAYBACK.UKM.NO

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

$fileurl = 'http://playback.ukm.no/upload/data/'. $file['pb_season'] .'/'. $file['pl_id'] . '/'. $file['pb_file'];

echo 'Redirect til fil: '. $fileurl;
die();