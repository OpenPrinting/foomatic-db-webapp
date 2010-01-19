<?php
include('inc/common.php');

if($SESSION->isloggedIn()){
	
		$SMARTY->assign('isLoggedIn', $SESSION->isloggedIn() );
		$auth = $USER->fetchUserRoles();
		
		$adminPerms = $USER->getPerms();
		$SMARTY->assign('isAdmin', $adminPerms['roleadmin']);

		$SMARTY->assign('isUploader', $USER->isUploader($auth) );
		$SMARTY->assign('isTrustedUploader', $USER->isTrustedUploader($auth) );
}

		// Load manufacturers
		//$res = $DB->query("SELECT * FROM printer WHERE make='".$_GET['manufacturer']."' AND model='".$_GET['model']."' ");
		$res = $DB->query("SELECT * FROM printer WHERE make='".$_GET['manufacturer']."' AND id='".$_GET['id']."' ");
		$makes = array();
		while($row = $res->getRow()){
			 $data[] = $row;
			 $printer_make = $row['model'];
			 $printer_id = $row['id'];
			 $driver_id = $row['default_driver'];
		}

/**
 * Had to place down a few lines to do the db call to refactore model 
 * and url. Url now uses ID
 */

$PAGE->setPageTitle('Printer: ' . $_GET['manufacturer'] . ' ' . $printer_make);	
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb($_GET['manufacturer'] . ' ' . $printer_make);	

		
		$driver_url = "";
		$resDriver = $DB->query("SELECT * FROM driver WHERE id='".$driver_id."' ");
		while($rowDriver = $resDriver->getRow()){
			if($rowDriver['url']){
				$driver_url = $rowDriver['url'];
			}
			else{
				$driver_url = "";
			}
		}
		
		$resDriverAssoc = $DB->query("SELECT COUNT(driver_id) AS cnt FROM driver_printer_assoc WHERE printer_id='".$printer_id."' ");
		while($rowCnt = $resDriverAssoc->getRow()){
			if($rowCnt['cnt'] > 0){
				$print_assoc = 1;
				
			}
			else{
				$print_assoc = 0;
			}
		}
		
				 
		//check if There is a PPD file 
		//IF the "ppd" field in the "driver_printer_assoc" table is filled 
		//OR if the "prototype" field in the table "driver" is filled
		
		$resIsPPDChk1 = $DB->query("SELECT COUNT(driver_id) AS cnt  
								FROM driver_printer_assoc  
								WHERE printer_id='".$printer_id."' 
								AND driver_id = '".$driver_id."'  
								AND (ppd <> '' AND ppd IS NOT NULL) ");
								
		while($rowCnt2 = $resIsPPDChk1->getRow()){
			if($rowCnt2['cnt'] > 0){
				$hasPPD = 1;
			}
			else{
				//do next check
				$resIsPPDChk2 = $DB->query("SELECT COUNT(id) AS cnt 
											FROM driver 
											WHERE id='".$driver_id."' 
											AND (prototype <> '' AND prototype IS NOT NULL) ");
											
				while($rowCnt3 = $resIsPPDChk2->getRow()){			
					if($rowCnt3['cnt'] > 0){
						$hasPPD = 1;
					}
					else{
						$hasPPD = 0;				
					}
				}
				
			}
		}
		
		$SMARTY->assign('manufacturer',$_GET['manufacturer']);
		//$SMARTY->assign('model',urldecode($_GET['model']) );
		$SMARTY->assign('model',$printer_make );

		$SMARTY->assign('data',$data);
		
		$SMARTY->assign('printer_assoc',$print_assoc);
		if($driver_url != ""){
			$SMARTY->assign('driverUrl', $driver_url);
		}
		else{
			$SMARTY->assign('driverUrl', "");
		}
		
		$SMARTY->assign('hasPPD', $hasPPD );
		
		$SMARTY->display('printers/detail.tpl');

	
?>