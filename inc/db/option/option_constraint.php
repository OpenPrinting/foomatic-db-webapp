<?php
require_once("db.php");

class OptionConstraint
{
  // Boolean flag to determine if data is present
  private $loaded;

  // Supported option types
  public static $types = array('enum', 'bool', 'int', 'float', 'string', 'password');

  // Support execution models
  public static $execution_types = array('substitution', 'postscript', 'pjl', 'composite', 'forced_composite');

  // This contains the XML data
  public $data = array();

  public function __construct($id, $data, $choice_id = null) {
    if ($id == null || $data == null) {
      return false;
    }
    $this->loaded = false;
    $this->data['option_id'] = $id;
    if ($choice_id) {
      $this->data['choice_id'] = $choice_id;
      $this->data['is_choice_constraint'] = true;
    }
    if ($data != null) {
      switch((string)gettype($data)) {			
	case 'object':
	  if (get_class($data) == "SimpleXMLElement") {
	    $this->data['sense'] = (string)$data['sense'];
	    $this->data['driver'] = (string)$data->driver;
	    list($prefix, $this->data['printer']) = preg_split("/\//", (string)$data->printer);
	    if (!$this->data['printer'] or ($prefix != "printer")) {
		$this->data['printer'] = (string)$data->printer;
	    }
	    if (!$this->data['printer']) {
	      if ($data->make) {
		$this->data['printer'] = (string)printerIDfromMakeModel($data->make, $data->model);
	      }
	    }
	    list($prefix, $this->data['defval']) = preg_split("/\//", (string)$data->arg_defval);
	    if (!$this->data['defval'] or ($prefix != "ev")) {
	      $this->data['defval'] = (string)$data->arg_defval;
	    }
	    $this->loaded = true;
	  }
	  break;
	case 'array':
	  $this->data['sense'] = (string)$data['sense'];
	  $this->data['printer'] = (string)$data['printer'];
	  $this->data['driver'] = (string)$data['driver'];
	  $this->data['defval'] = (string)$data['defval'];
	  $this->loaded = true;
	  break;
      }
    }
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

  /*
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

    return $this->loaded;
  }

  public function toXML($indent = 0, $is_eval_option = false) {
    $is = str_pad("", $indent);
    $xmlstr = "$is<constraint";
    if (strlen($this->data['sense'])) {
      $xmlstr .= " sense=\"{$this->data['sense']}\"";
    }
    $xmlstr .= ">\n";
    if (strlen($this->data['driver']))
      $xmlstr .= "$is  <driver>{$this->data['driver']}</driver>\n";
    if (strlen($this->data['printer'])) {
      if (substr($this->data['printer'], -1) == "-") {
	$make = substr($this->data['printer'], 0, -1);
	$xmlstr .= "$is  <make>" . htmlspecialchars($make) . "</make>\n";
      } else {
	$xmlstr .= "$is  <printer>printer/{$this->data['printer']}" .
	  "</printer>\n";
      }
    }
    if (strlen($this->data['defval']) and
	$this->data['is_choice_constraint'] != true) {
      $xmlstr .= "$is  <arg_defval>";
      if ($is_eval_option) $xmlstr .= "ev/";
      $xmlstr .= htmlspecialchars($this->data['defval']) . "</arg_defval>\n";
    }
    $xmlstr .= "$is</constraint>\n";

    return $xmlstr;
  }

  public function loadDB($id, DB $db = null) {
    if ($id == null) {
      return false;
    }

    if ($db == null) {
      $db = DB::getInstance();
    }

    $id = mysql_real_escape_string($id);

    // Prepare the query string for extracting main driver details
    $query = "select * from option_constraint where option_id=\"$id\"";
    $result = $db->query($query);

    if ($result == null) {
      return false;
    }
    $row = mysql_fetch_assoc($result);
    $this->__construct($row);
    mysql_free_result($result);

    return true;
  }

  public function saveDB(DB $db = null) {
    if ($db == null) {
      $db = DB::getInstance();
    }

    if (!$this->loaded) return false;

    $this->data['option_id'] = mysql_real_escape_string($this->data['option_id']);
    $this->data['sense'] = mysql_real_escape_string($this->data['sense']);
    // Find out if there is already an entry present
    $query = "select * from option_constraint where option_id=\"{$this->data['option_id']}\"";
    if (array_key_exists('choice_id', $this->data)) {
      $this->data['choice_id'] = mysql_real_escape_string($this->data['choice_id']);
      $query .= " and choice_id=\"{$this->data['choice_id']}\"";
    } else {
      $query .= " and (choice_id=\"\" or choice_id is null)";
    }
    if (array_key_exists('printer', $this->data)) {
      $this->data['printer'] = mysql_real_escape_string($this->data['printer']);
      $query .= " and printer=\"{$this->data['printer']}\"";
    } else {
      $query .= " and (printer=\"\" or printer is null)";
    }
    if (array_key_exists('driver', $this->data)) {
      $this->data['driver'] = mysql_real_escape_string($this->data['driver']);
      $query .= " and driver=\"{$this->data['driver']}\"";
    } else {
      $query .= " and (driver=\"\" or driver is null)";
    }
    $result = $db->query($query);
    $count = 0;
    if ($result) {
      $count = mysql_num_rows($result);
      mysql_free_result($result);
    }

    // Prepare the query string. Update if data exists or insert a new record
    if ($count) {
      $query = "update option_constraint set ";
      foreach ($this->data as $key=>$value) {
	$query .= "$key=\"".mysql_real_escape_string($value)."\",";
      }
      $query[strlen($query) - 1] = " ";
      $query .= " where option_id=\"{$this->data['option_id']}\" and sense=\"{$this->data['sense']}\"";
      if (array_key_exists('choice_id', $this->data)) {
	$query .= " and choice_id=\"{$this->data['choice_id']}\"";
      }
      if (array_key_exists('printer', $this->data)) {
	$query .= " and printer=\"{$this->data['printer']}\"";
      }
      if (array_key_exists('driver', $this->data)) {
	$query .= " and driver=\"{$this->data['driver']}\"";
      }
    } else {
      $query = "insert into option_constraint(";
      $fields = $values = "";
      foreach($this->data as $key=>$value) {
	$fields .= "$key,";
	$values .= "\"".mysql_real_escape_string($value)."\",";
      }
      $fields[strlen($fields) - 1] = ')';
      $values[strlen($values) - 1] = ')';
      $query .= $fields." values(".$values;
    }

//		echo "\n**********************************************\n";
//		echo "[QUERY STRING] OptionConstraint :: $query\n";
//		echo "\n**********************************************\n";
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While saving option's constraint data...\n".$db->getError()."\n";
      echo "[QUERY STRING] $query\n";
      return false;
    }

    return true;
  }
}
?>
