<?php
include('inc/common.php');


$PAGE->setPageTitle('Driver List');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Database');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');

if($SESSION->checkPermission('driver_upload')) $SMARTY->assign("UPLOAD_ALLOWED",1);

$SMARTY->display('drivers/list.tpl');

?>
