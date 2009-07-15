<?php

class LDAP {

	public $ldap = false;
	
	public function __construct($u = false, $p = false) {
		global $CONF;
		$ldap = @ldap_connect($CONF->ldapServer);
		if(!$ldap) {
			if($CONF->ldapServer2) $ldap = @ldap_connect($CONF->ldapServer2);
			if(!$ldap) die('Could not connect to LDAP server.');
		}
		
		
		{
			ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
			
			if(!empty($u) && !empty($p)) {
				$bind = @ldap_bind($ldap,$CONF->ldapUsernameField.'='.$u.','.$CONF->ldapUserBaseDN,$p);
			} else {
				$bind = @ldap_bind($ldap);
			}
			
			if($bind) {
				$this->ldap = $ldap;
			}
		}
	}
	
	public function isBound() {
		return ($this->ldap ? true : false);
	}
	
	public function __destroy() {
		if($this->isBound()) {
			ldap_unbind($this->ldap);
		}
	}
	
	public function getUser($user) {
		if($this->isBound()) {
			global $CONF;
			
			$result = ldap_search($this->ldap, $CONF->ldapUserBaseDN, '('.$CONF->ldapUsernameField.'='.$user.')');
			$info = ldap_get_entries($this->ldap, $result);
			
			if($info['count'] > 0) {
				$d = array();
				$d['userName'] = $info[0][$CONF->ldapUsernameField][0];
				$d['fullName'] = $info[0]['cn'][0];
				$d['firstName'] = $info[0]['givenname'][0];
				$d['lastName'] = $info[0]['sn'][0];
				$d['email'] = $info[0][$CONF->ldapMailField][0];
				return $d;
			} else return false;
		} else return false;
			
	}

}


?>
