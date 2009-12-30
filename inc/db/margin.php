<?php
require_once("mysqldb.php");
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

  public function toXML($indent = 0) {
    $xmlstr = "";
    $is = str_pad("", $indent);
    if (strlen($this->unit)) $xmlstr .= "$is    <unit>{$this->unit}</unit>\n";
    if (strlen($this->unit) or
	strlen($this->top) or strlen($this->left) or
	strlen($this->right) or strlen($this->bottom)) {
      if ($this->absolute != false) {
	$xmlstr .= "$is    <absolute />\n";
      } else {
	$xmlstr .= "$is    <relative />\n";
      }
    }
    if (strlen($this->top))
      $xmlstr .= "$is    <top>{$this->top}</top>\n";
    if (strlen($this->left))
      $xmlstr .= "$is    <left>{$this->left}</left>\n";
    if (strlen($this->right))
      $xmlstr .= "$is    <right>{$this->right}</right>\n";
    if (strlen($this->bottom))
      $xmlstr .= "$is    <bottom>{$this->bottom}</bottom>\n";
    if (strlen($xmlstr)) $xmlstr = "$is  <general>\n".$xmlstr."$is  </general>\n";

    if ($this->exception) {
      foreach ($this->exception as $exception) {
	  if (!strlen($exception['pagesize']) or
	      (!strlen($exception['top']) and !strlen($exception['left']) and
	       !strlen($exception['right']) and !strlen($exception['bottom'])))
	    continue;
	$xmlstr .= "$is  <exception PageSize=\"{$exception['pagesize']}\">\n";
	if (strlen($exception['unit']))
	    $xmlstr .= "$is    <unit>{$exception['unit']}</unit>\n";
	if ($exception['absolute'] != false) {
	  $xmlstr .= "$is    <absolute />\n";
	} else {
	  $xmlstr .= "$is    <relative />\n";
	}
	if (strlen($exception['top']))
	  $xmlstr .= "$is    <top>{$exception['top']}</top>\n";
	if (strlen($exception['left']))
	  $xmlstr .= "$is    <left>{$exception['left']}</left>\n";
	if (strlen($exception['right']))
	  $xmlstr .= "$is    <right>{$exception['right']}</right>\n";
	if (strlen($exception['bottom']))
	  $xmlstr .= "$is    <bottom>{$exception['bottom']}</bottom>\n";
	$xmlstr .= "$is  </exception>\n";
      }
    }
    if (strlen($xmlstr)) $xmlstr = "$is<margins>\n".$xmlstr."$is</margins>\n";
	
    return $xmlstr;
  }

  public function loadDB($printer_id = null, $driver_id = null, $db = null) {
    if (!$printer_id and !$driver_id) return null;
    if ($db == null) {
      $db = OPDB::getInstance();
    }

    $printer_id = mysql_escape_string($printer_id);
    $driver_id = mysql_escape_string($driver_id);

    $query = "select * from margin where ";
    $conditions = "";
    if ($printer_id)
      $conditions .= "printer_id=\"$printer_id\"";
    else
      $conditions .= "(printer_id is null or printer_id=\"\")";
    $conditions .= " and ";
    if ($driver_id)
      $conditions .= "driver_id=\"$driver_id\"";
    else
      $conditions .= "(driver_id is null or driver_id=\"\")";
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

      unset($this->exception);
      $this->exception = array();
      $i = 0;
      while($row = mysql_fetch_array($result)) {
	$this->exception[$i]['pagesize'] = (string)$row['pagesize'];
	$this->exception[$i]['unit'] = (string)$row['margin_unit'];
	$this->exception[$i]['absolute'] = (bool)$row['margin_absolute'];
	$this->exception[$i]['top'] = (string)$row['margin_top'];
	$this->exception[$i]['left'] = (string)$row['margin_left'];
	$this->exception[$i]['right'] = (string)$row['margin_right'];
	$this->exception[$i]['bottom'] = (string)$row['margin_bottom'];
	$i++;
      }
    }
  }

  public function saveDB(OPDB $db = null) {
    if ($db == null) {
      $db = OPDB::getInstance();
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
      $query .= "margin_absolute=\"".mysql_real_escape_string(($this->absolute == true ? "1" : "0"))."\",";
      if (strlen($this->top))
	$query .=
	  "margin_top=\"".mysql_real_escape_string($this->top)."\",";
      else $query .= "margin_top=null,";
      if (strlen($this->left))
	$query .=
	  "margin_left=\"".mysql_real_escape_string($this->left)."\",";
      else $query .= "margin_left=null,";
      if (strlen($this->right))
        $query .=
	  "margin_right=\"".mysql_real_escape_string($this->right)."\",";
      else $query .= "margin_right=null,";
      if (strlen($this->bottom))
        $query .=
	  "margin_bottom=\"".mysql_real_escape_string($this->bottom)."\"";
      else $query .= "margin_bottom=null";
      $query .= " where driver_id=\"{$this->driver_id}\" and printer_id=\"{$this->printer_id}\" and margin_type=\"general\"";
    } else {
      $query = "insert into margin(driver_id,printer_id,margin_unit,margin_type,margin_absolute,margin_top,margin_left,margin_right,margin_bottom) values(";
      $query .= "\"".mysql_real_escape_string($this->driver_id)."\",";
      $query .= "\"".mysql_real_escape_string($this->printer_id)."\",";
      $query .= "\"".mysql_real_escape_string($this->unit)."\",";
      $query .= "\"general\",";
      $query .= "\"".mysql_real_escape_string(($this->absolute == true ? "1" :  "0"))."\",";
      if (strlen($this->top))
	$query .= "\"".mysql_real_escape_string($this->top)."\",";
      else $query .= "null,";
      if (strlen($this->left))
        $query .= "\"".mysql_real_escape_string($this->left)."\",";
      else $query .= "null,";
      if (strlen($this->right))
        $query .= "\"".mysql_real_escape_string($this->right)."\",";
      else $query .= "null,";
      if (strlen($this->bottom))
        $query .= "\"".mysql_real_escape_string($this->bottom)."\")";
      else $query .= "null)";
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
	  $query .= "margin_absolute=\"".mysql_real_escape_string(($e['absolute'] == true ? "1" : "0"))."\",";
	  if (strlen($e['top']))
	    $query .=
	      "margin_top=\"".mysql_real_escape_string($e['top'])."\",";
	  else $query .= "margin_top=null,";
          if (strlen($e['left']))
	    $query .=
	      "margin_left=\"".mysql_real_escape_string($e['left'])."\",";
          else $query .= "margin_left=null,";
          if (strlen($e['right']))
	    $query .=
	      "margin_right=\"".mysql_real_escape_string($e['right'])."\",";
          else $query .= "margin_right=null,";
          if (strlen($e['bottom']))
	    $query .=
	      "margin_bottom=\"".mysql_real_escape_string($e['bottom'])."\"";
          else $query .= "margin_bottom=null";
	  $query .= " where driver_id=\"{$this->driver_id}\" and printer_id=\"{$this->printer_id}\" and margin_type=\"exception\" and pagesize=\"{$e['pagesize']}\"";
	} else {
	  $query = "insert into margin(driver_id,printer_id,margin_unit,margin_type,pagesize,margin_absolute,margin_top,margin_left,margin_right,margin_bottom) values(";
	  $query .= "\"".mysql_real_escape_string($this->driver_id)."\",";
	  $query .= "\"".mysql_real_escape_string($this->printer_id)."\",";
	  $query .= "\"".mysql_real_escape_string($e['unit'])."\",";
	  $query .= "\"exception\",";
	  $query .= "\"".mysql_real_escape_string($e['pagesize'])."\",";
	  $query .= "\"".mysql_real_escape_string(($e['absolute'] == true ? "1" : "0"))."\",";
          if (strlen($e['top']))
	    $query .= "\"".mysql_real_escape_string($e['top'])."\",";
	  else $query .= "null,";
          if (strlen($e['left']))
	    $query .= "\"".mysql_real_escape_string($e['left'])."\",";
          else $query .= "null,";
          if (strlen($e['right']))
	    $query .= "\"".mysql_real_escape_string($e['right'])."\",";
          else $query .= "null,";
          if (strlen($e['bottom']))
	    $query .= "\"".mysql_real_escape_string($e['bottom'])."\")";
          else $query .= "null)";
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
