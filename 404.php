<?php
include('inc/common.php');
include('inc/login.php');

$PAGE->setPageTitle('404');
$PAGE->addBreadCrumb('Page not found');

if(isset($_GET['id'])) $PAGE->setActiveID($_GET['id']);

$SMARTY->display('404.tpl');

?>