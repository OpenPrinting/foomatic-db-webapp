<?php

class User {

	private $valid = false;
	
	private $firstName = false;
	private $lastName = false;
	private $fullName = false;
	private $userName = false;
	private $email = false;
	private $ldapGroups = array();
	
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
			} else {
				// What do we even do if we fail?
			}
		}
	}

	public function isValid() { return $this->valid; }
	public function getUserName() { return $this->userName; }
	public function getFullName() { return $this->fullName; }
	public function getEmail() { return $this->email; }
	


}


?>
