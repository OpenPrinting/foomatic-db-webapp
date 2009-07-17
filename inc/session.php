<?php

class Session {

	private static $self = false;

	private $loggedIn = false;
	private $user = false;
	private $loginStatus = false;
	
	public static function getInstance() {
		if(empty(Session::$self)) {
			if(!isset($_SESSION['SMGR'])) {
				$s = new Session();
				$_SESSION['SMGR'] = $s;
			} else {
				$s = $_SESSION['SMGR'];
			}
			Session::$self = $s;
		}
		
		return Session::$self;
	}

	public function isLoggedIn() { return $this->loggedIn; }
	
	public function authenticate($u,$p) {
		if(empty($u) || empty($p)) {
			$this->loginStatus = "empty";
			return false;
		}
		
		// Authenticate against LDAP
		$ldap = new LDAP($u,$p);
		if(!$ldap->isBound()) {
			$this->loginStatus = "badcred";
			return false;
		}
		
		unset($ldap);
		
		$this->loggedIn = true;
		$this->user = new User($u);
		return true;
	}
	
	public function startupTasks() {
		if(isset($_GET['doLogin']) && !empty($_POST) && !$this->loggedIn) {
			if($this->authenticate($_POST['username'],$_POST['password'])) {
				header('Location: index.php');
				exit;
			}
		}
		
		if(isset($_GET['doLogout'])) {
			$_SESSION = array();
			header('Location: ./');
			exit;
		}
	}

	public function getLoginStatus() {
		$a =  $this->loginStatus;
		$this->loginStatus = false;
		return $a;
	}
	
	public function getLoginMessage() {
		$stat = $this->getLoginStatus();
		switch($stat) {
			case "empty": return "Both a username and password are required to login.";
			case "badcred": return "Credentials were not valid. Please try again.";
		}
		return false;
	}

	// If not allowed to access this page, redirect to home page.
	// Ought to be called before page headers are sent.
	// If you wanted to make it say "access denied" in a nicer way, you could.
	public function pageLock($permission) {
		if(!$this->requireLogin() || !$this->user->checkPermission($permission)) {
			global $CONF;
			header('Location: '.$CONF->baseURL);
			exit;
		}
	}	
	
	public function requireLogin() {
		if(!$this->isLoggedIn() || empty($this->user)) {
			global $CONF;
			header('Location: '.$CONF->baseURL.'login.php');
			exit;
		}
		return true;
	}	
	
	public function getUser() {
		$a = &$this->user;
		return $a;
	}
	
	public function getUserName() {
		return $this->user->getUserName();
	}
}
?>
