<?php
include('inc/common.php');

if(empty($_GET['driver'])) {
	header('Location: /drivers');
	exit;
}

$drivertypes = array(
    "ghostscript" => 'Ghostscript built-in',
    "uniprint" => 'Ghostscript Uniprint',
    "filter" => 'Filter',
    "ijs" => 'IJS',
    "cups" => 'CUPS Raster',
    "opvp" => 'OpenPrinting Vector',
    "postscript" => 'PostScript');

$driverparameters = array(
    "text" => 'Text',
    "lineart" => 'Line Art',
    "graphics" => 'Graphics',
    "photo" => 'Photo',
    "load_time" => 'System Load',
    "speed" => 'Speed');

$PAGE->setPageTitle('Driver: ' . $_GET['driver']);
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');	
$PAGE->addBreadCrumb($_GET['driver']);	

// Check if the driver is already accepted, released, and not rejected
$res = $DB->query("
    SELECT id FROM driver_approval
    WHERE id=? AND
    (approved IS NULL OR approved=0 OR approved='' OR
     (rejected IS NOT NULL AND rejected!=0 AND rejected!='') OR
     (showentry IS NOT NULL AND showentry!='' AND showentry!=1 AND
      showentry>CAST(NOW() AS DATE)))
", $_GET['driver']);
$row = $res->getRow();
if (!$row) {
    // Driver data (Load only if the driver is accepted, not rejected, and 
    // released)
    $res = $DB->query("SELECT * FROM driver WHERE id = ?", $_GET['driver']);
    $driver = $res->getRow();
} else {
    $driver = null;
}
$SMARTY->assign('driver',$driver);

// Load driver printer assoc
$resDPA = $DB->query("SELECT dpa.*, p.make, p.model 
					  FROM driver_printer_assoc dpa, printer p 
					  WHERE driver_id = ?
					  AND p.id = dpa.printer_id", $_GET['driver']);
$driverPrinterAssoc = $resDPA->getRow();
$SMARTY->assign('driverPrinterAssoc',$driverPrinterAssoc);

// Load printers for this driver
$res = $DB->query("
        SELECT pr.id AS id, make, model
	FROM (SELECT dpa.printer_id AS id, p.make, p.model 
	      FROM driver_printer_assoc dpa
	      LEFT JOIN printer p 
		       ON p.id = dpa.printer_id 
	      WHERE dpa.driver_id = ?) AS pr
        LEFT JOIN printer_approval
        ON pr.id=printer_approval.id                           
        WHERE (printer_approval.id IS NULL OR     
         ((printer_approval.rejected IS NULL OR         
           printer_approval.rejected=0 OR   
           printer_approval.rejected='') AND
          (printer_approval.showentry IS NULL OR        
           printer_approval.showentry='' OR 
           printer_approval.showentry=1 OR  
           printer_approval.showentry<=CAST(NOW() AS DATE))))       
	ORDER BY pr.id, pr.make, pr.model ", $_GET['driver']);
$printers = $res->toArray('id');
// For unregistered printers (only mentioned in driver's printer list)
// make and model name are empty. Derive make and model name from the
// printer ID in such a case.
foreach($printers as $pr) {
  if (strlen($pr['make']) == 0) {
    $matches = array();
    if (preg_match("/^([^\-]+)\-(.*)$/", $pr['id'], $matches) != 0) {
      $printers[$pr['id']]['make'] =
	preg_replace("/_+/", " ", $matches[1]);
      $printers[$pr['id']]['model'] =
	preg_replace("/_+/", " ", preg_replace("/plus\b/", "+", $matches[2]));
    }
  }
}
$SMARTY->assign('printers',$printers);

$res = $DB->query("SELECT *
		   FROM `driver_support_contact` 
		   WHERE driver_id = ?", $_GET['driver']);

$contacts = $res->toArray();

$res = $DB->query("SELECT *
		   FROM `driver_package` 
		   WHERE driver_id = ?", $_GET['driver']);

$packages = $res->toArray();

$packagedownloads = "";
$mask = "";
foreach($packages as $p) {
  if (strlen($p['name']) > 0) {
    $params =
      (strlen($p['fingerprint']) > 0 ?
       (strlen($p['scope']) > 0 ? "({$p['scope']}:" : "(general:") .
       $p['fingerprint'] . ")" :
       (strlen($p['scope']) > 0 ? "({$p['scope']})" : ""));
    $pattern = preg_replace("/;/", ";$params", $p['name']);
    $mask .= (strlen($mask) > 0 ? ";" : "") . $params . $pattern;
  }
}
if (strlen($mask) <= 0) {
    $mask = "{$_GET['driver']};openprinting-{$_GET['driver']};" .
	"openprinting-ppds-{$_GET['driver']}";
}
$out = array();
exec("cd foomatic; ./packageinfo " . escapeshellarg($mask), $out, $ret_value);
if (sizeof($out) > 0)
    $packagedownloads = $out[0];

$res = $DB->query("SELECT *
		   FROM `driver_dependency` 
		   WHERE driver_id = ?", $_GET['driver']);

$dependencies = $res->toArray();

$infobox = "<p>" .
    "<table border=\"0\" bgcolor=\"#f5f5f5\" cellpadding=\"1\"" .
    "cellspacing=\"0\" width=\"100%\">" .
    "<tr><td colspan=\"8\">" .
    "<table border=\"0\" bgcolor=\"#eeeeee\" cellpadding=\"0\"" .
    "cellspacing=\"0\" width=\"100%\">" .
    "<tr valign=\"center\" bgcolor=\"#eeeeee\">" .
    "<td width=\"2%\"></td>" .
    "<td width=\"96%\"><font size=\"-4\">&nbsp;" .
    "</font></td><td width=\"2%\"></td></tr>" .
    "<tr valign=\"center\" bgcolor=\"#eeeeee\">" .
    "<td width=\"2%\"></td>" .
    "<td width=\"96%\"><font size=\"+2\"><b>";
if ($driver['url']) {
    $infobox .= "<a href=\"{$driver['url']}\">{$driver['name']}</a>";
} else {
    $infobox .= "{$driver['name']}";
}
$infobox .= "</b></font></td><td width=\"2%\"></td></tr>" .
    "<tr valign=\"center\" bgcolor=\"#eeeeee\">" .
    "<td width=\"2%\"></td>" .
    "<td width=\"96%\"><font size=\"-4\">&nbsp;" .
    "</font></td><td width=\"2%\"></td></tr>" .
    "</table>" .
    "</td></tr>";
if ($driver['obsolete']) {
    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
	"<td width=\"96%\" colspan=\"6\"><b><i><font size=\"-2\">" .
	"This driver is obsolete. " .
	"Recommended replacement driver: " .
	"<a href=\"{$CONF->baseURL}driver/{$driver['obsolete']}/\">" .
	"{$driver['obsolete']}</a>" .
	"</font></i></b></td>" .
	"<td width=\"2%\"></td></tr>";
}
if ($driver['shortdescription'] or $driver['supplier'] or
    (strlen($driver['manufacturersupplied']) > 0 and 
     $driver['manufacturersupplied'] != "0") or
    (strlen($driver['thirdpartysupplied']) > 0 and
     $driver['thirdpartysupplied'] == "0") or
    $driver['license'] or
    $driver['licensetext'] or $driver['licenselink'] or
    strlen($driver['nonfreesoftware']) > 0 or
    (strlen($driver['patents']) > 0 and
     $driver['patents'] != "0")) {
    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
	"<td width=\"96%\" colspan=\"6\"><font size=\"-2\">";
    if ($driver['shortdescription']) {
	$infobox .= "<b>{$driver['shortdescription']}</b><br>";
    }
    if ($driver['supplier'] or
	(strlen($driver['manufacturersupplied']) > 0 and
	 $driver['manufacturersupplied'] != "0") or
	(strlen($driver['thirdpartysupplied']) > 0 and
	 $driver['thirdpartysupplied'] == "0")) {
	if ($driver['supplier']) {
	    $infobox .= "Supplier: {$driver['supplier']}";
	    if ((strlen($driver['manufacturersupplied']) > 0 and
		 $driver['manufacturersupplied'] != "0") or
		(strlen($driver['thirdpartysupplied']) > 0 and
		 $driver['thirdpartysupplied'] == "0")) {
		$infobox .= " (printer manufacturer)";
	    }
	} else {
	    $infobox .= "Supplied by printer manufacturer";
	}
	$infobox .= "<br>";
    }
    if ($driver['license'] or
	$driver['licensetext'] or $driver['licenselink'] or
	strlen($driver['nonfreesoftware']) > 0) {
	if ($driver['license']) {
	    $infobox .= "License: {$driver['license']}";
	    if (strlen($driver['nonfreesoftware']) > 0 and
		$driver['nonfreesoftware'] != "0") {
		$infobox .= " (non-free software";
	    } else {
		$infobox .= " (free software";
	    }
	    if ($driver['licensetext'] or
		$driver['licenselink']) {
		$infobox .= ", ";
		if ($driver['licenselink']) {
		    $infobox .= "<a href=\"{$driver['licenselink']}\">";
		} else {
		    $infobox .=
			"<a href=\"{$CONF->baseURL}driver/" .
			"{$driver['name']}/license/\">";
		}
		$infobox .= "show license text</a>)";
	    } else {
		$infobox .= ")";
	    }
	} else {
	    if (strlen($driver['nonfreesoftware']) > 0 and
		$driver['nonfreesoftware'] != "0") {
		$infobox .= "This driver is non-free software";
	    } else {
		$infobox .= "This driver is free software";
	    }
	    if ($driver['licensetext'] or
		$driver['licenselink']) {
		$infobox .= " (";
		if ($driver['licenselink']) {
		    $infobox .= "<a href=\"{$driver['licenselink']}\">";
		} else {
		    $infobox .= "<a href=\"{$CONF->baseURL}driver/" .
			"{$driver['name']}/license/\">";
		}
		$infobox .= "show license text</a>).";
	    } else {
		$infobox .= ".";
	    }
	}
	$infobox .= "<br>";
    }
    if (strlen($driver['patents']) and $driver['patents'] != "0") {
	$infobox .= "<b>&nbsp;&nbsp;This driver contains algorithms " .
	    "which are (possibly) patented";
	if ($driver['licensetext'] or
	    $driver['licenselink']) {
	    $infobox .= " (See ";
	    if ($driver['licenselink']) {
		$infobox .= "<a href=\"{$driver['licenselink']}\">";
	    } else {
		$infobox .= "<a href=\"{$CONF->baseURL}driver/" .
		    "{$driver['name']}/license/\">";
	    }
	    $infobox .= "license text</a>).";
	} else {
	    $infobox .= ".";
	}
	$infobox .= "</b><br>";
    }
    $infobox .= "</font></td>" .
	"<td width=\"2%\"></td></tr>";
}
if (is_array($contacts) and count($contacts) > 0) {
    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
	"<td width=\"16%\"><font size=\"-2\">" .
	"User support:" .
	"</font></td>" .
	"<td width=\"80%\" colspan=\"5\"><font size=\"-2\">";
    foreach ($contacts as $c)
	if ($c['description'])
	    $infobox .= "<a href=\"{$c['url']}\">{$c['description']}</a>" .
		" ({$c['level']})<br>";
    $infobox .= "</td>" .
	"<td width=\"2%\"></td></tr>";
}
if ($driver['max_res_x'] or $driver['max_res_y'] or
    strlen($driver['color']) > 0 or strlen($driver['execution']) > 0) {
    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
	"<td width=\"96%\" colspan=\"6\"><font size=\"-2\">";
    if ($driver['max_res_x'] or $driver['max_res_y']) {
	if ($driver['max_res_x'])
	    $xr = $driver['max_res_x'];
	else
	    $xr = $driver['max_res_y'];
	if ($driver['max_res_y'])
	    $yr = $driver['max_res_y'];
	else
	    $yr = $driver['max_res_x'];
	$infobox .= "Max. rendering resolution: {$xr}x{$yr}dpi&nbsp;&nbsp; ";
    }
    if (strlen($driver['color']) > 0) {
	if ($driver['color'] == "0") {
	    $infobox .= "Only monochrome output&nbsp;&nbsp; ";
	} else {
	    $infobox .= "Color output&nbsp;&nbsp; ";
	}
    }
    if (strlen($driver['execution']) > 0) {
	$infobox .= "Type: {$drivertypes[$driver['execution']]}";
    }
    $infobox .= "</font></td>" .
	"<td width=\"2%\"></td></tr>";
}
if (strlen($driver['text']) > 0 or strlen($driver['lineart']) > 0 or
    strlen($driver['graphics']) > 0 or strlen($driver['photo']) > 0 or
    strlen($driver['load_time']) > 0 or strlen($driver['speed']) > 0) {
    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>";
    foreach(array('text', 'graphics', 'load_time',
		  'lineart', 'photo', 'speed') as $par) {
	$infobox .= "<td width=\"16%\"><font size=\"-2\">" .
	    "{$driverparameters[$par]}:</font></td>" .
	    "<td width=\"16%\"><font size=\"-2\">";
	if (strlen($driver[$par]) > 0) {
	    $value = $driver[$par];
	    if ($value <= 33) {
		$color = "red";
	    } elseif ($value <= 66) {
		$color = "orange";
	    } else {
		$color = "green";
	    }
	    $units = $value / 10;
	    $infobox .= "<font color=\"$color\">";
	    for ($i = 0; $i < $units; $i ++) $infobox .= "|";
	    $infobox .= "</font><font color=\"#ffffff\">";
	    for ($i = $units; $i < 10; $i ++) $infobox .= "|";
	    $infobox .= "</font>&nbsp;&nbsp;$driver[$par]</font></td>";
	} else {
	    $infobox .= "Unknown</font></td>";
	}
	if ($par == 'load_time') {
	    $infobox .= "<td width=\"2%\"></td></tr>" .
		"<tr valign=\"top\"><td width=\"2%\"></td>";
	}
    }
    $infobox .= "<td width=\"2%\"></td></tr>";
}
if ($packagedownloads != "") {
    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
	"<td width=\"16%\"><font size=\"-2\"><b>" .
	"Download:" .
	"</b></font></td>" .
	"<td width=\"80%\" colspan=\"5\"><font size=\"-2\">" .
	"Driver packages: {$packagedownloads}" .
	"<font size=\"-3\">" .
	" (<a href=\"http://www.linux-foundation.org/en/OpenPrinting/Database/DriverPackages\">" .
	"How to install</a>)</font><br>" .
	"</font></td>" .
	"<td width=\"2%\"></td></tr>";
}
if (is_array($dependencies) and count($dependencies) > 0) {
    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
	"<td width=\"16%\"><font size=\"-2\">" .
	"<b>Dependencies:</b>" .
	"</font></td>" .
	"<td width=\"80%\" colspan=\"5\"><font size=\"-2\">" .
	"To use this driver the following drivers " .
	"need also to be installed: ";
    foreach ($dependencies as $d)
	if (strlen($d['required_driver']) > 0)
	    $infobox .= "<a href=\"{$CONF->baseURL}driver/" .
		"{$d['required_driver']}\">{$d['required_driver']}</a>" .
		" ({$d['version']}), ";
    $infobox = substr($infobox, 0, -2);
    $infobox .= "</td>" .
	"<td width=\"2%\"></td></tr>";
}
$infobox .= "<tr>" .
    "<td width=\"2%\"></td>" .
    "<td width=\"96%\" colspan=\"6\"><font size=\"-4\">&nbsp;" .
    "</font></td><td width=\"2%\"></td></tr>" .
    "</table></p><p></p>";

$SMARTY->assign('driverinfobox', $infobox);

$SMARTY->display('drivers/detail.tpl');

?>
