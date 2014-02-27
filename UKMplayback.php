<?php  
/* 
Plugin Name: UKM Playback
Plugin URI: http://www.ukm-norge.no
Description: Opplasting av playback/singback til innslag for bruk i sceneproduksjon
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/

if(is_admin()) {
	require_once('UKM/inc/handlebars.inc.php');
	add_action('UKM_admin_menu', 'UKMplayback_menu');
	
	add_action('wp_ajax_UKMplayback_load', 'UKMplayback_ajax_load');
	add_action('wp_ajax_UKMplayback_action', 'UKMplayback_ajax_action');

}

function UKMplayback_menu() {
	$pltype = get_option('site_type');
	if( $pltype == 'land' || $pltype == 'kommune' || $pltype == 'fylke' ) {
		UKM_add_menu_page('monstring','Playback', 'Playback', 'administrator', 'UKMplayback','UKMplayback', 'http://ico.ukm.no/music-menu.png', 16);
		UKM_add_scripts_and_styles('UKMplayback', 'UKMplayback_scripts_and_styles' );
	}
}

function UKMplayback_ajax_action() {
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	if(!isset( $_POST['subaction'] ) ) 
		die(0);
	
	require_once('ajax/action_'. $_POST['subaction'] .'.ajax.php');
	
	die( json_encode( $AJAX_DATA ) );
	
}

function UKMplayback_ajax_load() {
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	if(!isset( $_POST['load'] ) ) 
		die(0);
	
	require_once('ajax/load_'. $_POST['load'] .'.ajax.php');
	
	die( json_encode( $AJAX_DATA ) );
}


function UKMplayback() {
	$INFOS = array();
	
	if( !isset($_GET['action']) ) {
		$_GET['action'] = 'list';
	}
	require_once('UKM/monstring.class.php');

	$pl = new monstring( get_option('pl_id') );
	$INFOS['innslag'] = $pl->innslag();	
	$INFOS['monstring'] = new stdClass();
	$INFOS['monstring']->season = $pl->g('season');
	$INFOS['monstring']->pl_id = $pl->g('pl_id');

	require_once('controller/save.controller.php');
	require_once('controller/delete.controller.php');

	
	if( $_GET['action'] == 'upload' ) {
		require_once('controller/upload.controller.php');
	}
	if( $_GET['action'] == 'list' ) {
		require_once('controller/list.controller.php');
	}
	
	
	$INFOS['action'] = $_GET['action'];
	$INFOS['tab_active'] = $_GET['action'];
	
	echo TWIG($_GET['action'].'.twig.html', $INFOS, dirname(__FILE__));	
	echo HANDLEBARS( dirname(__FILE__) );
}

function UKMplayback_scripts_and_styles(){
	wp_enqueue_script('handlebars_js');
	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');

	wp_enqueue_script('UKMplaybackJS', plugin_dir_url( __FILE__ ) .'UKMplayback.js');


	wp_enqueue_style('UKMresources_tabs');

	wp_enqueue_script('jquery');
	wp_enqueue_script('jqueryGoogleUI', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');

	wp_enqueue_style( 'blueimp-gallery-css', plugin_dir_url( __FILE__ ) . 'jqueryuploader/css/blueimp-gallery.min.css');

	// CSS to style the file input field as button and adjust the Bootstrap progress bars
	wp_enqueue_style( 'jquery-fileupload-css', plugin_dir_url( __FILE__ ) . 'jqueryuploader/css/jquery.fileupload.css');
	wp_enqueue_style( 'jquery-fileupload-ui-css', plugin_dir_url( __FILE__ ) . 'jqueryuploader/css/jquery.fileupload-ui.css');
	
	// The jQuery UI widget factory, can be omitted if jQuery UI is already included
	wp_enqueue_script('jquery_ui_widget', plugin_dir_url(__FILE__) . 'jqueryuploader/js/vendor/jquery.ui.widget.js');
	// The Load Image plugin is included for the preview images and image resizing functionality
	wp_enqueue_script('load-image', plugin_dir_url(__FILE__) . 'jqueryuploader/js/vendor/load-image.min.js');
	// The Canvas to Blob plugin is included for image resizing functionality
	wp_enqueue_script('canvas-to-blob', plugin_dir_url(__FILE__) . 'jqueryuploader/js/vendor/canvas-to-blob.min.js');
	// The Iframe Transport is required for browsers without support for XHR file uploads
	wp_enqueue_script('iframe-transport', plugin_dir_url(__FILE__) . 'jqueryuploader/js/jquery.iframe-transport.js');	
	// The basic File Upload plugin
	wp_enqueue_script('fileupload', plugin_dir_url(__FILE__) . 'jqueryuploader/js/jquery.fileupload.js');	
	// The File Upload user interface plugin
	wp_enqueue_script('fileupload-ui', plugin_dir_url(__FILE__) . 'jqueryuploader/js/jquery.fileupload-ui.js');
	// The File Upload processing plugin
	wp_enqueue_script('fileupload-process', plugin_dir_url(__FILE__) . 'jqueryuploader/js/jquery.fileupload-process.js');	
	// The File Upload image preview & resize plugin 
	wp_enqueue_script('fileupload-image', plugin_dir_url(__FILE__) . 'jqueryuploader/js/jquery.fileupload-image.js');	
	// The File Upload validation plugin
	wp_enqueue_script('fileupload-validate', plugin_dir_url(__FILE__) . 'jqueryuploader/js/jquery.fileupload-validate.js');	
}