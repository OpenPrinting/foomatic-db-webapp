<?php
  
  include_once('inc/common.php');
  include_once('inc/notifications.php');
  include_once('inc/db/processtarballs.php');
  
  // FIXME: deprecate! - replace with PDO::quote
  function my_mysql_real_escape_string($str) {
      $str = htmlspecialchars($str);
      $str = addslashes($str);
      return $str;
  }

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
    "MPL" => 'Mozilla Public License'
  ));
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
    "100" => '100 - Perfect'
  ));
  
  $SMARTY->assign('scaleSelect', '');

  $res = $DB->query('SELECT id, name, execution, shortdescription, pj.count as printerCount
  	FROM driver 
  	LEFT JOIN 
  		(SELECT driver_id, count(printer_id) as count 
  		 FROM driver_printer_assoc
  		 GROUP BY driver_id)
  		 AS pj
  		 ON pj.driver_id = driver.id
  	ORDER BY name
  ');

  $r = $res->toArray('id');

  $SMARTY->assign("drivers", $r);
  
  if (isset($_POST['submit'])) {
    $error = "";
    
    if (strlen($_POST['licensecustom']) > 0) {
      $_POST['license'] = $_POST['licensecustom'];
    }
    
    if (strlen($_POST['driver_name']) <= 0) {
    	if (strlen($_FILES['payload']['name']) > 0) {
  	    $_POST['driver_name'] = preg_replace("/^(.*?)[\-_\.]\d+\..*$/", "$1", $_FILES['payload']['name']);
  	    $_POST['execution'] = "tarballonly";
    	} else {
  	    $error = "No driver name entered!";
    	}
    }
    
    if (preg_match("/^[A-Za-z0-9_\-]+$/", $_POST['driver_name']) == 0) {
    	$error = "Driver name can only contain letters, numbers, \"-\", and \"_\"!";
    }
    
    $id = $_POST['driver_name'];
    
    $res = $DB->query("SELECT id FROM driver WHERE id=?", $id);
    
    if ($res->numRows() > 0) {
    	$error = "Driver already exists in the database!";
    }
    
    if (!array_key_exists('execution', $_POST)) {
    	if (strlen($_FILES['payload']['name']) > 0) {
  	    $_POST['execution'] = "tarballonly";
    	} else {
  	    $error = "Driver execution style must be set!";
    	}
    }
    
    if (!array_key_exists('supportlevel', $_POST) and strlen($_POST['supportdescription']) > 0) {
    	$error = "Support level must be set!";
    }
    
    $tarballfailed = false;
    $check = "";
    
    if (strlen($_FILES['payload']['name']) > 0 and strlen($error) == 0) {
    	if (strlen($_FILES['payload']['tmp_name']) > 0 and $_FILES['payload']['error'] == 0 and $_FILES['payload']['size'] <= $_POST['MAX_FILE_SIZE']) {
  	    $dir = "upload/driver/$id";
      
        // TODO: valid replacement?
        // $pwd = exec("pwd");
        $pwd = getcwd();
      
  	    if (!mkdir($pwd . "/" . $dir, 0777, true)) {
      		$error = "Problem with file upload, " . "Creating directory caused error code: $return_value!"; 
  	    } else {
      		if (!move_uploaded_file($_FILES['payload']['tmp_name'], $pwd . '/' . $dir . '/' . $_FILES['payload']['name'])) {
    		    $error = "Problem with file upload, Copying the file caused error code: $return_value!"; 
      		} else {
    		    exec("chmod -R ug+rwX,o+rX " . escapeshellarg($pwd . '/' . upload), $output = array(), $return_value);
            
    		    if ($return_value != 0) {
        			$error = "Problem with file upload, Setting the file permissions caused error code: $return_value!"; 
    		    } else {
        			$result = processtarball($id, $_POST['execution'], "check");
        			if ($result == -1) {
      			    $tarballfailed = true;
      			    $error = "Could not check integrity of " . $_FILES['payload']['name'] . "\n" .
        				file_get_contents("$pwd/$dir/log.txt");
        			} elseif ($result == 0) {
      			    $tarballfailed = true;
                $check = "Integrity check of " . $_FILES['payload']['name'] . " FAILED:\n" .
        				file_get_contents("$pwd/$dir/log.txt");
        			} else {
      			    $tarballfailed = false;
                $check = "Integrity check of " . $_FILES['payload']['name'] . " PASSED\n";
        			}
    		    }
      		}
  	    }
    	} else {
  	    if ($_FILES['payload']['error'] != 0) { 
      		$error = "Problem with file upload, error code: " . $_FILES['payload']['error'];
  	    } elseif ($_FILES['payload']['size'] > $_POST['MAX_FILE_SIZE']){
      		$error = "Uploaded file too big: Uploaded size " . ($_FILES['payload']['size'] / 1024) . "KB, allowed size " . ($_POST['MAX_FILE_SIZE'] / 1024) . "KB.";
  	    } else {
      		$error = "Problem with file upload!";
  	    }
    	}
    }
    
    if (strlen($error) > 0) {
  		$SMARTY->assign('msg','error');
  		$SMARTY->assign('error', $error);
    }

    /**
     * Insert into driver tables
     */
    
    $DB->query("INSERT INTO driver (
        id,
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
      ) values (
        \"" . my_mysql_real_escape_string($id) . "\",
        \"" . my_mysql_real_escape_string($id) . "\",
        null,
        null,
        \"" . my_mysql_real_escape_string($_POST['obsolete']) . "\",
        null,
        \"" . my_mysql_real_escape_string($_POST['download_url']) . "\",
        \"" . my_mysql_real_escape_string($_POST['supplier']) . "\",
        " . (array_key_exists('manufacturersupplied', $_POST) ?
        "0" : "1") . ",
        " . (array_key_exists('manufacturersupplied', $_POST) ?
        "\"" . my_mysql_real_escape_string($_POST['supplier']) . "\"":
        "null") . ",
        \"" . my_mysql_real_escape_string($_POST['license']) . "\",
        \"" . my_mysql_real_escape_string($_POST['licensetext']) . "\",
        \"" . my_mysql_real_escape_string($_POST['licenselink']) . "\",
        " . (array_key_exists('nonfreesoftware', $_POST) ?
        "1" : "0") . ",
        " . (array_key_exists('patents', $_POST) ?
        "1" : "0") . ",
        \"" . my_mysql_real_escape_string($_POST['description']) . "\",
        " . (strlen($_POST['max_res_x']) > 0 ?
        my_mysql_real_escape_string($_POST['max_res_x']) : "null") . ",
        " . (strlen($_POST['max_res_y']) > 0 ?
        my_mysql_real_escape_string($_POST['max_res_y']) : "null") . ",
        " . (array_key_exists('color', $_POST) ? "1" :
        (array_key_exists('grayscale', $_POST) ? "0" : "null")) . ",
        " . (strlen($_POST['text']) > 0 ?
        my_mysql_real_escape_string($_POST['text']) : "null") . ",
        " . (strlen($_POST['lineart']) > 0 ?
        my_mysql_real_escape_string($_POST['lineart']) : "null") . ",
        " . (strlen($_POST['graphics']) > 0 ?
        my_mysql_real_escape_string($_POST['graphics']) : "null") . ",
        " . (strlen($_POST['photo']) > 0 ?
        my_mysql_real_escape_string($_POST['photo']) : "null") . ",
        " . (strlen($_POST['load_time']) > 0 ?
        my_mysql_real_escape_string($_POST['load_time']) : "null") . ",
        " . (strlen($_POST['speed']) > 0 ?
        my_mysql_real_escape_string($_POST['speed']) : "null") . ",
        " . (array_key_exists('execution', $_POST) ?
        "\"" . my_mysql_real_escape_string($_POST['execution']) .
        "\"" : "null") . ",
        0,
        0,
        null,
        null,
        null,
        null
      )
    ");
    
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
        \"" . my_mysql_real_escape_string($id) . "\", 
        \"en\", 
        \"" . my_mysql_real_escape_string($_POST['supplier']) . "\",
        \"" . my_mysql_real_escape_string($_POST['license']) . "\",
        \"" . my_mysql_real_escape_string($_POST['licensetext']) . "\",
        \"" . my_mysql_real_escape_string($_POST['licenselink']) . "\",
        \"" . my_mysql_real_escape_string($_POST['description']) . "\",
        null
      )
    ");

    /**
     * Insert into driver_approval table
     */

    $today = date('Y-m-d');
    
    if (strtotime($_POST['release_date']) != 0) {
      $release = "\"" . date('Y-m-d', strtotime($_POST['release_date'])) . "\"";
    } else {
    	$release = "null";
    }
    
    $user = $SESSION->getUserName();
    $approved = ($SESSION->checkPermission('printer_noqueue') and $tarballfailed == false);
    
    $DB->query("INSERT INTO driver_approval (
        id,
        contributor,
        submitted,
        showentry,
        approved,
        rejected,
        approver,
        comment
      ) VALUES (
        \"" . my_mysql_real_escape_string($id) . "\", 
        \"" . my_mysql_real_escape_string($user) . "\", 
        \"" . $today . "\", 
        " . $release . ", 
        " . ($approved ?
        "\"" . $today . "\"" : "null") . ",
        null,
        " . ($approved ?
        "\"" . my_mysql_real_escape_string($user) . "\"" :
        "null") . ",
        \"" . my_mysql_real_escape_string($_POST['comment']) . "\"
      )
    ");

    if (strlen($_POST['supportdescription']) > 0) {
      $DB->query("INSERT INTO driver_support_contact (
          driver_id, 
          url,
          level,
          description
        ) VALUES (
          \"" . my_mysql_real_escape_string($id) . "\", 
          \"" . my_mysql_real_escape_string($_POST['supporturl']) . "\",
          " . (array_key_exists('supportlevel', $_POST) ?
          "\"" . my_mysql_real_escape_string($_POST['supportlevel']) .
          "\"" : "null") . ",
          \"" . my_mysql_real_escape_string($_POST['supportdescription']) . "\"
        )
      ");

      $DB->query("INSERT INTO driver_support_contact_translation (
          driver_id,
          url,
          level,
          lang,
          description
        ) VALUES (
          \"" . my_mysql_real_escape_string($id) . "\", 
          \"" . my_mysql_real_escape_string($_POST['supporturl']) . "\",
          " . (array_key_exists('supportlevel', $_POST) ?
          "\"" . my_mysql_real_escape_string($_POST['supportlevel']) .
          "\"" : "null") . ",
          \"en\", 
          \"" . my_mysql_real_escape_string($_POST['supportdescription']) . "\"
        )
      ");
    }

    /**
     * Upload already approved entry
     */

    $upload = "";
    
    if (strlen($_FILES['payload']['name']) > 0 and $approved) {
      $result = processtarball($id, $_POST['execution'], "apply", array_key_exists('nonfreesoftware', $_POST));
      
      if ($result == -1) {
        $tarballfailed = true;
        $upload = "ERROR: Could not add files from " . $_FILES['payload']['name'] . "\n" . file_get_contents("$pwd/$dir/log.txt");
      } elseif ($result == 0) {
        $tarballfailed = true;
        $upload = "Adding files of " . $_FILES['payload']['name'] . " FAILED:\n" . file_get_contents("$pwd/$dir/log.txt");
      } else {
        $tarballfailed = false;
        $upload = "Adding files of " . $_FILES['payload']['name'] . " PASSED\n";
      }
    }
    
    $notifications = new Notifications($CONF, $DB);
    $notifications->notifyDriverUpload($_POST['driver_name'], $user, !$approved);
    
    // send email notification of driver upload
    
    // compose email text
    $mailbody = "Date: " . $today . "\n";
    $mailbody .= "User: " . $user . "\n";
    $mailbody .= "Uploaded a driver \n";
    $mailbody .= "Name : " . $_POST['driver_name'] . "\n\n";
    $mailbody .= "This email is auto generated by OpenPrinting.org driver submission.";
    
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = $CONF->mailhost;
    $mail->SMTPAuth = true;
    $mail->Username = $CONF->mailusername;
    $mail->Password = $CONF->mailpassword;
    
    $mail->From = $CONF->mailfrom_driver;
    $mail->FromName = $CONF->mailfromname_driver;
    $mail->AddAddress($CONF->mailsendaddress_driver);
    $mail->Subject = $CONF->mailsubject_driver;
    $mail->Body = $mailbody;
  
    
    if (!$mail->Send()) {
       echo "Error sending: " . $mail->ErrorInfo;
    }
    
    $SMARTY->assign('msg','success');
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
