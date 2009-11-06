<?php
require_once("db.php");
class Margin
{
  // Boolean flag to determine if data is present
  private $loaded;

  // ID of the entity to which this margin entry belongs
  public $driver_id;
  public $printer_id;

  public $unit;
  public $absolute;
  public $top;
  public $left;
  public $right;
  public $bottom;
  public $exception;

  public function __construct($data, $printer_id = null, $driver_id = null) {
	
    if ($printer_id == null && $driver_id == null) {
      $this->loaded = false;
      return false;
    }

    $this->printer_id = $printer_id;
    $this->driver_id = $driver_id;
	
    switch((string)gettype($data)) {
    case 'object':
      if (get_class($data) == "SimpleXMLElement") {
	if ($data->general != false)
	  {
	    $this->unit = (string)$data->general->unit;
	    $this->absolute = (bool)array_key_exists('absolute', $data->general);
	    $this->top = (string)$data->general->top;
	    $this->left = (string)$data->general->left;
	    $this->right = (string)$data->general->right;
	    $this->bottom = (string)$data->general->bottom;
	  }
	if ($data->exception != false)
	  {
	    $this->exception = array ();
	    $exceptions = &$this->exception;
	    foreach ($data->exception as $ex)
	      {
		$j = sizeof($exceptions);
		if (sizeof($ex) >= 1)
		  {
		    $exceptions[$j]['pagesize'] = (string)$ex['PageSize'];
		    $exceptions[$j]['unit'] = (string)$ex->unit;
		    $exceptions[$j]['absolute'] = (bool)array_key_exists('absolute', $ex);
		    $exceptions[$j]['top'] = (string)$ex->top;
		    $exceptions[$j]['left'] = (string)$ex->left;
		    $exceptions[$j]['right'] = (string)$ex->right;
		    $exceptions[$j]['bottom'] = (string)$ex->bottom;
		  }
	      }
	  }
      }
      $this->loaded = true;
      break;
    }
  }

  public function toXML() {
    $xmlstr = "";
    if ($this->unit != false) $xmlstr .= "<unit>$this->unit</unit>";
    if ($this->absolute != false) $xmlstr .= "<absolute />";
    if ($this->top != false) $xmlstr .= "<top>$this->top</top>";
    if ($this->left != false) $xmlstr .= "<left>$this->left</left>";
    if ($this->right != false) $xmlstr .= "<right>$this->right</right>";
    if ($this->bottom != false) $xmlstr .= "<bottom>$this->bottom</bottom>";
    if (strlen($xmlstr)) $xmlstr = "<general>".$xmlstr."</general>";

    foreach ($this->exception as $exception) {
      if ($exception['pagesize'] == false) continue;
      $xmlstr .= "<exception PageSize=\"{$exception['PageSize']}\">";
      if ($this->unit != false) $xmlstr .= "<unit>{$exception['unit']}</unit>";
      if ($this->absolute != false) $xmlstr .= "<absolute />";
      if ($this->top != false) $xmlstr .= "<top>{$exception['top']}</top>";
      if ($this->left != false) $xmlstr .= "<left>{$exception['left']}</left>";
      if ($this->right != false) $xmlstr .= "<right>{$exception['right']}</right>";
      if ($this->bottom != false) $xmlstr .= "<bottom>{$exception['bottom']}</bottom>";
      $xmlstr = "</exception>";
    }
	
    return $xmlstr;
  }

  public function loadDB($printer_id = null, $driver_id = null, $db = null) {
    if (!$printer_id && !$driver_id) return null;
    if ($db == null) {
      $db = DB::getInstance();
    }

    $printer_id = mysql_escape_string($printer_id);
    $driver_id = mysql_escape_string($driver_id);

    $query = "select * from margins where ";
    $conditions = "";
    if ($printer_id != false) $conditions .= "printer_id=\"$printer_id\"";
    if (strlen($conditions)) $conditions .= " and ";
    if ($driver_id != false) $conditions .= "driver_id=\"$driver_id\"";
    $query .= $conditions;

    $this->driver_id = $this->printer_id = null;

    // Get the general settings
    $result = $db->query($query." and margin_type=\"general\"");
    if ($result != null) {
      $this->driver_id = $driver_id;
      $this->printer_id = $printer_id;
		
      while($row = mysql_fetch_array($result)) {
	$this->unit = (string)$row['margin_unit'];
	$this->absolute = (bool)$row['margin_absolute'];
	$this->top = (string)$row['margin_top'];
	$this->left = (string)$row['margin_left'];
	$this->right = (string)$row['margin_right'];
	$this->bottom = (string)$row['margin_bottom'];
      }
    }
	
    // Get the exceptions
    $result = $db->query($query." and margin_type=\"exception\"");
    if ($result != null) {
      $this->driver_id = $driver_id;
      $this->printer_id = $printer_id;
		
      unset($this->exceptions);
      $this->exceptions = array();
      $i = 0;
      while($row = mysql_fetch_array($result)) {
	$this->exceptions[$i]['PageSize'] = (string)$row['pagesize'];
	$this->exceptions[$i]['unit'] = (string)$row['margin_unit'];
	$this->exceptions[$i]['absolute'] = (bool)$row['margin_absolute'];
	$this->exceptions[$i]['top'] = (string)$row['margin_top'];
	$this->exceptions[$i]['left'] = (string)$row['margin_left'];
	$this->exceptions[$i]['right'] = (string)$row['margin_right'];
	$this->exceptions[$i]['bottom'] = (string)$row['margin_bottom'];
	$i++;
      }
    }
  }

  public function saveDB(DB $db = null) {
    if ($db == null) {
      $db = DB::getInstance();
    }
	
    if (!$this->loaded) return false;

    // Find out if an entry already exists under the general settings
    $query = "select * from margin where driver_id=\"{$this->driver_id}\" and printer_id=\"{$this->printer_id}\" and margin_type=\"general\"";
    $result = $db->query($query);
    $count = mysql_num_rows($result);
    mysql_free_result($result);

    if ($count) {
      $query = "update margin set ";
      $query .= "margin_unit=\"".mysql_real_escape_string($this->unit)."\",";
      $query .= "margin_absolute=\"".(bool)$this->absolute."\",";
      $query .= "margin_top=\"".mysql_real_escape_string($this->top)."\",";
      $query .= "margin_left=\"".mysql_real_escape_string($this->left)."\",";
      $query .= "margin_right=\"".mysql_real_escape_string($this->right)."\",";
      $query .= "margin_bottom=\"".mysql_real_escape_string($this->bottom)."\"";
      $query .= " where driver_id=\"{$this->driver_id}\" and printer_id=\"{$this->printer_id}\" and margin_type=\"general\"";
    } else {
      $query = "insert into margin(driver_id,printer_id,margin_unit,margin_type,margin_absolute,margin_top,margin_left,margin_right,margin_bottom) values(";
      $query .= "\"".mysql_real_escape_string($this->driver_id)."\",";
      $query .= "\"".mysql_real_escape_string($this->printer_id)."\",";
      $query .= "\"".mysql_real_escape_string($this->unit)."\",";
      $query .= "\"general\",";
      $query .= "\"".(bool)($this->absolute)."\",";
      $query .= "\"".mysql_real_escape_string($this->top)."\",";
      $query .= "\"".mysql_real_escape_string($this->left)."\",";
      $query .= "\"".mysql_real_escape_string($this->right)."\",";
      $query .= "\"".mysql_real_escape_string($this->bottom)."\")";
    }
	
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] Unable to save margin's general data: (".$db->getErrorNo().") ".$db->getError()."\n";
      return false;
    }
    if ($this->exception) {
      foreach ($this->exception as $e) {
	// Find out if an entry already exists under the general settings
	$query = "select * from margin where driver_id=\"{$this->driver_id}\" and printer_id=\"{$this->printer_id}\" and margin_type=\"exception\" and pagesize=\"{$e['pagesize']}\"";
	$result = $db->query($query);
	$count = mysql_num_rows($result);
	mysql_free_result($result);

	if ($count) {
	  $query = "update margin set ";
	  $query .= "margin_unit=\"".mysql_real_escape_string($e['unit'])."\",";
	  $query .= "margin_absolute=\"".(bool)$e['absolute']."\",";
	  $query .= "margin_top=\"".mysql_real_escape_string($e['top'])."\",";
	  $query .= "margin_left=\"".mysql_real_escape_string($e['left'])."\",";
	  $query .= "margin_right=\"".mysql_real_escape_string($e['right'])."\",";
	  $query .= "margin_bottom=\"".mysql_real_escape_string($e['bottom'])."\"";
	  $query .= " where driver_id=\"{$this->driver_id}\" and printer_id=\"{$this->printer_id}\" and margin_type=\"exception\" and pagesize=\"{$e['pagesize']}\"";
	} else {
	  $query = "insert into margin(driver_id,printer_id,margin_unit,margin_type,pagesize,margin_absolute,margin_top,margin_left,margin_right,margin_bottom) values(";
	  $query .= "\"".mysql_real_escape_string($this->driver_id)."\",";
	  $query .= "\"".mysql_real_escape_string($this->printer_id)."\",";
	  $query .= "\"".mysql_real_escape_string($e['unit'])."\",";
	  $query .= "\"exception\",";
	  $query .= "\"".mysql_real_escape_string($e['pagesize'])."\",";
	  $query .= "\"".(bool)($this->absolute)."\",";
	  $query .= "\"".mysql_real_escape_string($e['top'])."\",";
	  $query .= "\"".mysql_real_escape_string($e['left'])."\",";
	  $query .= "\"".mysql_real_escape_string($e['right'])."\",";
	  $query .= "\"".mysql_real_escape_string($e['bottom'])."\")";
	}

	$result = $db->query($query);
	if ($result == null) {
	  echo "[ERROR] Unable to save margin's exception data: ".$db->getError()."\n";
	  return false;
	}
      }
    }

    return true;
  }
}
?>
