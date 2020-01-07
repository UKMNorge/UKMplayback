<?php
use UKMNorge\Arrangement\Arrangement;

UKMplayback::addViewData('arrangement', new Arrangement( get_option( 'pl_id' ) ));

?>