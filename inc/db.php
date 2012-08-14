<?php	
class Result {
	
	private $statement;
	public $hasError = false;
	
	public function __construct(PDOStatement $statement) {
		$this->statement = $statement;
	}
	
	public function toArray($fieldAsIndex = false) {
		if ($this->hasError) {
			return false;
		}
		
		$result = array();
		
		while ($row = $this->statement->fetch(PDO::FETCH_ASSOC)) {
			if (!$fieldAsIndex) {
				array_push($result, $row);
			} else {
				$result[$row[$fieldAsIndex]] = $row;
			}
		}
		
		return $result;
	}
	
	public function getRow() {
		if ($this->hasError) {
			return false;
		}
		
		return $this->statement->fetch(PDO::FETCH_ASSOC);
	}
	
	public function numRows() {
		if ($this->hasError) {
			return false;
		}
		
		return $this->statement->rowCount();
	}
	
	
	/*
	TODO: reimplement?
	public function seek($to) {
		
	}
	*/
	
	// FIXME: deprecated
	public function free() {
		return true;
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
		// TODO: move to common.php, call with DB::getInstance
		global $CONF;
		
		try {
			$this->connection = new PDO('mysql:host=' . $CONF->dbServer . ';dbname=' . $CONF->db, $CONF->dbUser, $CONF->dbPass);
		} catch (PDOException $exception) {
			// TODO: die more gracefully
			die('Database connection error.');
		}
	}
	
	public function __destroy() {
		$this->mysqli->close();
	}
		
	// Starts a transaction.
	function beginTransaction() {
		return $this->connection->beginTransaction();
	}
	
	// Commits all changes made since the transaction began.
	function commit() {
		return $this->connection->commit();
	}
	
	// Undoes all changes since the beginning of the  transaction.
	function rollback() {
		return $this->connection->rollBack();
	}
	
	// Number of affected rows by the last performed query.
	function affectedRows() {
		if ($this->lastStatement === null) {
			return false;
		}
		
		return $this->lastStatement->rowCount();
	}
	
	// Unique autonumber of the last inserted record.
	function lastInsertID() {
		return $this->connection->lastInsertId();
	}
	
	function query($query, $arguments = array()) {
		
		$this->lastStatement = $this->connection->prepare($query);
		
		if (!is_array($arguments)) {
			$arguments = array_slice(func_get_args(), 1);
		}
		
		$result = new Result($this->lastStatement);
		
		if (!$this->lastStatement->execute($arguments)) {
			$result->hasError = true;
			// TODO: report errors as exception?
			$error = $this->lastStatement->errorInfo();
			trigger_error($error[2] . ' [[' . $query . ']]', E_USER_ERROR);
			return false;
		}
		
		return $result;
		/*
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
		*/
	}	
}

?>