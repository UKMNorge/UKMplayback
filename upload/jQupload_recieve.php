<?php
ini_set("log_errors", 1);
ini_set("error_log", dirname(__FILE__).'/error_log');
ini_set('display_errors', 0);

require_once('jQupload_handler.inc.php');
require_once('UKM/sql.class.php');

define('DIR_UPLOAD', 'upload_temp/');
define('DIR_DATA', 'data/');

error_log('UPLOADED: '. DIR_UPLOAD);

	################################################
	## SET ALL HEADERS AND ACTUALLY PERFORM UPLOAD 
	header('Access-Control-Allow-Headers: true');
	header('Access-Control-Allow-Origin: ukm.no');
	header('Access-Control-Request-Method: OPTIONS, HEAD, GET, POST, PUT, PATCH, DELETE');
	header('Access-Control-Allow-Credentials: true');

	$upload_handler = new UploadHandler(array('upload_dir' => DIR_UPLOAD));

	################################################
	## GET THE DATA ARRAY FOR FURTHER MANIPULATING
	$data = json_decode($upload_handler->get_body());
	$data_object = $data->files[0];
	
	if(empty($data_object->size)) {
		$error = new stdClass;
		$error->success = false;
		$error->message = 'Det er en feil med filstørrelsen. Dette kan være en feil i nettleseren din, eller fordi filen er skadet og inneholder feil informasjon om egen filstørrelse.';
		$error->data = $data;
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
		die(json_encode($error));
	}
	
	################################################
	## CHECK SUBMITTED FILE IS VIDEO-FILE (MIME-TYPE)
	$filetype_matches = null;
	$returnValue = preg_match('^audio\\/(.)+^', $data_object->type, $filetype_matches);
	
	if(sizeof($filetype_matches) == 0) {
		$error = new stdClass;
		$error->success = false;
		$error->message = 'Playbackopplasteren tar kun i mot audiofiler!';
		die(json_encode($error));
	}

	################################################
	## SET POST VARS AS VARS	
	$SEASON		= $_POST['season'];
	$PL_ID		= $_POST['pl_id'];

	################################################
	## CALCULATE FILE EXTENSION OF UPLOADED FILE
	$file_ext = strtolower(substr($data_object->name, strrpos($data_object->name, '.')));

	################################################
	## CALCULATE NEW FILENAME OF FILE
	
	$file_path = DIR_DATA . $SEASON .'/'. $PL_ID.'/' ;
	
	if( !is_dir( $file_path ) ) {
		mkdir( $file_path );
	}
	
	$target_dir_files = scandir( $file_path, SCANDIR_SORT_DESCENDING );
	if( sizeof( $target_dir_files ) == 0 ) {
		$num = 1;
	} else {
		$last_file = end( $target_dir_files );
		$last_file_num = explode('_num', $last_file);
		$last_file_num = explode('.', $last_file_num[1]);
		$last_file_num = (int) $last_file_num[0];
		$num = ++$last_file_num;
	}
	
	$file_name = 'UKMpb_'.$SEASON .'_pl'. $PL_ID . '_num'. $num .'.'.$file_ext;

			
	###################################################
	## CALCULATE THE REAL WIDTH AND HEIGHT BASED ON UPLOADED FILE
	$file_uploaded = DIR_TEMP_UPLOAD.$data_object->name;
	
	
	###################################################
	## MOVE FILE TO CONVERT-DIRECTORY
	
	rename($file_uploaded, DIR_DATA.$file_path.$file_name);
				
	###################################################
	## CREATE RETURN-OBJECT FOR JQUERY UPLOADER
	$data_object->file_id = $file_name;
	$data->files[0] = $data_object;
	$data->success = true;
	die(json_encode($data));
?>