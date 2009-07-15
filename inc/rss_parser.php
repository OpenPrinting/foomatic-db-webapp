<?php

class RSS_Parser {

	// RSS parser code modified from tutorial at http://www.sitepoint.com/article/php-xml-parsing-rss-1-0/3/
	// This is a bit messy since the built-in xml functions can't handle the OO style stuff, so 
	// variables and functions are static.  Not like you read two feeds at once though.
	
	// And it doesn't do caching.  Maybe it wasn't worth the effort.
	
	private static $insideitem = false;
	private static $tag = false;
	private static $title = false;
	private static $desc = false;
	private static $link = false;
	private static $elems = array();

	public static function parse($feed) {
		RSS_Parser::$elems = array();
		$xml_parser = xml_parser_create();
		xml_set_element_handler($xml_parser, array('RSS_Parser','startElement'), array('RSS_Parser','endElement'));
		xml_set_character_data_handler($xml_parser, array('RSS_Parser',"characterData"));
		$fp = fopen($feed,"r")
			   or die("Error reading RSS feed $feed");

		// Read the XML file 4KB at a time
		while ($data = fread($fp, 4096))
		   // Parse each 4KB chunk with the XML parser created above
		   xml_parse($xml_parser, $data, feof($fp))
			   // Handle errors in parsing
			   or die(sprintf("XML error: %s at line %d",  
				   xml_error_string(xml_get_error_code($xml_parser)),  
				   xml_get_current_line_number($xml_parser)));

		fclose($fp);
		xml_parser_free($xml_parser);
		
		return RSS_Parser::$elems;
	}

	private static  function startElement($parser, $name, $attrs) {
		if (RSS_Parser::$insideitem) {
			RSS_Parser::$tag = $name;
		} elseif ($name == "ITEM") {
			RSS_Parser::$insideitem = true;
		}
	}

	private static function endElement($parser, $name) {
		if ($name == "ITEM") {
		
			array_push(RSS_Parser::$elems, new RSS_Entry(RSS_Parser::$title,RSS_Parser::$desc,RSS_Parser::$link));
		
			RSS_Parser::$title = "";
			RSS_Parser::$desc = "";
			RSS_Parser::$link = "";
			RSS_Parser::$insideitem = false;
		}
	}

	private static function characterData($parser, $data) {
		if (RSS_Parser::$insideitem) {
			switch (RSS_Parser::$tag) {
				case "TITLE":
				RSS_Parser::$title .= $data;
				break;
				case "DESCRIPTION":
				RSS_Parser::$desc .= $data;
				break;
				case "LINK":
				RSS_Parser::$link .= $data;
				break;
			}
		}
	}
}

class RSS_Entry {
	private $title = false;
	private $desc = false;
	private $link = false;
	
	public function __construct($title,$desc=false,$link=false) {
		$this->title = $title;
		$this->desc = $desc;
		$this->link = $link;
	}
	
	public function getTitle() { return $this->title; }
	public function getDescription() { return $this->desc; }
	public function getLink() { return $this->link; }
}


?>