<?php
include('inc/common.php');
include('inc/login.php');

$PAGE->setPageTitle('Printers by Manufacturer');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb($_GET['manufacturer']);

if($_GET['manufacturer'] ==""){
	header('location: /printers');
	
}

$SMARTY->assign('manufacturer',$_GET['manufacturer']);

		$resPerfect = $DB->query("
		    SELECT printer.id AS id, make, model,
                    (printer_approval.id IS NULL OR
                     (printer_approval.approved IS NOT NULL AND
                      printer_approval.approved!=0 AND
                      printer_approval.approved!='')) AS approved
		    FROM printer LEFT JOIN printer_approval
		    ON printer.id=printer_approval.id
		    WHERE printer.make='".$_GET['manufacturer']."' AND
		    printer.functionality='A' AND
		    (printer_approval.id IS NULL OR
		     ((printer_approval.rejected IS NULL OR
		       printer_approval.rejected=0 OR
		       printer_approval.rejected='') AND
		      (printer_approval.showentry IS NULL OR
		       printer_approval.showentry='' OR
		       printer_approval.showentry=1 OR
		       printer_approval.showentry<=CAST(NOW() AS DATE))))
		    ORDER BY model");
		$dataPerfect = array();
		while($rowPerfect = $resPerfect->getRow()){
			 $dataPerfect[] = $rowPerfect;

		}

		$resMostly = $DB->query("
		    SELECT printer.id AS id, make, model,
                    (printer_approval.id IS NULL OR
                     (printer_approval.approved IS NOT NULL AND
                      printer_approval.approved!=0 AND
                      printer_approval.approved!='')) AS approved
		    FROM printer LEFT JOIN printer_approval
		    ON printer.id=printer_approval.id
		    WHERE printer.make='".$_GET['manufacturer']."' AND
		    printer.functionality='B' AND
		    (printer_approval.id IS NULL OR
		     ((printer_approval.rejected IS NULL OR
		       printer_approval.rejected=0 OR
		       printer_approval.rejected='') AND
		      (printer_approval.showentry IS NULL OR
		       printer_approval.showentry='' OR
		       printer_approval.showentry=1 OR
		       printer_approval.showentry<=CAST(NOW() AS DATE))))
		    ORDER BY model");
		$dataMostly = array();
		while($rowMostly = $resMostly->getRow()){
			 $dataMostly[] = $rowMostly;

		}

		$resPartially = $DB->query("
		    SELECT printer.id AS id, make, model,
                    (printer_approval.id IS NULL OR
                     (printer_approval.approved IS NOT NULL AND
                      printer_approval.approved!=0 AND
                      printer_approval.approved!='')) AS approved
		    FROM printer LEFT JOIN printer_approval
		    ON printer.id=printer_approval.id
		    WHERE printer.make='".$_GET['manufacturer']."' AND
		    printer.functionality='D' AND
		    (printer_approval.id IS NULL OR
		     ((printer_approval.rejected IS NULL OR
		       printer_approval.rejected=0 OR
		       printer_approval.rejected='') AND
		      (printer_approval.showentry IS NULL OR
		       printer_approval.showentry='' OR
		       printer_approval.showentry=1 OR
		       printer_approval.showentry<=CAST(NOW() AS DATE))))
		    ORDER BY model");
		$dataPartially = array();
		while($rowPartially = $resPartially->getRow()){
			 $dataPartially[] = $rowPartially;

		}

		$resUnknown = $DB->query("
		    SELECT printer.id AS id, make, model,
                    (printer_approval.id IS NULL OR
                     (printer_approval.approved IS NOT NULL AND
                      printer_approval.approved!=0 AND
                      printer_approval.approved!='')) AS approved
		    FROM printer LEFT JOIN printer_approval
		    ON printer.id=printer_approval.id
		    WHERE printer.make='".$_GET['manufacturer']."' AND
		    printer.functionality='' AND
		    (printer_approval.id IS NULL OR
		     ((printer_approval.rejected IS NULL OR
		       printer_approval.rejected=0 OR
		       printer_approval.rejected='') AND
		      (printer_approval.showentry IS NULL OR
		       printer_approval.showentry='' OR
		       printer_approval.showentry=1 OR
		       printer_approval.showentry<=CAST(NOW() AS DATE))))
		    ORDER BY model");
		$dataUnknown = array();
		while($rowUnknown = $resUnknown->getRow()){
			 $dataUnknown[] = $rowUnknown;

		}

		$resPaperweight = $DB->query("
		    SELECT printer.id AS id, make, model,
                    (printer_approval.id IS NULL OR
                     (printer_approval.approved IS NOT NULL AND
                      printer_approval.approved!=0 AND
                      printer_approval.approved!='')) AS approved
		    FROM printer LEFT JOIN printer_approval
		    ON printer.id=printer_approval.id
		    WHERE printer.make='".$_GET['manufacturer']."' AND
		    printer.functionality='F' AND
		    (printer_approval.id IS NULL OR
		     ((printer_approval.rejected IS NULL OR
		       printer_approval.rejected=0 OR
		       printer_approval.rejected='') AND
		      (printer_approval.showentry IS NULL OR
		       printer_approval.showentry='' OR
		       printer_approval.showentry=1 OR
		       printer_approval.showentry<=CAST(NOW() AS DATE))))
		    ORDER BY model");
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