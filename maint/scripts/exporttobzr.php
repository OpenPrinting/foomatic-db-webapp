<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
require_once("db.php");
require_once("driver/driver.php");
require_once("printer/printer.php");
require_once("option/option.php");
include('inc/siteconf.php');
$CONF = new SiteConfig();

$foomatic_dir = "foomatic/foomatic-db";
//$foomatic_dir = "../../foomatic-db";
$driver_dir = $foomatic_dir."/db/source/driver";
$printer_dir = $foomatic_dir."/db/source/printer";
$option_dir = $foomatic_dir."/db/source/opt";

$queryprinters = "select printer.id from " .
  "printer left join printer_approval on printer.id=printer_approval.id " .
  "where (printer.unverified is null or printer.unverified=0 or " .
  "printer.unverified='') and " .
  "(printer_approval.id is null or " .
  "(printer_approval.approved is not null and " .
  "printer_approval.approved!=0 and printer_approval.approved!='' and " .
  "(printer_approval.rejected is null or printer_approval.rejected=0 or " .
  "printer_approval.rejected='') and " .
  "(printer_approval.showentry is null or printer_approval.showentry='' or " .
  "printer_approval.showentry=1 or " .
  "printer_approval.showentry<=CAST(NOW() AS DATE))));";
$querydrivers = "select driver.id from " .
  "driver left join driver_approval on driver.id=driver_approval.id " .
  "where driver_approval.id is null or " .
  "(driver_approval.approved is not null and " .
  "driver_approval.approved!=0 and driver_approval.approved!='' and " .
  "(driver_approval.rejected is null or driver_approval.rejected=0 or " .
  "driver_approval.rejected='') and " .
  "(driver_approval.showentry is null or driver_approval.showentry='' or " .
  "driver_approval.showentry=1 or " .
  "driver_approval.showentry<=CAST(NOW() AS DATE)));";
$queryoptions = "select id from options;";

# Connect to MySQL database
$db = DB::getInstance();

# export the printer data
mkdir($printer_dir, 0755, true);
$error = false;
$dir = $printer_dir;
$result = $db->query($queryprinters);
if ($result == null) {
  echo "[ERROR] Unable to get list of printers to be exported: ".$db->getError()."\n";
  exit;
}
while($row = mysql_fetch_array($result)) {
  $id = $row['id'];
  print "Exporting printer $id ...\n";
  $printer = new Printer();
  $error = !$printer->loadDB($id);
  if (!$error) {
    $xml = $printer->toXML();
    $filename = "$id.xml";
    $fh = fopen("$dir/$filename", "w");
    if ($fh) {
      $written = fwrite($fh, $xml);
      if (!$written or $written < strlen($xml)) {
	$error = true;
	break;
      }
    } else {
      $error = true;
      break;
    }
  }
}

if ($error) {
  print "[FATAL ERROR]: Something went wrong while exporting printer data\n";
  exit;
}

# export the driver data
mkdir($driver_dir, 0755, true);
$error = false;
$dir = $driver_dir;
$result = $db->query($querydrivers);
if ($result == null) {
  echo "[ERROR] Unable to get list of drivers to be exported: ".$db->getError()."\n";
  exit;
}
while($row = mysql_fetch_array($result)) {
  $id = $row['id'];
  print "Exporting driver \"$id\" ...\n";
  $driver = new Driver();
  $error = !$driver->loadDB($id);
  if (!$error) {
    $xml = $driver->toXML();
    $filename = "$id.xml";
    $fh = fopen("$dir/$filename", "w");
    if ($fh) {
      $written = fwrite($fh, $xml);
      if (!$written or $written < strlen($xml)) {
	$error = true;
	break;
      }
    } else {
      $error = true;
      break;
    }
  }
}

if ($error) {
  print "[FATAL ERROR]: Something went wrong while exporting driver data\n";
  exit;
}

# export the option data
mkdir($option_dir, 0755, true);
$error = false;
$dir = $option_dir;
$result = $db->query($queryoptions);
if ($result == null) {
  echo "[ERROR] Unable to get list of options to be exported: ".$db->getError()."\n";
  exit;
}
while($row = mysql_fetch_array($result)) {
  $id = $row['id'];
  print "Exporting option \"$id\" ...\n";
  $option = new Option();
  $error = !$option->loadDB($id);
  if (!$error) {
    $xml = $option->toXML();
    $filename = "$id.xml";
    $fh = fopen("$dir/$filename", "w");
    if ($fh) {
      $written = fwrite($fh, $xml);
      if (!$written or $written < strlen($xml)) {
	$error = true;
	break;
      }
    } else {
      $error = true;
      break;
    }
  }
}

if ($error) {
  print "[FATAL ERROR]: Something went wrong while exporting option data\n";
  exit;
}
?>
