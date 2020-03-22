<?php

use UKMNorge\Arrangement\Arrangement;

require_once('UKM/Autoloader.php');

$arrangement = new Arrangement( intval(get_option('pl_id')));

$zipPackages = [];

foreach( $arrangement->getProgram()->getAbsoluteAll() as $hendelse ) {
    $zipPackages[ $hendelse->getId() ] = UKMplayback::zipPackage(
        $hendelse->getNavn(),
        $hendelse->getInnslag()
    );
}
$zipPackages['arrangement'] = UKMplayback::zipPackage(
    'Alle innslag',
    $arrangement->getInnslag()
);

UKMplayback::addViewData('zipPackages', $zipPackages);