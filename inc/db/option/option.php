<?php
require_once("db.php");
require_once("translation.php");
require_once("option_choice.php");
require_once("option_constraint.php");

class Option
{
  // Boolean flag to determine if data is present
  private $loaded;

  // Supported option types
  public static $types = array('enum', 'bool', 'int', 'float', 'string', 'password');

  // Support execution models
  public static $execution_types = array('substitution', 'postscript', 'pjl', 'composite', 'forced_composite');

  // This contains the XML data
  public $data = array();

  // Contains of list of objects of respective data
  public $choice = array();
  public $constraint = array();

  public function __construct($data = null) {
    $this->loaded = false;		
    if ($data != null) {
      switch((string)gettype($data)) {
      case 'object':
	if (get_class($data) == "SimpleXMLElement") {
	  list(,$this->data['id']) = preg_split("/\//", $data['id']);
	  $this->data['option_type'] = (string)$data['type'];
	  $this->data['shortname'] = (string)$data->arg_shortname->en;
	  $this->data['longname'] = (string)$data->arg_longname->en;
	  if (array_key_exists('arg_execution', $data)) {
	    $this->data['option_spot'] = (string)$data->arg_execution->arg_spot;
	    $this->data['option_order'] = (string)$data->arg_execution->arg_order;
	    $this->data['option_section'] = (string)$data->arg_execution->arg_section;
	    $this->data['option_group'] = (string)$data->arg_execution->arg_group;
	    $this->data['prototype'] = (string)$data->arg_execution->arg_proto;
	    $this->data['required'] = (bool)array_key_exists('arg_required', $data->arg_execution);
	    unset($data->arg_execution->arg_spot);
	    unset($data->arg_execution->arg_order);
	    unset($data->arg_execution->arg_section);
	    unset($data->arg_execution->arg_group);
	    unset($data->arg_execution->arg_proto);
	    unset($data->arg_execution->arg_required);
	    // Remove also the entry coming from lines which are commented
	    // out in the XML file
	    unset($data->arg_execution->comment);
	    $this->data['execution'] = (string)key($data->arg_execution);
	    $this->data['execution'] = substr($this->data['execution'], 4);
	  }
	  $this->data['comments'] = (string)$data->comments->en;
	  $this->data['max_value'] = (string)$data->arg_max;
	  $this->data['min_value'] = (string)$data->arg_min;
	  $this->data['shortname_false'] = (string)$data->arg_shortname_false->en;
	  $this->data['maxlength'] = (string)$data->arg_maxlength;
	  $this->data['allowed_chars'] = (string)$data->arg_allowedchars;
	  $this->data['allowed_regexp'] = (string)$data->arg_allowedregexp;
	  $this->loaded = true;
	}
	// The option's constraints
	if ($data->constraints && $data->constraints->constraint) {
	  foreach ($data->constraints->constraint as $constraint) {
	    $this->constraint[sizeof($this->constraint)] = new OptionConstraint($this->data['id'], $constraint);
	  }
	}

	break;

      case 'array':
	$this->data = $data;
	$this->loaded = true;
	break;
      }

      // Prepare the translation data
      if ($this->data['longname']) {
	$this->translation["longname"] = new Translation($data->arg_longname, "options", array("id" => $this->data['id']), "longname");
      }
      if ($this->data['comments']) {
	$this->translation["comments"] = new Translation($data->comments, "options", array("id" => $this->data['id']), "comments");
      }

      // The option's enumerated values
      if ($data->enum_vals && $data->enum_vals->enum_val) {
	foreach ($data->enum_vals->enum_val as $choice) {
	  $this->choice[sizeof($this->choice)] = new OptionChoice($this->data['id'], $choice);
	}
      }
    }
    return $this->loaded;
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

  public function toXML($indent = 0) {
    $is = str_pad("", $indent);
    if (!$this->data['id']) return false;
    $xmlstr = "$is<option type=\"{$this->data['option_type']}\" " .
      "id=\"opt/{$this->data['id']}\">\n";
    if (strlen($this->data['shortname'])) {
      $xmlstr .= "$is  <arg_shortname>\n$is    <en>";
      $xmlstr .= htmlspecialchars($this->data['shortname']);
      $xmlstr .= "</en>\n$is  </arg_shortname>\n";
    }
    if (strlen($this->data['longname'])) {
      $xmlstr .= "$is  <arg_longname>\n$is    <en>";
      $xmlstr .= htmlspecialchars($this->data['longname']);
      $xmlstr .= "</en>\n";
      if ($this->translation["longname"])
	$xmlstr .= $this->translation["longname"]->toXML($indent + 4);
      $xmlstr .= "$is  </arg_longname>\n";
    }
    if (strlen($this->data['comments'])) {
      $xmlstr .= "$is  <comments>\n$is    <en>";
      $xmlstr .= htmlspecialchars($this->data['comments']);
      $xmlstr .= "</en>\n";
      if ($this->translation["comments"])
	$xmlstr .= $this->translation["comments"]->toXML($indent + 4);
      $xmlstr .= "$is  </comments>\n";
    }
    $exec = "";
    if (strlen($this->data['option_group'])) {
      $exec .= "$is    <arg_group>";
      $exec .= htmlspecialchars($this->data['option_group']);
      $exec .= "</arg_group>\n";
    }
    if (strlen($this->data['option_order'])) {
      $exec .= "$is    <arg_order>";
      $exec .= htmlspecialchars($this->data['option_order']);
      $exec .= "</arg_order>\n";
    }
    if (strlen($this->data['option_section'])) {
      $exec .= "$is    <arg_section>";
      $exec .= htmlspecialchars($this->data['option_section']);
      $exec .= "</arg_section>\n";
    }
    if (strlen($this->data['option_spot'])) {
      $exec .= "$is    <arg_spot>";
      $exec .= htmlspecialchars($this->data['option_spot']);
      $exec .= "</arg_spot>\n";
    }
    if ($this->data['required'] != null and $this->data['required'] == 1)
	$func .= "$is    <arg_required />\n";
    if (strlen($this->data['execution'])) {
      $exec .= "$is    <arg_";
      $exec .= htmlspecialchars($this->data['execution']);
      $exec .= " />\n";
    }
    $exec .= "$is    <arg_proto>";
    $exec .= htmlspecialchars($this->data['prototype']);
    $exec .= "</arg_proto>\n";
    if ($exec)
      $xmlstr .= "$is  <arg_execution>\n$exec$is  </arg_execution>\n";
    if ($this->constraint != false) {
      $xmlstr .= "$is  <constraints>\n";
      foreach($this->constraint as $constraint) {
	$xmlstr .=
	  $constraint->toXML($indent + 4,
			     ($this->data['option_type'] == "enum"));
      }
      $xmlstr .= "$is  </constraints>\n";
    }
    if ($this->choice != false) {
      $xmlstr .= "$is  <enum_vals>\n";
      foreach($this->choice as $choice) {
	$xmlstr .=
	  $choice->toXML($indent + 4);
      }
      $xmlstr .= "$is  </enum_vals>\n";
    }
    if (strlen($this->data['max_value'])) {
      $xmlstr .= "$is  <arg_max>";
      $xmlstr .= htmlspecialchars($this->data['max_value']);
      $xmlstr .= "</arg_max>\n";
    }
    if (strlen($this->data['min_value'])) {
      $xmlstr .= "$is  <arg_min>";
      $xmlstr .= htmlspecialchars($this->data['min_value']);
      $xmlstr .= "</arg_min>\n";
    }
    if (strlen($this->data['shortname_false'])) {
      $xmlstr .= "$is  <arg_shortname_false>\n$is  <en>";
      $xmlstr .= htmlspecialchars($this->data['shortname_false']);
      $xmlstr .= "</en>\n$is  </arg_shortname_false>\n";
    }
    if (strlen($this->data['maxlength'])) {
      $xmlstr .= "$is  <arg_maxlength>";
      $xmlstr .= htmlspecialchars($this->data['maxlength']);
      $xmlstr .= "</arg_maxlength>\n";
    }
    if (strlen($this->data['allowed_chars'])) {
      $xmlstr .= "$is  <arg_allowedchars>";
      $xmlstr .= htmlspecialchars($this->data['allowed_chars']);
      $xmlstr .= "</arg_allowedchars>\n";
    }
    if (strlen($this->data['allowed_regexp'])) {
      $xmlstr .= "$is  <arg_allowedregexp>";
      $xmlstr .= htmlspecialchars($this->data['allowed_regexp']);
      $xmlstr .= "</arg_allowedregexp>\n";
    }
    $xmlstr .= "$is</option>\n";

    return $xmlstr;
  }

  public function loadDB($id, DB $db = null) {
    if ($id == null) {
      return false;
    }

    if ($db == null) {
      $db = DB::getInstance();
    }
	
    // Clear any previous data present
    unset($this->constraint);
    unset($this->choice);

    $id = mysql_real_escape_string($id);
	
    // Load the translations
    foreach(array('longname', 'comments') as $field) {
      $this->translation[$field] = new Translation(null, "option", array("id" => $id), $field);
      $this->translation[$field]->loadDB("option", array("id" => $id), $field, $db);
    }

    // Prepare the query string for extracting main option details
    $query = "select * from options where id=\"$id\"";
    $result = $db->query($query);
	
    if ($result == null) {
      return false;
    }
    $row = mysql_fetch_assoc($result);
    $this->__construct($row);
    mysql_free_result($result);
	
    // Query string for extracting details about the option's choices
    $query = "select * from option_choice where option_id=\"{$this->data['id']}\"";
    $result = $db->query($query);
	
    if ($result) {
      while($row = mysql_fetch_assoc($result)) {
	$this->choice[sizeof($this->choice)] = new OptionChoice($this->data['id'], $row);
	$this->choice[sizeof($this->choice)-1]->loadDB($this->data['id'], $row['id']);
      }
    }
    mysql_free_result($result);
	
    // Query string for extracting details about the option's constraints
    $query = "select * from option_constraint where option_id=\"{$this->data['id']}\" and is_choice_constraint=0";
    $result = $db->query($query);
	
    if ($result) {
      while($row = mysql_fetch_assoc($result)) {
	$this->constraint[sizeof($this->constraint)] = new OptionConstraint($this->data['id'], $row);
      }
    }
    mysql_free_result($result);

    return true;
  }

  public function saveDB(DB $db = null) {
    if ($db == null) {
      $db = DB::getInstance();
    }
	
    if (!$this->loaded) {
      print "[ERROR] Option data not loaded...\n";
      return false;
    }
	
    $this->data['id'] = mysql_real_escape_string($this->data['id']);
    // Find out if there is already an entry present
    $query = "select * from options where id=\"{$this->data['id']}\"";
    $result = $db->query($query);
    $count = 0;
    if ($result) {
      $count = mysql_num_rows($result);
      mysql_free_result($result);
    } else {
      echo "[ERROR] Option :: ".$db->getError()."\n";
      return false;
    }
    if ($count) {
      if (!$this->removeFromDB($this->data['id'], $db)) {
	return false;
      }
    }

    // Prepare the query string for inserting a new record
    $query = "insert into options(";
    $fields = $values = "";
    foreach($this->data as $key=>$value) {
      $fields .= "$key,";
      if ((($key == "max_value") or
	   ($key == "min_value") or
	   ($key == "maxlength")) and
	  ($value == "")) {
	$values .= "NULL,";
      } else {
	$values .= "\"".mysql_real_escape_string($value)."\",";
      }
    }
    $fields[strlen($fields) - 1] = ')';
    $values[strlen($values) - 1] = ')';
    $query .= $fields." values(".$values;
    //		echo "\n**********************************************\n";
    //		echo "[QUERY STRING] Option :: $query\n";
    //		echo "\n**********************************************\n";
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While saving options data...\n".$db->getError()."\n";
      return false;
    }

    // Trigger the save of choice data
    if ($this->choice) {
      foreach ($this->choice as $choice) {
	if (!$choice->saveDB($db)) {
	  print "[ERROR] While saving option's choice data...\n";
	  return false;
	}
      }
    }

    // Trigger the save of constraint data
    if ($this->constraint) {
      foreach ($this->constraint as $constraint) {
	if (!$constraint->saveDB($db)) {
	  print "[ERROR] While saving option's constraint data...\n";
	  return false;
	}
      }
    }

    // Trigger the save of translation data
    if ($this->translation) {
      foreach ($this->translation as $field => $trobj) {
	if (!$trobj->saveDB($db)) {
	  echo "[ERROR] While saving options translation data for the \"$field\" field...\n".$db->getError()."\n";
	  return false;
	}
      }
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
    $query = "delete from options where id=\"$id\";";
    // Remove the main entry, this automatically removes also the
    // translations, choices, and constraints
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting printer data...\n".$db->getError()."\n";
      return false;
    }
	
    return true;
  }
}
?>
