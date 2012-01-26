<?php

// REMOVE BEFORE GOING LIVE
//exec('rm /srv/www/lptest/templates_c/*');
include('libphp-phpmailer/class.phpmailer.php');
include('inc/siteconf.php');
include('inc/db.php');
include('inc/smarty/Smarty.class.php');
include('inc/page.php');
include('inc/session.php');
include('inc/ldap.php');
include('inc/user.php');
include('inc/userrole.php');
include('inc/uploadmgr.php');
include('inc/rss/rss_fetch.inc');

session_start();
error_reporting(E_ALL);

$CONF = new SiteConfig();
$PAGE = Page::getInstance();
$SMARTY = new Smarty();
$PAGE->setSmarty($SMARTY);
$SESSION = Session::getInstance();

$DB = DB::getInstance();

$SMARTY->assign('CONF',$CONF);
$SMARTY->assign('PAGE',$PAGE);
$SMARTY->assign('BASEURL',$CONF->baseURL);
$SMARTY->assign('MAINURL',$CONF->mainURL);
$SMARTY->assign_by_ref('SESSION',$SESSION);
$SESSION->startupTasks();
$SMARTY->assign_by_ref('SESSION',$SESSION);
$SMARTY->assign_by_ref('USER',$SESSION->getUser());

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
