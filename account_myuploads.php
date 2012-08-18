<?php
include('inc/common.php');

//$SESSION->pageLock('account_myuploads');

if(!$SESSION->isloggedIn()){
	header('location: /login?err=expired');
}

$PAGE->setPageTitle('Your Uploads');
$PAGE->setActiveID('driver');
//$PAGE->addBreadCrumb('My Account',$CONF->baseURL.'account/');

$PAGE->addBreadCrumb('Your Uploads');

		$SMARTY->assign('showTabs', "1");

		$SMARTY->assign('isLoggedIn', $SESSION->isloggedIn() );
		$auth = $USER->fetchUserRoles();
		
		$adminPerms = $USER->getPerms();
		$SMARTY->assign('isAdmin', $adminPerms['roleadmin']);

		$SMARTY->assign('isUploader', $USER->isUploader($auth) );
		$SMARTY->assign('isTrustedUploader', $USER->isTrustedUploader($auth) );
		
$SMARTY->display('account/myuploads.tpl');

?>		
