<?php

use UKMNorge\Kommunikasjon\Mottaker;
use UKMNorge\Kommunikasjon\SMS;

$avsender = $_POST['avsender'];
$mobilnummer = $_POST['tel_nr'];
$melding = $_POST['melding']; // POST melding variabel

SMS::setSystemId('wordpress', get_current_user());
SMS::setArrangementId(UKMplayback::getArrangementId());

$sms = new SMS($avsender); // (avsender) 

try {
    $result = $sms->setMelding( $melding )->setMottaker( Mottaker::fraMobil( $mobilnummer ) )->send();
} catch(Exception $e) {
    // bare for ukm.dev
    if($e->getCode() == 148005) {
        $result = true;
        UKMplayback::addResponseData('message', $e->getMessage()); // Response til klienten
    }
    else {
        $result = false;
        UKMplayback::addResponseData('message', $e->getMessage()); // Response til klienten
    }
}
UKMplayback::addResponseData('success', $result); // Response til klienten