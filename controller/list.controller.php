<?php
require_once('UKM/innslag.class.php');

$pl = new monstring( get_option('pl_id') );

$alle_innslag = $pl->innslag();

foreach( $alle_innslag as $innslag ) {
	$inn = new innslag( $innslag['b_id'] );
	
	if( $inn->har_playback() ) {
		$data = new stdClass();
		$data->ID = $inn->g('b_id');
		$data->name = $inn->g('b_name');
		$data->playback = $inn->playback();
		
		$INFOS['pb_innslag'][] = $data;
	}
}