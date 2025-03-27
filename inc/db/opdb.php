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
    $this->server = [];
    $i = count($this->db);
    $this->db[$i] = [];
    $this->db[$i]['id'] = (string)$i+1;
    $this->db[$i]['name'] = "OpenPrinting" . ((string)$i+1);
    $this->db[$i]['server'] = (string)$CONF->dbServer;
    $this->db[$i]['database'] = (string)$CONF->db;
    $this->db[$i]['username'] = (string)$CONF->dbUser;
    $this->db[$i]['password'] = (string)$CONF->dbPass;
    $index = &$this->db[$i];
    $test_con = @mysqli_connect($index['server'], $index['username'], $index['password']);
    if ($test_con->errno != 0 || !$test_con->select_db($index['database'])) {
      unset($this->db[$i]);
      echo "[ERROR]: ".mysqli_error($test_con)."\n";
    } else {
      mysqli_close($test_con);
    }
    $this->server = 0;
    if (!empty($this->db)) {
      $index = $this->db[0];
    }
    $this->connection = @mysqli_connect($index['server'], $index['username'], $index['password']);
    mysqli_select_db($this->connection, $index['database']);
  }

  function __destruct() {
    if ($this->connection !== false) {
      mysqli_close($this->connection);
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
      mysqli_close($this->connection);
      $this->connection = null;
    }
    $index = $this->db[$this->server];
    $this->connection = @mysqli_connect($index['server'], $index['username'], $index['password']);
    mysqli_select_db($index['database'], $this->connection);
    if (mysqli_errno($this->connection) != 0) {
      echo "[ERROR]: ".mysqli_error($this->connection)."\n";
      $this->connection = null;
      return false;
    }
    return true;
  }

  public function close() {
    if ($this->connection !== null) {
      mysqli_close($this->connection);
      $this->connection = null;
    }
  }

  public function query($string) {
    if ($this->connection === null) {
      return false;
    }
    $results = mysqli_query($this->connection, $string);
    return $results;
  }

  public function mysqli_real_escape_string($string) {
    if ($this->connection === null) {
      return false;
    }
    $results = mysqli_real_escape_string($this->connection, $string);
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
    return mysqli_errno($this->connection);
  }

  public function getError() {
    return mysqli_error($this->connection);
  }
}

function printerIDfromMakeModel($make, $model) {
  $mk = $make;
  $mk = str_replace('+', 'plus', $mk);
  $mk = preg_replace('[^A-Za-z0-9\.]+', '_', $mk);
  $mk = preg_replace('^_', '', $mk);
  $mk = preg_replace('_$', '', $mk);
  $mdl = $model;
  $mdl = str_replace('+', 'plus', $mdl);
  $mdl = preg_replace('[^A-Za-z0-9\.\-]+', '_', $mdl);
  $mdl = preg_replace('^_', '', $mdl);
  $mdl = preg_replace('_$', '', $mdl);
  return $mk . '-' . $mdl;
}

?>
