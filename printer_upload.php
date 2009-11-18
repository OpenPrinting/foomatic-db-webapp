<?php
include('inc/common.php');

$SESSION->pageLock('printer_upload');


$PAGE->setPageTitle('Printer Upload');
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb('Printer Upload');


/**
 * Post data gets processed
 * Catch post data and do check to see if printer exists
 * if exists, notify user
 * else insert into db
 * 
 */
if(isset($_POST['submit'])){
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	
	/**
	 * Insert into printer tables
	 */
	
	/*
	$DB->query('INSERT INTO printer (id,
						make,
						model,
						pcmodel,
						url,
						functionality,
						default_driver,
						ppdentry,
						contrib_url,
						comments,
						unverified,
						mechanism,
						color,
						res_x,
						res_y,
						postscript,
						pdf,
						pcl,
						lips,
						escp,
						escp2,
						hpgl2,
						tiff,
						proprietary,
						pjl,
						postscript_level,
						pdf_level,
						pcl_level,
						lips_level,
						escp_level,
						escp2_level,
						hpgl2_level,
						tiff_level,
						text,
						general_model,
						general_ieee1284,
						general_commandset,
						general_description,
						general_manufacturer
						) 
					values ("Test-HL-5150D",
						"Test",
						"HL-5150D",
						"",
						"http://www.brother.com/usa/printer/info/hl5150d/hl5150d_ove.html",
						"A",
						"Postscript-Test",
						"",
						"",
						"some text here ",
						"",
						"laser",
						"",
						"2400",
						"600",
						"1",
						"",
						"1",
						"",
						"",
						"",
						"",
						"",
						"",
						"1",
						"3",
						"",
						"6",
						"",
						"",
						"",
						"",
						"",
						"us-ascii",
						"Test HL-5150D series",
						"MFG:Test;MDL:Test HL-5150D series;",
						"PJL,PCL,PCLXL,POSTSCRIPT",
						"",
						"Test"
						)' );
	
	$DB->query('INSERT INTO printer_translation (
						id, 
						lang, 
						comments
				) values (
				"Test-HL-5150D", 
				"en", 
				"some text here") ' );
	
	$DB->query('INSERT INTO driver_printer_assoc (
						printer_id,
						driver_id,
						ppd,
						pcomments,
						fromprinter 
						) values (
						"Test-HL-5150D",
						"lj5gray",
						"",
						"",
						"1")' );

	 POST Array
	(
    [noqueue] => 1
    [submit] => Add Printer
    [release_date] => 
    [make] => 
    [make_new] => 
    [model] => 
    [url] => 
    [resolution_x] => 
    [resolution_y] => 
    [type] => 
    [refill] => 
    [postscript_level] => 
    [ppdurl] => 
    [pdf_level] => 
    [lips_level] => 
    [pcl_level] => 
    [escp_level] => 
    [escp2_level] => 
    [hpgl2_level] => 
    [tiff_level] => 
    [func] => F
    [dnumber] => 1
    [dactive0] => on
    [dname0] => 
    [dname1] => 
    [dcomment0] => 
    [contrib_url] => 
    [notes] => 
    [general_ieee] => 
    [general_mfg] => 
    [general_mdl] => 
    [general_des] => 
    [general_cmd] => 
    [par_ieee] => 
    [par_mfg] => 
    [par_mdl] => 
    [par_des] => 
    [par_cmd] => 
    [usb_ieee] => 
    [usb_mfg] => 
    [usb_mdl] => 
    [usb_des] => 
    [usb_cmd] => 
    [snmp_des] => 
	)
	 */
	
}




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
		
		// Load manufacturers
		$res = $DB->query("SELECT DISTINCT make FROM printer ORDER BY make");
		$makes = array();
		while($r = $res->getRow()) $makes[$r['make']] = $r['make'];
		$SMARTY->assign('makes',$makes);

		$resDriver = $DB->query("
			SELECT id, name, execution, shortdescription, pj.count as printerCount
			FROM driver 
			LEFT JOIN 
				(SELECT driver_id, count(printer_id) as count 
				 FROM driver_printer_assoc
				 GROUP BY driver_id)
				 AS pj
				 ON pj.driver_id = driver.id
			ORDER BY name 
			");
		$rD = $resDriver->toArray('id');
		
		$SMARTY->assign("drivers",$rD);
		
$SMARTY->display('printers/upload.tpl');
	
?>
