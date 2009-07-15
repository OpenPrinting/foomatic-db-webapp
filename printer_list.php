<?php
include('inc/common.php');
$PAGE->setPageTitle('Printer List');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Database');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');

$SMARTY->display('printers/searchform.tpl');

?>
