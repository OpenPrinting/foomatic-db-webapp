<?php
include('inc/common.php');

$SESSION->pageLock('driver_upload');

$PAGE->setPageTitle('Driver Upload');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Database');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');
$PAGE->addBreadCrumb('Upload New Driver');

$um = new UploadManager('/srv/www/lptest/freshies');
if(isset($_GET['upload']) && $um->hasFiles()) {
	$file = $um->pop();
	
	while($um->hasFiles()) {
		// Someone tried to upload more than one file. Cheater.
		$file2 = $um->pop();
		$um->delete();
	}
	
	if(!preg_match(',^([A-Za-z0-9-]*)-([A-Za-z0-9.]*).tar.gz$,',$file->getOrigName(),$matches)) {
		echo 'File not acceptable.<br />';
		echo $file->getOrigName();
		$file->delete();
	} else {
		echo 'Name okay.<br /><br />';
		print_r($matches);
	}
	
}

?>		
