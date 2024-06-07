<?php

require __DIR__ . '/../vendor/autoload.php';

// TODO: do we have to include PHPMailer on every page?
include('PHPMailer/class.phpmailer.php');

include('inc/siteconf.php');
$CONF = new SiteConfig();
include($CONF->casModulePath . '/CAS.php');

include('inc/db.php');
include('inc/page.php');

session_start();
error_reporting(E_ALL);

use Smarty\Smarty;
$SMARTY = new Smarty();

$PAGE = Page::getInstance();
$PAGE->setSmarty($SMARTY);

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

?>
