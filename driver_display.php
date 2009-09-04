<?php
include('inc/common.php');

if(empty($_GET['driver'])) {
	header('Location: ./drivers');
	exit;
}

$PAGE->setPageTitle('Driver List');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Database');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');	


$res = $DB->query("
	SELECT p.id, p.make, p.model 
	FROM driver_printer_assoc dpa
	JOIN printer p 
		ON concat('printer/',p.id) = dpa.printer_id 
	WHERE dpa.driver_id = '?' 
	ORDER BY p.make, p.model ", $_GET['driver']);
$printers = $res->toArray('id');
$SMARTY->assign('printers',$printers);

$SMARTY->display('drivers/display.tpl');

?>
