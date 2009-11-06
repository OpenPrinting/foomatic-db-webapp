<?php
// -*- PHP -*-
require_once("db.php");

class Translation
{
	// Boolean flag to determine if data is present
	private $loaded;
	
	// Name of the table to which the translation belongs
	public $table;
	// Array of primary keys and their values to identify the record to
	// which the translation belongs
	public $pkeys;
	// Name of the field which is translated
	public $field;
	// Array with the actual translations
	public $translations;
	
	public function __construct($data = null, $table = null, $pkeys = null, $field = null) {
                if ($table == null || $pkeys == null || $field == null) {
                        $this->loaded = false;
                        return false;
                }
                
                $this->table = $table;
                $this->pkeys = $pkeys;
		$this->field = $field;
		$this->translations = array();
		
		if ($data != null) {
			switch((string)gettype($data)) {
				case 'array':
					foreach ($data as $lang => $trans) {
					    $this->translations[$lang] = (string)$trans;
					}
					$this->loaded = true;
				break;
				
				case 'object':
					if (get_class($data) == "SimpleXMLElement") {
					   	// NOTE: After some testing we will ignore English text as it goes into the main tables
						$internationalized = 0;
						foreach	($data->children() as $ch) {
							$lang = $ch->getName();
							if ($field != "licenselink") {
								$this->translations[$lang] = (string)$ch[0];
							} else {
								$this->translations[$lang] = (string)$ch['url'];
							}
							$internationalized = 1;
						}
						if ($internationalized == 0) {
						   	if ($field != "licenselink") {
								$lang = "en";
								$this->translations[$lang] = (string)$data[0];
							}
						}
					}
					$this->loaded = true;
				break;
			}
		}
	}
	
	/*
	 * Initialize class from an XML string
	 * @return bool True if initialization was successful
	 * @param $data string Contains the XML as a string
	 */
	public function loadXMLString($data, $table = null, $pkeys = null, $field = null) {
		$xml = simplexml_load_string($data);
		if (!$xml) {
			return false;
		}
		
		$this->__construct($xml, $table, $pkeys, $field);
		
		return $this->loaded;
	}
	
	public function loadDB($table = null, $pkeys = null, $field = null, DB $db = null) {
                if ($table == null || $pkeys == null || $field == null) {
                        return false;
                }
                
                $this->table = $table;
                $this->pkeys = $pkeys;
		$this->field = $field;
		
		if ($db == null) {
			$db = DB::getInstance();
		}
		
		// Clear any previous data present
		unset($this->translations);
		
		// Prepare the query string for extracting the desired translations
		$query = "select lang, $field from ${table}_translation where";
		foreach($pkeys as $key => $value) {
			$query .= " $key=\"$value\" and";
		}
		$query = substr($query, 0, -4);
		$query .= ";";

		$result = $db->query($query);
		
		if ($result == null) {
			return false;
		}

		$t = array();
		while($row = mysql_fetch_assoc($result)) {
			$t[$row["lang"]] = $row[$field];
		}

		$this->__construct($t, $table, $pkeys, $field);
		mysql_free_result($result);
		
		return true;
	}
	
	public function saveDB(DB $db = null) {
		if ($db == null) {
			$db = DB::getInstance();
		}
		
		if (!$this->loaded) return false;

                $table = $this->table;
                $pkeys = $this->pkeys;
		$field = $this->field;
		$t = $this->translations;

		$pkeys_expr = "";
		$pkeys_fields = "";
		$pkeys_values = "";
		foreach($pkeys as $key => $value) {
			$pkeys_expr .= " $key=\"".mysql_real_escape_string($value)."\" and";
			$pkeys_fields .= "$key, ";
			$pkeys_values .= "\"".mysql_real_escape_string($value)."\", ";
		}
		$pkeys_expr = substr($pkeys_expr, 1, -4);
		$pkeys_fields = substr($pkeys_fields, 0, -2);
		$pkeys_values = substr($pkeys_values, 0, -2);

		// Loop through each translation and add it to the table.
		// We must add the translations one by one as they go into
		// different lines
		foreach($t as $lang => $trans) { 
			// Find out if there is already an entry present
			$query = "select lang from ${table}_translation where $pkeys_expr and lang=\"$lang\";";
			$result = $db->query($query);
			$count = mysql_num_rows($result);
			mysql_free_result($result);
		
			// Prepare the query string. Update if data exists or insert a new record
			if ($count) {
				$query = "update ${table}_translation set $field=\"".mysql_real_escape_string($trans)."\" where $pkeys_expr and lang=\"$lang\";";
			} else {
				$query = "insert into ${table}_translation($pkeys_fields, lang, $field) values($pkeys_values, \"$lang\", \"".mysql_real_escape_string($trans)."\");";
			}
			$result = $db->query($query);
			if ($result == null) {
				echo "[ERROR] While saving translation data...\n".$db->getError()."\n";
				return false;
			}
		}
		
		return true;
	}
}
?>
