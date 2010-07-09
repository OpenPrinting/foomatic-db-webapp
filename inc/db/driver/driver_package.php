<?php
require_once("opdb.php");

class DriverPackage
{
  // Boolean flag to determine if data is present
  private $loaded = false;

  public $driver_id;
  public $scope;
  public $fingerprint;
  public $name;

  public function __construct($id, $data) {
    if ($id == null || $data == null) {
      $this->loaded = false;
      return false;
    }

    $this->driver_id = $id;
    $this->scope = (string)$data['scope'];
    $this->fingerprint = (string)$data['fingerprint'];

    switch((string)gettype($data)) {
    case 'object':
      if(get_class($data) == "SimpleXMLElement") {
	$this->name = (string)$data;
	$this->loaded = true;
      }
      break;

    case 'array':
      $this->name = (string)$data['name'];
      $this->loaded = true;
      break;
    }
  }

  public function toXML($indent = 0) {
    $is = str_pad("", $indent);
    $xmlstr = "$is<package";
    if (strlen($this->scope)) {
      $xmlstr .= " scope=\"" . htmlspecialchars($this->scope) . "\"";
    }
    if (strlen($this->fingerprint)) {
      $xmlstr .= " fingerprint=\"" . htmlspecialchars($this->fingerprint) .
	"\"";
    }
    $xmlstr .= ">" . htmlspecialchars($this->name) . "</package>\n";
    
    return $xmlstr;
  }

  public function saveDB(OPDB $db = null) {
    if ($db == null) {
      $db = OPDB::getInstance();
    }

    if (!$this->loaded) return false;

    // Find out if there is already an entry present
    $query = "select * from driver_package where driver_id=\"{$this->driver_id}\" and scope=\"{$this->scope}\" and fingerprint=\"{$this->fingerprint}\"";
    $result = $db->query($query);
    if ($result == null) {
      echo __FILE__."[ERROR]".$db->getError()."\n";
      return false;
    }
    $count = mysql_num_rows($result);
    mysql_free_result($result);

    // Insert a new record only if there are no records
    if (!$count) {
      $query =
	"insert into driver_package(driver_id,scope,fingerprint,name) values(";
      $query .= "\"".mysql_real_escape_string($this->driver_id)."\",";
      $query .= "\"".mysql_real_escape_string($this->scope)."\",";
      $query .= "\"".mysql_real_escape_string($this->fingerprint)."\",";
      $query .= "\"".mysql_real_escape_string($this->name)."\")";
    } else {
      $query = "update driver_package set name=\"{$this->name}\" where driver_id=\"{$this->driver_id}\" and scope=\"{$this->scope}\" and fingerprint=\"{$this->fingerprint}\"";
    }

    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] Unable to save driver's package data: ".$db->getError()."\n";
      return false;
    }

    return true;
  }
}
?>
