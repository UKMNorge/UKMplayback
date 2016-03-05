<?php

$info = new stdClass;
$info->diskspace = diskfreespace(__DIR__);
$info->total_diskspace = disk_total_space(__DIR__);

// Writeable upload directory
$info->writeable_upload_folder = is_writeable( dirname(__DIR__). '/upload/upload_temp/');
$info->writeable_data_folder = is_writeable( dirname(__DIR__). '/upload/data/');

die(json_encode($info));