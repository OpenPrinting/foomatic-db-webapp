<?php

// PHP wrapper for running the web query API for printer setup tools
// http://www.linuxfoundation.org/en/OpenPrinting/Database/Query
// The output of the called program "query" should not be modified, to
// keep the web query API compatible to the former site.
// PHP Code for access statistics, logging, ... can be added though.

// To be compatible with the former query.cgi the line
//   RewriteRule ^query.cgi/?$ query.php               [L]
// needs to be added to .htaccess

if (isset($_GET['papps']) && $_GET['papps'] == "true") {
  header("Content-Type: text/plain; name=query.txt; charset=UTF-8");
  header("Content-Disposition: inline; filename=\"query.txt\"");

  // Printer apps are stored in priority order in snap/printer-apps.txt
  $dir = getcwd();
  chdir('snap');

  $papp_args = "";
  foreach($_GET as $k => $v) {
    $wrappedv = "'" . '"' . htmlspecialchars($v) . '"' . "'";
    $papp_args .= " -o " . $k . "=" . $wrappedv;
  }

  if ($papp_list = fopen("printer-apps.txt", "r")) {
    while(!feof($papp_list)) {
      $papp_name = "/var/lib/snapd/snap/bin/" . fgets($papp_list);
      $papp_name = str_replace(array("\r", "\n"), '', $papp_name);

      if (empty($papp_name)) {
        continue;
      }

      $querycmdline = $papp_name;
      $querycmdline .= " drivers";
      $querycmdline .= $papp_args;
      $querycmdline .= " 2>/dev/null";

      $result = null;
      $status = null;
      exec($querycmdline, $result, $status);

      if ($status == 0) {
        // todo: filter?
        echo $papp_name . ": " . $result[0] . "\n";
      }
    }
  }
  chdir($dir);
} else {
  if ($_GET['format'] == "xml") {
    header("Content-Type: text/xml; name=query.xml; charset=UTF-8");
    header("Content-Disposition: inline; filename=\"query.xml\"");
  } else {
    header("Content-Type: text/plain; name=query.txt; charset=UTF-8");
    header("Content-Disposition: inline; filename=\"query.txt\"");
  }

  $dir = getcwd();
  $querycmdline = "/usr/bin/perl ./query";
  foreach($_GET as $k => $v) {
    $querycmdline .= " " . escapeshellarg($k) . "=" . escapeshellarg($v);
  }

  chdir('foomatic');
  passthru($querycmdline);
  chdir($dir);
}

?>
