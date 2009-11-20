<?php
include('inc/common.php');

if($SESSION->isloggedIn()){
	
		$SMARTY->assign('isLoggedIn', $SESSION->isloggedIn() );
		$auth = $USER->fetchUserRoles();
		
		$adminPerms = $USER->getPerms();
		$SMARTY->assign('isAdmin', $adminPerms['roleadmin']);

		$SMARTY->assign('isUploader', $USER->isUploader($auth) );
		$SMARTY->assign('isTrustedUploader', $USER->isTrustedUploader($auth) );
}

$SESSION->pageLock('show_admin');

$ALLOW_ROLE_ADMIN = $USER->checkPermission('roleadmin');
$SMARTY->assign('ALLOW_ROLE_ADMIN',$ALLOW_ROLE_ADMIN);

$PAGE->setPageTitle('Admin');
$PAGE->addBreadCrumb('Admin',$CONF->baseURL.'admin/');

$SMARTY->display('admin/main.tpl');

?>
