<?php
include_once('inc/common.php');
include_once('inc/db/processtarballs.php');

$SESSION->pageLock('driver_upload');

$PAGE->setPageTitle('Driver Upload');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');
$PAGE->addBreadCrumb('Upload New Driver');

$SMARTY->assign('licenseOptions', array(
                                "" => '--select a license type--',
                                "GPLv1" => 'GPLv1',
                                "GPLv2" => 'GPLv2',
                                "GPLv3" => 'GPLv3',
                                "Commercial" => 'Commercial',
                                "BSD" => 'BSD',
                                "MPL" => 'Mozilla Pulic License')
                                );
$SMARTY->assign('licenseSelect', '');

$SMARTY->assign('scaleOption', array(
                                "" => '--select a scale--',
                                "0" => '0 - Not Suitable',
                                "10" => '10',
                                "20" => '20',
                                "30" => '30',
                                "40" => '40',
                                "50" => '50 - Moderate',
                                "60" => '60',
                                "70" => '70',
                                "80" => '80',
                                "90" => '90',
                                "100" => '100 - Perfect')
                                );
$SMARTY->assign('scaleSelect', '');


$res = $DB->query("
	SELECT id, name, execution, shortdescription, pj.count as printerCount
	FROM driver 
	LEFT JOIN 
		(SELECT driver_id, count(printer_id) as count 
		 FROM driver_printer_assoc
		 GROUP BY driver_id)
		 AS pj
		 ON pj.driver_id = driver.id
	ORDER BY name 
	");
$r = $res->toArray('id');

$SMARTY->assign("drivers",$r);


///// Kevin Legacy code /////
		/*
		//$um = new UploadManager('/srv/www/lptest/freshies');
		if(isset($_GET['upload']) && $um->hasFiles()) {
			$file = $um->pop();
			
			while($um->hasFiles()) {
				// Someone tried to upload more than one file. Cheater.
				$file2 = $um->pop();
				$um->delete();
			}
			
			if(!preg_match(',^([A-Za-z0-9-]*)-([A-Za-z0-9.]*).tar.gz$,',$file->getOrigName(),$matches)) {
				echo 'File not acceptable.<br />';
				echo $file->getOrigName();
				$file->delete();
			} else {
				echo 'Name okay.<br /><br />';
				print_r($matches);
			}
			
		}
		*/
///// Kevin Legacy code /////

if(isset($_POST['submit'])){
    $error = "";
    if (strlen($_POST['licensecustom']) > 0) {
        $_POST['license'] = $_POST['licensecustom'];
    }
    if (strlen($_POST['driver_name']) <= 0) {
	if (strlen($_FILES['payload']['name']) > 0) {
	    $_POST['driver_name'] =
		preg_replace("/^(.*?)[\-_\.]\d+\..*$/", "$1", $_FILES['payload']['name']);
	    $_POST['execution'] = "tarballonly";
	} else {
	    $error = "No driver name entered!";
	}
    }
    if (preg_match("/^[A-Za-z0-9_\-]+$/", $_POST['driver_name']) == 0) {
	$error = "Driver name can only contain letters, numbers, \"-\", and \"_\"!";
    }
    $id = $_POST['driver_name'];
    $res = $DB->query("SELECT id FROM driver WHERE id=\"$id\"");
    $row = $res->getRow();
    if (strlen($row['id']) > 0) {
	$error = "Driver already exists in the database!";
    }
    if (!array_key_exists('execution', $_POST)) {
	if (strlen($_FILES['payload']['name']) > 0) {
	    $_POST['execution'] = "tarballonly";
	} else {
	    $error = "Driver execution style must be set!";
	}
    }
    if (!array_key_exists('supportlevel', $_POST) and
	strlen($_POST['supportdescription']) > 0) {
	$error = "Support level must be set!";
    }
    $tarballfailed = false;
    $check = "";
    if (strlen($_FILES['payload']['name']) > 0 and strlen($error) == 0) {
	if (strlen($_FILES['payload']['tmp_name']) > 0 and
	    $_FILES['payload']['error'] == 0 and
	    $_FILES['payload']['size'] <= $_POST['MAX_FILE_SIZE']) {
	    $dir = "upload/driver/$id";
	    $pwd = exec("pwd");
	    exec("mkdir -p $pwd/$dir",
		 $output = array(), $return_value);
	    if ($return_value != 0) {
		$error = "Problem with file upload, " .
		    "Creating directory caused error code: $return_value!"; 
	    } else {
		exec("mv " . $_FILES['payload']['tmp_name'] .
		     " $pwd/$dir/" . $_FILES['payload']['name'],
		     $output = array(), $return_value);
		if ($return_value != 0) {
		    $error = "Problem with file upload, " .
			"Copying the file caused error code: $return_value!"; 
		} else {
		    exec("chmod -R ug+rwX,o+rX $pwd/upload",
			 $output = array(), $return_value);
		    if ($return_value != 0) {
			$error = "Problem with file upload, " .
			    "Setting the file permissions caused error code: $return_value!"; 
		    } else {
			$result = processtarball($id, "check");
			if ($result == -1) {
			    $tarballfailed = true;
			    $error = "Could not check integrity of " .
				$_FILES['payload']['name'] . "\n" .
				file_get_contents("$pwd/$dir/log.txt");
			} elseif ($result == 0) {
			    $tarballfailed = true;
                            $check = "Integrity check of " .
				$_FILES['payload']['name'] . " FAILED:\n" .
				file_get_contents("$pwd/$dir/log.txt");
			} else {
			    $tarballfailed = false;
                            $check = "Integrity check of " .
				$_FILES['payload']['name'] . " PASSED\n";
			}
		    }
		}
	    }
	} else {
	    if ($_FILES['payload']['error'] != 0) { 
		$error = "Problem with file upload, error code: " .
		    $_FILES['payload']['error'];
	    } elseif ($_FILES['payload']['size'] > $_POST['MAX_FILE_SIZE']){
		$error = "Uploaded file too big: Uploaded size " .
		    $_FILES['payload']['size'] / 1024 . "KB, allowed size " .
		    $_POST['MAX_FILE_SIZE'] / 1024 . "KB.";
	    } else {
		$error = "Problem with file upload!";
	    }
	}
    }

    if (strlen($error) > 0) {
	echo "<pre>";
	print "ERROR: $error\n";
	print_r($SESSION->getUserName());
        print_r($_POST);
	print_r($_FILES);
        echo "</pre>";
	exit(0);
    }

    /**
     * Insert into driver_approval table
     */

    $today = date('Y-m-d');
    if (strtotime($_POST['release_date']) != 0) {
	$release = 
	    "\"" . date('Y-m-d', strtotime($_POST['release_date'])) . "\"";
    } else {
	$release = "null";
    }
    $user = $SESSION->getUserName();
    $DB->query("INSERT INTO driver_approval (
        id,
        contributor, 
        showentry,
        approved,
        rejected,
        approver,
        comment
    ) values (
        \"" . _mysql_real_escape_string($id) . "\", 
        \"" . _mysql_real_escape_string($user) . "\", 
        " . _mysql_real_escape_string($release) . ", 
        " . (($SESSION->checkPermission('printer_noqueue') and
	      $tarballfailed == false) ?
	     "\"" . _mysql_real_escape_string($today) . "\"" :
	     "null") . ",
        null,
        " . (($SESSION->checkPermission('printer_noqueue')  and
	      $tarballfailed == false) ?
	     "\"" . _mysql_real_escape_string($user) . "\"" :
	     "null") . ",
        \"" . _mysql_real_escape_string("TODO: Upload comment") . "\"
    )");

    /**
     * Insert into driver tables
     */
    
    $DB->query("INSERT INTO driver (id,
             name,
	     driver_group,
	     locales,
	     obsolete,
	     pcdriver,
	     url,
	     supplier,
	     thirdpartysupplied,
	     manufacturersupplied,
	     license,
	     licensetext,
	     licenselink,
	     nonfreesoftware,
	     patents,
	     shortdescription,
	     max_res_x,
	     max_res_y,
	     color,
	     text,
	     lineart,
	     graphics,
	     photo,
	     load_time,
	     speed,
	     execution,
	     no_pjl,
	     no_pageaccounting,
	     prototype,
	     pdf_prototype,
	     ppdentry,
	     comments
         ) values (\"" . _mysql_real_escape_string($id) . "\",
	     \"" . _mysql_real_escape_string($id) . "\",
	     null,
	     null,
	     \"" . _mysql_real_escape_string($_POST['obsolete']) . "\",
	     null,
	     \"" . _mysql_real_escape_string($_POST['download_url']) . "\",
	     \"" . _mysql_real_escape_string($_POST['supplier']) . "\",
	     " . (array_key_exists('manufacturersupplied', $_POST) ?
	         "0" : "1") . ",
	     " . (array_key_exists('manufacturersupplied', $_POST) ?
		  "\"" . _mysql_real_escape_string($_POST['supplier']) . "\"":
		  "null") . ",
	     \"" . _mysql_real_escape_string($_POST['license']) . "\",
	     \"" . _mysql_real_escape_string($_POST['licensetext']) . "\",
	     \"" . _mysql_real_escape_string($_POST['licenselink']) . "\",
	     " . (array_key_exists('nonfreesoftware', $_POST) ?
	         "1" : "0") . ",
	     " . (array_key_exists('patents', $_POST) ?
	         "1" : "0") . ",
	     \"" . _mysql_real_escape_string($_POST['description']) . "\",
	     " . (strlen($_POST['max_res_x']) > 0 ?
		  _mysql_real_escape_string($_POST['max_res_x']) : "null") . ",
	     " . (strlen($_POST['max_res_y']) > 0 ?
		  _mysql_real_escape_string($_POST['max_res_y']) : "null") . ",
	     " . (array_key_exists('color', $_POST) ? "1" :
		  (array_key_exists('grayscale', $_POST) ? "0" : "null")) . ",
	     " . (strlen($_POST['text']) > 0 ?
		  _mysql_real_escape_string($_POST['text']) : "null") . ",
	     " . (strlen($_POST['lineart']) > 0 ?
		  _mysql_real_escape_string($_POST['lineart']) : "null") . ",
	     " . (strlen($_POST['graphics']) > 0 ?
		  _mysql_real_escape_string($_POST['graphics']) : "null") . ",
	     " . (strlen($_POST['photo']) > 0 ?
		  _mysql_real_escape_string($_POST['photo']) : "null") . ",
	     " . (strlen($_POST['load_time']) > 0 ?
		  _mysql_real_escape_string($_POST['load_time']) : "null") . ",
	     " . (strlen($_POST['speed']) > 0 ?
		  _mysql_real_escape_string($_POST['speed']) : "null") . ",
	     " . (array_key_exists('execution', $_POST) ?
		  "\"" . _mysql_real_escape_string($_POST['execution']) .
		  "\"" : "null") . ",
	     0,
	     0,
	     null,
	     null,
	     null,
	     null
         )");

    $DB->query("INSERT INTO driver_translation (
             id, 
             lang,
             supplier,
             license,
             licensetext,
             licenselink,
             shortdescription,
             comments
         ) values (
             \"" . _mysql_real_escape_string($id) . "\", 
             \"en\", 
             \"" . _mysql_real_escape_string($_POST['supplier']) . "\",
             \"" . _mysql_real_escape_string($_POST['license']) . "\",
             \"" . _mysql_real_escape_string($_POST['licensetext']) . "\",
             \"" . _mysql_real_escape_string($_POST['licenselink']) . "\",
             \"" . _mysql_real_escape_string($_POST['description']) . "\",
             null
         )");

    if (strlen($_POST['supportdescription']) > 0) {
	$DB->query("INSERT INTO driver_support_contact (
                 driver_id, 
                 url,
                 level,
                 description
             ) values (
                 \"" . _mysql_real_escape_string($id) . "\", 
                 \"" . _mysql_real_escape_string($_POST['supporturl']) . "\",
	         " . (array_key_exists('supportlevel', $_POST) ?
		      "\"" . _mysql_real_escape_string($_POST['supportlevel']) .
		      "\"" : "null") . ",
                 \"" . _mysql_real_escape_string($_POST['supportdescription']) . "\"
             )");

	$DB->query("INSERT INTO driver_support_contact_translation (
                 driver_id,
                 url,
                 level,
                 lang,
                 description
             ) values (
                 \"" . _mysql_real_escape_string($id) . "\", 
                 \"" . _mysql_real_escape_string($_POST['supporturl']) . "\",
	         " . (array_key_exists('supportlevel', $_POST) ?
		      "\"" . _mysql_real_escape_string($_POST['supportlevel']) .
		      "\"" : "null") . ",
                 \"en\", 
                 \"" . _mysql_real_escape_string($_POST['supportdescription']) . "\"
             )");
    }

    echo "<pre>";
    print "SUCCESS\n";
    print_r($SESSION->getUserName());
    print_r($_POST);
    print_r($_FILES);
    print "$check\n";
    echo "</pre>";
    exit(0);

}
		
// Dummy function, will be removed, until problem with mysql_real_escape_string() is solved.
function _mysql_real_escape_string($str) {
    return $str;
}
		
		//$SMARTY->assign('data',$data);

		$SMARTY->assign('isLoggedIn', $SESSION->isloggedIn() );
		$auth = $USER->fetchUserRoles();
		
		$adminPerms = $USER->getPerms();
		$SMARTY->assign('isAdmin', $adminPerms['roleadmin']);

		$SMARTY->assign('isUploader', $USER->isUploader($auth) );
		$SMARTY->assign('isTrustedUploader', $USER->isTrustedUploader($auth) );
		
		$SMARTY->display('drivers/upload.tpl');

?>		
