<?php

use UKMNorge\Arrangement\Arrangement;

UKMplayback::include('controller/delete.controller.php');
UKMplayback::addViewData('arrangement', new Arrangement( get_option( 'pl_id' ) ));