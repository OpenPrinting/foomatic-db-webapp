<?php
include_once("db.php");
include_once("translation.php");
include_once("margin.php");

class DriverPrinterAssociation
{
  // Boolean flag to determine if data is present
  private $loaded;

  public $data;
  public $margins = null;

  public function __construct($id, $data, $is_printer = false) {
    if (!$id && !$data) {
      return false;
    }
	
    if ($is_printer) {
      $this->data['printer_id'] = $id;
      $this->data['driver_id'] = '';
    } else {
      $this->data['driver_id'] = $id;
      $this->data['printer_id'] = '';
    }
	
    switch((string)gettype($data)) {
    case 'object':
      if (get_class($data) == "SimpleXMLElement") {
	if ($is_printer) {
	  $this->data['driver_id'] = (string)$data->id;
	  $this->data['ppd'] = (string)$data->ppd;
	  $this->data['pcomments'] = (string)$data->comments->en;
	  $this->data['fromprinter'] = true;
	} else {
	  list(,$this->data['printer_id']) = preg_split("/\//", (string)$data->id);

	  // <functionality>
	  if ($data->functionality != false) {
	    if ($data->functionality->maxresx != false) {
	      $this->data['max_res_x'] = (string)$data->functionality->maxresx;
	    } else {
	      $this->data['max_res_x'] = -1;
	    }
	    if ($data->functionality->maxresy != false) {
	      $this->data['max_res_y'] = (string)$data->functionality->maxresy;
	    } else {
	      $this->data['max_res_y'] = -1;
	    }
	    if (array_key_exists('color', $data->functionality)) {
	      $this->data['color'] = 1;
	    } else if (array_key_exists('monochrome', $data->functionality)) {
	      $this->data['color'] = 0;
	    } else {
	      $this->data['color'] = -1;
	    }
	    if ($data->functionality->text != false) {
	      $this->data['text'] = (string)$data->functionality->text;
	    } else {
	      $this->data['text'] = -1;
	    }
	    if ($data->functionality->lineart != false) {
	      $this->data['lineart'] = (string)$data->functionality->lineart;
	    } else {
	      $this->data['lineart'] = -1;
	    }
	    if ($data->functionality->graphics != false) {
	      $this->data['graphics'] = (string)$data->functionality->graphics;
	    } else {
	      $this->data['graphics'] = -1;
	    }
	    if ($data->functionality->photo != false) {
	      $this->data['photo'] = (string)$data->functionality->photo;
	    } else {
	      $this->data['photo'] = -1;
	    }
	    if ($data->functionality->load != false) {
	      $this->data['load_time'] = (string)$data->functionality->load;
	    } else {
	      $this->data['load_time'] = -1;
	    }
	    if ($data->functionality->speed != false) {
	      $this->data['speed'] = (string)$data->functionality->speed;
	    } else {
	      $this->data['speed'] = -1;
	    }
	  } else {
	    $this->data['max_res_x'] = -1;
	    $this->data['max_res_y'] = -1;
	    $this->data['color'] = -1;
	    $this->data['text'] = -1;
	    $this->data['lineart'] = -1;
	    $this->data['graphics'] = -1;
	    $this->data['photo'] = -1;
	    $this->data['load_time'] = -1;
	    $this->data['speed'] = -1;
	  }
	  // </functionality>

	  if ($data->ppdentry != false) {
	    $this->data['ppdentry'] = (string)$data->ppdentry;
	  } else {
	    $this->data['ppdentry'] = -1;
	  }

	  if ($data->margins != false) $this->margins = new Margin($data->margins, $this->data['printer_id'], $this->data['driver_id']);
	  $this->data['comments'] = (string)$data->comments->en;
	  $this->data['fromdriver'] = true;
	}

	$this->loaded = true;
      }
      break;

    case 'array':
      if ($is_printer) {
	$this->data['driver_id'] = (string)$data['id'];
	$this->data['ppd'] = (string)$data['ppd'];
	$this->data['pcomments'] = (string)$data['pcomments'];
	$this->data['fromprinter'] = (string)$data['fromprinter'];
      } else {
	list(,$this->data['printer_id']) = preg_split("/\//", (string)$data['id']);
	
	// <functionality>
	if ($data->functionality != false) {
	  $this->data['max_res_x'] = (string)$data['maxresx'];
	  $this->data['max_res_y'] = (string)$data['maxresy'];
	  $this->data['color'] = (int)array_key_exists('color', $data);
	  $this->data['text'] = (string)$data['text'];
	  $this->data['lineart'] = (string)$data['lineart'];
	  $this->data['graphics'] = (string)$data['graphics'];
	  $this->data['photo'] = (string)$data['photo'];
	  $this->data['load_time'] = (string)$data['load'];
	  $this->data['speed'] = (string)$data['speed'];
	}
	// </functionality>

	$this->data['ppdentry'] = (string)$data['ppdentry'];
	$this->data['comments'] = (string)$data['comments'];
	$this->data['fromdriver'] = (string)$data['fromdriver'];
      }
      break;
    }

    // Prepare the translation data
    if ($data->comments) {
      if ($is_printer) {
	$this->translation["pcomments"] = new Translation($data->comments, "driver_printer_assoc", array("driver_id" => $this->data['driver_id'], "printer_id" => $this->data['printer_id']), "pcomments");
      } else {
	$this->translation["comments"] = new Translation($data->comments, "driver_printer_assoc", array("driver_id" => $this->data['driver_id'], "printer_id" => $this->data['printer_id']), "comments");
      }
    }

  }

  public function saveDB(DB $db = null) {
    if ($db == null) {
      $db = DB::getInstance();
    }
	
    if ($this->loaded === false) return false;
	
    // Find out if an entry of this printer exists. If yes, then just update that entry
    $query = "select * from driver_printer_assoc where driver_id=\"{$this->data['driver_id']}\" and printer_id=\"{$this->data['printer_id']}\"";
    $result = $db->query($query);
    if ($result == null) {
      echo __FILE__."[ERROR]".$db->getError()."\n";
      return false;
    }
    $count = mysql_num_rows($result);
    mysql_free_result($result);
    if ($count) {
      $query = "update driver_printer_assoc set ";
      foreach ($this->data as $key=>$value) {
	if (((string)gettype($value) == 'integer') and
	    ((int)$value == -1)) {
	  $v = "NULL";
	} else {
	  $v = "\"".mysql_real_escape_string($value)."\"";
	}
	$query .= "$key=$v,";
      }
      $query[strlen($query) - 1] = ' ';
      $query .= "where driver_id=\"{$this->data['driver_id']}\" and printer_id=\"{$this->data['printer_id']}\"";
    } else {
      $query = "insert into driver_printer_assoc(";
      $fields = $values = "";
      foreach($this->data as $key=>$value) {
	$fields .= "$key,";
	if (((string)gettype($value) == 'integer') and
	    ((int)$value == -1)) {
	  $values .= "NULL,";
	} else {
	  $values .= "\"".mysql_real_escape_string($value)."\",";
	}
      }
      $fields[strlen($fields) - 1] = ')';
      $values[strlen($values) - 1] = ')';
      $query .= $fields." values(".$values;
    }

    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] Unable to save driver & printer association data: ".$db->getError()."\n";
      return false;
    }

    // Save the margins data
    if ($this->margins) {
      if (!$this->margins->saveDB($db)) {
	echo "[ERROR] While saving driver's margin specs...\n".$db->getError()."\n";
	return false;
      }
    }

    // Trigger the save of translation data
    if ($this->translation) {
      foreach ($this->translation as $field => $trobj) {
	if (!$trobj->saveDB($db)) {
	  echo "[ERROR] While saving driver printer association translation data for the \"$field\" field...\n".$db->getError()."\n";
	  return false;
	}
      }
    }
			
    return true;
  }
}
?>
