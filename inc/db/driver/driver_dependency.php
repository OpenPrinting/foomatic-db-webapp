<?php
require_once("db.php");

class DriverDependency
{
  // Boolean flag to determine if data is present
  private $loaded = false;

  public $driver_id;
  public $required_driver;
  public $version;

  public function __construct($id, $data) {
    if ($id == null || $data == null) {
      $this->loaded = false;
      return false;
    }

    $this->driver_id = $id;
    $this->version = (string)$data['version'];
	
    switch((string)gettype($data)) {
    case 'object':
      if(get_class($data) == "SimpleXMLElement") {
	$this->required_driver = (string)$data;
	$this->loaded = true;
      }
      break;

    case 'array':
      $this->required_driver =
	(string)$data['required_driver'];
      $this->loaded = true;
      break;
    }
  }

  public function toXML() {
    $xmlstr = "<requires";
    if ($this->version) {
      $xmlstr .= " version=\"{$this->version}\"";
    }
    $xmlstr .= ">{$this->required_driver}</requires>";

    return $xmlstr;
  }

  public function saveDB(DB $db = null) {
    if ($db == null) {
      $db = DB::getInstance();
    }

    if (!$this->loaded) return false;

    // Find out if there is already an entry present
    $query = "select * from driver_dependency where driver_id=\"{$this->driver_id}\" and required_driver=\"{$this->required_driver}\";";
    $result = $db->query($query);
    if ($result == null) {
      echo __FILE__."[ERROR]".$db->getError()."\n";
      return false;
    }
    $count = mysql_num_rows($result);
    mysql_free_result($result);

    // Insert a new record only if there are no records
    if ($count) {
      $query = "update driver_dependency set version=\"{$this->version}\" where driver_id=\"{$this->driver_id}\" and required_driver=\"{$this->required_driver}\"";
    } else {
      $query = "insert into driver_dependency(driver_id,required_driver,version) values(";
      $query .= "\"".mysql_real_escape_string($this->driver_id)."\",";
      $query .= "\"".mysql_real_escape_string($this->required_driver)."\",";
      $query .= "\"".mysql_real_escape_string($this->version)."\")";
    }

    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] Unable to save driver's dependency data: ".$db->getError()."\n";
      return false;
    }

    return true;
  }
}
?>
