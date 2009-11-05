<?php

class Page {

	private static $self = false;

	private $pageTitle = 'Untitled';
	// Holds the array of breadcrumbs
	private $breadcrumbs = array();
	private $gNavItems = array();
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
		$this->addBreadcrumb('The Linux Foundation',$m);
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
		$this->addGNavItem('Database',$mb.'database/databaseintro','db');
		$this->addGNavItem('Printers',$b.'printers','printer');
		$this->addGNavItem('Drivers',$b.'drivers','driver');
		$this->addGNavItem('FAQ',$mb.'database/indexfaq','faq');
		$this->addGNavItem('Foomatic',$mb.'database/foomatic','foo');
		$this->addGNavItem('Articles',$mb.'database/articles','articles');
		$this->addGNavItem('Projects',$mb.'database/projects','projects');
		$this->addGNavItem('Documentation',$mb.'database/docs','doc');
		$this->addGNavItem('Developers',$mb.'development','dev');
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
