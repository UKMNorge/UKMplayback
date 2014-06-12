<?php
echo 'lets put it here: '. ZIP_WRITE_PATH;
require_once('UKM/monstring.class.php');
require_once('UKM/forestilling.class.php');
require_once('UKM/innslag.class.php');
require_once('UKM/zip.class.php');

$m = new monstring( get_option('pl_id') );

$alle_innslag = $m->innslag();

$mediafiler = array();

foreach( $alle_innslag as $inn ) {
	$i = new innslag( $inn['b_id'] );
	
	if( $i->har_playback() ) {
		$data = new stdClass();
		$data->name = $i->g('b_name');
		$data->playback = $i->playback();
		$mediafiler['alle_innslag'][] = $data;
	}
}

$zipname = 'UKM Playback '. $m->g('pl_name');
$zip = new zip( $zipname .' ALLE FILER' , true );
// LOOP ALLE INNSLAG
foreach( $mediafiler['alle_innslag'] as $data ) {
	// LOOP ALLE PLAYBACKFILER TILKNYTTET INNSLAGET OG LEGG TIL ZIP
	foreach( $data->playback as $i => $playback ) {
		$path = $playback->local_file;
		$name = $data->name .' FIL '. $i . $playback->extension;
		echo $path .' => '. $name .'<br />';
		$zip->add( $path, $name );
	}
	$INFOS['alle_filer'] = $zip->compress();
}