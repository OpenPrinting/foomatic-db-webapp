<?php
include('inc/common.php');

$escapedManufacturer = htmlspecialchars($_GET['manufacturer'], ENT_QUOTES, 'UTF-8');

$PAGE->setPageTitle('Printers by Manufacturer');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb($escapedManufacturer);

if($_GET['manufacturer'] ==""){
	header('location: /printers');
	
}

$SMARTY->assign('manufacturer',$escapedManufacturer);

		$resPerfect = $DB->query("
		    SELECT id, make, model
		    FROM printer
		    WHERE printer.make=? AND
		    printer.functionality='A'
		    ORDER BY model", $_GET['manufacturer']);
		$dataPerfect = array();
		while($rowPerfect = $resPerfect->getRow()){
			 $dataPerfect[] = $rowPerfect;
		}

		$resMostly = $DB->query("
		    SELECT id, make, model
		    FROM printer
		    WHERE printer.make=? AND
		    printer.functionality='B'
		    ORDER BY model", $_GET['manufacturer']);
		$dataMostly = array();
		while($rowMostly = $resMostly->getRow()){
			 $dataMostly[] = $rowMostly;
		}

		$resPartially = $DB->query("
		    SELECT id, make, model
		    FROM printer
		    WHERE printer.make=? AND
		    printer.functionality='D'
		    ORDER BY model", $_GET['manufacturer']);
		$dataPartially = array();
		while($rowPartially = $resPartially->getRow()){
			 $dataPartially[] = $rowPartially;
		}

		$resUnknown = $DB->query("
		    SELECT id, make, model
		    FROM printer
		    WHERE printer.make=? AND
		    printer.functionality=''
		    ORDER BY model", $_GET['manufacturer']);
		$dataUnknown = array();
		while($rowUnknown = $resUnknown->getRow()){
			 $dataUnknown[] = $rowUnknown;
		}

		$resPaperweight = $DB->query("
		    SELECT id, make, model
		    FROM printer
		    WHERE printer.make=? AND
		    printer.functionality='F'
		    ORDER BY model", $_GET['manufacturer']);
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
