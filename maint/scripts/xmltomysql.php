<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
require_once("opdb.php");
require_once("driver/driver.php");
require_once("printer/printer.php");
include('inc/siteconf.php');
$CONF = new SiteConfig();

$options = getopt("p:d:");

foreach (array_keys($options) as $opt) switch ($opt) {
case 'p':
  $filename = $options['p'];
  $fh = fopen($filename, 'r');
  if ($fh) {
    $xml = fread($fh, filesize($filename));
    $printer = new Printer();
    $error = !$printer->loadXMLString($xml);
    if (!$error) {
      $error = !$printer->saveDB();
    }
  } else {
    print "[ERROR]: Unable to open $filename\n";
    $error = true;
  }
  if ($error) {
    print "[ERROR]: Failed importing printer XML file $filename into the MySQL database\n";
    exit;
  }
  break;

case 'd':
  $filename = $options['d'];
  $fh = fopen($filename, 'r');
  if ($fh) {
    $xml = fread($fh, filesize($filename));
    $driver = new Driver();
    $error = !$driver->loadXMLString($xml);
    if (!$error) {
      $error = !$driver->saveDB();
    }
  } else {
    print "[ERROR]: Unable to open $filename\n";
    $error = true;
  }
  if ($error) {
    print "[ERROR]: Failed importing driver XML file $filename into the MySQL database\n";
    exit;
  }
  break;
}

?>
