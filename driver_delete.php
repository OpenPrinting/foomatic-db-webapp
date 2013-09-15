<?php
  
  include('inc/common.php');
  include('inc/login.php');
  
  $SESSION->pageLock('driver_delete');
  
  $result = $DB->query('DELETE FROM driver WHERE driver.id = ?', $_GET['driver']);
  
  header('Location: ' . $CONF->baseURL . 'admin/queue');
  
?>