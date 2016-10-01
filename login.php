<?php
//First try to get referring uri
if (isset($_SERVER['HTTP_REFERER'])) {
  $referrer = $_SERVER['HTTP_REFERER'];
}
else
{
  $referrer = "";
}

include('inc/common.php');

//set referrer url
if (($referrer != "") && ($SESSION->getReferrer() == "")) {
  $SESSION->setReferrer($referrer);
}

if ($CONF->authType == 'cas') {
  $SESSION->authenticate();
}

if($SESSION->isLoggedIn()) 
{
  $referrer = $SESSION->getReferrer();
  if ($referrer == "") {
    $referrer = "/printers";
  }
  //$SMARTY->assign('successRefer', $referrer);
  header('Location: ' . $referrer);
  exit;
}

$PAGE->setActiveID('home');
$PAGE->setPageTitle('Login');
$PAGE->addBreadCrumb('Authentication');

// if not using CAS, use our login screen
if ($CONF->authType != 'cas') {
   $a = $SESSION->getLoginMessage();

   if($a) {
	$SMARTY->assign('loginMessage',$a);
   } else {
	
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
}


$SMARTY->display('login.tpl');
?>
