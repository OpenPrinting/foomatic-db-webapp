<?php
  
  include('inc/common.php');
  include('inc/login.php');
  
  $result = $DB->query("SELECT printer.*, printer_approval.* FROM printer INNER JOIN printer_approval ON printer.id = printer_approval.id WHERE printer.make = ? AND printer.id = ?", $_GET['manufacturer'], $_GET['id']);
  $printer = $result->getRow();
  
  if ($result->numRows() < 1) {
    // TODO: report 404
  }
  
  $name = $printer['make'] . ' ' . $printer['model'];
  
  $PAGE->setPageTitle('Edit Printer: ' . $name);
  $PAGE->setActiveID('printer');
  $PAGE->addBreadCrumb('Printers', $CONF->baseURL . 'printers/');
  $PAGE->addBreadCrumb($name, $CONF->baseURL . 'printer/' . $printer['make'] . '/' . $printer['id']);
  $PAGE->addBreadCrumb('Edit');
  
  $SMARTY->assign('printer', $printer);
  
  if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
    
    $DB->query("INSERT INTO printer (
        id,
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
  	 ) VALUES (
       \"" . my_mysql_real_escape_string($id) . "\",
	     \"" . my_mysql_real_escape_string($make) . "\",
	     \"" . my_mysql_real_escape_string($model) . "\",
	     null,
	     \"" . my_mysql_real_escape_string($_POST['url']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['func']) . "\",
	     null,
	     null,
	     \"" . my_mysql_real_escape_string($_POST['contrib_url']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['notes']) . "\",
	     0,
	     \"" . my_mysql_real_escape_string($_POST['type']) . "\",
	     " . ((array_key_exists("color", $_POST) and
		   $_POST['color'] == "on") ?
	          "1" : "0") . ",
	     " . ($_POST['resolution_x'] > 0 ?
		  my_mysql_real_escape_string($_POST['resolution_x']) :
		  "0") . ",
	     " . ($_POST['resolution_y'] > 0 ?
                  my_mysql_real_escape_string($_POST['resolution_y']) :
                  "0") . ",
	     " . ((array_key_exists("postscript", $_POST) and
		   $_POST['postscript'] == "on") ?
	          "1" : "0") . ",
	     " . ((array_key_exists("pdf", $_POST) and
		   $_POST['pdf'] == "on") ?
	          "1" : "0") . ",
	     " . ((array_key_exists("pcl", $_POST) and
		   $_POST['pcl'] == "on") ?
	          "1" : "0") . ",
	     " . ((array_key_exists("lips", $_POST) and
		   $_POST['lips'] == "on") ?
	          "1" : "0") . ",
	     " . ((array_key_exists("escp", $_POST) and
		   $_POST['escp'] == "on") ?
	          "1" : "0") . ",
	     " . ((array_key_exists("escp2", $_POST) and
		   $_POST['escp2'] == "on") ?
	          "1" : "0") . ",
	     " . ((array_key_exists("hpgl2", $_POST) and
		   $_POST['hpgl2'] == "on") ?
	          "1" : "0") . ",
	     " . ((array_key_exists("tiff", $_POST) and
		   $_POST['tiff'] == "on") ?
	          "1" : "0") . ",
	     " . ((array_key_exists("proprietary", $_POST) and
		   $_POST['proprietary'] == "on") ?
	          "1" : "0") . ",
	     " . ((array_key_exists("pjl", $_POST) and
		   $_POST['pjl'] == "on") ?
	          "1" : "0") . ",
	     \"" . my_mysql_real_escape_string($_POST['postscript_level']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['pdf_level']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['pcl_level']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['lips_level']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['escp_level']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['escp2_level']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['hpgl2_level']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['tiff_level']) . "\",
	     " . ((array_key_exists("ascii", $_POST) and
		   $_POST['ascii'] == "on") ?
	          "\"us-ascii\"" :
	          "null") . ",
	     \"" . my_mysql_real_escape_string($_POST['general_mdl']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['general_ieee']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['general_cmd']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['general_des']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['general_mfg']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['par_mdl']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['par_ieee']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['par_cmd']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['par_des']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['par_mfg']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['usb_mdl']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['usb_ieee']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['usb_cmd']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['usb_des']) . "\",
	     \"" . my_mysql_real_escape_string($_POST['usb_mfg']) . "\",
	     null,
	     null,
	     null,
	     \"" . my_mysql_real_escape_string($_POST['snmp_des']) . "\",
	     null
  	 )
    ");
      
     $DB->query("INSERT INTO printer_approval (
         id, 
         contributor, 
         submitted,
         showentry,
         approved,
         rejected,
         approver,
         comment
       ) VALUES (
         \"" . my_mysql_real_escape_string($id) . "\", 
         \"" . my_mysql_real_escape_string($user) . "\",
         \"" . $today . "\",
         " . $release . ", 
         " . ($SESSION->checkPermission('printer_noqueue') ?
         "\"" . $today . "\"" : "null") . ",
         null,
         " . ($SESSION->checkPermission('printer_noqueue') ?
         "\"" . my_mysql_real_escape_string($user) . "\"" :
         "null") . ",
         \"" . my_mysql_real_escape_string($_POST['comments']) . "\"
       )
     ");
  }
  
  $res = $DB->query("SELECT DISTINCT make FROM printer ORDER BY make");
  $makes = array();
  
  // TODO: use toArray or similar?
  while ($r = $res->getRow()) {
    $makes[$r['make']] = $r['make'];
  }
  
  $SMARTY->assign('makes', $makes);
  
  $SMARTY->display('printers/edit.tpl');
  
?>