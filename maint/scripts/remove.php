<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
require_once("opdb.php");
require_once("driver/driver.php");
require_once("printer/printer.php");
include('inc/siteconf.php');
$CONF = new SiteConfig();

$options = getopt("p:d:");

if ($options['p']) {
  $id = clean($options['p']);
  $printer = new Printer();
  $error = !$printer->removeFromDB($id, null, true);
}
if ($error) {
  print "[ERROR]: Failed removing printer entry $id from the MySQL database\n";
  exit;
}

if ($options['d']) {
  $id = clean($options['d']);
  $driver = new Driver();
  $error = !$driver->removeFromDB($id, null, true);
}
if ($error) {
  print "[ERROR]: Failed removing driver entry $id from the MySQL database\n";
  exit;
}

function clean($id) {
  $id = preg_replace(":^(.*/)([^/]+)$:", "$2", $id);
  $id = preg_replace(":\.xml$:", "", $id);
  return $id;
}

?>
