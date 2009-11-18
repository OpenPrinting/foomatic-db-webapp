<?php
include('inc/common.php');
$PAGE->setPageTitle('Printers by Manufacturer');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb($_GET['manufacturer']);

if($_GET['manufacturer'] ==""){
	header('location: /printers');
	
}

$SMARTY->assign('manufacturer',$_GET['manufacturer']);

		$resPerfect = $DB->query("SELECT * FROM printer WHERE make='".$_GET['manufacturer']."' AND functionality='A' ");
		
		$dataPerfect = array();
		while($rowPerfect = $resPerfect->getRow()){
			 $dataPerfect[] = $rowPerfect;

		}

		
		$resMostly = $DB->query("SELECT * FROM printer WHERE make='".$_GET['manufacturer']."' AND functionality='B' ");

		$dataMostly = array();
		while($rowMostly = $resMostly->getRow()){
			 $dataMostly[] = $rowMostly;

		}
		
		$resPartially = $DB->query("SELECT * FROM printer WHERE make='".$_GET['manufacturer']."' AND functionality='D' ");

		$dataPartially = array();
		while($rowPartially = $resPartially->getRow()){
			 $dataPartially[] = $rowPartially;

		}
		
		$resUnknown = $DB->query("SELECT * FROM printer WHERE make='".$_GET['manufacturer']."' AND functionality='' ");

		$dataUnknown = array();
		while($rowUnknown = $resUnknown->getRow()){
			 $dataUnknown[] = $rowUnknown;

		}
		
		$resPaperweight = $DB->query("SELECT * FROM printer WHERE make='".$_GET['manufacturer']."' AND functionality='F' ");
		
		$dataPaperweight = array();
		while($rowPaperweight = $resPaperweight->getRow()){
			 $dataPaperweight[] = $rowPaperweight;

		}

		
		$SMARTY->assign('dataPerfect',$dataPerfect);
		$SMARTY->assign('dataPerfectCnt', count($dataPerfect));
		
		$SMARTY->assign('dataMostly',$dataMostly);
		$SMARTY->assign('dataMostlyCnt', count($dataMostly));
		
		$SMARTY->assign('dataPartially',$dataPartially);
		$SMARTY->assign('dataPartiallyCnt', count($dataPartially));
		
		$SMARTY->assign('dataUnknown',$dataUnknown);
		$SMARTY->assign('dataUnknownCnt', count($dataUnknown));
		
		$SMARTY->assign('dataPaperweight',$dataPaperweight);
		$SMARTY->assign('dataPaperweightCnt', count($dataPaperweight));

		
		$SMARTY->display('printers/detail_manufacturer.tpl');
?>