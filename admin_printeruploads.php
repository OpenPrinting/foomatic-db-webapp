<?php
include('inc/common.php');

$SESSION->pageLock('driver_upload');
if(!$SESSION->checkPermission('driver_noqueue')) $SMARTY->assign('UNTRUSTED',1);

$PAGE->setPageTitle('Uploads and Statuses');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');
$PAGE->addBreadCrumb('Uploads and Statuses');

$sql = $DB->query("SELECT pa.*, p.id, p.make, p.model 
					FROM printer_approval AS pa
					LEFT JOIN printer AS p ON pa.id = p.id ");
$dataPrinters = $sql->toArray('id');

$SMARTY->assign('dataPrinters',$dataPrinters);

$SMARTY->display('admin/printeruploads.tpl');

?>		
