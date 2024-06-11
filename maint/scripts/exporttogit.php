<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
require_once("opdb.php");
require_once("driver/driver.php");
require_once("printer/printer.php");
include('inc/siteconf.php');
$CONF = new SiteConfig();

$opts = getopt("fnad:p:");
$selection = "all";
$basedir = "foomatic";
$ppdsource = false;
foreach (array_keys($opts) as $opt) switch ($opt) {
  case 'f':
    $selection = "free";
    break;
  case 'n':
    $selection = "nonfree";
    break;
  case 'a':
    $selection = "all";
    break;
  case 'd':
    $basedir = $opts['d'];
    break;
  case 'p':
    $ppdsource = $opts['p'];
    break;
}

if ($selection == 'nonfree') {
  $foomatic_dir = "foomatic-db-nonfree";
} else {
  $foomatic_dir = "foomatic-db";
}
if ($basedir != "")
  $foomatic_dir = $basedir . "/" . $foomatic_dir;
//$foomatic_dir = "../../foomatic-db";
$driver_dir = $foomatic_dir."/db/source/driver";
$printer_dir = $foomatic_dir."/db/source/printer";
$source_dir = $foomatic_dir."/db/source";

if ($selection == 'nonfree') {
  $nonfree_and = "driver.nonfreesoftware=1 and";
  $nonfree = "where driver.nonfreesoftware=1 ";
} elseif ($selection == 'free') {
  $nonfree_and = "driver.nonfreesoftware=0 and ";
  $nonfree = "where driver.nonfreesoftware=0 ";
} else {
  $nonfree_and = "";
  $nonfree = "";
}

$queryprinters = "select printer.id from printer " .
  "where (printer.unverified is null or printer.unverified=0 or " .
  "printer.unverified='');";
$querydrivers = "select driver.id from driver " . $nonfree . ";";
$queryppdlist = "select driver_printer_assoc.ppd from " .
  "driver_printer_assoc, driver " .
  "where " . $nonfree_and . "driver_printer_assoc.driver_id=driver.id;";

# Connect to MySQL database
$db = OPDB::getInstance();

if ($selection != 'nonfree') {
  # export the printer data
  $error = false;
  $dir = $printer_dir;
  $result = $db->query($queryprinters);
  if ($result == null) {
    fwrite(STDERR, "[ERROR] Unable to get list of printers to be exported: ".$db->getError()."\n");
    exit;
  }
  $dircreated = 0;
  while($row = mysql_fetch_[$result]) {
    $id = $row['id'];
    fwrite(STDERR, "Exporting printer $id ...\n");
    $printer = new Printer();
    $error = !$printer->loadDB($id);
    if (!$error) {
      $xml = $printer->toXML();
      $filename = "$id.xml";
      if ($dircreated == 0) {
	exec ("mkdir -p $dir");
	$dircreated = 1;
      }
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
    fwrite(STDERR, "[FATAL ERROR]: Something went wrong while exporting printer data\n");
    exit;
  }
}

# export the driver data
$error = false;
$dir = $driver_dir;
$result = $db->query($querydrivers);
if ($result == null) {
  fwrite(STDERR, "[ERROR] Unable to get list of drivers to be exported: ".$db->getError()."\n");
  exit;
}
$dircreated = 0;
while($row = mysql_fetch_[$result]) {
  $id = $row['id'];
  fwrite(STDERR, "Exporting driver \"$id\" ...\n");
  $driver = new Driver();
  $error = !$driver->loadDB($id);
  if (!$error) {
    $xml = $driver->toXML();
    $filename = "$id.xml";
    if ($dircreated == 0) {
      exec ("mkdir -p $dir");
      $dircreated = 1;
    }
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
  fwrite(STDERR, "[FATAL ERROR]: Something went wrong while exporting driver data\n");
  exit;
}

if ($ppdsource) {
  # export the printer data
  $error = false;
  $dir = $ppdsource . "/db/source";
  $result = $db->query($queryppdlist);
  if ($result == null) {
    fwrite(STDERR, "[ERROR] Unable to get list of PPDs to be copied: ".$db->getError()."\n");
    exit;
  }
  while($row = mysql_fetch_[$result]) {
    $file = $row['ppd'];
    if ($file == NULL) continue;
    $destdir = $source_dir . "/" . dirname($file);
    fwrite(STDERR, "Copying $file ...\n");
    exec ("mkdir -p $destdir");
    exec ("cp -f $dir/$file $destdir");
  }

  if ($error) {
    fwrite(STDERR, "[FATAL ERROR]: Something went wrong while copying PPD files\n");
    exit;
  }
}

?>
