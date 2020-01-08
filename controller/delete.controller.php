<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Database\SQL\Delete;

if( isset($_GET['delete_id']) && isset( $_GET['delete_b_id'] ) ) {
	$sql = new Delete('ukm_playback', array('pb_id' => $_GET['delete_id'], 'pl_id' => get_option('pl_id'), 'b_id' => $_GET['delete_b_id']) );
	
	$res = $sql->run();
    
    $arrangement = new Arrangement( intval( get_option( 'pl_id' ) ) );

	$innslag = $arrangement->getInnslag()->get( $_GET['delete_b_id'] );
    
	if( $res ) {
        UKMplayback::getFlashbag()->success('Lydfilen er nå slettet');
	} else {
        UKMplayback::getFlashbag()->error('En ukjent feil gjorde at den valgte lydfilen for "'. $innslag->getNavn().'" ikke ble slettet');
	}
	
}