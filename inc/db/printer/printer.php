<?php
require_once("db.php");
require_once("translation.php");
require_once("margin.php");
require_once("driver_printer_association.php");

class Printer
{
  // Boolean flag to determine if data is present
  private $loaded;

  // Static data
  public static $types = array('laser', 'led', 'inkjet', 'dotmatrix', 'impact', 'sublimation', 'transfer');
    
  public $id;
  public $make;
  public $model;
  public $pcmodel;
  public $mechanism;
  public $margins = "";
  public $url;
  public $obsolete = "";
  public $lang;
  public $autodetect;
  public $functionality;
  public $default_driver;
  public $drivers;
  public $ppdentry;
  public $contrib_url;
  public $comments;
  public $unverified;

  public function __construct($data = null) {
    $this->mechanism = array();
    $this->lang = array();
    $this->autodetect = array();
    $this->drivers = array();

    $this->loaded = false;

    if ($data != null) {
      switch((string)gettype($data)) {
      case 'object':
	if (get_class($data) == "SimpleXMLElement") {
	  $xml = &$data;
	  list(,$this->id) = preg_split("/\//", $xml['id']);
	  $this->make = (string)$xml->make;
	  $this->model = (string)$xml->model;
	  $this->url = (string)$xml->url;
	  if ($xml->obsolete) $this->obsolete = (string)$xml->obsolete['replace'];
	  $this->pcmodel = (string)$xml->pcmodel;
	  $this->functionality = (string)$xml->functionality;
	  $this->default_driver = (string)$xml->driver;
	  $this->ppdentry = (string)$xml->ppdentry;
	  $this->contrib_url = (string)$xml->contrib_url;
	  $this->comments = (string)$xml->comments->en;
	  $this->unverified = (bool)array_key_exists('unverified', $xml);

	  // <mechanism>
	  if ($xml->mechanism != false)
	    {
	      $mechanism = &$xml->mechanism;
	      $this->mechanism['resx'] = '';
	      $this->mechanism['resy'] = '';
	      if (array_key_exists("resolution", $mechanism)) {
		$this->mechanism['resx'] = (string)$mechanism->resolution->dpi->x;
		$this->mechanism['resy'] = (string)$mechanism->resolution->dpi->y;
	      }
	      $this->mechanism['color'] = (bool)array_key_exists('color', $mechanism);
	      if ($mechanism->consumables) {
		if (array_key_exists('comments', $mechanism->consumables)) {
		  $consumables = (string)$mechanism->consumables->comments->en;
		}
		if (array_key_exists('partno', $mechanism->consumables)) {
		  $consumables .= "\nPart Nos:[";
		  foreach ($mechanism->consumables->partno as $partno) {
		    $consumables .= (string)$partno;
		    $consumables .= ', ';
		  }
		  $consumables[strlen($consumables) - 2] = "]\n";
		  $consumables = "Consumables:\n".$consumables;
		  $this->comments .= "\n$consumables\n";
		}
	      }
	      if ($mechanism->margins != false) {
		$this->margins = new Margin($mechanism->margins, $this->id);
	      }
	      unset ($mechanism->color);
	      unset ($mechanism->resolution);
	      unset ($mechanism->consumables);
	      unset ($mechanism->margins);
	      $this->mechanism['type'] = (string)key($mechanism);
	    }
	  // </mechanism>

	  // <lang>
	  if ($xml->lang != false) {
	    $this->lang['postscript'] = false;
	    $this->lang['postscript_level'] = "";
	    if(array_key_exists('postscript', $xml->lang)) {
	      $this->lang['postscript'] = true;
	      $this->lang['postscript_level'] = (string)$xml->lang->postscript['level'];
	    }
	    $this->lang['pdf'] = false;
	    $this->lang['pdf_level'] = "";
	    if(array_key_exists('pdf', $xml->lang)) {
	      $this->lang['pdf'] = true;
	      $this->lang['pdf_level'] = (string)$xml->lang->pdf['level'];
	    }
	    $this->lang['pcl'] = false;
	    $this->lang['pcl_level'] = "";
	    if(array_key_exists('pcl', $xml->lang)) {
	      $this->lang[	'pcl'] = true;
	      $this->lang['pcl_level'] = (string)$xml->lang->pcl['level'];
	    }
	    $this->lang['lips'] = false;
	    $this->lang['lips_level'] = "";
	    if(array_key_exists('lips', $xml->lang)) {
	      $this->lang['lips'] = true;
	      $this->lang['lips_level'] = (string)$xml->lang->lips['level'];
	    }
	    $this->lang['escp'] = false;
	    $this->lang['escp_level'] = "";
	    if(array_key_exists('escp', $xml->lang)) {
	      $this->lang['escp'] = true;
	      $this->lang['escp_level'] = (string)$xml->lang->escp['level'];
	    }
	    $this->lang['escp2'] = false;
	    $this->lang['escp2_level'] = "";
	    if(array_key_exists('escp2', $xml->lang)) {
	      $this->lang['escp2'] = true;
	      $this->lang['escp2_level'] = (string)$xml->lang->escp2['level'];
	    }
	    $this->lang['hpgl2'] = false;
	    $this->lang['hpgl2_level'] = "";
	    if(array_key_exists('hpgl2', $xml->lang)) {
	      $this->lang['hpgl2'] = true;
	      $this->lang['hpgl2_level'] = (string)$xml->lang->hpgl2['level'];
	    }
	    $this->lang['tiff'] = false;
	    $this->lang['tiff_level'] = "";
	    if(array_key_exists('tiff', $xml->lang)) {
	      $this->lang['tiff'] = true;
	      $this->lang['tiff_level'] = (string)$xml->lang->tiff['level'];
	    }
	    $this->lang['proprietary'] = (bool)array_key_exists('proprietary', $xml->lang)?true:false;
	    $this->lang['pjl'] = (bool)array_key_exists('pjl', $xml->lang)?true:false;
	    $this->lang['text'] = "";
	    if($xml->lang->text != false && $xml->lang->text->charset != false) {
	      $this->lang['text'] = (string)$xml->lang->text->charset;
	    }
	  }
	  // </lang>
							
	  // <autodetect>
	  if ($xml->autodetect != false) {
	    if ($xml->autodetect->general != false && sizeof($xml->autodetect->general) >= 1) {
	      $this->autodetect['general'] = array();
	      $temp = &$this->autodetect['general'];
	      $temp['model'] = (string)$xml->autodetect->general->model;
	      $temp['ieee1284'] = (string)$xml->autodetect->general->ieee1284;
	      $temp['commandset'] = (string)$xml->autodetect->general->commandset;
	      $temp['description'] = (string)$xml->autodetect->general->description;
	      $temp['manufacturer'] = (string)$xml->autodetect->general->manufacturer;
	    }
	    if ($xml->autodetect->parallel != false && sizeof($xml->autodetect->parallel) >= 1) {
	      $this->autodetect['parallel'] = array();
	      $temp = &$this->autodetect['parallel'];
	      $temp['model'] = (string)$xml->autodetect->parallel->model;
	      $temp['ieee1284'] = (string)$xml->autodetect->parallel->ieee1284;
	      $temp['commandset'] = (string)$xml->autodetect->parallel->commandset;
	      $temp['description'] = (string)$xml->autodetect->parallel->description;
	      $temp['manufacturer'] = (string)$xml->autodetect->parallel->manufacturer;
	    }
	    if ($xml->autodetect->usb != false && sizeof($xml->autodetect->usb) >= 1) {
	      $this->autodetect['usb'] = array();
	      $temp = &$this->autodetect['usb'];
	      $temp['model'] = (string)$xml->autodetect->usb->model;
	      $temp['ieee1284'] = (string)$xml->autodetect->usb->ieee1284;
	      $temp['commandset'] = (string)$xml->autodetect->usb->commandset;
	      $temp['description'] = (string)$xml->autodetect->usb->description;
	      $temp['manufacturer'] = (string)$xml->autodetect->usb->manufacturer;
	    }
	    if ($xml->autodetect->snmp != false && sizeof($xml->autodetect->snmp) >= 1) {
	      $this->autodetect['snmp'] = array();
	      $temp = &$this->autodetect['snmp'];
	      $temp['model'] = (string)$xml->autodetect->snmp->model;
	      $temp['ieee1284'] = (string)$xml->autodetect->snmp->ieee1284;
	      $temp['commandset'] = (string)$xml->autodetect->snmp->commandset;
	      $temp['description'] = (string)$xml->autodetect->snmp->description;
	      $temp['manufacturer'] = (string)$xml->autodetect->snmp->manufacturer;
	    }
	  }
	  // </autodetect>
	}
	$this->loaded = true;
	break;
      }
      // Prepare the translation data
      if ($data->comments) {
	$this->translation["comments"] = new Translation($data->comments, "printer", array("id" => $this->id), "comments");
      }
      
      // <drivers>
      if ($data->drivers != false && $data->drivers->driver != false) {
	$i = 0;
	$this->drivers = array();
	foreach($data->drivers->driver as $driver) {
	  $this->drivers[$i] = new DriverPrinterAssociation($this->id, $driver, true);
	  $i ++;
	}
      }
      // </drivers>
    }
  }
    
  /**
   * Initialize class from an XML string
   * @return bool True if initialization was successful
   * @param $data string Contains the XML as a string
   */
  public function loadXMLString($data) {
    $xml = simplexml_load_string($data);
    if (!$xml) {
      return false;
    }

    $this->__construct($xml);
		
    $this->loaded = true;
		
    return true;
  }
    
  public function loadXMLFile($filename) {
    if (!file_exists($filename)) {
      return false;
    }
    $fh = fopen($filename, "r");
    if ($fh) {
      $data = fread($fh, filesize($filename));
      return $this->loadXMLString($data);
    }
    return false;
  }
	
  public function toXML($indent = 0) {
    $is = str_pad("", $indent);
    if (!$this->id) return false;
    $xmlstr = "$is<printer id=\"printer/{$this->id}\">\n";
    if (strlen($this->make))
	$xmlstr .= "$is  <make>" . htmlspecialchars($this->make) . "</make>\n";
    if (strlen($this->model)) 
      $xmlstr .= "$is  <model>" . htmlspecialchars($this->model) .
	"</model>\n";
    if (strlen($this->pcmodel))
      $xmlstr .= "$is  <pcmodel>{$this->pcmodel}</pcmodel>\n";
    $mechanism = "";
    if (strlen($this->mechanism['type']))
      $mechanism .= "$is    <{$this->mechanism['type']} />\n";
    if (strlen($this->mechanism['color']))
      $mechanism .= "$is    <color />\n";
    $res = "";
    if ($this->mechanism['resx'])
      $res .= "$is        <x>{$this->mechanism['resx']}</x>\n";
    if ($this->mechanism['resy'])
      $res .= "$is        <y>{$this->mechanism['resy']}</y>\n";
    if ($res)
      $mechanism .= "$is    <resolution>\n$is      <dpi>\n$res" .
	"$is      </dpi>\n$is    </resolution>\n";
    if ($this->margins)
      $mechanism .= $this->margins->toXML($indent + 4);
    if ($mechanism)
      $xmlstr .= "$is  <mechanism>\n$mechanism$is  </mechanism>\n";
    if (strlen($this->url))
      $xmlstr .= "$is  <url>" . htmlspecialchars($this->url) . "</url>\n";
    $lang = "";
    foreach(array('postscript', 'pdf', 'pcl', 'lips', 'escp', 'escp2',
		  'hpgl2', 'tiff') as $pdl) {
	if ($this->lang[$pdl]) {
	$lang .= "$is    <{$pdl} ";
	if (strlen($this->lang["{$pdl}_level"]))
	  $lang .= "level=\"" . htmlspecialchars($this->lang["{$pdl}_level"]) .
	     "\" ";
	$lang .= "/>\n";
      }
    }
    if ($this->lang['proprietary']) $lang .= "$is    <proprietary />\n";
    if ($this->lang['pjl']) $lang .= "$is    <pjl />\n";
    if (strlen($this->lang['text']))
	$lang .= "$is    <text>\n$is      <charset>" . $this->lang['text'] .
	    "</charset>\n$is    </text>\n";
    if ($lang)
	$xmlstr .= "$is  <lang>\n$lang$is  </lang>\n";
    $autodetect = "";
    foreach(array('general', 'parallel', 'usb', 'snmp') as $connection) {
      $components = "";
      foreach(array('ieee1284', 'commandset', 'description',
		    'manufacturer', 'model') as $component) {
	  if (strlen($this->autodetect[$connection][$component]))
	  $components .= "$is      <$component>" .
	    htmlspecialchars($this->autodetect[$connection][$component]) .
	    "</$component>\n";
      }
      if ($components)
	$autodetect .= "$is    <$connection>\n$components" .
	  "$is    </$connection>\n";
    }
    if ($autodetect)
      $xmlstr .= "$is  <autodetect>\n$autodetect" .
	"$is  </autodetect>\n";
    if (strlen($this->functionality))
      $xmlstr .= "$is  <functionality>{$this->functionality}" .
	"</functionality>\n";
    if (strlen($this->default_driver))
      $xmlstr .= "$is  <driver>{$this->default_driver}</driver>\n";
    if ($this->drivers != false) {
      $drvlist = "";
      foreach($this->drivers as $driver) {
	$drvlist .= $driver->toXML($indent + 4, true);
      }
      if ($drvlist)
	$xmlstr .= "$is  <drivers>\n" . $drvlist . "$is  </drivers>\n";
    }
    if (strlen($this->ppdentry))
      $xmlstr .= "$is  <ppdentry>" . htmlspecialchars($this->ppdentry) .
      "</ppdentry>\n";
    if (strlen($this->contrib_url))
      $xmlstr .= "$is  <contrib_url>" .
	htmlspecialchars($this->contrib_url) .
	"</contrib_url>\n";
    if (strlen($this->comments)) {
      $xmlstr .= "$is  <comments>\n$is    <en>";
      $xmlstr .= htmlspecialchars($this->comments);
      $xmlstr .= "</en>\n";
      if ($this->translation["comments"])
	$xmlstr .= $this->translation["comments"]->toXML($indent + 4);
      $xmlstr .= "$is  </comments>\n";
    }
    $xmlstr .= "$is</printer>\n";

    return $xmlstr;
  }

  public function loadDB($id, DB $db = null) {
    if ($db == null) {
      $db = DB::getInstance();
    }
		
    if ($id == "") {
      $this->loaded = false;
      return false;
    }
		
    $id = mysql_real_escape_string($id);
    $query = "select * from printer where id=\"$id\";";
    $result = $db->query($query);
    if ($result == null) {
      return false;
    }

    // Clear any previous data present
    unset($this->translation);

    // Load the translations
    $this->translation['comments'] = new Translation(null, "printer", array("id" => $id), 'comments');
    $this->translation['comments']->loadDB("printer", array("id" => $id), 'comments', $db);
		
    while($row = mysql_fetch_assoc($result)) {
      $this->id = (string)$row['id'];
      $this->make = (string)$row['make'];
      $this->model = (string)$row['model'];
      $this->pcmodel = (string)$row['pcmodel'];
      $this->url = (string)$row['url'];
      $this->functionality = (string)$row['functionality'];
      $this->default_driver = (string)$row['default_driver'];
      $this->contrib_url = (string)$row['contrib_url'];
      $this->ppdentry = (string)$row['ppdentry'];
      $this->comments = (string)$row['comments'];
      $this->unverified = (bool)$row['unverified'];

      $this->mechanism['type'] = (string)$row['mechanism'];
      $this->mechanism['color'] = (bool)$row['color'];
      $this->mechanism['resx'] = (string)$row['res_x'];
      $this->mechanism['resy'] = (string)$row['res_y'];

      $this->lang['postscript'] = (bool)$row['postscript'];
      $this->lang['postscript_level'] = (string)$row['postscript_level'];
      $this->lang['pdf'] = (bool)$row['pdf'];
      $this->lang['pdf_level'] = (string)$row['pdf_level'];
      $this->lang['pcl'] = (bool)$row['pcl'];
      $this->lang['pcl_level'] = (string)$row['pcl_level'];
      $this->lang['lips'] = (bool)$row['lips'];
      $this->lang['lips_level'] = (string)$row['lips_level'];
      $this->lang['escp'] = (bool)$row['escp'];
      $this->lang['escp_level'] = (string)$row['escp_level'];
      $this->lang['escp2'] = (bool)$row['escp2'];
      $this->lang['escp2_level'] = (string)$row['escp2_level'];
      $this->lang['hpgl2'] = (bool)$row['hpgl2'];
      $this->lang['hpgl2_level'] = (string)$row['hpgl2_level'];
      $this->lang['tiff'] = (bool)$row['tiff'];
      $this->lang['tiff_level'] = (string)$row['tiff_level'];
      $this->lang['proprietary'] = (bool)$row['proprietary'];
      $this->lang['pjl'] = (bool)$row['pjl'];
      $this->lang['text'] = (string)$row['text'];
			
      $this->autodetect['general']['ieee1284'] = (string)$row['general_ieee1284'];
      $this->autodetect['general']['commandset'] = (string)$row['general_commandset'];
      $this->autodetect['general']['description'] = (string)$row['general_description'];
      $this->autodetect['general']['manufacturer'] = (string)$row['general_manufacturer'];
      $this->autodetect['general']['model'] = (string)$row['general_model'];
      $this->autodetect['parallel']['ieee1284'] = (string)$row['parallel_ieee1284'];
      $this->autodetect['parallel']['commandset'] = (string)$row['parallel_commandset'];
      $this->autodetect['parallel']['description'] = (string)$row['parallel_description'];
      $this->autodetect['parallel']['manufacturer'] = (string)$row['parallel_manufacturer'];
      $this->autodetect['parallel']['model'] = (string)$row['parallel_model'];
      $this->autodetect['usb']['ieee1284'] = (string)$row['usb_ieee1284'];
      $this->autodetect['usb']['commandset'] = (string)$row['usb_commandset'];
      $this->autodetect['usb']['description'] = (string)$row['usb_description'];
      $this->autodetect['usb']['manufacturer'] = (string)$row['usb_manufacturer'];
      $this->autodetect['usb']['model'] = (string)$row['usb_model'];
      $this->autodetect['snmp']['ieee1284'] = (string)$row['snmp_ieee1284'];
      $this->autodetect['snmp']['commandset'] = (string)$row['snmp_commandset'];
      $this->autodetect['snmp']['description'] = (string)$row['snmp_description'];
      $this->autodetect['snmp']['manufacturer'] = (string)$row['snmp_manufacturer'];
      $this->autodetect['snmp']['model'] = (string)$row['snmp_model'];
    }

    // Prepare the query string for extracting details about the drivers that
    // work with this printer
    $query = "select * from driver_printer_assoc where printer_id=\"{$this->id}\"";
    $result = $db->query($query);

    if ($result) {
      while($row = mysql_fetch_assoc($result)) {
	$this->drivers[sizeof($this->drivers)] = new DriverPrinterAssociation($this->id, $row, true);
	$this->drivers[sizeof($this->drivers)-1]->loadDB($row['driver_id'], $this->id, true);
      }
    }
    mysql_free_result($result);

    // Load margin info
    $this->margins = new Margin(null, $id, null);
    $this->margins->loadDB($id, null);

    $this->loaded = true;
    return true;
  }
	
  public function saveDB(DB $db = null) {
    if ($db == null) {
      $db = DB::getInstance();
    }
		
    if (!$this->loaded) return false;
		
    $props = array();
    $props['id'] = (string)$this->id;
    $props['make'] = (string)$this->make;
    $props['model'] = (string)$this->model;
    $props['pcmodel'] = (string)$this->pcmodel;
    $props['url'] = (string)$this->url;
    $props['functionality'] = (string)$this->functionality;
    $props['default_driver'] = (string)$this->default_driver;
    $props['ppdentry'] = (string)$this->ppdentry;
    $props['contrib_url'] = (string)$this->contrib_url;
    $props['comments'] = (string)$this->comments;
    $props['unverified'] = (string)$this->unverified;
    $props['mechanism'] = (string)$this->mechanism['type'];
    $props['color'] = (string)$this->mechanism['color'];
    $props['res_x'] = (string)$this->mechanism['resx'];
    $props['res_y'] = (string)$this->mechanism['resy'];
    $props['postscript'] = (string)$this->lang['postscript'];
    $props['pdf'] = (string)$this->lang['pdf'];
    $props['pcl'] = (string)$this->lang['pcl'];
    $props['lips'] = (string)$this->lang['lips'];
    $props['escp'] = (string)$this->lang['escp'];
    $props['escp2'] = (string)$this->lang['escp2'];
    $props['hpgl2'] = (string)$this->lang['hpgl2'];
    $props['tiff'] = (string)$this->lang['tiff'];
    $props['proprietary'] = (string)$this->lang['proprietary'];
    $props['pjl'] = (string)$this->lang['pjl'];
    $props['postscript_level'] = (string)$this->lang['postscript_level'];
    $props['pdf_level'] = (string)$this->lang['pdf_level'];
    $props['pcl_level'] = (string)$this->lang['pcl_level'];
    $props['lips_level'] = (string)$this->lang['lips_level'];
    $props['escp_level'] = (string)$this->lang['escp_level'];
    $props['escp2_level'] = (string)$this->lang['escp2_level'];
    $props['hpgl2_level'] = (string)$this->lang['hpgl2_level'];
    $props['tiff_level'] = (string)$this->lang['tiff_level'];
    $props['text'] = (string)$this->lang['text'];
    foreach ($this->autodetect as $k=>$v) {
      $tag = &$this->autodetect[$k];
      foreach($tag as $l=>$m) {
	$props[$k."_".$l] = (string)$m; 
      }
    }
		
    // Find out if an entry of this printer exists. If yes, delete this entry
    // before creating a new one
    $query = "select make from printer where id=\"{$this->id}\"";
    $result = $db->query($query);
    $count = mysql_num_rows($result);
    mysql_free_result($result);
    if ($count) {
      if (!$this->removeFromDB($this->id, $db)) {
	return false;
      }
    }

    // Prepare the query string
    $query = "insert into printer(";
    $fields = $values = "";
    foreach ($props as $key=>$value) {
      $fields .= "$key,";
      $values .= "\"".mysql_real_escape_string($value)."\",";
    }
    $fields[strlen($fields) - 1] = ')';
    $values[strlen($values) - 1] = ')';
    $query .= $fields." values(".$values;
    // Execute the query and return the results
    $result = $db->query($query);
    if ($result == null) {
      echo "[QUERY]: $query\n";
      echo "[ERROR]: ".$db->getError()."\n";
      return false;
    }
		
    // Trigger the save of translation data
    if ($this->translation) {
      foreach ($this->translation as $field => $trobj) {
	if (!$trobj->saveDB($db)) {
	  echo "[ERROR] While saving printer translation data for the \"$field\" field...\n".$db->getError()."\n";
	  return false;
	}
      }
    }

    // Trigger the save of margins data	
    if ($this->margins) $this->margins->saveDB($db);

    // Trigger the save of associated drivers data	
    foreach($this->drivers as $driver) {
      $driver->saveDB($db);
    }

    return true;
  }

  public function removeFromDB($id, DB $db = null) {
    if ($id == null) {
      return false;
    }
	
    if ($db == null) {
      $db = DB::getInstance();
    }
	
    $id = mysql_real_escape_string($id);

    // Prepare the query string for removing the main printer entry
    $query = "delete from printer where id=\"$id\";";
    // Remove the main entry, this automatically removes also the
    // translations
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting printer data...\n".$db->getError()."\n";
      return false;
    }

    // Now delete the unprintable margin data. All printer-specific (not
    // the printer/driver-combo-specific) definitions belong to the printer
    // entry. This means that we have to delete all margin definitions with
    // the ID of this printer and no driver ID
    $query = "delete from margin where printer_id=\"$id\"" .
      "and (driver_id is null or driver_id=\"\");";
    // Execute the deletion of margin definitions. This does not delete any
    // items in other tables
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting driver data...\n".$db->getError()."\n";
    }

    // Complete the deletion by removing the printer-related printer/driver
    // association data.
    // Completely delete printer/driver associations which come only from the
    // printer entry (check also if the driver entry does not exist and
    // also delete in that case)
    $query = "delete driver_printer_assoc " .
      "from driver_printer_assoc left join driver " .
      "on driver_printer_assoc.driver_id=driver.id " .
      "where driver_printer_assoc.printer_id=\"$id\" and " .
      "(fromdriver=false or driver.id is null);";
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting driver data...\n".$db->getError()."\n";
    }
    // Remove printer-specific items from the printer/driver association
    // otherwise
    $query = "update driver_printer_assoc set " .
      "ppd=NULL, pcomments=NULL, fromprinter=false " .
      "where printer_id=\"$id\";";
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting driver data...\n".$db->getError()."\n";
    }
    $query = "update driver_printer_assoc_translation set " .
      "pcomments=NULL " .
      "where printer_id=\"$id\";";
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting driver data...\n".$db->getError()."\n";
    }
	
    return true;
  }
}
?>
