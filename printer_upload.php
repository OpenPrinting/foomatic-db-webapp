<?php
include('inc/common.php');

$SESSION->pageLock('printer_upload');


$PAGE->setPageTitle('Printer Upload');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb('Printer Upload');

$SMARTY->assign('licenseOptions', array(
                                "" => '--select a license type--',
                                "GPLv1" => 'GPLv1',
                                "GPLv2" => 'GPLv2',
                                "GPLv3" => 'GPLv3',
                                "Commercial" => 'Commercial',
                                "BSD" => 'BSD',
                                "MPL" => 'Mozilla Pulic License')
                                );
$SMARTY->assign('licenseSelect', '');

$SMARTY->assign('scaleOption', array(
                                "" => '--select a scale--',
                                "0" => '0 - Unusable',
                                "25" => '25 - Poor',
                                "50" => '50 - Moderate',
                                "75" => '75 - Good',
                                "100" => '100 - Perfect')
                                );
$SMARTY->assign('scaleSelect', '');


		if($SESSION->checkPermission('printer_noqueue')) $SMARTY->assign('isTrusted',1);
		
		$SMARTY->assign('isLoggedIn', $SESSION->isloggedIn() );
		$auth = $USER->fetchUserRoles();
		
		$adminPerms = $USER->getPerms();
		$SMARTY->assign('isAdmin', $adminPerms['roleadmin']);
		
$SMARTY->display('printers/upload.tpl');
	
?>
