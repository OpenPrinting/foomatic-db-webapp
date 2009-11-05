<?php
include('inc/common.php');
$PAGE->setPageTitle('Printer: ' . $_GET['manufacturer'] . ' ' . $_GET['model']);	
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb($_GET['manufacturer'] . ' ' . $_GET['model']);	

$SMARTY->assign('manufacturer',$_GET['manufacturer']);
$SMARTY->assign('model',urldecode($_GET['model']) );


		// Load manufacturers
		$res = $DB->query("SELECT * FROM printer WHERE make='".$_GET['manufacturer']."' AND model='".$_GET['model']."' ");
		$makes = array();
		while($row = $res->getRow()){
			 $data[] = $row;
		}
		
		$SMARTY->assign('data',$data);
		$SMARTY->display('printers/detail.tpl');

	
?>
