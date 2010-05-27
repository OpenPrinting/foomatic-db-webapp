<?php
include('inc/common.php');

$SESSION->pageLock('driver_upload');
if(!$SESSION->checkPermission('driver_noqueue')) $SMARTY->assign('UNTRUSTED',1);

$PAGE->setPageTitle('Uploads and Statuses');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');
$PAGE->addBreadCrumb('Uploads and Statuses');

//Get number of items per page to display from siteconf
$pagesize = $CONF->printer_queue_pagesize;

//get total numer of itmes in database
$sql = $DB->query("SELECT count(*) as num FROM printer_approval AS pa
					         LEFT JOIN printer AS p ON pa.id = p.id ");
$r = $sql->getRow();
$itemtotal = $r['num'];

//compute number of sections to build
$sectiontotal = ceil($itemtotal/$pagesize);

$sql = $DB->query("SELECT pa.*, p.id, p.make, p.model 
					FROM printer_approval AS pa
					LEFT JOIN printer AS p ON pa.id = p.id ");
$dataPrinters = $sql->toArray('id');

$SMARTY->assign('sectiontotal',$sectiontotal);
$SMARTY->assign('pagesize',$pagesize);
$SMARTY->assign('datatotal',$itemtotal);
$SMARTY->assign('dataPrinters',$dataPrinters);

$SMARTY->display('admin/printeruploads.tpl');

?>		
