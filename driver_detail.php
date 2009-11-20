<?php
include('inc/common.php');

if(empty($_GET['driver'])) {
	header('Location: ./drivers');
	exit;
}

$PAGE->setPageTitle('Driver: ' . $_GET['driver']);
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');	
$PAGE->addBreadCrumb($_GET['driver']);	

// Load driver
$res = $DB->query("SELECT * FROM driver WHERE id = '?'", $_GET['driver']);
$driver = $res->getRow();
$SMARTY->assign('driver',$driver);

// Load driver printer assoc
$resDPA = $DB->query("SELECT dpa.*, p.make, p.model 
					  FROM driver_printer_assoc dpa, printer p 
					  WHERE driver_id = '?' 
					  AND p.id = dpa.printer_id", $_GET['driver']);
$driverPrinterAssoc = $resDPA->getRow();
$SMARTY->assign('driverPrinterAssoc',$driverPrinterAssoc);

// Load printers for this driver
$res = $DB->query("
	SELECT p.id, p.make, p.model 
	FROM driver_printer_assoc dpa
	JOIN printer p 
		ON p.id = dpa.printer_id 
	WHERE dpa.driver_id = '?' 
	ORDER BY p.make, p.model ", $_GET['driver']);
$printers = $res->toArray('id');
$SMARTY->assign('printers',$printers);

$SMARTY->display('drivers/detail.tpl');

?>
