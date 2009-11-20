<?php
include('inc/common.php');

if($SESSION->isLoggedIn()) header('Location: /account/myuploads');

$PAGE->setActiveID('home');
$PAGE->setPageTitle('Login');
$PAGE->addBreadCrumb('Authentication');


$a = $SESSION->getLoginMessage();

if($a) {
	$SMARTY->assign('loginMessage',$a);
}
else {
	
	if(isset($_GET['err']) && $_GET['err'] != ""){
		switch ($_GET['err']) {
			case "expired":
				$SMARTY->assign('loginMessage',"Your Session is Expired. Please Login");
				break;
			default:
				$SMARTY->assign('loginMessage',"Unknown Error Occurred. Please Login");
				break;
		}
	}
}


$SMARTY->display('login.tpl');
?>
