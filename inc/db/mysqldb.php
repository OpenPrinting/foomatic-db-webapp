<?php
class OPDB
{
  // the actual connection resource
  private $connection;

  // points to the current database selected
  private $server;
	
  // A handle to the instance of this class
  private static $instance;
	
  // An array containing the possible servers and its configurations
  private $db;
	
  // path to the file containing server information
  private static $config_file = "/home/till/printing/foomatic/openprinting/www/includes/openprintingdb.xml";

  private function __construct() {
    global $CONF;
    $this->server = array();
    $i = sizeof($this->db);
    $this->db[$i] = array();
    $this->db[$i]['id'] = (string)$i+1;
    $this->db[$i]['name'] = "OpenPrinting" . (string)$i+1;
    $this->db[$i]['server'] = (string)$CONF->dbServer;
    $this->db[$i]['database'] = (string)$CONF->db;
    $this->db[$i]['username'] = (string)$CONF->dbUser;
    $this->db[$i]['password'] = (string)$CONF->dbPass;
    $index = &$this->db[$i];
    $test_con = @mysql_connect($index['server'], $index['username'], $index['password']);
    if (mysql_errno($test_con) != 0 || !mysql_select_db($index['database'])) {
      unset($this->db[$i]);
      echo "[ERROR]: ".mysql_error($test_con)."\n";
    } else {
      mysql_close($test_con);
    }
    $this->server = 0;
    $index = $this->db[0];
    $this->connection = @mysql_connect($index['server'], $index['username'], $index['password']);
    mysql_select_db($index['database'], $this->connection);
  }

  function __destruct() {
    if ($this->connection !== false) {
      mysql_close($this->connection);
      $this->connection = null;
    }
  }
	
  public function getInstance() {
    if (!isset(self::$instance)) {
      $class = __CLASS__;
      self::$instance = new $class;
    }
    return self::$instance;
  }
	
  public function getDBCount() {
    return sizeof($this->db);
  }

  public function getDBDetails($n) {
    $n--;
    if ($n < 0 || $n >= sizeof($this->db)) {
      return null;
    }
    return $this->db[$n];
  }
	
  public function open() {
    if ($this->connection !== null) {
      mysql_close($this->connection);
      $this->connection = null;
    }
    $index = $this->db[$this->server];
    $this->connection = @mysql_connect($index['server'], $index['username'], $index['password']);
    mysql_select_db($index['database'], $this->connection);
    if (mysql_errno($this->connection) != 0) {
      echo "[ERROR]: ".mysql_error($this->connection)."\n";
      $this->connection = null;
      return false;
    }
    return true;
  }

  public function close() {
    if ($this->connection !== null) {
      mysql_close($this->connection);
      $this->connection = null;
    }
  }
	
  public function query($string) {
    if ($this->connection === null) {
      return false;
    }
    $results = mysql_query($string, $this->connection);
    return $results;
  }
	
  public function changeDB($n) {
    $n--;
    if ($n < 0 || $n >= sizeof($this->db)) {
      return false;
    }
    $this->server = $n;
    return $this->open();
  }
	
  public function getErrorNo() {
    return mysql_errno($this->connection);
  }
	
  public function getError() {
    return mysql_error($this->connection);
  }
}

function printerIDfromMakeModel($make, $model) {
  $mk = $make;
  $mk = str_replace('+', 'plus', $mk);
  $mk = ereg_replace('[^A-Za-z0-9\.]+', '_', $mk);
  $mk = ereg_replace('^_', '', $mk);
  $mk = ereg_replace('_$', '', $mk);
  $mdl = $model;
  $mdl = str_replace('+', 'plus', $mdl);
  $mdl = ereg_replace('[^A-Za-z0-9\.\-]+', '_', $mdl);
  $mdl = ereg_replace('^_', '', $mdl);
  $mdl = ereg_replace('_$', '', $mdl);
  return $mk . '-' . $mdl;
}

?>
