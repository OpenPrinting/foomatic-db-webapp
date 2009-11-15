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
      // Prepare the translation data
      if ($data->comments) {
	if ($is_printer) {
	  $this->translation["pcomments"] = new Translation($data->comments, "driver_printer_assoc", array("driver_id" => $this->data['driver_id'], "printer_id" => $this->data['printer_id']), "pcomments");
	} else {
	  $this->translation["comments"] = new Translation($data->comments, "driver_printer_assoc", array("driver_id" => $this->data['driver_id'], "printer_id" => $this->data['printer_id']), "comments");
	}
      }
      break;

    case 'array':
      if ($is_printer) {
	$this->data['driver_id'] = (string)$data['driver_id'];
	$this->data['ppd'] = (string)$data['ppd'];
	$this->data['pcomments'] = (string)$data['pcomments'];
	$this->data['fromprinter'] = (string)$data['fromprinter'];
      } else {
	list(,$this->data['printer_id']) = preg_split("/\//", (string)$data['printer_id']);
	if (!$this->data['printer_id'])
	  $this->data['printer_id'] = (string)$data['printer_id'];
	$this->data['max_res_x'] = (string)$data['max_res_x'];
	$this->data['max_res_y'] = (string)$data['max_res_y'];
	$this->data['color'] = $data['color'];
	$this->data['text'] = (string)$data['text'];
	$this->data['lineart'] = (string)$data['lineart'];
	$this->data['graphics'] = (string)$data['graphics'];
	$this->data['photo'] = (string)$data['photo'];
	$this->data['load_time'] = (string)$data['load_time'];
	$this->data['speed'] = (string)$data['speed'];
	$this->data['ppdentry'] = (string)$data['ppdentry'];
	$this->data['comments'] = (string)$data['comments'];
	$this->data['fromdriver'] = (string)$data['fromdriver'];
      }
      break;
    }
  }

  public function toXML($indent = 0, $is_printer = false) {
    $xmlstr = "";
    $is = str_pad("", $indent);
    if ($is_printer == true and $this->data['fromprinter'] == true) {
      $xmlstr .= "$is<driver>\n";
      $xmlstr .= "$is  <id>{$this->data['driver_id']}</id>\n";
      if (strlen($this->data['pcomments'])) {
	$xmlstr .= "$is  <comments>\n$is    <en>";
	$xmlstr .= htmlspecialchars($this->data['pcomments']);
	$xmlstr .= "</en>\n";
	if ($this->translation["pcomments"])
	  $xmlstr .= $this->translation["pcomments"]->toXML($indent + 2);
	$xmlstr .= "$is  </comments>\n";
      }
      if (strlen($this->data['ppd']))
	$xmlstr .= "$is  <ppd>" . htmlspecialchars($this->data['ppd']) . 
	  "</ppd>\n";
      $xmlstr .= "$is</driver>\n";
    } elseif ($is_printer == false and $this->data['fromdriver'] == true) {
      $xmlstr .= "$is<printer>\n";
      $xmlstr .= "$is  <id>printer/{$this->data['printer_id']}</id>\n";
      if (strlen($this->data['comments'])) {
	$xmlstr .= "$is  <comments>\n$is    <en>";
	$xmlstr .= htmlspecialchars($this->data['comments']);
	$xmlstr .= "</en>\n";
	if ($this->translation["comments"])
	  $xmlstr .= $this->translation["comments"]->toXML($indent + 2);
	$xmlstr .= "$is  </comments>\n";
      }
      $func = "";
      if ($this->data['max_res_x'] != false and
          $this->data['max_res_x'] > 0)
	$func .= "$is    <maxresx>{$this->data['max_res_x']}</maxresx>\n";
      if ($this->data['max_res_y'] != false and
          $this->data['max_res_y'] > 0)
	$func .= "$is    <maxresy>{$this->data['max_res_y']}</maxresy>\n";
      if ($this->data['color'] != null) {
	  if ($this->data['color'] == 1) {
	      $func .= "$is    <color />\n";
	  } elseif ($this->data['color'] == 0) {
	      $func .= "$is    <monochrome />\n";
	  }
      }
      if (strlen($this->data['text']) and
          $this->data['text'] != -1)
	$func .= "$is    <text>{$this->data['text']}</text>\n";
      if (strlen($this->data['lineart']) and
          $this->data['lineart'] != -1)
	$func .= "$is    <lineart>{$this->data['lineart']}</lineart>\n";
      if (strlen($this->data['graphics']) and
          $this->data['graphics'] != -1)
	$func .= "$is    <graphics>{$this->data['graphics']}</graphics>\n";
      if (strlen($this->data['photo']) and
          $this->data['photo'] != -1)
	$func .= "$is    <photo>{$this->data['photo']}</photo>\n";
      if (strlen($this->data['load_time']) and
          $this->data['load_time'] != -1)
	$func .= "$is    <load>{$this->data['load_time']}</load>\n";
      if (strlen($this->data['speed']) and
          $this->data['speed'] != -1)
	$func .= "$is    <speed>{$this->data['speed']}</speed>\n";
      if (strlen($func))
	  $xmlstr .= "$is  <functionality>\n$func$is  </functionality>\n";
      if ($this->margins)
	  $xmlstr .= $this->margins->toXML($indent + 2);
      if (strlen($this->data['ppdentry']) and
	  $this->data['ppdentry'] != -1)
	$xmlstr .= "$is  <ppdentry>" .
	  htmlspecialchars($this->data['ppdentry']) . "</ppdentry>\n";
      $xmlstr .= "$is</printer>\n";
    }
    return $xmlstr;
  }

  public function loadDB($driver_id, $printer_id, $for_printer = false,
			 DB $db = null) {
    if ($driver_id == null or $printer_id == null) {
      return false;
    }

    if ($db == null) {
      $db = DB::getInstance();
    }

    // Clear any previous data present
    unset($this->translation);
    unset($this->data);
    $this->margins = null;
    
    $driver_id = mysql_real_escape_string($driver_id);
    $printer_id = mysql_real_escape_string($printer_id);

    // Load the translations
    foreach(array('comments', 'pcomments') as $field) {
	$this->translation[$field] = new Translation(null, "driver_printer_assoc", array("driver_id" => $driver_id, "printer_id" => $printer_id), $field);
	$this->translation[$field]->loadDB("driver_printer_assoc", array("driver_id" => $driver_id, "printer_id" => $printer_id), $field, $db);
    }

    // Prepare the query string for extracting main driver/printer combo
    // details
    $query = "select * from driver_printer_assoc where driver_id=\"$driver_id\" and printer_id=\"$printer_id\"";
    $result = $db->query($query);

    if ($result == null) {
      return false;
    }
    $row = mysql_fetch_assoc($result);
    if ($for_printer) {
      $this->__construct($printer_id, $row, true);
    } else {
      $this->__construct($driver_id, $row);
    }
    mysql_free_result($result);

    // Load margin info
    $this->margins = new Margin(null, $printer_id, $driver_id);
    $this->margins->loadDB($printer_id, $driver_id);
    
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
