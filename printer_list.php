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

$PAGE->setPageTitle('Printer List');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');


	if(isset($_GET['action'])) {
		
		if($_GET['action']=='search'){

			if( isset($_POST['manufacturer']) && isset($_POST['model']) ){
				header( 'location: /printer/'.$_POST['manufacturer'].'/'.urlencode($_POST['model']));
			}
		
			else{
				
				
				// Load manufacturers
				$res = $DB->query("SELECT DISTINCT make FROM printer ORDER BY make");
				$makes = array();
				while($r = $res->getRow()) $makes[$r['make']] = $r['make'];
				
				$SMARTY->assign('makes',$makes);
				$SMARTY->assign('errorMessage',"Please select a manufacturer and model to continue.");
				
				$SMARTY->display('printers/searchform.tpl');
			}
		}
		if($_GET['action']=='searchall'){
			if( isset($_POST['showby_manufacturer']) && $_POST['showby_manufacturer'] != "" ){
				header( 'location: /printers/manufacturer/'.$_POST['showby_manufacturer']);
			}
		
			else{
				$res = $DB->query("SELECT DISTINCT make FROM printer ORDER BY make");
				$makes = array();
				while($r = $res->getRow()) $makes[$r['make']] = $r['make'];
				
				$SMARTY->assign('makes',$makes);
				
				$SMARTY->assign('errorMessage',"Please select a manufacturer continue.");
				$SMARTY->display('printers/searchform.tpl');
			}
		}
	
	} 
	
	else {
		// Load manufacturers
		$res = $DB->query("SELECT DISTINCT make FROM printer ORDER BY make");
		$makes = array();
		while($r = $res->getRow()) $makes[$r['make']] = $r['make'];
		$SMARTY->assign('makes',$makes);
		
		// Load array of models, keyed by makes
		//$res = $DB->query("SELECT model FROM printer WHERE ORDER BY make, model");
		//while($r = $res->getRow()) array_push($makes,$r['make']);
		//$SMARTY->assign('makes',$makes);
		
		
		$SMARTY->display('printers/searchform.tpl');
	}
	
?>
