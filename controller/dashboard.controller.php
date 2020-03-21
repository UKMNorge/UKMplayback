<?php

use UKMNorge\Arrangement\Arrangement;

UKMplayback::include('delete/playback.delete.php');
UKMplayback::addViewData('arrangement', new Arrangement( get_option( 'pl_id' ) ));