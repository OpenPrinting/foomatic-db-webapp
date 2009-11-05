<?php
include('inc/common.php');

$SESSION->pageLock('driver_upload');
if(!$SESSION->checkPermission('driver_noqueue')) $SMARTY->assign('UNTRUSTED',1);

$PAGE->setPageTitle('Uploads and Statuses');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');
$PAGE->addBreadCrumb('Uploads and Statuses');

$SMARTY->display('drivers/mydrivers.tpl');

?>		
