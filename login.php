<?php
include('inc/common.php');

if($SESSION->isLoggedIn()) header('Location: index.php');
$PAGE->setActiveID('home');
$PAGE->setPageTitle('Login');
$PAGE->addBreadCrumb('Authentication');

$a = $SESSION->getLoginMessage();
if($a) $SMARTY->assign('loginMessage',$a);

$SMARTY->display('login.tpl');
?>
