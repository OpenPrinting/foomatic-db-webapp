<?php

class UserRole {
	public $roleName;
	public $roleID;
	
	private $isValid = false;
	
	
	public function __construct($roleID = false) {
		if($roleID) {
			$DB = DB::getInstance();
			$res = $DB->query("SELECT * FROM web_roles WHERE roleID = '?' ",$roleID);
			if($r = $res->getRow()) {
				$this->isValid = true;
				$this->roleName = $r['roleName'];
				$this->roleID = $r['roleID'];
			} 
		}
	}
	
	public function addMember($userName) {
		if($this->isValid) {
			$DB = DB::getInstance();
			$DB->query("INSERT INTO web_roles_userassign SET uid = '?', roleID = '?'",$userName,$this->roleID);
		}	
	}
	
	public function removeMember($userName) {
		if($this->isValid) {
			$DB = DB::getInstance();
			$DB->query("DELETE FROM web_roles_userassign WHERE uid = '?' AND roleID = '?'",$userName,$this->roleID);
		}	
	}
	
	public function getMembers() {
		if($this->isValid) {
			$DB = DB::getInstance();
			$res = $DB->query("SELECT uid FROM web_roles_userassign WHERE roleID = '?' ORDER BY uid",$this->roleID);
			$members = array();
			while($r = $res->getRow()) {
				array_push($members,$r['uid']);
			}
			return $members;
		}	
		return false;
	}
	
	// Returns array( privName => array(privName, title, value)
	public function getPermissions($keyByName = true) {
		$DB = DB::getInstance();
		$res = $DB->query("SELECT wp.privName, title, value 
					FROM web_permissions wp 
					LEFT JOIN web_roles_privassign pa 
						ON wp.privName = pa.privName AND pa.roleID = '?' ",$this->roleID);
		$privs = $res->toArray('privName');
		
		return $privs;
	}
	
	public function isValid() { return $this->isValid; }
}
?>
