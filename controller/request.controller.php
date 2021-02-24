<?php

use UKMNorge\Arrangement\Arrangement;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Post to server
    UKMplayback::addViewData('avsender', $_POST['avsender']);
    UKMplayback::addViewData('melding', $_POST['melding']);

    // hvis ingen mottaker
    if(!isset($_POST['innslag'])) {
        UKMplayback::getFlash()->error('Må ha mottaker');
    }
    else {
        UKMplayback::addViewData('mottakere', $_POST['innslag']);
        UKMplayback::setAction('sendRequest');
    }
}
else {
    // UKMplayback::require('delete/playback.delete.php');
    UKMplayback::addViewData('arrangement', new Arrangement( get_option( 'pl_id' ) ));
}
