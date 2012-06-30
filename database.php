<?php
include('inc/common.php');
include('inc/login.php');

$PAGE->setPageTitle('Database');
$PAGE->setActiveID('db');
$PAGE->addBreadCrumb('Database');

$SMARTY->display('database.tpl');
?>
