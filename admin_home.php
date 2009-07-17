<?php
include('inc/common.php');
$SESSION->pageLock('show_admin');

$ALLOW_ROLE_ADMIN = $USER->checkPermission('roleadmin');
$SMARTY->assign('ALLOW_ROLE_ADMIN',$ALLOW_ROLE_ADMIN);

$PAGE->setPageTitle('Admin');
$PAGE->addBreadCrumb('Admin',$CONF->baseURL.'admin/');

$SMARTY->display('admin/main.tpl');

?>
