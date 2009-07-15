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
		global $CONF; $b = $CONF->baseURL;
		$this->addBreadcrumb('The Linux Foundation','http://www.linuxfoundation.org/');
		$this->addBreadcrumb('OpenPrinting',$b);
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
		global $CONF; $b = $CONF->baseURL;		
		$this->addGNavItem('OpenPrinting',$b,'home');
		$this->addGNavItem('Database',$b.'404.php?id=db','db');
		$this->addGNavItem('Printers',$b.'printers/','printer');
		$this->addGNavItem('Drivers',$b.'drivers/','driver');
		$this->addGNavItem('FAQ',$b.'404.php?id=faq','faq');
		$this->addGNavItem('Foomatic',$b.'404.php?id=foo','foo');
		$this->addGNavItem('Articles',$b.'404.php?id=articles','articles');
		$this->addGNavItem('Documentation',$b.'404.php?id=doc','doc');
		$this->addGNavItem('Developers',$b.'404.php?id=dev','dev');
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
