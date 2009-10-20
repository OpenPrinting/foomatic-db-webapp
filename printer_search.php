<?php
include('inc/common.php');
$PAGE->setPageTitle('Printer Search');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Database');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');


$SMARTY->assign('manufacturer',$_GET['manufacturer']);
$SMARTY->assign('model',$_GET['model']);


		// Load manufacturers
		/*$res = $DB->query("SELECT DISTINCT make FROM printer ORDER BY make");
		$makes = array();
		while($r = $res->getRow()) $makes[$r['make']] = $r['make'];
		$SMARTY->assign('makes',$makes);*/
		
		// Load array of models, keyed by makes
		//$res = $DB->query("SELECT model FROM printer WHERE ORDER BY make, model");
		//while($r = $res->getRow()) array_push($makes,$r['make']);
		//$SMARTY->assign('makes',$makes);
		
		
		$SMARTY->display('printers/searchresult.tpl');

	
?>
