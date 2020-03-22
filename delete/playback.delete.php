<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Innslag\Playback\Write;

if( isset($_GET['delete_id']) && isset( $_GET['delete_b_id'] ) ) {    
    $arrangement = new Arrangement( get_option('pl_id') );
    $innslag = $arrangement->getInnslag()->get( $_GET['delete_b_id'] );
	$playback = $innslag->getPlayback()->get( $_GET['delete_id']);

    if( $playback ) {
        try {
            Write::slett( $arrangement, $playback);
            UKMplayback::getFlashbag()->success('Mediefilen er nå slettet fra '. $innslag->getNavn());
        } catch( Exception $e ) {
            UKMplayback::getFlashbag()->error('En ukjent feil gjorde at den valgte lydfilen for "'. $innslag->getNavn().'" ikke ble slettet');
        }	
    }
}