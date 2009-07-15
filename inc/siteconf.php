<?php

class SiteConfig {
		
	public $htmlTitle = '%s | OpenPrinting - The Linux Foundation';
		// %s will be replaced with the page title as set per page
		
	public $baseURL = '/'; // with tailing slash, from document root

	public $dbUser = false;
	public $dbPass = false;
	public $dbServer = false;
	public $db = false;
	
	/*public $ldapServer = '140.211.169.120';
	public $ldapServer2 = false;
	public $ldapBaseDN = 'dc=lf,dc=org';
	public $ldapUserBaseDN = 'ou=Users,dc=lf,dc=org';
	public $ldapUsernameField = 'uid';
	public $ldapMailField = 'mail';*/
	
	public $ldapServer = 'ldap1.linux-foundation.org';
	public $ldapServer2 = 'ldap2.linux-foundation.org';
	public $ldapBaseDN = 'dc=freestandards,dc=org';
	public $ldapUserBaseDN = 'ou=Users,dc=freestandards,dc=org';
	public $ldapUsernameField = 'uid';
	public $ldapMailField = 'mail';
	
}

?>
