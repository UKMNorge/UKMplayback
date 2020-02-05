<?php

/* 
Plugin Name: UKM Playback
Plugin URI: http://www.ukm-norge.no
Description: Opplasting av playback/singback til innslag for bruk i sceneproduksjon
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/

use UKMNorge\Wordpress\Modul;

require_once('UKM/Autoloader.php');

class UKMplayback extends Modul
{
    public static $action = 'list';
    public static $path_plugin = null;

    public static function hook()
    {
        add_action('admin_menu', ['UKMplayback','meny'], 500);
    }

    public static function meny() {
        if( get_option('pl_id') ) {
            $page = add_submenu_page(
                'UKMdeltakere',
                'Mediefiler',
                'Mediefiler',
                'ukm_playback',
                'UKMplayback',
                ['UKMplayback','renderAdmin']
            );
    
            add_action(
                'admin_print_styles-' . $page,
                ['UKMplayback','scripts_and_styles']
            );
        }
    }

    public static function save( $getParamSave ) {
        static::include('controller/save.controller.php');
    }

    public static function renderAdmin()
    {
        static::include('controller/status.controller.php');
        
        parent::renderAdmin();

    }

    public static function scripts_and_styles()
    {
        wp_enqueue_script('TwigJS');
        wp_enqueue_script('WPbootstrap3_js');
        wp_enqueue_style('WPbootstrap3_css');
    
        wp_enqueue_style('UKMresources_tabs');
        wp_enqueue_script('UKMplaybackJS', self::getPluginUrl() . 'UKMplayback.js');
    
        wp_enqueue_script('jquery');
        wp_enqueue_script('jqueryGoogleUI', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
    
        wp_enqueue_style('blueimp-gallery-css', self::getPluginUrl() . 'jqueryuploader/css/blueimp-gallery.min.css');
    
        // CSS to style the file input field as button and adjust the Bootstrap progress bars
        wp_enqueue_style('jquery-fileupload-css', self::getPluginUrl() . 'jqueryuploader/css/jquery.fileupload.css');
        wp_enqueue_style('jquery-fileupload-ui-css', self::getPluginUrl() . 'jqueryuploader/css/jquery.fileupload-ui.css');
    
        // The jQuery UI widget factory, can be omitted if jQuery UI is already included
        wp_enqueue_script('jquery_ui_widget', self::getPluginUrl() . 'jqueryuploader/js/vendor/jquery.ui.widget.js');
        // The Load Image plugin is included for the preview images and image resizing functionality
        wp_enqueue_script('load-image', self::getPluginUrl() . 'jqueryuploader/js/vendor/load-image.min.js');
        // The Canvas to Blob plugin is included for image resizing functionality
        wp_enqueue_script('canvas-to-blob', self::getPluginUrl() . 'jqueryuploader/js/vendor/canvas-to-blob.min.js');
        // The Iframe Transport is required for browsers without support for XHR file uploads
        wp_enqueue_script('iframe-transport', self::getPluginUrl() . 'jqueryuploader/js/jquery.iframe-transport.js');
        // The basic File Upload plugin
        wp_enqueue_script('fileupload', self::getPluginUrl() . 'jqueryuploader/js/jquery.fileupload.js');
        // The File Upload user interface plugin
        wp_enqueue_script('fileupload-ui', self::getPluginUrl() . 'jqueryuploader/js/jquery.fileupload-ui.js');
        // The File Upload processing plugin
        wp_enqueue_script('fileupload-process', self::getPluginUrl() . 'jqueryuploader/js/jquery.fileupload-process.js');
        // The File Upload image preview & resize plugin 
        wp_enqueue_script('fileupload-image', self::getPluginUrl() . 'jqueryuploader/js/jquery.fileupload-image.js');
        // The File Upload validation plugin
        wp_enqueue_script('fileupload-validate', self::getPluginUrl() . 'jqueryuploader/js/jquery.fileupload-validate.js');
    }
}

UKMplayback::init(__DIR__);
UKMplayback::hook();