<?php
include('inc/common.php');
$PAGE->setPageTitle('Driver Upload');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Database');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');
$PAGE->addBreadCrumb('Upload New Driver');

$SMARTY->display('drivers/upload_start.tpl');

?>		
