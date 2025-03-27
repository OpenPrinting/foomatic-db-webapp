<?php

class Page {

	private static $self = false;

	private $pageTitle = 'Untitled';
	// Holds the array of breadcrumbs
	private $breadcrumbs = [];
	private $gNavItems = [];
	private $activeID = '';
	private $smarty = false;
	
	public static function getInstance() {
		if(empty(Page::$self)) Page::$self = new Page();
		return Page::$self;
	}
	
	// Initiate the page
	public function __construct() {
		global $CONF;
		// Base "local" URL
		$m = $CONF->mainURL;		
		// Main openprinting workgroup base URL
		$mb = $CONF->mainURL . $CONF->mainURI;
		// Add initial breadcrumb items
		$this->addBreadcrumb('OpenPrinting',$mb);

		// Add second level navigation items
		$this->addGNavItems();
	}
	
	public function setSmarty($s) { $this->smarty = $s; }
	public function getSmarty() { return $this->smarty; }
	
	// Set the active page for CSS display purposes
	// Typically this is called in the individual pages (i.e. printer_detail.php) as one of the first items set
	public function setActiveID($str)		{ $this->activeID = $str; }

	public function getActiveID()			{ return $this->activeID; }

	// Set the page title
	// Typically this is called in the individual pages (i.e. printer_detail.php) as one of the first items set
	public function setPageTitle($str) 		{ $this->pageTitle = $str; }

	public function getPageTitle() 			{  global $CONF; return sprintf($CONF->htmlTitle,$this->pageTitle); }

	// Add an item to the breadcrumbs array
	// Typically this is called in the individual pages (i.e. printer_detail.php) as one of the first items set
	public function addBreadcrumb($name,$link = false) {
		// Create a new Breadcrumb object and add to the breadcrumbs array
		array_push($this->breadcrumbs,new Breadcrumb($name,$link));
	}

	public function getBreadcrumbs() 		{ return $this->breadcrumbs; }
	public function getBreadcrumbsCount() 	{	return count($this->breadcrumbs)-1;}
	private function addGNavItem($n,$l,$i) 	{ array_push($this->gNavItems,new GNavItem($n,$l,$i)); }
	public function getGNavItems() 			{ return $this->gNavItems; }

	// Create Second Level Navigation Items
	public function addGNavItems() {
	
		global $CONF; 
		// Base "local" URL
		$b = $CONF->baseURL;		
		// Main openprinting workgroup base URL
		$mb = $CONF->mainURL . $CONF->mainURI;
		
		// Create subnavigation
		// Most of this points to the corporate site static pages for openprinting
		// Only Printers and Drivers are "local"
		$this->addGNavItem('OpenPrinting',$mb,'home');
		$this->addGNavItem('News and Events',$mb.'news/','news');
		$this->addGNavItem('Projects',$mb.'projects/','projects');
		$this->addGNavItem('Downloads',$mb.'downloads/','downloads');
		$this->addGNavItem('Driverless',$mb.'driverless/','driverless');
		$this->addGNavItem('Database',$mb.'databaseintro','db');
		$this->addGNavItem('Printers',$b.'printers','printer');
		$this->addGNavItem('Drivers',$b.'drivers','driver');
		$this->addGNavItem('Contact Us',$mb.'contact/','contact');
		$this->addGnavItem('Donations',$mb.'donations/','donation');
	}
}

class Breadcrumb {
	// Display Name
	public $name = false;
	// Link
	public $link = false;
	public function __construct($n,$l=false) {
		$this->name = $n;
		$this->link = $l;
	}
}

class GNavItem {
	// Display Name
	public $name = false;
	// Link
	public $link = false;
	// ID used for identifiying the active and inactive navigation items
	public $id = false;
	public function __construct($n,$l,$i) {
		$this->name = $n;
		$this->link = $l;
		$this->id = $i;
	}
}

?>
