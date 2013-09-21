<?php

class SiteConfig {
		
	public $htmlTitle = '%s | OpenPrinting - The Linux Foundation';
		// %s will be replaced with the page title as set per page
		
	public $baseURL = '/'; // with tailing slash, from document root
	public $mainURL = 'http://www.linuxfoundation.org'; //main site for tabs
	public $mainURI = '/collaborate/workgroups/openprinting/'; // with tailing slash, from document root

	public $dbUser = 'opuser';
	public $dbPass = 'Goose5ai';
	public $db = 'openprinting';
	public $dbServer = 'db.linuxfoundation.org';
	/* Allow login using db credentials? */
	public $allowDBlogin = false;
	
	/*public $dbUser = 'root';
	public $dbPass = 'administrator';
	public $db = 'openprinting';
	public $dbServer = 'localhost';*/
	
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
  
  //Set size for pagination for printer and driver queue
  public $printer_queue_pagesize = 5;
  public $driver_queue_pagesize = 5;
  
  //Set pagination for assigned role users
  public $role_pagesize = 10;
  
  //Set email connection and messages SMTP
  public $mailhost = "localhost";
  public $mailusername = '';
  public $mailpassword = '';
  
  //driver upload mail configuration
  public $mailsendaddress_driver ="openprinting@linuxfoundation.org";
  
  public $mailfrom_driver ="openprinting@linuxfoundation.org";
  public $mailfromname_driver = "OpenPrinting";
  
  public $mailsubject_driver = "Driver Uploaded to Openprinting";
  public $mailbody_driver = "Driver upload test Test .";
  
  
  //printer upload mail configuration
  public $mailsendaddress_printer ="openprinting@linuxfoundation.org";
  
  public $mailfrom_printer ="openprinting@linuxfoundation.org";
  public $mailfromname_printer = "OpenPrinting";
  
  public $mailsubject_printer = "Printer Uploaded to Openprinting";
  public $mailbody_printer = "Printer upload test Test .";
  
  
}

?>
