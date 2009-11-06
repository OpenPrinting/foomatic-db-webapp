<?php
include_once("db.php");
require_once("translation.php");

class DriverSupportContact
{
  // Boolean flag to determine if data is present
  private $loaded = false;

  public $driver_id;
  public $url;
  public $level;
  public $description;

  public function __construct($driver_id, $data) {
    if ($driver_id == null || $data == null) {
      $this->loaded = false;
      return false;
    }

    $this->driver_id = $driver_id;
    $this->url = (string)$data['url'];
    $this->level = (string)$data['level'];

    switch((string)gettype($data)) {
    case 'object':
      if(get_class($data) == "SimpleXMLElement") {
	if ($data->en) {
	  $this->description = (string)$data->en;
	} else {
	  $this->description = (string)$data;
	}
	$this->loaded = true;
      }
      break;

    case 'array':
      $this->description = (string)$data['description'];
      $this->loaded = true;
      break;
    }

    // Prepare the translation data
    if ($data) {
      $this->translation["description"] = new Translation($data, "driver_support_contact", array("driver_id" => $this->driver_id, "url" => $this->url, "level" => $this->level), "description");
    }
  }

  public function loadXMLString($data) {
    $xml = simplexml_load_string($data);
    if (!$xml) {
      return false;
    }

    $this->__construct($xml);
    
    $this->loaded = true;

    return true;
  }
  
  public function toXML() {
    $xmlstr = "<supportcontact ";
    if ($this->url) {
      $xmlstr .= "url=\"{$this->url}\" ";
    }
    if ($this->level) {
      $xmlstr .= "level=\"{$this->level}\"";
    }
    $xmlstr .= ">{$this->description}</supportcontact>";

    return $xmlstr;
  }

  public function saveDB(DB $db = null) {
    if ($db == null) {
      $db = DB::getInstance();
    }
 
    if (!$this->loaded) return false;
    
    // Find out if there is already an entry present
    $query = "select * from driver_support_contact where driver_id=\"{$this->driver_id}\" and url=\"{$this->url}\" and level=\"{$this->level}\"";
    $result = $db->query($query);
    if ($result == null) {
      echo __FILE__."[ERROR]".$db->getError()."\n";
      return false;
    }
    $count = mysql_num_rows($result);
    mysql_free_result($result);

    // if there exists an entry just update its description field else insert a new record
    if ($count) {
      $query = "update driver_support_contact set description=\"{$this->description}\" where driver_id=\"{$this->driver_id}\" and url=\"{$this->url}\" and level=\"{$this->level}\"";
    } else {
      $query = "insert into driver_support_contact(driver_id,url,level,description) values(";
      $query .= "\"".mysql_real_escape_string($this->driver_id)."\",";
      $query .= "\"".mysql_real_escape_string($this->url)."\",";
      $query .= "\"".mysql_real_escape_string($this->level)."\",";
      $query .= "\"".mysql_real_escape_string($this->description)."\")";
    }
    
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] Unable to save driver's support contact: ".$db->getError()."\n";
      return false;
    }

    // Trigger the save of translation data
    if ($this->translation) {
      foreach ($this->translation as $field => $trobj) {
	if (!$trobj->saveDB($db)) {
	  echo "[ERROR] While saving driver support contact translation data for the \"$field\" field...\n".$db->getError()."\n";
	  return false;
	}
      }
    }

    return true;
  }
}
?>
