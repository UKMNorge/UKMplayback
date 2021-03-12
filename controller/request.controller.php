<?php

require_once('UKMconfig.inc.php');
use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Innslag\Innslag;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Post to server
    UKMplayback::addViewData('avsender', $_POST['avsender']);
    UKMplayback::addViewData('melding', $_POST['melding']);

    // hvis ingen mottaker
    if(!isset($_POST['innslag'])) {
        UKMplayback::getFlash()->error('Må ha mottaker');
    }
    else {
        $innslagArr = [];

        foreach($_POST['innslag'] as $key => $value) {
            $innslag = Innslag::getById($key);
            $innslagArr[$key] = [$innslag->getNavn(), $value];
        }


        

        UKMplayback::addViewData('ukmHostName', UKM_HOSTNAME);
        UKMplayback::addViewData('mottakere', $innslagArr);
        UKMplayback::setAction('sendRequest');
    }
}
else {
    // UKMplayback::require('delete/playback.delete.php');
    UKMplayback::addViewData('arrangement', new Arrangement( get_option( 'pl_id' ) ));
}
