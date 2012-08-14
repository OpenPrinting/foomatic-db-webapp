<?php

class User {

	private $valid = false;
	
	private $firstName = false;
	private $lastName = false;
	private $fullName = false;
	private $userName = false;
	private $email = false;
	private $ldapGroups = array();
	
	private $permissions = array();
	
	public function __construct($userName = false) {
		if($userName) {
			// Fetch some data from LDAP, eh?
			$ldap = new LDAP(); // anon bind is ok
			$usr = $ldap->getUser($userName);
			unset($ldap);      
			if($usr) {
				$this->firstName = $usr['firstName'];
				$this->lastName = $usr['lastName'];
				$this->fullName = $usr['fullName'];
				$this->userName = $usr['userName'];
				$this->email = $usr['email'];
				$this->valid = true;
      	}
      	else 
      	{
			// What do we even do if we fail?
		}
	  }
	}

	public function isValid() { 
		return $this->valid; 
	}
	
	public function getUserName() { 
		return $this->userName; 
	}
	
	public function getFullName() { 
		return $this->fullName; 
	}
	public function getEmail() { 
		return $this->email; 
	}
	
	public function getPerms(){
		return $this->permissions;
	}
	
	public function isUploader($arr){
		if(array_key_exists('1',$arr)){
			if($arr['1'] == "Uploader"){
				return true;
			}
		}
		else{
			return false;
		}
	}
	public function isTrustedUploader($arr){
		if(array_key_exists('2',$arr)){
			if($arr['2'] == "Trusted Uploader"){
				return true;
			}
		}
		else{
			return false;
		}
	}

	// Return true if permission is granted, false if otherwise
	public function checkPermission($priv) {
		if(empty($this->permissions)) $this->loadPermissions();
		if(isset($this->permissions[$priv]) && $this->permissions[$priv] == 1) return true;
		return false;
	}
	
	// Return a list of roles user is assigned to, array( role ID => role name )
	public function fetchUserRoles() {
		$DB = DB::getInstance();
		$roles = array();

		$res = $DB->query("SELECT wr.roleID, wr.roleName 
							FROM web_roles_userassign ua 
							JOIN web_roles wr 
								ON wr.roleID = ua.roleID 
							WHERE uid = ?
							ORDER BY roleName",$this->userName);
		while($r = $res->getRow()) {
			$roles[$r['roleID']] = $r['roleName'];
		}
		
		return $roles;
	}

	
	// Compute the permissions this user has.... all permissions get loaded into
	// the $this->permissions array as privName => value, where value can be
	// -1 for never, 0 for unset (basically "no") and 1 for allowed.
	// Load all permissions from all roles, then, one at a time, adjust the value
	// appropriately.
	private function loadPermissions() {
		$DB = DB::getInstance();
		
		$roles = array_keys($this->fetchUserRoles());
		$privs = array();
		if(count($roles)) {
			$res = $DB->query("SELECT wp.privName, value 
						FROM web_permissions wp 
						LEFT JOIN web_roles_privassign pa 
							ON wp.privName = pa.privName AND pa.roleID IN (".implode(',',$roles).")");
			while($r = $res->getRow()) {
				if($r['value'] == NULL) $r['value'] = 0;   // permission not yet added for this role
				if(!isset($privs[$r['privName']])) $privs[$r['privName']] = 0; // first encounter, set unset
				
				if($privs[$r['privName']] == 0) {
					// The loaded permission has a value of 0. We can overwrite it anytime.
					$privs[$r['privName']] = $r['value'];
				} else if ($r['value'] == -1) {
					// This role's permission is a "never" so we can always apply it.
					$privs[$r['privName']] = -1;
				}	
			}
		}

		$this->permissions = $privs;
	}

}


?>
