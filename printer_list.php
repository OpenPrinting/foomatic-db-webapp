<?php
include('inc/common.php');
$PAGE->setPageTitle('Printer List');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Database');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');


	if(isset($_GET['make'])) {
	
		if(isset($_GET['model'])) {
		
		} else {
		
		}
	
	} else {
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
