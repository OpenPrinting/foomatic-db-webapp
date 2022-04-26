<?php
include('inc/common.php');

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
				$res = $DB->query("
				    SELECT DISTINCT make
				    FROM printer
				    ORDER BY make");
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
				$res = $DB->query("
				    SELECT DISTINCT make
				    FROM printer LEFT JOIN printer_approval
				    ON printer.id=printer_approval.id
				    WHERE (printer_approval.id IS NULL OR
				    ((printer_approval.rejected IS NULL OR
				      printer_approval.rejected=0 OR
				      printer_approval.rejected='') AND
				     (printer_approval.showentry IS NULL OR
				      printer_approval.showentry='' OR
				      printer_approval.showentry=1 OR
				      printer_approval.showentry<=CAST(NOW() AS DATE))))
				    ORDER BY make");
				$makes = array();
				while($r = $res->getRow()) $makes[$r['make']] = $r['make'];
				
				$SMARTY->assign('makes',$makes);
				
				$SMARTY->assign('errorMessage',"Please select a manufacturer to continue.");
				$SMARTY->display('printers/searchform.tpl');
			}
		}
	
	} 
	
	else {
		// Load manufacturers
		$res = $DB->query("
				    SELECT DISTINCT make
				    FROM printer LEFT JOIN printer_approval
				    ON printer.id=printer_approval.id
				    WHERE (printer_approval.id IS NULL OR
				    ((printer_approval.rejected IS NULL OR
				      printer_approval.rejected=0 OR
				      printer_approval.rejected='') AND
				     (printer_approval.showentry IS NULL OR
				      printer_approval.showentry='' OR
				      printer_approval.showentry=1 OR
				      printer_approval.showentry<=CAST(NOW() AS DATE))))
				    ORDER BY make");
		$makes = array();
		while($r = $res->getRow()) $makes[$r['make']] = $r['make'];
		$SMARTY->assign('makes',$makes);
		
		$SMARTY->display('printers/searchform.tpl');
	}
	
?>
