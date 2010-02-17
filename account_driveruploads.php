<?php
include('inc/common.php');

$SESSION->pageLock('driver_upload');
if(!$SESSION->checkPermission('driver_noqueue')) $SMARTY->assign('UNTRUSTED',1);

$PAGE->setPageTitle('Uploads and Statuses');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');
$PAGE->addBreadCrumb('Uploads and Statuses');

$sql = $DB->query("SELECT da.*, d.id, d.name 
					FROM driver_approval AS da
					LEFT JOIN driver AS d ON da.id = d.id
					WHERE da.contributor = '".$USER->getUserName()."' ");
$dataDrivers = $sql->toArray('id');

$SMARTY->assign('dataDrivers',$dataDrivers);

$SMARTY->display('account/driveruploads.tpl');

?>		
