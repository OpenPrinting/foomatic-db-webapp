<?php

class SiteConfig {

  // %s will be replaced with the page title as set per page
  public $htmlTitle = '%s | OpenPrinting - The Linux Foundation';

  public $baseURL = '/'; // with tailing slash, from document root
  public $mainURL = 'http://openprinting.github.io'; //main site for tabs
  public $mainURI = '/'; // with tailing slash, from document root

  /*public $dbUser = 'opuser';
  public $dbPass = 'Goose5ai';
  public $db = 'openprinting';
  public $dbServer = 'db.linuxfoundation.org';*/

  public $dbUser = 'root';
  public $dbPass = 'lfdev';
  public $db = 'openprinting_dev';
  public $dbServer = 'localhost';
  /* Allow login using db credentials? */
  public $allowDBlogin = true;

  public $authType = 'cas';

  public $casServer = 'cas.example.com';
  public $casPort = 443;
  public $casContext = '/cas';
  public $casCaCert = '/etc/ssl/certs/ca-certificates.crt';

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
