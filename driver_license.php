<?php
	
	include('inc/common.php');
	
	if(empty($_GET['driver'])) {
		header('Location: /drivers');
		exit;
	}
	
	$PAGE->setPageTitle('License for ' . $_GET['driver']);
	$PAGE->setActiveID('driver');
	$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');	
	$PAGE->addBreadCrumb($_GET['driver'],
			     $CONF->baseURL.'driver/'.$_GET['driver']."/");	
	$PAGE->addBreadCrumb('License');	

	// Load driver's license info
	$res = $DB->query("SELECT name, license, licensetext, licenselink FROM driver WHERE id = ?", $_GET['driver']);
	$driver = $res->getRow();
	$SMARTY->assign('driver', $driver);

	$SMARTY->display('drivers/license.tpl');
	
?>
