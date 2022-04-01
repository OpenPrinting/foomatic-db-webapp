<?php
  
  include('inc/common.php');

  // TODO: use queue_admin or similar global role
  $SESSION->pageLock('driver_queue_adm');
  
  $PAGE->setPageTitle('Manage Queue');
  $PAGE->addBreadCrumb('Admin', $CONF->baseURL . 'admin/');
  $PAGE->addBreadCrumb('Queue');
  
  $queue = array();
  
  $printers = $DB->query('SELECT pa.*, DATE(pa.submitted) AS submitted, DATE(pa.showentry) AS showentry, p.id, p.make, p.model 
    FROM printer_approval AS pa
    LEFT JOIN printer AS p ON pa.id = p.id
    ORDER BY submitted ASC
  ');
  
  $queue['printer'] = $printers->toArray('id');
  
  $drivers = $DB->query('SELECT da.*, DATE(da.submitted) AS submitted, DATE(da.showentry) AS showentry, d.id, d.name 
    FROM driver_approval AS da
    LEFT JOIN driver AS d ON da.id = d.id
    ORDER BY submitted ASC
  ');
  
  $queue['driver'] = $drivers->toArray('id');
  
  $SMARTY->assign('types', array('printer', 'driver'));
  $SMARTY->assign('queue', $queue);
  
  $SMARTY->display('admin/queue_list.tpl');
  
?>
