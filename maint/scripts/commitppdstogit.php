<?php
// Script for automatically checking in newly uploaded PPD files
// (in upload/driver/<driver>/PPD) to the git repositories of
// foomatic-db and foomatic-db-nonfree. To be called via

// php /srv/www/openprinting/maint/scripts/commitppdstogit.php

// This script should be run as a cron job once a minute

$BASE="/srv/www/openprinting";
$UPLOADPATH="/upload/driver";
$LOGFILE="log.txt";
$LOCKFILE="/var/lock/commitppdstogit";

# Use a lock file to make sure that never more than one instance of this
# program is running
if (file_exists($LOCKFILE)) {
    # Another instance of this program is still running, so exit silently here
    exit(0);
}
$pid = getmypid();
if (file_put_contents($LOCKFILE, $pid, LOCK_EX) === false) {
    # Do not run the program if the log file cannot be created.
    fwrite(STDERR, "ERROR: Cannot create lock file for commitppdstogit, process ID $pid!\n");
    exit(1);
}

$dir = $BASE . $UPLOADPATH;
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
	while (($driver = readdir($dh)) !== false) {
	    if ($driver == "." or $driver == "..") continue;
	    if (strlen($driver) > 0) {
		$f = file_get_contents("$dir/$driver/ppdcommit");
		if (strlen($f) > 0 and ($f == "free" or $f == "nonfree")) {
		    $lfh = fopen("$dir/$driver/$LOGFILE", "a");
		    if (!$lfh) {
			// Cannot write to log file
			exit(1);
		    }
		    fwrite($lfh,
			   "\nGit check-in of the PPD files via cron job\n");
		    $result = array();
		    exec("$BASE/maint/scripts/updategitfrommysql --ppd-$f " .
			 "$dir/$driver/PPD $driver",
			 $result, $ret_value);
		    fwrite($lfh,
			   "Checking new PPD files for $driver into the Git repository\n");
		    foreach ($result as $line) {
			fwrite($lfh,
			       "   $line\n");
		    }
		    if ($ret_value == 0) {
			fwrite($lfh,
			       "   --> SUCCESS\n");
			$result = array();
			exec("rm -rf $dir/$driver/PPD", $result, $ret_value);
			if ($ret_value != 0) {
			    fwrite($lfh,
				   "ERROR: Cannot remove \"PPD\" directory for driver \"$driver\"!\n");
			}
			$result = array();
			exec("rm $dir/$driver/ppdcommit", $result, $ret_value);
			if ($ret_value != 0) {
			    fwrite($lfh,
				   "ERROR: Cannot remove ppdcommit file for driver \"$driver\"!\n");
			}
		    } else {
			fwrite($lfh,
			       "   --> ERROR: $ret_value\n");
		    }
		    fclose($lfh);
		}
	    }
	}
	closedir($dh);
    }
}

# Remove the lock file
if (unlink($LOCKFILE) === false) {
    # Report if the lock file could not get removed
    fwrite(STDERR, "ERROR: Cannot remove lock file of commitppdstogit, process ID $pid!\n");
    exit(1);
}

exit(0);

?>
