<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
require_once("opdb.php");
require_once("driver/driver.php");
require_once("printer/printer.php");
include('inc/siteconf.php');
$CONF = new SiteConfig();

$foomatic_dir = "foomatic/foomatic-db";
//$foomatic_dir = "../../foomatic-db";
$driver_dir = $foomatic_dir."/db/source/driver";
$printer_dir = $foomatic_dir."/db/source/printer";

$error = false;
# Populate the driver datas
$dir = $driver_dir;
$dh = opendir($dir);
if ($dh) {
  $i = 1;
  while($filename = readdir($dh)) {
    if ($filename == '.' || $filename == '..' || substr_compare($filename, ".xml", -4, 4) != 0) continue;
    print "************************************\n";
    print "[Driver $i] Opening file $dir/$filename\n";
    $fh = fopen($dir.'/'.$filename, 'r');
    if ($fh) {
      $xml = fread($fh, filesize($dir.'/'.$filename));
      $driver = new Driver();
      $error = !$driver->loadXMLString($xml);
      if (!$error) {
	$error = !$driver->saveDB();
	if ($error) break;
      }
    } else {
      print "[ERROR]: Unable to open $dir/$filename\n";
      $error = true;
      break;
    }
    print "************************************\n";
    $i++;
  }
} else {
  $error = true;
}

if ($error) {
  print "[FATAL ERROR]: Something went wrong while inserting driver datas\n";
  exit;
}

# Populate the printer datas
$dir = $printer_dir;
$dh = opendir($dir);
if ($dh) {
  $i = 1;
  while($filename = readdir($dh)) {
    if ($filename == '.' || $filename == '..' || substr_compare($filename, ".xml", -4, 4) != 0) continue;
    print "************************************\n";
    print "[Printer $i] Opening file $dir/$filename\n";
    $fh = fopen($dir.'/'.$filename, 'r');
    if ($fh) {
      $xml = fread($fh, filesize($dir.'/'.$filename));
      $printer = new Printer();
      $error = !$printer->loadXMLString($xml);
      if (!$error) {
	$error = !$printer->saveDB();
	if ($error) break;
      }
    } else {
      print "[ERROR]: Unable to open $dir/$filename\n";
      $error = true;
      break;
    }
    print "************************************\n";
    $i++;
  }
} else {
  $error = true;
}

if ($error) {
  print "[FATAL ERROR]: Something went wrong while inserting printer datas\n";
  exit;
}
?>
