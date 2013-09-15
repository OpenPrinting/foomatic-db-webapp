<?php
  
  include('inc/common.php');
  include('inc/login.php');
  
  $SESSION->pageLock('printer_delete');
  
  $result = $DB->query('DELETE FROM printer WHERE printer.make = ? AND printer.id = ?', $_GET['manufacturer'], $_GET['id']);
  
  header('Location: ' . $CONF->baseURL . 'admin/queue');
  
?>