<?php
ini_set("log_errors", 1);
#ini_set('display_errors', 1);
#ini_set("error_log", dirname(__FILE__).'/error_log');
#error_reporting(E_ALL);
#ini_set('display_errors', 1);

require_once('jQupload_handler.php');
require_once('UKMconfig.inc.php');

define('ACCESS_HEADER', 'https://' . UKM_HOSTNAME);

// SET HEADERS MANUALLY, BECAUSE IF UPLOAD FAILS, IT SKIPS THE HEADERS AND CRASHES IT ALL...
header('Access-Control-Allow-Origin: '.ACCESS_HEADER);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: OPTIONS,HEAD,GET,POST,PUT,PATCH,DELETE');
header('Access-Control-Allow-Headers: Content-Type,Content-Range,Content-Disposition');

define('DIR_UPLOAD', dirname(__FILE__). '/upload_temp/');
define('DIR_DATA', dirname(__FILE__). '/data/');

error_log('UPLOAD START');

#error_log( var_export( $_SERVER['REQUEST_METHOD'], true ) );
#error_log( var_export( $_POST, true ) );
#error_log( var_export( $_FILES, true ) );
#error_log( 'UPLOAD_HANDLE' );
	$upload_handler = new UploadHandler(array('upload_dir' => DIR_UPLOAD,
											  'access_control_allow_origin'=>ACCESS_HEADER,
											  'access_control_allow_credentials'=>true
											 )
										);
	$upload_handler->head();
	error_log('UPLOAD_HANDLED');
	
	################################################
	## GET THE DATA ARRAY FOR FURTHER MANIPULATING
	$data = json_decode($upload_handler->get_body());
	$data_object = $data->files[0];
error_log( var_export( $data_object, true ) );
error_log( var_export( $data, true ) );
error_log( var_export( $_POST, true ) );
error_log( var_export( $_FILES, true ) );

	
	if(empty($data_object->size)) {
		$error = new stdClass;
		$error->success = false;
		$error->message = utf8_encode('Det er en feil med filstørrelsen. Dette kan være en feil i nettleseren din, eller fordi filen er skadet og inneholder feil informasjon om egen filstørrelse.');
		$error->data = $data;
error_log('UPLOAD END DUE TO FILESIZE BUG');
		die(json_encode($error));
	}
	
	################################################
	## CHECK FOR ALL REQUIRED POST-VALUES
	if( !isset($_POST['season']) ||  !isset($_POST['pl_id']) ) {
		$error = new stdClass;
		$error->success = false;
		$error->message = 'Opplasteren sendte ikke med alle POST-verdier (kontakt UKM Norge support, dette er en systemfeil)';
		$error->request = $_REQUEST;
		$error->post = $_POST;
		$error->get = $_GET;
error_log('UPLOAD END DUE TO MISSING POST VALUES');
		die(json_encode($error));
	}
	
	################################################
	## CHECK SUBMITTED FILE IS VIDEO-FILE (MIME-TYPE)
/*	$filetype_matches = null;
	$returnValue = preg_match('^audio\\/(.)+^', $data_object->type, $filetype_matches);
	
	if(sizeof($filetype_matches) == 0) {
		$error = new stdClass;
		$error->success = false;
		$error->message = 'Playbackopplasteren tar kun i mot audiofiler! (.flac er ikke støttet)';
		#error_log('UPLOAD END DUE TO WRONG FILE TYPE');
		die(json_encode($error));
	}*/

	################################################
	## SET POST VARS AS VARS	
	$SEASON		= $_POST['season'];
	$PL_ID		= $_POST['pl_id'];

	################################################
	## CALCULATE FILE EXTENSION OF UPLOADED FILE
	$file_ext = strtolower(substr($data_object->name, strrpos($data_object->name, '.')));
error_log('File extension:' . $file_ext);

	################################################
	## CALCULATE NEW FILENAME OF FILE
	## Rewrite 20. april 2016 fordi scandir ikke skjønner at 10 er større enn 9.
	
	$file_path = DIR_DATA . $SEASON .'/'. $PL_ID.'/' ;
	
	if( !is_dir( $file_path ) ) {
		mkdir( $file_path, 0777, true );
	}
	$filenum_separator = '_fil_';
	
	$target_dir_files = scandir( $file_path, SCANDIR_SORT_DESCENDING );
	if( sizeof( $target_dir_files ) == 0 ) {
		$file_num = 1;
	} else {
		$highest_file_num = 1;
		foreach ($target_dir_files as $file) {
			$num = explode($filenum_separator, $file);
			$num = explode('.' , $num[1]);
			$num = $num[0];
			if ( $num > $highest_file_num ) {
				$highest_file_num = $num;
			}
		}
	}
	$file_num = (int)$highest_file_num+1;
	$file_name = 'UKMplayback_' . $SEASON . '_pl' . $PL_ID . $filenum_separator . $file_num . $file_ext;
	
	error_log('File name:' . $file_name);

	###################################################
	## CALCULATE THE REAL WIDTH AND HEIGHT BASED ON UPLOADED FILE
	$file_uploaded = DIR_UPLOAD.$data_object->name;
error_log('Savefile name:' . $file_name);
	
	###################################################
	## MOVE FILE TO CONVERT-DIRECTORY
	
	rename($file_uploaded, $file_path.$file_name);
				
	###################################################
	## CREATE RETURN-OBJECT FOR JQUERY UPLOADER
	$data_object->file_id = $file_name;
	$data->files[0] = $data_object;
	$data->success = true;
error_log('UPLOAD COMPLETED SUCCESSFULLY');
	die(json_encode($data));
?>
