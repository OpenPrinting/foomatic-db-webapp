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

$SESSION->pageLock('roleadmin');

$editRole = false;

if(!empty($_POST) && isset($_GET['createRole'])) {
	$DB->query("INSERT INTO web_roles SET roleName = '?' ",$_POST['roleName']);
}

if(!empty($_REQUEST['roleID']) && is_numeric($_REQUEST['roleID'])) {
	$id = $_REQUEST['roleID'];

	if(isset($_POST['deleteRole'])) {
		$DB->query("DELETE FROM web_roles WHERE roleID = '?' LIMIT 1",$id);
	} else {
	
		// Load data for this role
		$role = new UserRole($id);
		if($role->isValid()) {
			$editRole = $id;
			$SMARTY->assign('roleID',$editRole);
			$SMARTY->assign('roleName',$role->roleName);
			
			
			if(isset($_GET['addMember']) && !empty($_POST['userName'])) {
				$role->addMember($_POST['userName']);
			}
			
			if(isset($_GET['removeMembers']) && isset($_POST['userName'])) {
				foreach($_POST['userName'] as $u) $role->removeMember($u);
			}
			
			if(isset($_GET['savePrivs'])) {
				$privs = $role->getPermissions();
				$privnames = array_keys($privs);
				foreach($_POST as $k => $v) {
					if(substr($k,0,5) == "priv_" && is_numeric($v)) {
						$key = substr($k,5);
						if($privs[$key]['value'] == null){
							$DB->query("INSERT INTO web_roles_privassign SET privName = '?', roleID = '?', value = '?'",$key,$editRole,$v);
						}else if($v != $privs[$key]['value']) {
							$DB->query("UPDATE web_roles_privassign SET value = '?' WHERE privName = '?' AND roleID = '?' LIMIT 1",$v,$key,$editRole);
						}
					} 
				}
			}
			
			$SMARTY->assign('members',$role->getMembers());
			$SMARTY->assign('permissions',$role->getPermissions());
			$SMARTY->assign('priv_opts',array("0" => 'Unset', "1" => 'Allow', "-1" => 'Never'));
		} else {
			// Not a valid record. Uh oh.
		}
	}
}



$res = $DB->query("SELECT * FROM web_roles ORDER BY roleName");
$roles = $res->toArray('roleID');
$SMARTY->assign("roles",$roles);

$PAGE->setPageTitle('User Roles');
$PAGE->addBreadCrumb('Admin',$CONF->baseURL.'admin/');
$PAGE->addBreadCrumb('User Roles');

$SMARTY->display('admin/roleadmin.tpl');

?>
