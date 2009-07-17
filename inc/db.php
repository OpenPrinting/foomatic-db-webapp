<?php	
class Result {
	
	private $result = false;
	public $hasError = false;
	private $currentRow = 0;
	
	public function Result($result) {
		$this->result = $result;
	}
	
	public function toArray($fieldAsIndex = false) {
		if($this->hasError) return false;
		$arr = array();
		while($r = $this->result->fetch_assoc()) {
			if(!$fieldAsIndex) 
				array_push($arr,$r);
			else
				$arr[$r[$fieldAsIndex]] = $r;
		}
		return $arr;
	}
	
	public function getRow() {
		if($this->result == false) {
			return false;
		} else {
			return $this->result->fetch_assoc();
		}
	}
	
	public function numRows() {
		if($this->hasError) {
			return 0;
		} else {
			return $this->result->num_rows;
		}
	}
	
	public function seek($to) {
		if($to < 0 || $to >= $this->numRows()) return false;
		return $this->result->data_seek($to);
	}
	
	public function free() {
		return $this->result->free();
	}
}
	
class DB {

	private $mysqli = false;
	public $lasterror = false;
	
	private static $instance = false;
	public static function getInstance() {
		if(!DB::$instance) DB::$instance = new DB();
		return DB::$instance;
	}

	public function __construct() {	
		global $CONF;
		$this->mysqli = @new mysqli($CONF->dbServer, $CONF->dbUser, $CONF->dbPass, $CONF->db);
		if(mysqli_connect_errno()) {
			die('Database connection error.');
			exit;
		}	
	}
	
	public function __destroy() {
		$this->mysqli->close();
	}
		
	// Starts a transaction.
	function beginTransaction() {
		return @$this->mysqli->autocommit(false);
	}
	
	// Commits all changes made since the transaction began.
	function commit() {
		$res = @$this->mysqli->commit();
		$this->mysqli->autocommit(true);
		return $res;
	}
	
	// Undoes all changes since the beginning of the  transaction.
	function rollback() {
		$res = @$this->mysqli->rollback();
		$this->mysqli->autocommit(true);
		return $res;
	}
	
	// Number of affected rows by the last performed query.
	function affectedRows() {
		return $this->mysqli->affected_rows;
	}
	
	// Unique autonumber of the last inserted record.
	function lastInsertID() {
		return $this->mysqli->insert_id;
	}
	
	function query($query) 
	{
		$args  = func_get_args();
		$query = array_shift($args);		
		$query = str_replace("?", "%s", $query);
		// add magic quotes check
		if(get_magic_quotes_gpc())
			$args  = array_map('stripslashes', $args);
		foreach($args as &$arg) {
			$arg = html_entity_decode($arg);
			$arg = $this->mysqli->escape_string($arg);
		}
		if(count($args) > 0) {
			array_unshift($args,$query);
			$query = call_user_func_array('sprintf',$args);
		}
		//echo $query;
		$result = @$this->mysqli->query($query);
		$res = new Result($result);
		if(!$result) {
			$this->lasterror = $this->mysqli->error;
			$res->hasError = true;
			trigger_error($this->mysqli->error.' [['.$query.']]');
			return false;
		} else 
			return $res;
	}	
}

?>
