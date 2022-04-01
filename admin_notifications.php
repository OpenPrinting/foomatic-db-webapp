<?php
include('inc/common.php');
include('inc/notifications.php');

$SESSION->pageLock('notifications');

if (!$SESSION->checkPermission('notifications')) {
  // TODO: where is this checked for?
  $SMARTY->assign('UNTRUSTED', 1);
}

$PAGE->setPageTitle('Notifications');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Notifications');

$notifications = new Notifications($CONF, $DB);

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
  if (!$notifications->saveSettings($USER->getUserName(), array(
    'email' => $_POST['email'],
    'printer_queue' => (isset($_POST['printer_queue']))? 1 : 0,
    'printer_noqueue' => (isset($_POST['printer_noqueue']))? 1 : 0,
    'driver_queue' => (isset($_POST['driver_queue']))? 1 : 0,
    'driver_noqueue' => (isset($_POST['driver_noqueue']))? 1 : 0
  ))) {
    
  }
}

$settings = $notifications->getSettings($USER->getUserName());

if ($settings['email'] === null) {
  $settings['email'] = $USER->getEmail();
}

$SMARTY->assign('settings', $settings);

$SMARTY->display('admin/notifications.tpl');

?>		
