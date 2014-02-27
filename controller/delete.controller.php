<?php
if( isset($_GET['delete_id']) && isset( $_GET['delete_b_id'] ) ) {
	$sql = new SQLdel('ukm_playback', array('pb_id' => $_GET['delete_id'], 'pl_id' => get_option('pl_id'), 'b_id' => $_GET['delete_b_id']) );
	
	$res = $sql->run();
	
	$innslag = new innslag( $_POST['b_id'] );
	
	if( $res ) {
		$INFOS['message'] = array('level' => 'success',
								  'header' => 'Lydfil slettet!',
								  'body' => 'Lydfilen er nå slettet');
	} else {
		$INFOS['message'] = array('level' => 'danger',
								  'header' => 'Lydfil ble ikke slettet!',
								  'body' => 'En ukjent feil gjorde at den valgte lydfilen for "'. $innslag->g('b_name').'" ikke ble slettet');
	}
	
}