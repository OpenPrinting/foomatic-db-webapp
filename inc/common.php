<?php

// TODO: do we have to include PHPMailer on every page?
include('PHPMailer/class.phpmailer.php');

include('inc/siteconf.php');
$CONF = new SiteConfig();
include($CONF->casModulePath . '/CAS.php');

include('inc/db.php');
include('inc/smarty/SmartyBC.class.php');
include('inc/page.php');
include('inc/session.php');
include('inc/ldap.php');
include('inc/user.php');
include('inc/userrole.php');
include('inc/uploadmgr.php');
include('inc/rss/rss_fetch.inc');

session_start();
error_reporting(E_ALL);

$SMARTY = new SmartyBC();
$SMARTY->clear_compiled_tpl();

$PAGE = Page::getInstance();
$PAGE->setSmarty($SMARTY);

$SESSION = Session::getInstance();

$DB = DB::getInstance();

# JAL: The following setting switches Smarty caching on.
# With cacning turned on, the load on the server is low, but the 
# content is incorrect; the driver and printer page are the same
# for all printers.  With Smarty caching turned off, the content is
# correct, but the load is high.

#$SMARTY->caching = 1;

$SMARTY->assign('CONF',$CONF);
$SMARTY->assign('PAGE',$PAGE);
$SMARTY->assign('BASEURL',$CONF->baseURL);
$SMARTY->assign('MAINURL',$CONF->mainURL);
// FIXME: woot? double assignmet?
// $SMARTY->assign_by_ref('SESSION',$SESSION);
$SESSION->startupTasks();
$SMARTY->assign_by_ref('SESSION',$SESSION);
$SMARTY->assign('isLoggedIn', false);
$SMARTY->assign('USER',$SESSION->getUser());

$USER = $SESSION->getUser();
if($SESSION->isLoggedIn()) {
	$SMARTY->assign('SHOW_ADMIN_UI',$USER->checkPermission('show_admin'));
}

// OpenPrinting RSS for right pane
//$rss = fetch_rss('http://forums.freestandards.org/rss.php?21');
//$rss = array_slice($rss->items,0,4);
//foreach($rss as &$r) { $r['pubdate'] = date('M d, Y',strtotime($r['pubdate'])); }
//$SMARTY->assign('AnnouncementsRSS',$rss);
//unset($rss);

?>
