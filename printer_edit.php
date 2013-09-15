<?php
  
  include('inc/common.php');
  include('inc/login.php');
  
  $SESSION->pageLock('printer_edit');
  
  $result = $DB->query("SELECT printer.*, printer_approval.*, DATE(printer_approval.submitted) AS submitted FROM printer INNER JOIN printer_approval ON printer.id = printer_approval.id WHERE printer.make = ? AND printer.id = ?", $_GET['manufacturer'], $_GET['id']);
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
  
  if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
    $DB->query('UPDATE printer SET url = ?, functionality = ?, contrib_url = ?, comments = ?, mechanism = ?, color = ?, res_x = ?, res_y = ?, postscript = ?, pdf = ?, pcl = ?, lips = ?, escp = ?, escp2 = ?, hpgl2 = ?, tiff = ?, proprietary = ?, pjl = ?, postscript_level = ?, pdf_level = ?, pcl_level = ?, lips_level = ?, escp_level = ?, escp2_level = ?, hpgl2_level = ?, tiff_level = ?, text = ?, general_model = ?, general_ieee1284 = ?, general_commandset = ?, general_description = ?, general_manufacturer = ?, parallel_model = ?, parallel_ieee1284 = ?, parallel_commandset = ?, parallel_description = ?, parallel_manufacturer = ?, usb_model = ?, usb_ieee1284 = ?, usb_commandset = ?, usb_description = ?, usb_manufacturer = ?, snmp_description = ? WHERE id = ?',
      $_POST['url'],
      $_POST['func'],
      $_POST['contrib_url'],
      $_POST['notes'],
      $_POST['type'],
      (!empty($_POST['color']))? '1' : '0',
      $_POST['resolution_x'],
      $_POST['resolution_y'],
      (!empty($_POST['postscript']))? '1' : '0',
      (!empty($_POST['pdf']))? '1' : '0',
      (!empty($_POST['pcl']))? '1' : '0',
      (!empty($_POST['lips']))? '1' : '0',
      (!empty($_POST['escp']))? '1' : '0',
      (!empty($_POST['escp2']))? '1' : '0',
      (!empty($_POST['hpgl2']))? '1' : '0',
      (!empty($_POST['tiff']))? '1' : '0',
      (!empty($_POST['proprietary']))? '1' : '0',
      (!empty($_POST['pjl']))? '1' : '0',
      $_POST['postscript_level'],
      $_POST['pdf_level'],
      $_POST['pcl_level'],
      $_POST['lips_level'],
      $_POST['escp_level'],
      $_POST['escp2_level'],
      $_POST['hpgl2_level'],
      $_POST['tiff_level'],
      !empty($_POST['ascii'])? '1' : '0',
      $_POST['general_mdl'],
      $_POST['general_ieee'],
      $_POST['general_cmd'],
      $_POST['general_des'],
      $_POST['general_mfg'],
      $_POST['par_mdl'],
      $_POST['par_ieee'],
      $_POST['par_cmd'],
      $_POST['par_des'],
      $_POST['par_mfg'],
      $_POST['usb_mdl'],
      $_POST['usb_ieee'],
      $_POST['usb_cmd'],
      $_POST['usb_des'],
      $_POST['usb_mfg'],
      $_POST['snmp_des'],
      $printer['id']
    );
    
    $DB->query('UPDATE printer_approval SET approved = ?, rejected = ?, approver = ?, comment = ? WHERE id = ?',
      (!empty($_POST['approve']))? date('Y-m-d') : null,
      (!empty($_POST['reject']))? date('Y-m-d') : null,
      (!empty($_POST['approve']))? $SESSION->getUserName() : null,
      $_POST['comments'],
      $printer['id']
    );
    
    $result = $DB->query("SELECT printer.*, printer_approval.* FROM printer INNER JOIN printer_approval ON printer.id = printer_approval.id WHERE printer.make = ? AND printer.id = ?", $_GET['manufacturer'], $_GET['id']);
    $printer = $result->getRow();
  }
  
  $SMARTY->assign('printer', $printer);
  
  $result = $DB->query("SELECT DISTINCT make FROM printer ORDER BY make");
  $SMARTY->assign('makes', $result->toArray('make'));
  
  $SMARTY->display('printers/edit.tpl');
  
?>