<?php

class Session {

	private static $self = false;

	private $loggedIn = false;
	private $user = false;
	private $loginStatus = false;
  private $referrer = "";
	
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
		
		$loggedIn = false;

		// Authenticate against LDAP
		$ldap = new LDAP($u,$p);
		if(!$ldap->isBound()) {
			// Authenticate using the database
			// (try to check if this user has db access and log him in, if this is the case)
			try {
				$test_connection = new PDO('mysql:host=' . $CONF->dbServer . ';dbname=' . $CONF->db, $u, $p);
				$loggedIn = true;
			} catch (PDOException $exception) {
				// Do not show any message now, just remember that we have failed to login
			}
			
			if( !$loggedIn ) {
				$this->loginStatus = "badcred";
				return false;
			}
		}
		unset($ldap);

		$this->loggedIn = true;
		$this->user = new User($u);
		return true;
	}
	
	public function startupTasks() {
		//@TODO Need to login and return to the page you logged in from
		
		if(isset($_GET['doLogin']) && !empty($_POST) && !$this->loggedIn) {
			if($this->authenticate($_POST['username'],$_POST['password'])) {
				
        /*  05-23-2010 PCN
         *  Added table web_user to openprinting database.
         *  user information ( username, full name, user ip address, referring url)
         *  logged in this table when user logs in 
         * */ 
        //Try to find web_user if already in database
        $DB = DB::getInstance();
        $res = $DB->query("SELECT id
							FROM web_user
							WHERE username = ?
              AND name=?"
							,$this->user->getUserName(),$this->user->getFullName() );
        $numrow = $res->numRows();
        if($numrow == 0)
        {
          //if user not found insert user into web_user table
          $DB = DB::getInstance();
          $DB->query("INSERT INTO web_user(username,name,lastlogin,ipaddress,referrer) 
                      VALUES (?,?,NOW(),?,?)",
                      $this->user->getUserName(), 
                      $this->user->getFullName(),
                      $this->ip(),
                      $this->getReferrer());
        }
        else
        {
          //update user in web_user table
          $webuser = $res->getRow();
          $DB = DB::getInstance();
          $DB->query("UPDATE web_user 
                      SET lastlogin = NOW(),
                      ipaddress = ?,
                      referrer=?
                      WHERE id = ?",
                      $this->ip(),$this->getReferrer(),$webuser['id']);
          
        }
        
        //check and set default permissions
        if(!$this->user->fetchUserRoles())
        {
          
          //Get role ID for default Uploader role
          $DB = DB::getInstance();
          //$res =$DB->query("SELECT roleID FROM web_roles where roleName = 'Uploader'"); //Needs to be printer uploader per Till
          $res =$DB->query("SELECT roleID FROM web_roles where roleName = ?", array('Printer Uploader'));
          if($r = $res->getRow()) {
           $id = $r['roleID'];
          } 
          
          $role = new UserRole($id);
          $role->addMember($this->user->getUserName());
        }
         
        header('Location: /account/myuploads');
				exit;
			}
		}
		
		if(isset($_GET['doLogout'])) {
			$_SESSION = array();
			header('Location: /login');
			exit;
		}
	}
  
  /*  05-23-2010 PCN
   * added function to get user ip address 
   * */
  function ip() {
     if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
     {
       $ip=$_SERVER['HTTP_CLIENT_IP'];
     }
     elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
     {
       $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];

     }
     else
     {
       $ip=$_SERVER['REMOTE_ADDR'];
     }
     return $ip;
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
  
  /*  05-23-2010 PCN
   * added setter and getter for user redferring url
   * */
  public function setReferrer($ref) {
		$this->referrer = $ref;
    return;
	}
  
  public function getReferrer() {
		return $this->referrer;
	}
}
?>
