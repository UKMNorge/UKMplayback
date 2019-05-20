<?php
if( isset( $_POST['submit_playback'] ) ) {

	require_once('UKM/innslag.class.php');

	$INFOS['message'] = ['level' => 'success'];
	$innslag = new innslag( $_POST['b_id'] );

	if( isset( $_POST['playback_id'] ) ) {
		$sql = new SQLins('ukm_playback', ['pb_id' => $_POST['playback_id']]);
		$INFOS['message']['header'] = 'Lydfil oppdatert!';
		$INFOS['message']['body'] = 'Oppdatering av navn og beskrivelse er lagret.';
	} else {
		$INFOS['message']['header'] = 'Lydfil lagt til!';
		$INFOS['message']['body'] = 'Lydfilen er nå lastet opp og knyttet til innslaget "'. $innslag->g('b_name').'"';

		$sql = new SQLins('ukm_playback');
		$sql->add('pl_id', $_POST['pl_id']);
		$sql->add('b_id', $_POST['b_id']);
		$sql->add('pb_file', $_POST['filename']);
		$sql->add('pb_season', $_POST['season']);
	}
	$sql->add('pb_name', $_POST['name']);
	$sql->add('pb_description', $_POST['description']);
	$res = $sql->run();
	
		
	if( !$res || $res == -1 ) {
		$INFOS['message'] = array('level' => 'danger',
								  'header' => 'Lydfil ble ikke lagt til!',
								  'body' => 'En ukjent feil gjorde at lydfilen for "'. $innslag->g('b_name').'" ikke ble lagret');
	}
}