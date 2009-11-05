<?php
include('inc/common.php');
//$SESSION->pageLock('printer_upload');
//if(!$SESSION->checkPermission('printer_noqueue')) $SMARTY->assign('UNTRUSTED',1);

$PAGE->setPageTitle('Printer Upload');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb('Printer Upload');

$SMARTY->display('printers/upload.tpl');
	
?>
