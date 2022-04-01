<?php
  
  include('inc/common.php');
  
  $SESSION->pageLock('driver_edit');
  
  $result = $DB->query('SELECT driver.*, driver_approval.*, driver_support_contact.url AS support_url, driver_support_contact.level AS support_level, driver_support_contact.description AS support_description
    FROM driver
    INNER JOIN driver_approval ON driver.id = driver_approval.id
    INNER JOIN driver_support_contact ON driver.id = driver_support_contact.driver_id
    WHERE driver.id = ?',
  $_GET['driver']);
  $driver = $result->getRow();
  
  if (!$result->numRows() < 1) {
    // TODO: report 404
  }
  
  $PAGE->setPageTitle('Edit Driver: ' . $driver['name']);
  $PAGE->setActiveID('driver');
  $PAGE->addBreadCrumb('Drivers', $CONF->baseURL . 'drivers/');
  $PAGE->addBreadCrumb($driver['name'], $CONF->baseURL . 'driver/' . $driver['id']);
  $PAGE->addBreadCrumb('Edit');
  
  if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
    $DB->query('UPDATE driver SET obsolete = ?, url = ?, supplier = ?, thirdpartysupplied = ?, manufacturersupplied = ?, license = ?, licensetext = ?, licenselink = ?, nonfreesoftware = ?, patents = ?, shortdescription = ?, max_res_x = ?, max_res_y = ?, color = ?, text = ?, lineart = ?, graphics = ?, photo = ?, load_time = ?, speed = ?, execution = ? WHERE id = ?',
      $_POST['obsolete'],
      $_POST['download_url'],
      $_POST['supplier'],
      (!empty($_POST['manufacturersupplied']))? '0' : '1',
      (!empty($_POST['manufacturersupplied']))? $_POST['supplier'] : null,
      $_POST['license'],
      $_POST['licensetext'],
      $_POST['licenselink'],
      (!empty($_POST['nonfreesoftware']))? '1' : '0',
      (!empty($_POST['patents']))? '1' : '0',
      $_POST['description'],
      (!empty($_POST['max_res_x']))? $_POST['max_res_x'] : null,
      (!empty($_POST['max_res_y']))? $_POST['max_res_y'] : null,
      (!empty($_POST['color']))? '1' : ((!empty($_POST['grayscale']))? '0' : null),
      (isset($_POST['text']))? $_POST['text'] : null,
      (isset($_POST['lineart']))? $_POST['graphics'] : null,
      (isset($_POST['graphics']))? $_POST['lineart'] : null,
      (isset($_POST['photo']))? $_POST['load_time'] : null,
      (isset($_POST['load_time']))? $_POST['speed'] : null,
      (isset($_POST['speed']))? $_POST['photo'] : null,
      (isset($_POST['execution']))? $_POST['execution'] : null,
      $driver['id']
    );
    
    $DB->query('UPDATE driver_approval SET approved = ?, rejected = ?, approver = ?, comment = ? WHERE id = ?',
      (!empty($_POST['approve']))? date('Y-m-d') : null,
      (!empty($_POST['reject']))? date('Y-m-d') : null,
      (!empty($_POST['approve']))? $SESSION->getUserName() : null,
      $_POST['comment'],
      $driver['id']
    );
    
    $DB->query('UPDATE driver_support_contact SET description = ?, url = ?, level = ? WHERE driver_id = ?',
      $_POST['supportdescription'],
      $_POST['supporturl'],
      $_POST['supportlevel'],
      $driver['id']
    );
    
    $result = $DB->query('SELECT driver.*, driver_approval.*, driver_support_contact.url AS support_url, driver_support_contact.level AS support_level, driver_support_contact.description AS support_description
      FROM driver
      INNER JOIN driver_approval ON driver.id = driver_approval.id
      INNER JOIN driver_support_contact ON driver.id = driver_support_contact.driver_id
      WHERE driver.id = ?', $driver['id']);
    $driver = $result->getRow();
  }
  
  $SMARTY->assign('driver', $driver);
  
  $result = $DB->query('SELECT id, name, execution, shortdescription, pj.count as printerCount
  	FROM driver 
  	LEFT JOIN 
  		(SELECT driver_id, count(printer_id) as count 
  		 FROM driver_printer_assoc
  		 GROUP BY driver_id)
  		 AS pj
  		 ON pj.driver_id = driver.id
  	ORDER BY name
  ');
  $SMARTY->assign('drivers', $result->toArray('id'));
  
  $SMARTY->assign('scaleOption', array(
    "" => '--select a scale--',
    "0" => '0 - Not Suitable',
    "10" => '10',
    "20" => '20',
    "30" => '30',
    "40" => '40',
    "50" => '50 - Moderate',
    "60" => '60',
    "70" => '70',
    "80" => '80',
    "90" => '90',
    "100" => '100 - Perfect'
  ));
  
  $SMARTY->display('drivers/edit.tpl');
  
?>
