<?php
include('inc/common.php');


$PAGE->setPageTitle('Driver List');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Database');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');

if($SESSION->checkPermission('driver_upload')) $SMARTY->assign("UPLOAD_ALLOWED",1);

$res = $DB->query("
	SELECT id, name, execution, shortdescription, pj.count as printerCount
	FROM driver 
	LEFT JOIN 
		(SELECT driver_id, count(printer_id) as count 
		 FROM driver_printer_assoc
		 GROUP BY driver_id)
		 AS pj
		 ON pj.driver_id = driver.id
	ORDER BY name 
	");
$r = $res->toArray('id');

$SMARTY->assign("drivers",$r);

$SMARTY->display('drivers/list.tpl');

?>
