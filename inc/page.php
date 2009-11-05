<?php

class Page {

	private static $self = false;

	private $pageTitle = 'Untitled';
	private $breadcrumbs = array();
	private $gNavItems = array();
	private $activeID = '';
	private $smarty = false;
	
	public static function getInstance() {
		if(empty(Page::$self)) Page::$self = new Page();
		return Page::$self;
	}
	
	public function __construct() {
		global $CONF;
		$m = $CONF->mainURL;		
		$mb = $CONF->mainURL . $CONF->mainURI;
		$this->addBreadcrumb('The Linux Foundation',$m);
		$this->addBreadcrumb('OpenPrinting',$mb);
		$this->addGNavItems();
	}
	
	public function setSmarty($s) { $this->smarty = $s; }
	public function getSmarty() { return $this->smarty; }
	
	public function setActiveID($str)		{ $this->activeID = $str; }
	public function getActiveID()			{ return $this->activeID; }
	public function setPageTitle($str) 		{ $this->pageTitle = $str; }
	public function getPageTitle() 			{  global $CONF; return sprintf($CONF->htmlTitle,$this->pageTitle); }
	public function addBreadcrumb($name,$link = false) {array_push($this->breadcrumbs,new Breadcrumb($name,$link));}
	public function getBreadcrumbs() 		{ return $this->breadcrumbs; }
	public function getBreadcrumbsCount() 	{	return count($this->breadcrumbs)-1;}
	private function addGNavItem($n,$l,$i) 	{ array_push($this->gNavItems,new GNavItem($n,$l,$i)); }
	public function getGNavItems() 			{ return $this->gNavItems; }
	public function addGNavItems() {
		global $CONF; 
		$b = $CONF->baseURL;		
		$mb = $CONF->mainURL . $CONF->mainURI;
		
		//Create subnavigation
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
	public $name = false;
	public $link = false;
	public function __construct($n,$l=false) {
		$this->name = $n;
		$this->link = $l;
	}
}

class GNavItem {
	public $name = false;
	public $link = false;
	public $id = false;
	public function __construct($n,$l,$i) {
		$this->name = $n;
		$this->link = $l;
		$this->id = $i;
	}
}

?>
