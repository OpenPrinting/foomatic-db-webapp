<?php
include('inc/common.php');
$PAGE->setPageTitle('Driver List');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Database');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');

$SMARTY->display('drivers/list.tpl');

?>
