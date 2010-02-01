<?php
include('inc/common.php');

if(empty($_GET['driver'])) {
	header('Location: ./drivers');
	exit;
}

if($SESSION->isloggedIn()){
	
		$SMARTY->assign('isLoggedIn', $SESSION->isloggedIn() );
		$auth = $USER->fetchUserRoles();
		
		$adminPerms = $USER->getPerms();
		$SMARTY->assign('isAdmin', $adminPerms['roleadmin']);

		$SMARTY->assign('isUploader', $USER->isUploader($auth) );
		$SMARTY->assign('isTrustedUploader', $USER->isTrustedUploader($auth) );
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


$res = $DB->query("SELECT *
		   FROM `driver_support_contact` 
		   WHERE driver_id = '?'", $_GET['driver']);

$contacts = $res->toArray('driver_id');
$SMARTY->assign('contacts',$contacts);

///srv/www/openprinting/foomatic-db/db/source/driver/*.xml
/*$file = '/srv/www/openprinting/foomatic-db/db/source/driver/'.$_GET['driver'].'.xml';
if (file_exists($file)) {
    $xml = simplexml_load_file($file);
 	print_r($xml->packages); 
    
} else {
    $package = 'Failed to open '.$file;
}*/

$SMARTY->display('drivers/license.tpl');

?>
