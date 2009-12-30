<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
require_once("mysqldb.php");
include('inc/siteconf.php');
$CONF = new SiteConfig();

$opts = getopt("f:");
$file = "";
foreach (array_keys($opts) as $opt) switch ($opt) {
  case 'f':
    $file = $opts['f'];
    break;
}

if (strpos($file, "printer/") !== false) {
  $type = "printer";
} elseif (strpos($file, "driver/") !== false) {
  $type = "driver";
} else {
  exit;
}

$id = basename($file, ".xml"); 

$queryapproval = "select contributor, approved, approver, comment from " .
  "${type}_approval where id=\"$id\";";

# Connect to MySQL database
$db = OPDB::getInstance();

$result = $db->query($queryapproval);
if ($result == null) {
  exit;
}
$row = mysql_fetch_array($result);
if ($row === false) exit;

$entry = $file . ": ";
$elements = array();
if (strlen($row['comment']) > 0) $elements[sizeof($elements)] = $row['comment'];
if (strlen($row['contributor']) > 0)
   $elements[sizeof($elements)] = "submitted by " . $row['contributor'];
if (strlen($row['approver']) > 0 and $row['approver'] != $row['contributor']) {
   $appr = "approved by " . $row['approver'];
   if (strlen($row['approved']) >= 6) 
     $appr .= " on " . $row['approved'];
   $elements[sizeof($elements)] = $appr;
}
$entry .= implode(", ", $elements);
$entry = "\t* " . wordwrap($entry, 68, "\n\t  ");
print $entry . "\n\n";
?>
