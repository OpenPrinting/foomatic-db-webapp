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
			 $printer_id = $row['id'];
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
		
		
		$SMARTY->assign('data',$data);
		$SMARTY->assign('printer_assoc',$print_assoc);
		
		$SMARTY->display('printers/detail.tpl');

	
?>
