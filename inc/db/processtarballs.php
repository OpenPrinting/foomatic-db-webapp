<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
require_once("opdb.php");
require_once("driver/driver.php");
require_once("printer/printer.php");
require_once("option/option.php");

$UPLOADPATH="/upload/driver/";
$SCHEMADIR="/foomatic-db/xmlschema/";
$PPDTOXML="/foomatic/foomatic-db-engine/foomatic-ppd-to-xml";
$SEARCHPRINTER="/foomatic/foomatic-db-engine/foomatic-searchprinter";
$PPDBASEDIR="/foomatic-db/db/source";
$UNCOMPRESSEDDIR="uncompressed";
$LOGFILE="log.txt";

function tarballname($driver) {
    global $UPLOADPATH, $SCHEMADIR, $UNCOMPRESSEDDIR, $LOGFILE;
    $pwd = exec("pwd");
    $dir = $pwd . $UPLOADPATH . $driver;
    if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
	    while (($file = readdir($dh)) !== false) {
		if ($file == "." or $file == ".." or
		    $file == "$UNCOMPRESSEDDIR" or
		    preg_match("/\.txt$/i", $file)) continue;
		if (strlen($file) > 0 and preg_match("/\./", $file)) {
		    closedir($dh);
		    return $file;
		}
	    }
	    closedir($dh);
	}
    }
    return false;
}

function processtarball($driver, $drivertype, $op, $nonfree=false) {
    global $UPLOADPATH, $SCHEMADIR, $PPDTOXML, $SEARCHPRINTER, $PPDBASEDIR, 
	$UNCOMPRESSEDDIR, $LOGFILE;
    $pwd = exec("pwd");
    $dir = $pwd . $UPLOADPATH . $driver;
    $lfh = fopen("$dir/$LOGFILE", "w");
    if (!$lfh) {
	// Cannot create log file
	return -1;
    }
    $tarball = tarballname($driver);
    if ($tarball === false) {
	fwrite($lfh,
	       "ERROR: No tarball/archive/package found for this driver!\n");
	fclose($lfh);
	return -1;
    }
    $file_list = array();
    if (is_dir($dir . "/$UNCOMPRESSEDDIR")) {
	exec("rm -rf $dir/$UNCOMPRESSEDDIR", $out = array(), $ret_value);
	if ($ret_value != 0) {
	    fwrite($lfh,
	       "ERROR: Cannot remove old \"$UNCOMPRESSEDDIR\" directory!\n");
	    fclose($lfh);
            return -1;
	}
    }
    if (!is_dir($dir . "/$UNCOMPRESSEDDIR")) {
	// Uncompress the tarball
	exec("mkdir -p $dir/$UNCOMPRESSEDDIR", $out = array(), $ret_value);
	if ($ret_value == 0) {
	    // Uncompress the archive and generate a list of files contained
	    // in it
	    if (preg_match("/\.(tar\.gz|tgz)$/i", $tarball)) {
		exec("cd $dir/$UNCOMPRESSEDDIR; tar -xvzf ../$tarball",
		     $file_list, $ret_value);
	    } elseif (preg_match("/\.tar\.bz2$/i", $tarball)) {
		exec("cd $dir/$UNCOMPRESSEDDIR; tar -xvjf ../$tarball",
		     $file_list, $ret_value);
	    } elseif (preg_match("/\.zip$/i", $tarball)) {
		exec("cd $dir/$UNCOMPRESSEDDIR; unzip ../$tarball | perl -p -e 's/^\s*\S+:\s+//' | grep -v ../$tarball",
		     $file_list, $ret_value);
	    } elseif (preg_match("/\.rpm$/i", $tarball)) {
		exec("cd $dir/$UNCOMPRESSEDDIR; rpm2cpio ../$tarball | cpio -i --make-directories --list 2>/dev/null",
		     $file_list, $ret_value);
	    } elseif (preg_match("/\.deb$/i", $tarball)) {
		exec("dpkg -X ../$tarball $dir/$UNCOMPRESSEDDIR/ | grep -v '/$'",
		     $file_list, $ret_value);
	    }
	    if ($ret_value != 0) {
		fwrite($lfh,
		       "ERROR: Could not uncompress $tarball!\n");
		fclose($lfh);
		return -1;
	    }
	} else {
	    fwrite($lfh,
	       "ERROR: Cannot create \"$UNCOMPRESSEDDIR\" directory!\n");
	    fclose($lfh);
	    return -1;
	}
    } else {
	fwrite($lfh,
	       "ERROR: Old \"$UNCOMPRESSEDDIR\" directory did not get removed!\n");
	fclose($lfh);
	return -1;
    }
    // Go through all files of the tarball to check them
    $schemadir = $pwd . $SCHEMADIR;
    $fail = false;
    $driverfree = ($nonfree === false ? true : false);
    $numdriverentries = ($drivertype == "tarballonly" ? 0 : 1);
    foreach ($file_list as $file) {
	if (is_dir("$dir/$UNCOMPRESSEDDIR/$file")) continue;
	if (preg_match("/\.xml$/i", $file)) {
	    $fh = fopen("$dir/$UNCOMPRESSEDDIR/$file", "r");
	    $type = "";
	    while (!feof($fh)) {
		$line = fgets($fh);
		if (preg_match("/<\s*printer\s+id=\\\"printer\/(\S*)\\\"\s*>/",
			       $line, $res)) {
		    $type = "printer";
		    $filename = basename($file, ".xml");
		    if ($res[0] != $filename) {
			$fail = true;
			fwrite($lfh, "$file: FAIL -\n" .
			       "  Printer ID \"{$res[0]}\" and printer file name do not match!\n");
		    }
		    break;
		} elseif (preg_match("/<\s*driver\s+id=\\\"driver\/(\S*)\\\"\s*>/",
				     $line, $res)) {
		    $type = "driver";
		    $numdriverentries ++;
		    $filename = basename($file, ".xml");
		    if ($res[0] != $filename) {
			$fail = true;
			fwrite($lfh, "$file: FAIL -\n" .
			       "  Driver ID \"{$res[0]}\" and driver file name do not match!\n");
		    }
		    if ($filename != $driver) {
			$fail = true;
			fwrite($lfh, "$file: FAIL -\n" .
			       "  Driver XML file for another than the uploaded driver found!\n");
		    }
		    break;
		} elseif (preg_match("/<\s*option\s+type=\\\"\S*\\\"\s+id=\\\"opt\/(\S*)\\\"\s*>/",
				     $line, $res)) {
		    $type = "option";
		    if ($res[0] != $filename) {
			$fail = true;
			fwrite($lfh, "$file: FAIL -\n" .
			       "  Option ID \"{$res[0]}\" and option file name do not match!\n");
		    }
		    break;
		}
	    }
	    fclose($fh);
	    if ($type == "") {
		fwrite($lfh,
		       "$file: WARNING - XML file of unknown type\n");
		continue;
	    }
	    $result = array();
	    exec("xmllint --noout --schema $schemadir/$type.xsd " .
		 "$dir/$UNCOMPRESSEDDIR/$file 2>&1",
		 $result, $ret_value);
	    if ($ret_value != 0) $fail = true;
	    fwrite($lfh, "$file: " . ($ret_value == 0 ? "PASS" : "FAIL") .
		   " -\n  " . implode("\n  ", $result) . "\n");
	} elseif (preg_match("/\.ppd(|\.gz)$/i", $file)) {
	    $result = array();
	    exec("cupstestppd -W filters -W profiles " .
		 "$dir/$UNCOMPRESSEDDIR/$file 2>&1",
		 $result, $ret_value);
	    if ($ret_value != 0) $fail = true;
	    fwrite($lfh, "$file: " . ($ret_value == 0 ? "PASS" : "FAIL") .
		   " -\n  " . implode("\n  ", $result) . "\n");
	} else {
	    fwrite($lfh,
		   "$file: WARNING - File of unknown type\n");
	}
    }
    if ($numdriverentries < 1) {
	$fail = true;
	fwrite($lfh, "$file: FAIL -\n" .
	       "  Tarball-only upload without driver XML file!\n");
    } elseif ($numdriverentries > 1) {
	$fail = true;
	fwrite($lfh, "$file: FAIL -\n" .
	       "  Upload contains more than one driver entry!\n");
    }
    // Apply content of the tarball to the database 
    if ($op == "apply" and $fail == false) {
	// Driver entry from input form must be in MySQL DB already
	// - Go through all XML files
	foreach ($file_list as $file) {
	    if (is_dir("$dir/$UNCOMPRESSEDDIR/$file")) continue;
	    if (preg_match("/\.xml$/i", $file)) {
		//    o Enter them into the MySQL database
		$error = false;
		$xml = file_get_contents("$dir/$UNCOMPRESSEDDIR/$file");
		if (preg_match("/<\s*printer\s+id=\\\"printer\/(\S*)\\\"\s*>/",
			       $xml, $res)) {
		    $printer_id = $res[0];
		    $printer = new Printer();
		    $error = !$printer->loadXMLString($xml);
		    if (!$error) {
			//    o Printer XML: Add relationship to this driver.
			$need_to_add = false;
			if (array_key_exists("drivers", $printer)) {
			    $need_to_add = true;
			    foreach($printer->drivers as $drv) {
				if ($drv->data['driver_id'] == $driver) {
				    $need_to_add = false;
				}
			    }
			} else {
			    $printer->drivers = array();
			    $need_to_add = true;
			}
			if ($need_to_add == true) {
			    $entry = array();
			    $entry['driver_id'] = $driver;
			    $entry['ppd'] = "";
			    $entry['pcomments'] = "";
			    $entry['fromprinter'] = 1;
			    $printer->drivers[sizeof($printer->drivers)] =
				new DriverPrinterAssociation($printer_id,
							     $entry, true);
			}
			$error = !$printer->saveDB();
		    }
		} elseif (preg_match("/<\s*driver\s+id=\\\"driver\/(\S*)\\\"\s*>/",
				     $xml, $res)) {
		    $drv = new Driver();
		    $error = !$drv->loadXMLString($xml);
		    if (!$error) {
			//    o Determine the driver type
			$drivertype = $drv->execution;
			$driverfree =
			    ($drv->nonfreesoftware === false ?
			     true : false);
			$error = !$drv->saveDB();
		    }
		} elseif (preg_match("/<\s*option\s+type=\\\"\S*\\\"\s+id=\\\"opt\/(\S*)\\\"\s*>/",
				     $xml, $res)) {
		    $option = new Option();
		    $error = !$option->loadXMLString($xml);
		    if (!$error) {
			$error = !$option->saveDB();
		    }
		} else {
		    $error = true;
		}
		if (!$error) {
		    fwrite($lfh,
			   "$file: Successfully imported into the MySQL database.\n");
		} else {
		    $fail = true;
		    fwrite($lfh,
			   "$file: FAIL - Import into the MySQL database failed!\n");
		}
	    }
	}
	// - Go through all PPD files
	$ppdtoxml = $pwd . $PPDTOXML;
	$searchprinter = $pwd . $SEARCHPRINTER;
	$ppdbasedir = $pwd . $PPDBASEDIR;
	$ppdstocheckin = false;
	foreach ($file_list as $file) {
	    if (is_dir("$dir/$UNCOMPRESSEDDIR/$file")) continue;
	    if (preg_match("/\.ppd(|\.gz)$/i", $file)) {
		//    o Use Foomatic tool to convert the PPD to printer XMLs.
		//      Check the XMLs whether the corresponding printers
		//      are already in the database. If there is no
		//      corresponding printer entry in the database add
		//      this printer, otherwise merge the data of the PPD
		//      into the existing printer entries. 
		//      As we have already imported the printer XMLs of this
		//      tarball, they are taken into account in this process
		//      and no duplicates get created.
		//      Import the generated/modified printer data into the
		//      MySQL DB.
		$l = (($drivertype == "postscript" or
		       $drivertype == "ghostscript") ? "-l" : "");
		exec("$ppdtoxml -d $driver $l -b $ppdbasedir -f $dir -x " .
		     "$dir/$UNCOMPRESSEDDIR/$file", 
		     $out = array(), $ret_value);
		$ppdlocation = "";
		if ($ret_value == 0) {
		    if ($dh = opendir($dir)) {
			while (($xmlfile = readdir($dh)) !== false) {
			    if ($xmlfile == "." or $xmlfile == ".." or
				!preg_match("/\.xml$/i", $xmlfile)) continue;
			    $xml = file_get_contents("$dir/$xmlfile");
			    if (!$xml) {
				fwrite($lfh,
				       "ERROR: Cannot read the printer XML file $xmlfile from $file!\n");
				continue;
			    }
			    if (preg_match("/<ppd>([^<]+)<\/ppd>/",
					   $xml, $res))
				$ppdlocation = $res[0];
			    $printer = new Printer();
			    $error = !$printer->loadXMLString($xml);
			    if ($error) {
				fwrite($lfh,
				       "ERROR: Cannot parse the printer XML file $xmlfile from $file!\n");
				continue;
			    }
			    $printermake = $printer->make;
			    $result = array();
			    exec("$searchprinter -m4 -d1 " .
				 "\"{$printer->make}|{$printer->model}\"",
				 $result, $ret_value);
			    if ($ret_value != 0) {
				fwrite($lfh,
				       "ERROR: Cannot search database for printer data corresponding to the printer XML file $xmlfile from $file!\n");
				$fail = true;
				continue;
			    }
			    $found = false;
			    foreach($result as $pid) {
				if (!preg_match("/\S/", $pid)) continue;
				$printer2 = new Printer();
				$error = !$printer2->loadDB($pid);
				if ($error or $printer2->id != $pid) {
				    fwrite($lfh,
					   "ERROR: Cannot read database entry for the printer ID \"$pid\" while processing $xmlfile generated from $file!\n");
				    continue;
				}
				$found = true;
				foreach(array('postscript', 'pdf', 'pcl',
					      'lips', 'escp', 'escp2',
					      'hpgl2', 'tiff') as $pdl) {
				    if (!$printer2->lang[$pdl] and
					$printer->lang[$pdl])
					$printer2->lang[$pdl] =
					    $printer->lang[$pdl];
				    if ($printer2->lang[$pdl] and
					$printer->lang[$pdl] and
					((strlen($printer2->lang["{$pdl}_level"]) == 0 and
					  strlen($printer2->lang["{$pdl}_level"]) != 0) or
					 (strlen($printer2->lang["{$pdl}_level"]) != 0 and
					  strlen($printer2->lang["{$pdl}_level"]) != 0 and
					  $printer->lang["{$pdl}_level"] >
					  $printer2->lang["{$pdl}_level"])))
					$printer2->lang["{$pdl}_level"] =
					    $printer->lang["{$pdl}_level"];
				}
				foreach(array('ieee1284', 'commandset',
					      'description', 'manufacturer',
					      'model') as $component) {
				    if (strlen($printer2->autodetect['general'][$component]) == 0 and
					strlen($printer->autodetect['general'][$component]) != 0)
					$printer2->autodetect['general'][$component] =
					    $printer->autodetect['general'][$component];
				}
				if (strlen($printer2->default_driver) == 0)
				    $printer2->default_driver = $driver;
				if ($printer->drivers != false)
				    foreach($printer->drivers as $drv) {
					$drvfound = false;
					if ($printer2->drivers != false)
					    foreach($printer2->drivers as $drv2)
						if ($drv2->data['driver_id'] ==
						    $drv->data['driver_id']) {
						    $drvfound = 1;
						    break;
						}
					if (!$drvfound) {
					    if ($printer2->drivers == false)
						$printer2->drivers = array();
					    $printer2->drivers[sizeof($printer2->drivers)] =
						new DriverPrinterAssociation($printer2->id, null, true);
					    $drv2 = $printer2->drivers[sizeof($printer2->drivers)-1];
					    $drv2->data['driver_id'] =
						$drv->data['driver_id'];
					    $drv2->data['printer_id'] =
						$printer2->id;
					}
					if (strlen($drv->data['ppd'])) {
					    $drv2->data['ppd'] =
						$drv->data['ppd'];
					    $drv2->data['fromprinter'] =
						true;
					}
				    }
				$error = !$printer2->saveDB();
				if ($error) {
				    fwrite($lfh,
					   "ERROR: Failed to add info from the PPD file $file to the printer {$printer2->make} {$printer2->model} in the MySQL database!\n");
				    $fail = true;
				} else {
				    fwrite($lfh,
					   "Updated the database entry for the {$printer2->make} {$printer2->model} successfully with the information from the PPD file $file!\n");
				}
			    }
			    if (!$found) {
				$error = !$printer->saveDB();
				if ($error) {
				    fwrite($lfh,
					   "ERROR: Failed to add the printer {$printer->make} {$printer->model} derived from the PPD $file to the MySQL database!\n");
				    $fail = true;
				} else {
				    fwrite($lfh,
					   "Added a database entry for the {$printer2->make} {$printer2->model} successfully based on the information from the PPD file $file!\n");
				}
			    }
			}
			closedir($dh);
		    }
		} else {
		    fwrite($lfh,
			   "ERROR: Cannot generate a printer XML file from $file!\n");
		    $fail = true;
		}
		//    o Check the PPD itself into the BZR if the driver type is
		//      PostScript or Ghostscript built-in.
		if ($drivertype == "postscript" or
		    $drivertype == "ghostscript") {
		    $ret_value = 0;
                    if (preg_match("/\.ppd\.gz$/i", $file)) {
                        exec("gunzip $dir/$UNCOMPRESSEDDIR/$file",
			     $out = array(), $ret_value);
			if ($ret_value == 0)
			    $file = substr($file, 0, -3);
		    }
		    if ($ret_value == 0) {
			exec("mkdir -p $dir/$UNCOMPRESSEDDIR/" .
			     preg_replace(":/[^/]+\.ppd.*$:", "",
					  $ppdlocation),
			     $out = array() , $ret_value);
			if ($ret_value == 0)
			    exec("mv $dir/$UNCOMPRESSEDDIR/$file " .
				 "$dir/$UNCOMPRESSEDDIR/$ppdlocation 2>&1",
				 $out = array(), $ret_value);
			if ($ret_value == 0) {
			    $ppdstocheckin = true;
			    fwrite($lfh,
				   "Added $file for BZR check-in!\n");
			} else {
			    fwrite($lfh,
				   "ERROR: Cannot put $file into place for BZR check-in!\n");
			    $fail = true;
			}
		    } else {
			fwrite($lfh,
			       "ERROR: Cannot uncompress $file!\n");
			$fail = true;
		    }
		}
	    }
	}
	if ($ppdstocheckin == true) {
	    $f = ($driverfree === true ? "free" : "nonfree");
	    $result = array();
	    exec("$pwd/maint/scripts/updatebzrfrommysql --ppd-$f " .
		 "$dir/$UNCOMPRESSEDDIR/PPD $driver",
		 $result, $ret_value);
	    fwrite($lfh,
		   "Checking new PPD files into the BZR repository\n");
	    foreach ($result as $line) {
		fwrite($lfh,
		       "   $line\n");
	    }
	    if ($ret_value == 0) {
		fwrite($lfh,
		       "   --> SUCCESS\n");
	    } else {
		fwrite($lfh,
		       "   --> ERROR: $ret_value\n");
	    }
	}
    }
    // Remove uncompressed files of the tarball
    $result = array();
    exec("rm -rf $dir/$UNCOMPRESSEDDIR", $result, $ret_value);
    if ($ret_value != 0) {
	fwrite($lfh,
	       "ERROR: Cannot remove \"$UNCOMPRESSEDDIR\" directory!\n");
    }
    // Close log file
    fclose($lfh);
    return ($fail == true ? 0 : 1);
}

?>
