<?php
  
  include('inc/common.php');
  include('inc/login.php');

  $result = $DB->query("SELECT driver.*, driver_approval.* FROM driver INNER JOIN driver_approval ON driver.id = driver_approval.id WHERE id = ?", $_GET['driver']);
  $driver = $result->getRow();
  
  if (!$result->numRows() < 1) {
    // TODO: report 404
  }
  
  $PAGE->setPageTitle('Edit Driver: ' . $driver['name']);
  $PAGE->setActiveID('driver');
  $PAGE->addBreadCrumb('Drivers', $CONF->baseURL . 'drivers/');
  $PAGE->addBreadCrumb($driver['name'], $CONF->baseURL . 'driver/' . $driver['id']);
  $PAGE->addBreadCrumb('Edit');
  
  
  
  
  $SMARTY->assign('driver', $driver);
  
  $SMARTY->display('drivers/edit.tpl');
  
?>