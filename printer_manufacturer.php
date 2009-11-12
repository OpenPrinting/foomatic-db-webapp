<?php
include('inc/common.php');
$PAGE->setPageTitle('Printers by Manufacturer');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb($_GET['manufacturer']);

if($_GET['manufacturer'] ==""){
	header('location: /printers');
	
}

$SMARTY->assign('manufacturer',$_GET['manufacturer']);

		//$SMARTY->assign('data',$data);
		$SMARTY->display('printers/detail_manufacturer.tpl');
?>