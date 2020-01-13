<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Database\SQL\Insert;
use UKMNorge\Database\SQL\Update;

if( isset( $_POST['submit_playback'] ) ) {
	$arrangement = new Arrangement( get_option('pl_id') );

	$innslag = $arrangement->getInnslag()->get( $_POST['b_id'] ); // new innslag(  );

	if( isset( $_POST['playback_id'] ) ) {
		$sql = new Update(
            'ukm_playback',
            ['pb_id' => $_POST['playback_id']]
        );
		UKMplayback::getFlash()->success("Oppdatering av navn og beskrivelse er lagret.");
	} else {
		UKMplayback::getFlash()->success('Filen er nå lastet opp og knyttet til innslaget "' . $innslag->getNavn() . '"');

		$sql = new Insert('ukm_playback');
		$sql->add('pl_id', $_POST['pl_id']);
		$sql->add('b_id', $_POST['b_id']);
		$sql->add('pb_file', $_POST['filename']);
		$sql->add('pb_season', $_POST['season']);
	}
	$sql->add('pb_name', $_POST['name']);
	$sql->add('pb_description', $_POST['description']);
	$res = $sql->run();
	
	if( !$res || $res == -1 ) {
		UKMplayback::getFlash()->error('En ukjent feil gjorde at filen for "' . $innslag->getNavn() . '" ikke ble lagret');
	}
}