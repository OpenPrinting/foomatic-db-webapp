<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
require_once("mysqldb.php");
require_once("driver/driver.php");
require_once("printer/printer.php");
require_once("option/option.php");
include('inc/siteconf.php');
$CONF = new SiteConfig();

$options = getopt("p:d:o:");

if ($options['p']) {
  $id = $options['p'];
  $printer = new Printer();
  $error = !$printer->removeFromDB($id, null, true);
}
if ($error) {
  print "[ERROR]: Failed removing printer entry $id from the MySQL database\n";
  exit;
}

if ($options['d']) {
  $id = $options['d'];
  $driver = new Driver();
  $error = !$driver->removeFromDB($id, null, true);
}
if ($error) {
  print "[ERROR]: Failed removing driver entry $id from the MySQL database\n";
  exit;
}

if ($options['o']) {
  $id = $options['o'];
  $option = new Option();
  $error = !$option->removeFromDB($id);
}
if ($error) {
  print "[ERROR]: Failed removing option entry $id from the MySQL database\n";
  exit;
}

?>
