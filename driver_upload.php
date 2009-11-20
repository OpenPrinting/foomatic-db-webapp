<?php
include('inc/common.php');

$SESSION->pageLock('driver_upload');

$PAGE->setPageTitle('Driver Upload');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');
$PAGE->addBreadCrumb('Upload New Driver');

$SMARTY->assign('licenseOptions', array(
                                "" => '--select a license type--',
                                "GPLv1" => 'GPLv1',
                                "GPLv2" => 'GPLv2',
                                "GPLv3" => 'GPLv3',
                                "Commercial" => 'Commercial',
                                "BSD" => 'BSD',
                                "MPL" => 'Mozilla Pulic License')
                                );
$SMARTY->assign('licenseSelect', '');

$SMARTY->assign('scaleOption', array(
                                "" => '--select a scale--',
                                "0" => '0 - ',
                                "10" => '10',
                                "20" => '20',
                                "30" => '30',
                                "40" => '40',
                                "50" => '50 - Moderate',
                                "60" => '60',
                                "70" => '70',
                                "80" => '80',
                                "90" => '90',
                                "100" => '100 - Perfect')
                                );
$SMARTY->assign('scaleSelect', '');


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


///// Kevin Legacy code /////
		/*
		//$um = new UploadManager('/srv/www/lptest/freshies');
		if(isset($_GET['upload']) && $um->hasFiles()) {
			$file = $um->pop();
			
			while($um->hasFiles()) {
				// Someone tried to upload more than one file. Cheater.
				$file2 = $um->pop();
				$um->delete();
			}
			
			if(!preg_match(',^([A-Za-z0-9-]*)-([A-Za-z0-9.]*).tar.gz$,',$file->getOrigName(),$matches)) {
				echo 'File not acceptable.<br />';
				echo $file->getOrigName();
				$file->delete();
			} else {
				echo 'Name okay.<br /><br />';
				print_r($matches);
			}
			
		}
		*/
///// Kevin Legacy code /////

if(isset($_POST['submit'])){
	
	//echo "<pre>";
	//print_r($_POST);
	//print_r($_FILES);
	//print_r($SESSION->getUserName());
	//echo "</pre>";
	
	/**
	 * Insert into driver tables
	 */

}
		
		
		//$SMARTY->assign('data',$data);

		$SMARTY->assign('isLoggedIn', $SESSION->isloggedIn() );
		$auth = $USER->fetchUserRoles();
		
		$adminPerms = $USER->getPerms();
		$SMARTY->assign('isAdmin', $adminPerms['roleadmin']);

		$SMARTY->assign('isUploader', $USER->isUploader($auth) );
		$SMARTY->assign('isTrustedUploader', $USER->isTrustedUploader($auth) );
		
		$SMARTY->display('drivers/upload.tpl');

?>		
