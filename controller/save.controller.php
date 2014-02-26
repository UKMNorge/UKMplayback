<?php
if( isset( $_POST['submit_playback'] ) ) {

	require_once('UKM/innslag.class.php');

	$sql = new SQLins('ukm_playback');
	$sql->add('pl_id', $_POST['pl_id']);
	$sql->add('b_id', $_POST['b_id']);
	$sql->add('pb_name', $_POST['name']);
	$sql->add('pb_description', $_POST['description']);
	$sql->add('pb_file', $_POST['filename']);
	$sql->add('pb_season', $_POST['season']);
	$res = $sql->run();
	
	$innslag = new innslag( $_POST['b_id'] );
		
	if( !$res || $res == -1 ) {
		$INFOS['message'] = array('level' => 'danger',
								  'header' => 'Lydfil ble ikke lagt til!',
								  'body' => 'En ukjent feil gjorde at lydfilen for "'. $innslag->g('b_name').'" ikke ble lagret');
	} else {
		$INFOS['message'] = array('level' => 'success',
								  'header' => 'Lydfil lagt til!',
								  'body' => 'Lydfilen er nå lastet opp og knyttet til innslaget "'. $innslag->g('b_name').'"');
	}
}