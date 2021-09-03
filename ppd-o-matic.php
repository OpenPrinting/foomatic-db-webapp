<?php

// PHP wrapper for generating Foomatic PPDs, replacing the former
// ppd-o-matic.cgi.
// The output of the called program "foomatic-ppdfile" should not be modified,
// as it is a working PPD and identical to PPDs generated with a local
// Foomatic installation. For the PPD generation the same code is used.
// PHP Code for access statistics, logging, ... can be added though.

// To be compatible with the former URLs the line
//   RewriteRule ^ppd-o-matic.cgi/?$ ppd-o-matic.php               [L]
// needs to be added to .htaccess

$printer = $_GET['printer'];
$driver = $_GET['driver'];

if ($_GET['show'] == "1") {
  header("Content-Type: text/plain; name=$printer-$driver.ppd; charset=UTF-8");
  header("Content-Disposition: inline; filename=\"$printer-$driver.ppd\"");
} else {
  header("Content-Type: application/octet-stream; name=$printer-$driver.ppd; charset=UTF-8");
  header("Content-Disposition: attachment; filename=\"$printer-$driver.ppd\"");
}

$ppdcmdline = "cd foomatic/foomatic-db-engine; ./foomatic-ppdfile -p " . escapeshellarg($printer) . " -d " . escapeshellarg($driver);
if (isset($_GET['shortgui'])) {
  if ($_GET['shortgui'] == "on") {
    $ppdcmdline .= " -w";
  }
}

passthru($ppdcmdline);

?>
