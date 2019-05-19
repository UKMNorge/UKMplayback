<?php
require_once('UKM/playback.class.php');
require_once('UKM/innslag.class.php');

$playback = new playback( $_GET['id'] );

$INFOS['innslag'] = new innslag_v2( $playback->b_id );

$INFOS['playback'] = $playback;