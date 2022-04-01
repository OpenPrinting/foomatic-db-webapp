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

	// Check if the driver is already accepted, released, and not rejected
	$res = $DB->query("
	    SELECT id FROM driver_approval
	    WHERE id=? AND
	    (approved IS NULL OR approved=0 OR approved='' OR
	     (rejected IS NOT NULL AND rejected!=0 AND rejected!='') OR
	     (showentry IS NOT NULL AND showentry!='' AND showentry!=1 AND
	      showentry>CAST(NOW() AS DATE)))
	",  $_GET['driver']);
	$row = $res->getRow();
	if (!$row) {
	    // Load driver's license info (Load only if the driver is accepted, not
	    // rejected, and released)
	    $res = $DB->query("SELECT name, license, licensetext, licenselink FROM driver WHERE id = ?", $_GET['driver']);
	    $driver = $res->getRow();
	} else {
	    $driver = null;
	}
	$SMARTY->assign('driver', $driver);

	$SMARTY->display('drivers/license.tpl');
	
?>
