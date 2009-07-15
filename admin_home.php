<?php
include('inc/common.php');
$PAGE->setPageTitle('Admin');
$PAGE->addBreadCrumb('Admin',$CONF->baseURL.'admin/');

$SMARTY->display('admin/main.tpl');

?>
