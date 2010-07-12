<?php
include('inc/common.php');

$printertypes = array(
    "inkjet" => 'inkjet',
    "laser" => 'laser',
    "impact" => 'impact',
    "braille" => 'braille',
    "dotmatrix" => 'dot matrix',
    "led" => 'LED',
    "sublimation" => 'dye sublimation',
    "transfer" => 'thermal transfer');

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

if($SESSION->isloggedIn()){
	
    $SMARTY->assign('isLoggedIn', $SESSION->isloggedIn() );
    $auth = $USER->fetchUserRoles();

    $adminPerms = $USER->getPerms();
    $SMARTY->assign('isAdmin', $adminPerms['roleadmin']);

    $SMARTY->assign('isUploader', $USER->isUploader($auth) );
    $SMARTY->assign('isTrustedUploader', $USER->isTrustedUploader($auth) );
}

// Check whether we can already show this printer or whether it is not
// yet released. In contrary to drivers we show unapproved printers but
// add a remark that they are not approved.
$res = $DB->query("
    SELECT id FROM printer_approval
    WHERE id='".$_GET['id']."' AND
    ((rejected IS NOT NULL AND rejected!=0 AND
    rejected!='') OR
    (showentry IS NOT NULL AND
    showentry!='' AND
    showentry!=1 AND
    showentry>CAST(NOW() AS DATE)))
");
$notreleased = 0;
$row = $res->getRow();
if (count($row) == 0) {
    // Printer data (Load only if the printer is not unreleased)
    $res = $DB->query("SELECT * FROM printer WHERE make='".$_GET['manufacturer']."' AND id='".$_GET['id']."' ");
    $makes = array();
    $data = array();
    while($row = $res->getRow()){
	$data = $row;
	$printer_model = $row['model'];
	$printer_id = $row['id'];
	$default_driver = $row['default_driver'];
    }
    // Is the printer entry not yet approved?
    $res = $DB->query("
        SELECT id FROM printer_approval
        WHERE id='".$_GET['id']."' AND
        (approved IS NULL OR approved=0 OR approved='')
    ");
    $row = $res->getRow();
    if (count($row) > 0) {
	$data['unverified'] = "1";
    }
} else {
    $data = null;
    $notreleased = 1;
}
$printer_id = $_GET['id'];
$printer_make = $_GET['manufacturer'];
if (count($data) == 0) {
    // Printer not in the database or not yet released
    $printer_model = preg_replace("/_/", " ",
				  preg_replace("/^[^-]*-/", "", $printer_id));
    $default_driver = "";
    $data['url'] = "";
    $data['color'] = "";
    $data['mechanism'] = "";
    $data['res_x'] = "";
    $data['res_y'] = "";
    $data['functionality'] = "";
    $data['noentry'] = "1";
    if ($notreleased == 1) {
	$data['notreleased'] = "1";
    }
}

/**
 * Had to place down a few lines to do the db call to refactor model 
 * and url. Url now uses ID
 */

$PAGE->setPageTitle('Printer: ' . $_GET['manufacturer'] . ' ' . $printer_model);	
$PAGE->setActiveID('printer');
$PAGE->addBreadCrumb('Printers',$CONF->baseURL.'printers/');
$PAGE->addBreadCrumb($_GET['manufacturer'],
		     $CONF->baseURL."printers/manufacturer/" .
		     "{$_GET['manufacturer']}/");
$PAGE->addBreadCrumb($printer_model);	

/**
 * Get list of drivers which support this printer via driver_printer_assoc
 * table. Exclude unapproved or not yet released drivers using the
 * driver_approval table
 */

$resDriverList = $DB->query("
    SELECT driver_printer_assoc.driver_id AS id
    FROM driver_printer_assoc LEFT JOIN driver_approval 
    ON driver_printer_assoc.driver_id=driver_approval.id
    WHERE driver_printer_assoc.printer_id=\"{$printer_id}\" AND
    (driver_approval.id IS NULL OR
    (driver_approval.approved IS NOT NULL AND
    driver_approval.approved!=0 AND driver_approval.approved!='' AND
    (driver_approval.rejected IS NULL OR driver_approval.rejected=0 OR
    driver_approval.rejected='') AND
    (driver_approval.showentry IS NULL OR driver_approval.showentry='' OR
    driver_approval.showentry=1 OR
    driver_approval.showentry<=CAST(NOW() AS DATE))))
    ORDER BY id
");

/**
 * Generate one driver info box per supporting driver and stack them up in the
 * $driverinfoboxes array, putting the recommended driver to the beginning of
 * the array
 */

$driverinfoboxes = array();
$havedefdrv = 0;
$defdrvhomepage = "";
$defdrvppdlink = "";
$defdrvpackages = "";
while ($rowDriver = $resDriverList->getRow()) {
    if ($rowDriver['id']) {
	$driver_id = $rowDriver['id'];

	// Load driver
	$res = $DB->query("SELECT * FROM driver WHERE id = '?'",
			  $driver_id);
	$driver = $res->getRow();

	// Load driver printer assoc
	$resDPA = $DB->query("SELECT * 
			      FROM driver_printer_assoc 
			      WHERE driver_id=\"$driver_id\" AND  
			      printer_id=\"$printer_id\"");
	$driverPrinterAssoc = $resDPA->getRow();

	// Load support contacts for this driver
	$res = $DB->query("SELECT *
		   FROM `driver_support_contact` 
		   WHERE driver_id = '?'", $driver_id);
	$contacts = $res->toArray();

	// Load list of downloadable packages for this driver
	$res = $DB->query("SELECT *
		   FROM `driver_package` 
		   WHERE driver_id = '?'", $driver_id);
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
	    $mask = "{$driver_id};openprinting-{$driver_id};" .
		"openprinting-ppds-{$driver_id}";
	}
	$out = array();
	exec("cd foomatic; ./packageinfo '$mask'", $out, $ret_value);
	if (sizeof($out) > 0)
	    $packagedownloads = $out[0];

	// Load dependency list for this driver
	$res = $DB->query("SELECT *
		   FROM `driver_dependency` 
		   WHERE driver_id = '?'", $driver_id);
	$dependencies = $res->toArray();

	// Build driver info box
	$drvhomepage = "";
	$drvpackages = "";
	$drvppdlink = "";
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
	if ($driver == null) {
	    $infobox .= "{$driver_id}";
	} else {
	    $infobox .= "<a href=\"{$CONF->baseURL}driver/{$driver['name']}\">".
		"{$driver['name']}</a>";
	}
	$infobox .= "</b></font><font size=\"-2\">";
	if ($driver['url']) {
	    $drvhomepage = $driver['url'];
	    $infobox .= "&nbsp;&nbsp(<a href=\"{$drvhomepage}\">" .
	    "driver home page</a>)";
	}
	$infobox .= "</font></td><td width=\"2%\"></td></tr>" .
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
	if ($driver['shortdescription'] or
	    strlen($driverPrinterAssoc['comments']) > 0 or
	    strlen($driverPrinterAssoc['pcomments']) > 0 or
	    $driver['supplier'] or
	    (strlen($driver['manufacturersupplied']) > 0 and 
	     strpos($driver['manufacturersupplied'],
		    $printer_make) !== false) or
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
	    if (strlen($driverPrinterAssoc['comments']) > 0) {
		$infobox .= "{$driverPrinterAssoc['comments']}<br>";
	    }
	    if (strlen($driverPrinterAssoc['pcomments']) > 0) {
		$infobox .= "{$driverPrinterAssoc['pcomments']}<br>";
	    }
	    if ($driver['supplier'] or
		(strlen($driver['manufacturersupplied']) > 0 and
		 strpos($driver['manufacturersupplied'],
			$printer_make) !== false)) {
		if ($driver['supplier']) {
		    $infobox .= "Supplier: {$driver['supplier']}";
		    if ((strlen($driver['manufacturersupplied']) > 0 and
			 strpos($driver['manufacturersupplied'],
				$printer_make) !== false)) {
			$infobox .= " <b>(this printer's manufacturer)</b>";
		    }
		} else {
		    $infobox .=
			"<b>Supplied by this printer's manufacturer</b>";
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
			    $infobox .=
				"<a href=\"{$driver['licenselink']}\">";
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
		    $infobox .=
			"<a href=\"{$c['url']}\">{$c['description']}</a>" .
			" ({$c['level']})<br>";
	    $infobox .= "</td>" .
		"<td width=\"2%\"></td></tr>";
	}
	if ($driver['max_res_x'] or $driver['max_res_y'] or
	    strlen($driver['color']) > 0 or strlen($driver['execution']) > 0 or
	    $driverPrinterAssoc['max_res_x'] or
	    $driverPrinterAssoc['max_res_y'] or 
	    strlen($driverPrinterAssoc['color']) > 0) {
	    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
		"<td width=\"96%\" colspan=\"6\"><font size=\"-2\">";
	    if ($driver['max_res_x'] or $driver['max_res_y'] or
		$driverPrinterAssoc['max_res_x'] or
		$driverPrinterAssoc['max_res_y']) {
		if ($driverPrinterAssoc['max_res_x'])
		    $xr = $driverPrinterAssoc['max_res_x'];
		elseif ($driver['max_res_x'])
		    $xr = $driver['max_res_x'];
		elseif ($driverPrinterAssoc['max_res_y'])
		    $xr = $driverPrinterAssoc['max_res_y'];
		else
		    $xr = $driver['max_res_y'];
		if ($driverPrinterAssoc['max_res_y'])
		    $yr = $driverPrinterAssoc['max_res_y'];
		elseif ($driver['max_res_y'])
		    $yr = $driver['max_res_y'];
		elseif ($driverPrinterAssoc['max_res_x'])
		    $yr = $driverPrinterAssoc['max_res_x'];
		else
		    $yr = $driver['max_res_x'];
		$infobox .=
		    "Max. rendering resolution: {$xr}x{$yr}dpi&nbsp;&nbsp; ";
	    }
	    if (strlen($driver['color']) > 0 or
		strlen($driverPrinterAssoc['color']) > 0) {
		if ($driverPrinterAssoc['color'] == "0" or
		    (strlen($driverPrinterAssoc['color']) == 0 and 
		     $driver['color'] == "0")) {
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
	    strlen($driver['load_time']) > 0 or strlen($driver['speed']) > 0 or
	    strlen($driverPrinterAssoc['text']) > 0 or
	    strlen($driverPrinterAssoc['lineart']) > 0 or
	    strlen($driverPrinterAssoc['graphics']) > 0 or
	    strlen($driverPrinterAssoc['photo']) > 0 or
	    strlen($driverPrinterAssoc['load_time']) > 0 or
	    strlen($driverPrinterAssoc['speed']) > 0) {
	    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>";
	    foreach(array('text', 'graphics', 'load_time',
			  'lineart', 'photo', 'speed') as $par) {
		$infobox .= "<td width=\"16%\"><font size=\"-2\">" .
		    "{$driverparameters[$par]}:</font></td>" .
		    "<td width=\"16%\"><font size=\"-2\">";
		if (strlen($driver[$par]) > 0 or
		    strlen($driverPrinterAssoc[$par]) > 0) {
		    if (strlen($driverPrinterAssoc[$par]) > 0) {
			$value = $driverPrinterAssoc[$par];
		    } else {
			$value = $driver[$par];
		    }
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
	if ($packagedownloads != "" or
	    $driver['prototype'] or $driverPrinterAssoc['ppd']) {
	    $infobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
		"<td width=\"16%\"><font size=\"-2\"><b>" .
		"Download:" .
		"</b></font></td>" .
		"<td width=\"80%\" colspan=\"5\"><font size=\"-2\">";
	    if ($packagedownloads != "") {
		$drvpackages = "{$packagedownloads}" .
		    "<font size=\"-3\">" .
		    " (<a href=\"http://www.linux-foundation.org/en/OpenPrinting/Database/DriverPackages\">" .
		    "How to install</a>)</font>";
		$infobox .= "Driver packages: " . $drvpackages . "<br>";
	    }
	    if ($driver['prototype'] or $driverPrinterAssoc['ppd']) {
		$drvppdlink = "<a href=\"{$CONF->baseURL}ppd-o-matic.php?" .
		    "driver={$driver['id']}&printer={$printer_id}&" .
		    "show=1\">View PPD</a>, " .
		    "<a href=\"{$CONF->baseURL}ppd-o-matic.php?" .
		    "driver={$driver['id']}&printer={$printer_id}&" .
		    "show=0\">directly download PPD</a>";
		$infobox .= "PPD file: " . $drvppdlink . "<br>";
	    }
	    $infobox .= "</font></td>" .
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
			"{$d['required_driver']}\">" .
			"{$d['required_driver']}</a>" .
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

	if ($driver_id == $default_driver) {
	    array_unshift($driverinfoboxes,
			  "<p><b>Recommended driver:</b></p>" .
			  $infobox .
			  "<p><b>Other drivers:</b></p>");
	    $havedefdrv = 1;
	    $defdrvhomepage = $drvhomepage;
	    $defdrvppdlink = $drvppdlink;
	    $defdrvpackages = $drvpackages;
	} else {
	    array_push($driverinfoboxes, $infobox);
	}
    }
}
if (count($driverinfoboxes) == 1 and $havedefdrv == 1) {
    $driverinfoboxes[0] = preg_replace("/<p><b>Other drivers:<\/b><\/p>/", "",
				       $driverinfoboxes[0]);
}

$SMARTY->assign('driverinfoboxes', $driverinfoboxes);

// Build printer info box
$printerinfobox = "<p>" .
    "<table border=\"0\" bgcolor=\"#f5f5f5\" cellpadding=\"1\"" .
    "cellspacing=\"0\" width=\"100%\">" .
    "<tr><td colspan=\"4\">" .
    "<table border=\"0\" bgcolor=\"#eeeeee\" cellpadding=\"0\"" .
    "cellspacing=\"0\" width=\"100%\">" .
    "<tr valign=\"center\" bgcolor=\"#eeeeee\">" .
    "<td width=\"2%\"></td>" .
    "<td colspan=\"2\" width=\"96%\"><font size=\"-4\">&nbsp;" .
    "</font></td><td width=\"2%\"></td></tr>" .
    "<tr valign=\"center\" bgcolor=\"#eeeeee\">" .
    "<td width=\"2%\"></td>" .
    "<td colspan=\"2\" width=\"96%\"><font size=\"+2\"><b>";
if ($data['url']) {
    $printerinfobox .= "<a href=\"{$data['url']}\">" .
	"{$printer_make} {$printer_model}</a>";
} else {
    $printerinfobox .= "{$printer_make} {$printer_model}";
}
$printerinfobox .= "</b></font></td><td width=\"2%\"></td></tr>" .
    "<tr valign=\"center\" bgcolor=\"#eeeeee\">" .
    "<td width=\"2%\"></td>" .
    "<td colspan=\"2\" width=\"96%\"><font size=\"-4\">&nbsp;" .
    "</font></td><td width=\"2%\"></td></tr>" .
    "</table>" .
    "</td></tr>";
$printerinfobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
    "<td>";
if ($data['color'] == "1") {
    $printerinfobox .= "<font color=\"#6B44B6\">C</font>" .
	"<font color=\"#FFCC00\">o</font><font color=\"#10DC98\">l</font>" .
	"<font color=\"#1CA1C2\">o</font><font color=\"#2866EB\">r</font> ";
} elseif ($data['color'] == "0") {
    $printerinfobox .= "Black &amp; White ";
}
if (strlen($data['mechanism']) > 0) {
    $printerinfobox .= $printertypes[$data['mechanism']] . " ";
}
if (strlen($data['color']) > 0 or strlen($data['mechanism']) > 0) {
    $printerinfobox .= "printer, ";
}
if ($data['res_x'] or $data['res_y']) {
    $printerinfobox .= "max. ";
    if ($data['res_x']) {
	$printerinfobox .= "{$data['res_x']}";
	if ($data['res_y']) {
	    $printerinfobox .= "x{$data['res_y']}";
	}
    } else {
	$printerinfobox .= "{$data['res_y']}";
    }
    $printerinfobox .= " dpi, ";
}
if (strlen($data['functionality']) > 0) {
    if ($data['functionality'] == "A") {
	$printerinfobox .= "works <font color=\"green\">Perfectly</font>";
    } elseif ($data['functionality'] == "B") {
	$printerinfobox .= "works <font color=\"green\">Mostly</font>";
    } elseif ($data['functionality'] == "D") {
	$printerinfobox .= "works <font color=\"orange\">Partially</font>";
    } elseif ($data['functionality'] == "F") {
	$printerinfobox .= "this is a <font color=\"red\">Paperweight</font>";
    }
}
$printerinfobox = preg_replace("/, $/", "", $printerinfobox);
$printerinfobox .= "</td><td align=\"right\">";
if (strlen($data['functionality']) > 0) {
    if ($data['functionality'] == "A") {
	$printerinfobox .= "<img src=\"/images/icons/Linuxyes.png\">" .
	    "<img src=\"/images/icons/Linuxyes.png\">" .
	    "<img src=\"/images/icons/Linuxyes.png\">";
    } elseif ($data['functionality'] == "B") {
	$printerinfobox .= "<img src=\"/images/icons/Linuxyes.png\">" .
	    "<img src=\"/images/icons/Linuxyes.png\">";
    } elseif ($data['functionality'] == "D") {
	$printerinfobox .= "<img src=\"/images/icons/Linuxyes.png\">";
    } elseif ($data['functionality'] == "F") {
	$printerinfobox .= "<img src=\"/images/icons/Linuxno.png\">";
    }
}
$printerinfobox .= "</td><td width=\"2%\"></td></tr>";
if (strlen($default_driver) > 0) {
    $printerinfobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
	"<td colspan=\"2\">";
    $printerinfobox .= "Recommended Driver: " .
	"<a href=\"{$CONF->baseURL}driver/{$default_driver}\" " .
	"title=\"{$default_driver}\">{$default_driver}</a>";
    if (strlen($defdrvhomepage) > 0 or strlen($defdrvppdlink) > 0 or
	strlen($defdrvpackages) > 0) {
	$printerinfobox .= " (";
	if (strlen($defdrvhomepage) > 0) {
	    $printerinfobox .= "<a href=\"{$defdrvhomepage}\">Home page</a>, ";
	}
	if (strlen($defdrvppdlink) > 0) {
	    $printerinfobox .= "{$defdrvppdlink}, ";
	}
	if (strlen($defdrvpackages) > 0) {
	    $printerinfobox .= "Driver packages: {$defdrvpackages}";
	}
	$printerinfobox = preg_replace("/, $/", "", $printerinfobox);
	$printerinfobox .= ")";
    }
    $printerinfobox .= "</td><td width=\"2%\"></td></tr>";
} elseif (count($driverinfoboxes) > 0) {
    $printerinfobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
	"<td colspan=\"2\">" .
	"See drivers at the bottom of this page." .
	"</td><td width=\"2%\"></td></tr>";
}
if (count($driverinfoboxes) > 0) {
    $printerinfobox .= "<tr valign=\"top\"><td width=\"2%\"></td>" .
	"<td colspan=\"2\">" .
	"Generic Instructions: " .
	"<a href=\"/cups-doc.html\">CUPS</a>, " .
	"<a href=\"/lpd-doc.html\">LPD</a>, " .
	"<a href=\"/lpd-doc.html\">LPRng</a>, " .
	"<a href=\"/ppr-doc.html\">PPR</a>, " .
	"<a href=\"/pdq-doc.html\">PDQ</a>, " .
	"<a href=\"/direct-doc.html\">no spooler</a>" .
	"</td><td width=\"2%\"></td></tr>";
}
$printerinfobox .= "<tr>" .
    "<td width=\"2%\"></td>" .
    "<td width=\"96%\" colspan=\"2\"><font size=\"-4\">&nbsp;" .
    "</font></td><td width=\"2%\"></td></tr>" .
    "</table></p><p></p>";

$SMARTY->assign('printerinfobox', $printerinfobox);

$forumurl = "http://forums.openprinting.org/list.php?33";
$forummake = "various manufacturers";
if (preg_match("/^(brother)$/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?24";
    $forummake = "Brother";
} elseif (preg_match("/^(canon)$/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?25";
    $forummake = "Canon";
} elseif (preg_match("/^(epson)$/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?26";
    $forummake = "Epson";
} elseif (preg_match("/^(apollo|hp)$/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?20";
    $forummake = "HP and Apollo";
} elseif (preg_match("/(konica|minolta|qms)/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?27";
    $forummake = "Konica Minolta, Minolta, and QMS";
} elseif (preg_match("/^(kyocera)/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?28";
    $forummake = "Kyocera and Kyocera-Mita";
} elseif (preg_match("/^(lexmark|ibm)$/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?29";
    $forummake = "Lexmark and IBM";
} elseif (preg_match("/^(ricoh|gestetner|infoprint|infotec|lanier|nrg|savin)$/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?30";
    $forummake = "Ricoh family and OEMs (Gestetner, Infoprint, Infotec, Lanier, NRG, Ricoh, Savin)";
} elseif (preg_match("/^(samsung)$/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?31";
    $forummake = "Samsung";
} elseif (preg_match("/^(xerox|tektronix)$/i", $printer_make) != 0) {
    $forumurl = "http://forums.openprinting.org/list.php?32";
    $forummake = "Xerox and Tektronix";
}

$SMARTY->assign('forumurl',$forumurl);
$SMARTY->assign('forummake',$forummake);

$SMARTY->assign('manufacturer',$printer_make);
$SMARTY->assign('model',$printer_model);
$SMARTY->assign('data',$data);
		
$SMARTY->display('printers/detail.tpl');

	
?>
