<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
require_once("opdb.php");
require_once("driver/driver.php");
require_once("printer/printer.php");
require_once("option/option.php");
include('inc/siteconf.php');
$CONF = new SiteConfig();

$options = getopt("p:d:o:");

if ($options['p']) {
  $id = $options['p'];
  $printer = new Printer();
  $error = !$printer->loadDB($id);
  if (!$error) print $printer->toXML();
}
if ($error) {
  print "[ERROR]: Failed exporting printer entry $id to XML\n";
  exit;
}

if ($options['d']) {
  $id = $options['d'];
  $driver = new Driver();
  $error = !$driver->loadDB($id);
  if (!$error) print $driver->toXML();
}
if ($error) {
  print "[ERROR]: Failed exporting driver entry $id to XML\n";
  exit;
}

if ($options['o']) {
  $id = $options['o'];
  $option = new Option();
  $error = !$option->loadDB($id);
  if (!$error) print $option->toXML();
}
if ($error) {
  print "[ERROR]: Failed exporting option entry $id to XML\n";
  exit;
}


?>
