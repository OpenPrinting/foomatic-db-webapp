<?php
require_once("opdb.php");
require_once("translation.php");
require_once("driver_dependency.php");
require_once("driver_package.php");
require_once("driver_support_contact.php");
require_once("driver_printer_association.php");

class Driver
{
  // Boolean flag to determine if data is present
  private $loaded;
	
  // This contains the XML data
  public $id;
  public $name;
  public $driver_group;
  public $locales;
  public $obsolete;
  public $pcdriver;
  public $url;
  public $supplier;
  public $thirdpartysupplied;
  public $manufacturersupplied;
  public $license;
  public $licensetext;
  public $licenselink;
  public $nonfreesoftware;
  public $patents;
  public $shortdescription;
  public $maxresx;
  public $maxresy;
  public $color;
  public $text;
  public $lineart;
  public $graphics;
  public $photo;
  public $load_time;
  public $speed;
  public $execution;
  public $nopjl;
  public $nopageaccounting;
  public $prototype;
  public $pdf_prototype;
  public $ppdentry;
  public $comments;

  // Contains of list of objects of respective data
  public $packages;
  public $dependencies;
  public $supportcontacts;
  public $printers;

  public function __construct($data = null) {
    $this->packages = null;
    $this->dependencies = null;
    $this->supportcontacts = null;
    $this->printers = null;
	
    if ($data != null) {
      switch((string)gettype($data)) {
      case 'array':
	$this->id = (string)$data['id'];
	$this->name = (string)$data['name'];
	$this->driver_group = (string)$data['driver_group'];
	$this->locales = (string)$data['locales'];
	$this->obsolete = (string)$data['obsolete'];
	$this->pcdriver = (string)$data['pcdriver'];
	$this->url = (string)$data['url'];
	$this->supplier = (string)$data['supplier'];
	$this->thirdpartysupplied = (bool)$data['thirdpartysupplied'];
	$this->manufacturersupplied = (string)$data['manufacturersupplied'];
	$this->license = (string)$data['license'];
	$this->licensetext = (string)$data['licensetext'];
	$this->licenselink = (string)$data['licenselink'];
	$this->nonfreesoftware = (bool)$data['nonfreesoftware'];
	$this->patents = (bool)$data['patents'];
	$this->shortdescription = (string)$data['shortdescription'];
	$this->max_res_x = (string)$data['max_res_x'];
	$this->max_res_y = (string)$data['max_res_y'];
	$this->color = $data['color'];
	$this->text = (string)$data['text'];
	$this->lineart = (string)$data['lineart'];
	$this->graphics = (string)$data['graphics'];
	$this->photo = (string)$data['photo'];
	$this->load_time = (string)$data['load_time'];
	$this->speed = (string)$data['speed'];
	$this->execution = (string)$data['execution'];
	$this->nopjl = (string)$data['no_pjl'];
	$this->nopageaccounting = (string)$data['no_pageaccounting'];
	$this->prototype = (string)$data['prototype'];
	$this->pdf_prototype = (string)$data['pdf_prototype'];
	$this->ppdentry = (string)$data['ppdentry'];
	$this->comments = (string)$data['comments'];
	$this->loaded = true;
	break;
			
      case 'object':
	if (get_class($data) == "SimpleXMLElement") {
	  list(,$this->id) = preg_split("/\//", $data['id']);
	  $this->name = (string)$data->name;
	  $this->driver_group = (string)$data->group;
	  $this->locales = (string)$data->locales;
	  if (array_key_exists('obsolete', $data)) {
	    $this->obsolete = (string)$data->obsolete['replace'];
	  }
	  $this->pcdriver = (string)$data->pcdriver;
	  $this->url = (string)$data->url;
	  if ($data->license->en) {
	    $this->supplier = (string)$data->supplier->en;
	  } else {
	    $this->supplier = (string)$data->supplier;
	  }
	  $this->thirdpartysupplied = !array_key_exists('manufacturersupplied', $data);
	  if (!$this->thirdpartysupplied) {
	    $this->manufacturersupplied = (string)$data->manufacturersupplied;
	  }
	  if ($data->license->en) {
	    $this->license = (string)$data->license->en;
	  } else {
	    $this->license = (string)$data->license;
	  }
	  $this->licensetext = (string)$data->licensetext->en;
	  $this->licenselink = (string)$data->licensetext->en['url'];
	  $this->nonfreesoftware = (bool)array_key_exists('nonfreesoftware', $data);
	  $this->patents = (bool)array_key_exists('patents', $data);
	  $this->shortdescription = (string)$data->shortdescription->en;
	  $this->comments = (string)$data->comments->en;

	  // <functionality>
	  if ($data->functionality != false) {
	    if ($data->functionality->maxresx != false) {
	      $this->max_res_x = (string)$data->functionality->maxresx;
	    } else {
	      $this->max_res_x = -1;
	    }
	    if ($data->functionality->maxresy != false) {
	      $this->max_res_y = (string)$data->functionality->maxresy;
	    } else {
	      $this->max_res_y = -1;
	    }
	    if (array_key_exists('color', $data->functionality)) {
	      $this->color = 1;
	    } else if (array_key_exists('monochrome', $data->functionality)) {
	      $this->color = 0;
	    } else {
	      $this->color = -1;
	    }
	    if ($data->functionality->text != false) {
	      $this->text = (string)$data->functionality->text;
	    } else {
	      $this->text = -1;
	    }
	    if ($data->functionality->lineart != false) {
	      $this->lineart = (string)$data->functionality->lineart;
	    } else {
	      $this->lineart = -1;
	    }
	    if ($data->functionality->graphics != false) {
	      $this->graphics = (string)$data->functionality->graphics;
	    } else {
	      $this->graphics = -1;
	    }
	    if ($data->functionality->photo != false) {
	      $this->photo = (string)$data->functionality->photo;
	    } else {
	      $this->photo = -1;
	    }
	    if ($data->functionality->load != false) {
	      $this->load_time = (string)$data->functionality->load;
	    } else {
	      $this->load_time = -1;
	    }
	    if ($data->functionality->speed != false) {
	      $this->speed = (string)$data->functionality->speed;
	    } else {
	      $this->speed = -1;
	    }
	  } else {
	    $this->max_res_x = -1;
	    $this->max_res_y = -1;
	    $this->color = -1;
	    $this->text = -1;
	    $this->lineart = -1;
	    $this->graphics = -1;
	    $this->photo = -1;
	    $this->load_time = -1;
	    $this->speed = -1;
	  }
	  // </functionality>
				
	  // <execution>
	  if ($data->execution != false) {
	    $this->nopjl = (bool)array_key_exists('nopjl', $data->execution);
	    $this->nopageaccounting = (bool)array_key_exists('nopageaccounting', $data->execution);
	    $this->prototype = (string)$data->execution->prototype;
	    $this->pdf_prototype = (string)$data->execution->prototype_pdf;
	    $this->ppdentry = (string)$data->execution->ppdentry;
	    if ($data->execution->requires != false) {
	      $i = 0;
	      $this->dependencies = array();
	      foreach ($data->execution->requires as $dependency) {
		$this->dependencies[$i] = new DriverDependency($this->id, $dependency);
		$i++;
	      }
	    }
	
	    unset($data->execution->nopjl);
	    unset($data->execution->nopageaccounting);
	    unset($data->execution->prototype);
	    unset($data->execution->prototype_pdf);
	    unset($data->execution->ppdentry);
	    unset($data->execution->requires);
	    // Remove also the entry coming from lines which are commented
	    // out in the XML file
	    unset($data->execution->comment);
	    $this->execution = (string)key($data->execution);
	  }
	  // </execution>
	}
	$this->loaded = true;
	break;
      }
      // Prepare the translation data
      if ($data->supplier) {
	$this->translation["supplier"] = new Translation($data->supplier, "driver", array("id" => $this->id), "supplier");
      }
      if ($data->license) {
	$this->translation["license"] = new Translation($data->license, "driver", array("id" => $this->id), "license");
      }
      if ($data->licensetext) {
	$this->translation["licensetext"] = new Translation($data->licensetext, "driver", array("id" => $this->id), "licensetext");
      }
      if ($data->licensetext) {
	$this->translation["licenselink"] = new Translation($data->licensetext, "driver", array("id" => $this->id), "licenselink");
      }
      if ($data->shortdescription) {
	$this->translation["shortdescription"] = new Translation($data->shortdescription, "driver", array("id" => $this->id), "shortdescription");
      }
      if ($data->comments) {
	$this->translation["comments"] = new Translation($data->comments, "driver", array("id" => $this->id), "comments");
      }

      // Create supportcontacts list
      if ($data->supportcontacts && $data->supportcontacts->supportcontact) {
	$i = 0;
	$this->supportcontacts = array();
	foreach ($data->supportcontacts->supportcontact as $supportcontact) {
	  $this->supportcontacts[$i] = new DriverSupportContact($this->id, $supportcontact);
	  $i++;
	}
      }
	
      // Create packages list
      if ($data->packages && $data->packages->package) {
	$i = 0;
	$this->packages = array();
	foreach ($data->packages->package as $package) {
	  $this->packages[$i] = new DriverPackage($this->id, $package);
	  $i++;
	}
      }

      // Create printers list
      if ($data->printers && $data->printers->printer) {
	$i = 0;
	$this->printers = array();
	foreach ($data->printers->printer as $printer) {
	  $this->printers[$i] = new DriverPrinterAssociation($this->id, $printer);
	  $i++;
	}
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

  public function toXML($indent = 0) {
    $is = str_pad("", $indent);
    if (!$this->id) return false;
    $xmlstr = "$is<driver id=\"driver/{$this->id}\">\n";
    if (strlen($this->name))
      $xmlstr .= "$is  <name>" . htmlspecialchars($this->name) . "</name>\n";
    if (strlen($this->driver_group))
      $xmlstr .= "$is  <group>" . htmlspecialchars($this->driver_group) .
	"</group>\n";
    if (strlen($this->locales))
      $xmlstr .= "$is  <locales>" . htmlspecialchars($this->locales) .
	"</locales>\n";
    if (strlen($this->pcdriver))
      $xmlstr .= "$is  <pcdriver>" . $this->pcdriver . "</pcdriver>\n";
    if (strlen($this->url))
      $xmlstr .= "$is  <url>" . htmlspecialchars($this->url) . "</url>\n";
    if (strlen($this->obsolete))
      $xmlstr .= "$is  <obsolete replace=\"" .
	htmlspecialchars($this->obsolete) . "\" />\n";
    if (strlen($this->supplier)) {
      $trans = "";
      if ($this->translation['supplier'])
	$trans = $this->translation["supplier"]->toXML($indent + 4);
      if ($trans) {
	$xmlstr .= "$is  <supplier>\n$is    <en>";
	$xmlstr .= htmlspecialchars($this->supplier);
	$xmlstr .= "</en>\n";
	$xmlstr .= $trans;
	$xmlstr .= "$is  </supplier>\n";
      } else {
	$xmlstr .= "$is  <supplier>" . htmlspecialchars($this->supplier) .
	  "</supplier>\n";
      }
    }
    if ($this->thirdpartysupplied == true)
      $xmlstr .= "$is  <thirdpartysupplied />\n";
    if ($this->manufacturersupplied)
      $xmlstr .= "$is  <manufacturersupplied>" .
	htmlspecialchars($this->manufacturersupplied) .
	"</manufacturersupplied>\n";
    if (strlen($this->license)) {
      $trans = "";
      if ($this->translation['license'])
	$trans = $this->translation["license"]->toXML($indent + 4);
      if ($trans) {
	$xmlstr .= "$is  <license>\n$is    <en>";
	$xmlstr .= htmlspecialchars($this->license);
	$xmlstr .= "</en>\n";
	$xmlstr .= $trans;
	$xmlstr .= "$is  </license>\n";
      } else {
	$xmlstr .= "$is  <license>" . htmlspecialchars($this->license) .
	  "</license>\n";
      }
    }
    if (strlen($this->licensetext) or strlen($this->licenselink)) {
      $xmlstr .= "$is  <licensetext>\n";
      if (strlen($this->licensetext))
	$xmlstr .= "$is    <en>" . htmlspecialchars($this->licensetext) .
	  "</en>\n";
      else
	$xmlstr .= "$is    <en url=\"" .
	  htmlspecialchars($this->licenselink) . "\" />\n";
      if ($this->translation["licensetext"])
	$xmlstr .= $this->translation["licensetext"]->toXML($indent + 4);
      if ($this->translation["licenselink"])
	$xmlstr .= $this->translation["licenselink"]->toXML($indent + 4, True);
      $xmlstr .= "$is  </licensetext>\n";
    }
    if ($this->nonfreesoftware == true)
      $xmlstr .= "$is  <nonfreesoftware />\n";
    if ($this->patents == true)
      $xmlstr .= "$is  <patents />\n";
    if ($this->supportcontacts) {
      $xmlstr .= "$is  <supportcontacts>\n";
      foreach ($this->supportcontacts as $supportcontact)
	$xmlstr .= $supportcontact->toXML($indent + 4);
      $xmlstr .= "$is  </supportcontacts>\n";
    }
    if (strlen($this->shortdescription)) {
      $xmlstr .= "$is  <shortdescription>\n$is    <en>";
      $xmlstr .= htmlspecialchars($this->shortdescription);
      $xmlstr .= "</en>\n";
      if ($this->translation["shortdescription"])
	$xmlstr .= $this->translation["shortdescription"]->toXML($indent + 4);
      $xmlstr .= "$is  </shortdescription>\n";
    }
    if ($this->packages) {
      $xmlstr .= "$is  <packages>\n";
      foreach ($this->packages as $package)
	$xmlstr .= $package->toXML($indent + 4);
      $xmlstr .= "$is  </packages>\n";
    }
    $func = "";
    if ($this->max_res_x != false and
        $this->max_res_x > 0)
      $func .= "$is    <maxresx>{$this->max_res_x}</maxresx>\n";
    if ($this->max_res_y != false and
	$this->max_res_y > 0)
      $func .= "$is    <maxresy>{$this->max_res_y}</maxresy>\n";
    if ($this->color != null and $this->color != -1) {
      if ($this->color == 1) {
	$func .= "$is    <color />\n";
      } elseif ($this->color == 0) {
	$func .= "$is    <monochrome />\n";
      }
    }
    if (strlen($this->text) and
	$this->text != -1)
      $func .= "$is    <text>{$this->text}</text>\n";
    if (strlen($this->lineart) and
	$this->lineart != -1)
      $func .= "$is    <lineart>{$this->lineart}</lineart>\n";
    if (strlen($this->graphics) and
	$this->graphics != -1)
      $func .= "$is    <graphics>{$this->graphics}</graphics>\n";
    if (strlen($this->photo) and
	$this->photo != -1)
      $func .= "$is    <photo>{$this->photo}</photo>\n";
    if (strlen($this->load_time) and
	$this->load_time != -1)
      $func .= "$is    <load>{$this->load_time}</load>\n";
    if (strlen($this->speed) and
	$this->speed != -1)
      $func .= "$is    <speed>{$this->speed}</speed>\n";
    if (strlen($func))
      $xmlstr .= "$is  <functionality>\n$func$is  </functionality>\n";
    $execution = "";
    if ($this->dependencies)
      foreach ($this->dependencies as $dependency)
	$execution .= $dependency->toXML($indent + 4);
    if (strlen($this->execution))
      $execution .= "$is    <{$this->execution} />\n";
    if ($this->nopjl == true)
      $execution .= "$is    <nopjl />\n";
    if ($this->nopageaccounting == true)
      $execution .= "$is    <nopageaccounting />\n";
    if (strlen($this->prototype))
      $execution .= "$is    <prototype>" . htmlspecialchars($this->prototype) .
	"</prototype>\n";
    if (strlen($this->pdf_prototype))
      $execution .= "$is    <prototype_pdf>" .
        htmlspecialchars($this->pdf_prototype) .
        "</prototype_pdf>\n";
    if (strlen($this->ppdentry))
      $execution .= "$is    <ppdentry>" . htmlspecialchars($this->ppdentry) .
	"</ppdentry>\n";
    if (strlen($execution))
      $xmlstr .= "$is  <execution>\n$execution$is  </execution>\n";
    if (strlen($this->comments)) {
      $xmlstr .= "$is  <comments>\n$is    <en>";
      $xmlstr .= htmlspecialchars($this->comments);
      $xmlstr .= "</en>\n";
      if ($this->translation["comments"])
	$xmlstr .= $this->translation["comments"]->toXML($indent + 4);
      $xmlstr .= "$is  </comments>\n";
    }
    if ($this->printers != false) {
      $prnlist = "";
      foreach($this->printers as $printer) {
	$prnlist .= $printer->toXML($indent + 4);
      }
      if ($prnlist)
	$xmlstr .= "$is  <printers>\n" . $prnlist . "$is  </printers>\n";
    }
    $xmlstr .= "$is</driver>\n";

    return $xmlstr;
  }

  public function loadDB($id, OPDB $db = null) {
    if ($id == null) {
      return false;
    }
	
    if ($db == null) {
      $db = OPDB::getInstance();
    }
	
    // Clear any previous data present
    unset($this->translation);
    unset($this->supportcontacts);
    unset($this->dependencies);
    unset($this->printers);
    unset($this->packages);
	
    $id = mysql_real_escape_string($id);

    // Load the translations
    foreach(array('supplier', 'license', 'licensetext', 'licenselink', 'shortdescription', 'comments') as $field) {
      $this->translation[$field] = new Translation(null, "driver", array("id" => $id), $field);
      $this->translation[$field]->loadDB("driver", array("id" => $id), $field, $db);
    }

    // Prepare the query string for extracting main driver details
    $result = $db->query('SELECT * FROM driver WHERE id = ?', $id);

    if ($result == null) {
      return false;
    }
    $row = mysql_fetch_assoc($result);
    $this->__construct($row);
    mysql_free_result($result);
	
    // Prepare the query string for extracting details about the driver's support contacts
    $result = $db->query('SELECT * FROM driver_support_contact WHERE driver_id = ?', $this->id);
	
    if ($result) {
      while($row = mysql_fetch_assoc($result)) {
	$this->supportcontacts[sizeof($this->supportcontacts)] = new DriverSupportContact($this->id, $row);
	$this->supportcontacts[sizeof($this->supportcontacts)-1]->loadDB($this->id, $row['url'], $row['level']);
      }
    }
    mysql_free_result($result);
	
    // Prepare the query string for extracting details about the driver's dependencies
    $result = $db->query('SELECT * FROM driver_dependency WHERE driver_id = ?', $this->id);
	
    if ($result) {
      while($row = mysql_fetch_assoc($result)) {
	$this->dependencies[sizeof($this->dependencies)] = new DriverDependency($this->id, $row);
      }
    }
    mysql_free_result($result);
	
    // Prepare the query string for extracting details about the driver's packages
    $result = $db->query('SELECT * FROM driver_package WHERE driver_id = ?', $this->id);

    if ($result) {
      while($row = mysql_fetch_assoc($result)) {
	$this->packages[sizeof($this->packages)] = new DriverPackage($this->id, $row);
      }
    }
    mysql_free_result($result);
	
    // Prepare the query string for extracting details about the printers that work with this driver
    $result = $db->query('SELECT * FROM driver_printer_assoc WHERE driver_id = ?', $this->id);
	
    if ($result) {
      while($row = mysql_fetch_assoc($result)) {
	$this->printers[sizeof($this->printers)] = new DriverPrinterAssociation($this->id, $row);
        $this->printers[sizeof($this->printers)-1]->loadDB($this->id, $row['printer_id']);
      }
    }
    mysql_free_result($result);
	
    return true;
  }

  public function saveDB(OPDB $db = null) {
    if ($db == null) {
      $db = OPDB::getInstance();
    }

    if (!$this->loaded) return false;
	
    $props = array();
    $props['name'] = $this->name;
    $props['driver_group'] = $this->driver_group;
    $props['locales'] = $this->locales;
    $props['obsolete'] = $this->obsolete;
    $props['pcdriver'] = $this->pcdriver;
    $props['url'] = $this->url;
    $props['supplier'] = $this->supplier;
    if ($this->thirdpartysupplied == '') {
      $props['thirdpartysupplied'] = 0;
    } else {
      $props['thirdpartysupplied'] = $this->thirdpartysupplied;
    }
    $props['manufacturersupplied'] = $this->manufacturersupplied;
    $props['license'] = $this->license;
    $props['licensetext'] = $this->licensetext;
    $props['licenselink'] = $this->licenselink;
    if ($this->nonfreesoftware == '') {
      $props['nonfreesoftware'] = 0;
    } else {
      $props['nonfreesoftware'] = $this->nonfreesoftware;
    }
    if ($this->patents == '') {
      $props['patents'] = 0;
    } else {
      $props['patents'] = $this->patents;
    }
    $props['shortdescription'] = $this->shortdescription;
    $props['max_res_x'] = $this->max_res_x;
    $props['max_res_y'] = $this->max_res_y;
    if ($this->color == '') {
      $props['color'] = 0;
    } else {
      $props['color'] = $this->color;
    }
    $props['text'] = $this->text;
    $props['lineart'] = $this->lineart;
    $props['graphics'] = $this->graphics;
    $props['photo'] = $this->photo;
    $props['load_time'] = $this->load_time;
    $props['speed'] = $this->speed;
    $props['execution'] = $this->execution;
    if ($this->nopjl == '') {
      $props['no_pjl'] = 0;
    } else {
      $props['no_pjl'] = $this->nopjl;
    }
    if ($this->nopageaccounting == '') {
      $props['no_pageaccounting'] = 0;
    } else {
      $props['no_pageaccounting'] = $this->nopageaccounting;
    }
    $props['prototype'] = $this->prototype;
    $props['pdf_prototype'] = $this->pdf_prototype;
    $props['ppdentry'] = $this->ppdentry;
    $props['comments'] = $this->comments;
	
    // Find out if there is already an entry present and if so, remove it
    // before creating a new one
    $query = 'SELECT * FROM driver WHERE id = \'' . $this->id . '\'';
    $result = $db->query($query);

    if ($result) {
      $count = mysql_num_rows($result);
      mysql_free_result($result);
      if ($count) {
        if (!$this->removeFromDB($this->id, $db)) {
	  return false;
        }
      }
    } else
      echo "[WARNING] While removing old driver data...\n".$db->getError()."\n";

    // Prepare the query string to insert a new record
    $query = "insert into driver(";
    $fields = "id,";
    $values = "\"{$this->id}\",";
    foreach($props as $key=>$value) {
      $fields .= "$key,";
      // check if type is integer or boolean and adds to DB string (0 is
      // falsy, >=1 is truthy) boolean values will be auto cast to 0 or 1
      // depending on value
      if (((string)gettype($value) == 'integer') or ((string)gettype($value) == 'bool')) {
        if ((int)$value != -1) {
          $values .= (int)$value.",";
        } else {
          // sets to null otherwise
          $values .= "NULL,";
        }
      } else {
	$values .= "\"".mysql_real_escape_string($value)."\",";
      }
    }
    $fields[strlen($fields) - 1] = ')';
    $values[strlen($values) - 1] = ')';
    $query .= $fields." values(".$values;
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While saving driver data...\n".$db->getError()."\n";
      return false;
    }
	
    // Trigger the save of translation data
    if (property_exists($this, 'translation')) {
      foreach ($this->translation as $field => $trobj) {
	if (!$trobj->saveDB($db)) {
	  echo "[ERROR] While saving driver translation data for the \"$field\" field...\n".$db->getError()."\n";
	  return false;
	}
      }
    }
			
    // Trigger the save of package data
    if ($this->packages) {
      foreach ($this->packages as $package) {
	if (!$package->saveDB($db)) {
	  echo "[ERROR] While saving driver package data...\n".$db->getError()."\n";
	  return false;
	}
      }
    }
	
    // Trigger the save of support contact data
    if ($this->supportcontacts) {
      foreach ($this->supportcontacts as $supportcontact) {
	if (!$supportcontact->saveDB($db)) {
	  echo "[ERROR] While saving driver supportcontact data...\n".$db->getError()."\n";
	  return false;
	}
      }
    }
	
    // Trigger the save of dependency data
    if ($this->dependencies) {
      foreach ($this->dependencies as $dependency) {
	if (!$dependency->saveDB($db)) {
	  echo "[ERROR] While saving driver dependency data...\n".$db->getError()."\n";
	  return false;
	}
      }
    }
	
    // Trigger the save of associated printers data
    if ($this->printers) {
      foreach ($this->printers as $printer) {
	if (!$printer->saveDB($db)) {
	  echo "[ERROR] While saving driver printer data...\n".$db->getError()."\n";
	  return false;
	}
      }
    }
	
    return true;
  }

  public function removeFromDB($id, OPDB $db = null, $completeentry = false) {
    if ($id == null) {
      return false;
    }
	
    if ($db == null) {
      $db = OPDB::getInstance();
    }
	
    $id = mysql_real_escape_string($id);

    // Prepare the query string for removing the main driver entry
    $query = "delete from driver where id=\"$id\";";
    // Remove the main entry, this automatically removes also the
    // translations, dependencies, support contacts, and package masks
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting driver data...\n".$db->getError()."\n";
      return false;
    }

    // Complete the deletion by removing the driver-related printer/driver
    // association data.
    // Completely delete printer/driver associations which come only from the
    // driver entry (check also if the printer entry does not exist and
    // also delete in that case)
    $query = "delete driver_printer_assoc " .
      "from driver_printer_assoc left join printer " .
      "on driver_printer_assoc.printer_id=printer.id " .
      "where driver_printer_assoc.driver_id=\"$id\" and " .
      "(fromprinter=false or printer.id is null);";
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting driver data...\n".$db->getError()."\n";
    }
    // Remove driver-specific items from the printer/driver association
    // otherwise
    $query = "update driver_printer_assoc set " .
      "max_res_x=NULL, max_res_y=NULL, color=NULL, text=NULL, lineart=NULL, " . 
      "graphics=NULL, photo=NULL, load_time=NULL, speed=NULL, ppdentry=NULL, " .
      "comments=NULL, fromdriver=false " .
      "where driver_id=\"$id\";";
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting driver data...\n".$db->getError()."\n";
    }
    $query = "update driver_printer_assoc_translation set " .
      "comments=NULL " .
      "where driver_id=\"$id\";";
    $result = $db->query($query);
    if ($result == null) {
      echo "[ERROR] While deleting driver data...\n".$db->getError()."\n";
    }
	
    if ($completeentry) {
      // Delete an uploaded tarball
      $dir = "upload/driver/$id";
      $pwd = exec("pwd");
      exec("rm -rf $pwd/$dir",
         $output = array(), $return_value);
      if ($return_value != 0) {
        echo "[ERROR] Could not remove attached tarball. " .
	    "Error code: $return_value\n";
      }

      // Delete the driver approval data
      $query = "delete from driver_approval where id=\"$id\";";
      // Execute the deletion of approval data. This does not delete any
      // items in other tables
      $result = $db->query($query);
      if ($result == null) {
	echo "[ERROR] While deleting driver approval data...\n".$db->getError()."\n";
      }
    }

    return true;
  }
}
?>
