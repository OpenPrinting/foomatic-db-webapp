<?php
set_include_path(ini_get('include_path').PATH_SEPARATOR.'inc/db');
ini_set("memory_limit","128M");
//require_once("mysqldb.php");
//require_once("driver/driver.php");
//require_once("printer/printer.php");
//require_once("option/option.php");

$UPLOADPATH="/upload/driver/";
$SCHEMADIR="/foomatic-db/xmlschema/";
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

function processtarball($driver, $op, $nonfree=false) {
    global $UPLOADPATH, $SCHEMADIR, $UNCOMPRESSEDDIR, $LOGFILE;
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
    foreach ($file_list as $file) {
	if (is_dir($file)) continue;
	if (preg_match("/\.xml$/i", $file)) {
	    $fh = fopen("$dir/$UNCOMPRESSEDDIR/$file", "r");
	    $type = "";
	    while (!feof($fh)) {
		$line = fgets($fh);
		if (preg_match("/<\s*printer\s+id=\\\"\S*\\\"\s*>/", $line)) {
		    $type = "printer";
		    break;
		} elseif (preg_match("/<\s*driver\s+id=\\\"\S*\\\"\s*>/", $line)) {
		    $type = "driver";
		    break;
		} elseif (preg_match("/<\s*option\s+type=\\\"\S*\\\"\s+id=\\\"\S*\\\"\s*>/", $line)) {
		    $type = "option";
		    break;
		}
	    }
	    fclose($fh);
	    if ($type == "") {
		fwrite($lfh,
		       "$file: WARNING - XML file of unknown type\n");
		continue;
	    }
	    exec("xmllint --noout --schema $schemadir/$type.xsd " .
		 "$dir/$UNCOMPRESSEDDIR/$file 2>&1",
		 $result, $ret_value);
	    if ($ret_value != 0) $fail = true;
	    fwrite($lfh, "$file: " . ($ret_value == 0 ? "PASS" : "FAIL") .
		   " -\n  " . implode("\n  ", $result) . "\n");
	} elseif (preg_match("/\.ppd(|.gz)$/i", $file)) {
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
    // Remove uncompressed files of the tarball
    exec("rm -rf $dir/$UNCOMPRESSEDDIR", $out = array(), $ret_value);
    if ($ret_value != 0) {
	fwrite($lfh,
               "ERROR: Cannot remove \"$UNCOMPRESSEDDIR\" directory!\n");
    }
    // Close log file
    fclose($lfh);
    return ($fail == true ? 0 : 1);
}

?>
