<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Innslag\Playback\Write;

if( isset( $_POST['submit_playback'] ) ) {
	$arrangement = new Arrangement( get_option('pl_id') );
	$innslag = $arrangement->getInnslag()->get( $_POST['b_id'] );

	if( isset( $_POST['playback_id'] ) ) {
        try {
            $playback = $innslag->getPlayback()->get(intval($_POST['playback_id']));
            $playback->setNavn($_POST['name']);
            $playback->setBeskrivelse($_POST['description']);
            Write::lagre($playback);
            UKMplayback::getFlash()->success("Oppdatering av navn og beskrivelse er lagret.");
        } catch( Exception $e ) {
            UKMplayback::getFlash()->error('Kunne ikke lagre endringene.');
        }
	} else {
        try {
            Write::opprett( $arrangement, $innslag->getId(), $_POST['filename'], $_POST['name'], $_POST['description']);
            UKMplayback::getFlash()->success('Filen er nå lastet opp og knyttet til innslaget "' . $innslag->getNavn() . '"');
        } catch( Exception $e ) {
            UKMplayback::getFlash()->error('Kunne ikke laste opp filen. Systemet sa: '. $e->getMessage());
        }
		
	}
}