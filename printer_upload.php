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
    $error = "";
    if (strlen($_POST['make']) <= 0) {
	$_POST['make'] = $_POST['make_new'];
	if (strlen($_POST['make']) <= 0) {
	    $error = "No manufacturer name entered!";
	}
    }
    if (strlen($_POST['model']) <= 0) {
	$error = "No model name entered!";
    }
    $id = printerIDfromMakeModel($_POST['make'], $_POST['model']);
    $res = $DB->query("SELECT id FROM printer WHERE id=\"$id\"");
    $row = $res->getRow();
    if (strlen($row['id']) > 0) {
	$error = "Printer already exists in the database!";
    }
    if (strlen($error) > 0) {
	echo "<pre>";
	print "ERROR: $error\n";
	print_r($SESSION->getUserName());
        print_r($_POST);
        echo "</pre>";
	exit(0);
    }

    /**
     * Insert into printer_approval table
     */

    $today = date('Y-m-d');
    $release = date('Y-m-d', strtotime($_POST['release_date']));
    $user = $SESSION->getUserName();
    $DB->query("INSERT INTO printer_approval (
	     id, 
	     contributor, 
	     showentry,
             approved,
             rejected,
             approver,
             comment
	 ) values (
	     \"" . _mysql_real_escape_string($id) . "\", 
	     \"" . _mysql_real_escape_string($user) . "\", 
	     \"" . _mysql_real_escape_string($release) . "\", 
	     " . ($SESSION->checkPermission('printer_noqueue') ?
		  "\"" . _mysql_real_escape_string($today) . "\"" :
		  "null") . ",
             null,
	     " . ($SESSION->checkPermission('printer_noqueue') ?
		  "\"" . _mysql_real_escape_string($user) . "\"" :
		  "null") . ",
             \"" . _mysql_real_escape_string("TODO: Upload comment") . "\"
         )");
    /**
     * Insert into printer tables
     */

    $DB->query("INSERT INTO printer (id,
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
	     general_manufacturer,
	     parallel_model,
	     parallel_ieee1284,
	     parallel_commandset,
	     parallel_description,
	     parallel_manufacturer,
	     usb_model,
	     usb_ieee1284,
	     usb_commandset,
	     usb_description,
	     usb_manufacturer,
	     snmp_model,
	     snmp_ieee1284,
	     snmp_commandset,
	     snmp_description,
	     snmp_manufacturer
	 )
	 values (\"" . _mysql_real_escape_string($id) . "\",
	     \"" . _mysql_real_escape_string($_POST['make']) . "\",
	     \"" . _mysql_real_escape_string($_POST['model']) . "\",
	     null,
	     \"" . _mysql_real_escape_string($_POST['url']) . "\",
	     \"" . _mysql_real_escape_string($_POST['func']) . "\",
	     \"" . _mysql_real_escape_string($_POST['dname0']) . "\",
	     null,
	     \"" . _mysql_real_escape_string($_POST['contrib_url']) . "\",
	     \"" . _mysql_real_escape_string($_POST['notes']) . "\",
	     0,
	     \"" . _mysql_real_escape_string($_POST['type']) . "\",
	     " . ($_POST['color'] == "on" ?
	          "1" : "0") . ",
	     " . _mysql_real_escape_string($_POST['resolution_x']) . ",
	     " . _mysql_real_escape_string($_POST['resolution_y']) . ",
	     " . ($_POST['postscript'] == "on" ?
	          "1" : "0") . ",
	     " . ($_POST['pdf'] == "on" ?
	          "1" : "0") . ",
	     " . ($_POST['pcl'] == "on" ?
	          "1" : "0") . ",
	     " . ($_POST['lips'] == "on" ?
	          "1" : "0") . ",
	     " . ($_POST['escp'] == "on" ?
	          "1" : "0") . ",
	     " . ($_POST['escp2'] == "on" ?
	          "1" : "0") . ",
	     " . ($_POST['hpgl2'] == "on" ?
	          "1" : "0") . ",
	     " . ($_POST['tiff'] == "on" ?
	          "1" : "0") . ",
	     " . ($_POST['proprietary'] == "on" ?
	          "1" : "0") . ",
	     " . ($_POST['pjl'] == "on" ?
	          "1" : "0") . ",
	     \"" . _mysql_real_escape_string($_POST['postscript_level']) . "\",
	     \"" . _mysql_real_escape_string($_POST['pdf_level']) . "\",
	     \"" . _mysql_real_escape_string($_POST['pcl_level']) . "\",
	     \"" . _mysql_real_escape_string($_POST['lips_level']) . "\",
	     \"" . _mysql_real_escape_string($_POST['escp_level']) . "\",
	     \"" . _mysql_real_escape_string($_POST['escp2_level']) . "\",
	     \"" . _mysql_real_escape_string($_POST['hpgl2_level']) . "\",
	     \"" . _mysql_real_escape_string($_POST['tiff_level']) . "\",
	     " . ($_POST['ascii'] == "on" ?
	          "\"us-ascii\"" :
	          "null") . ",
	     \"" . _mysql_real_escape_string($_POST['general_mdl']) . "\",
	     \"" . _mysql_real_escape_string($_POST['general_ieee']) . "\",
	     \"" . _mysql_real_escape_string($_POST['general_cmd']) . "\",
	     \"" . _mysql_real_escape_string($_POST['general_des']) . "\",
	     \"" . _mysql_real_escape_string($_POST['general_mfg']) . "\",
	     \"" . _mysql_real_escape_string($_POST['par_mdl']) . "\",
	     \"" . _mysql_real_escape_string($_POST['par_ieee']) . "\",
	     \"" . _mysql_real_escape_string($_POST['par_cmd']) . "\",
	     \"" . _mysql_real_escape_string($_POST['par_des']) . "\",
	     \"" . _mysql_real_escape_string($_POST['par_mfg']) . "\",
	     \"" . _mysql_real_escape_string($_POST['usb_mdl']) . "\",
	     \"" . _mysql_real_escape_string($_POST['usb_ieee']) . "\",
	     \"" . _mysql_real_escape_string($_POST['usb_cmd']) . "\",
	     \"" . _mysql_real_escape_string($_POST['usb_des']) . "\",
	     \"" . _mysql_real_escape_string($_POST['usb_mfg']) . "\",
	     null,
	     null,
	     null,
	     \"" . _mysql_real_escape_string($_POST['snmp_des']) . "\",
	     null
	 )");
	
    $DB->query("INSERT INTO printer_translation (
	     id, 
	     lang, 
	     comments
	 ) values (
	     \"" . _mysql_real_escape_string($id) . "\", 
	     \"en\", 
	     \"" . _mysql_real_escape_string($_POST['notes']) . "\"
         )");

    for ($i = 0; $i < $_POST['dnumber']; $i++) {
	if ($_POST["dactive$i"] != "on") continue;
	$driver_id = $_POST["dname$i"];
	$res = $DB->query("SELECT printer_id FROM driver_printer_assoc WHERE printer_id=\"$id\" AND driver_id=\"$driver_id\"");
	$row = $res->getRow();
	if (strlen($row['printer_id']) > 0) {
	    $DB->query("UPDATE driver_printer_assoc SET
                pcomments=\"" . _mysql_real_escape_string($_POST["dcomment$i"]) . "\",
                fromprinter=1
            WHERE
                printer_id=\"" . _mysql_real_escape_string($id) . "\" AND
                driver_id=\"" . _mysql_real_escape_string($driver_id) . "\"");
	    $DB->query("UPDATE driver_printer_assoc_translation SET
                pcomments=\"" . _mysql_real_escape_string($_POST["dcomment$i"]) . "\"
            WHERE
                lang=\"en\" AND
                printer_id=\"" . _mysql_real_escape_string($id) . "\" AND
                driver_id=\"" . _mysql_real_escape_string($driver_id) . "\"");
	} else {
	    $DB->query("INSERT INTO driver_printer_assoc (
	        printer_id,
	        driver_id,
	        ppd,
	        pcomments,
	        fromprinter 
	    ) values (
	        \"" . _mysql_real_escape_string($id) . "\",
	        \"" . _mysql_real_escape_string($driver_id) . "\",
	        null,
	        \"" . _mysql_real_escape_string($_POST["dcomment$i"]) . "\",
	        1
            )" );
            $DB->query("INSERT INTO printer_translation (
                printer_id,
                driver_id,
                lang,
                pcomments
            ) values (
                \"" . _mysql_real_escape_string($id) . "\",
                \"" . _mysql_real_escape_string($driver_id) . "\",
                \"en\",
                \"" . _mysql_real_escape_string($_POST["dcomment$i"]) . "\"
            )" );
	}
    }

    echo "<pre>";
    print "SUCCESS\n";
    print_r($SESSION->getUserName());
    print_r($_POST);
    echo "</pre>";
    exit(0);

	/* POST Array
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

// Dummy function, will be removed, until problem with mysql_real_escape_string() is solved.
function _mysql_real_escape_string($str) {
    return $str;
}

function printerIDfromMakeModel($make, $model) {
    $mk = $make;
    $mk = str_replace('+', 'plus', $mk);
    $mk = ereg_replace('[^A-Za-z0-9\.]+', '_', $mk);
    $mk = ereg_replace('^_', '', $mk);
    $mk = ereg_replace('_$', '', $mk);
    $mdl = $model;
    $mdl = str_replace('+', 'plus', $mdl);
    $mdl = ereg_replace('[^A-Za-z0-9\.\-]+', '_', $mdl);
    $mdl = ereg_replace('^_', '', $mdl);
    $mdl = ereg_replace('_$', '', $mdl);
    return $mk . '-' . $mdl;
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
