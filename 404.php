<?php
include('inc/common.php');
$PAGE->setPageTitle('404!');
$PAGE->addBreadCrumb('This is not the file you are looking for.');

if(isset($_GET['id'])) $PAGE->setActiveID($_GET['id']);

$SMARTY->display('404.tpl');

?>