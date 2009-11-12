<?php
include('inc/common.php');
$PAGE->setPageTitle('Printers by Manufacturer');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');


		//$SMARTY->assign('data',$data);
		$SMARTY->display('printers/detail_manufacturer.tpl');
?>