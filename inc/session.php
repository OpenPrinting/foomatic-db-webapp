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

	public function isLoggedIn() { 
		return $this->loggedIn; 
	}
	
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
				header('Location: /account/myuploads');
				exit;
			}
		}
		
		if(isset($_GET['doLogout'])) {
			$_SESSION = array();
			header('Location: /logout');
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

	// If not allowed to access this page, let the user know.
	// Ought to be called before page headers are sent.
	public function pageLock($permission) {
		if(!$this->checkPermission($permission)) {
			global $CONF;
			$PAGE = Page::getInstance();
			$SMARTY = $PAGE->getSmarty();
			$PAGE->setPageTitle('Access Denied');
			$PAGE->setActiveID('');
			$PAGE->addBreadCrumb('Access Denied');
			$SMARTY->display('restricted_page.tpl');
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
	
	public function checkPermission($permission) {
		if(!$this->isLoggedIn() || empty($this->user) || !$this->user->checkPermission($permission)) return false;
		else return true;
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
