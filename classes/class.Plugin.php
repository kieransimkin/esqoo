<?php
class SQ_Class_Plugin extends SQ_Class { 
	private $identifier=null;
	private $xml=null;
	function __construct($identifier) { 
		$this->identifier=$identifier;
		$this->parseXML();
	}
	private function parseXML() { 
		$this->xml=simplexml_load_file($this->getPath().'plugin.xml');
	}
	private function getPath() { 
		return "plugins/".$this->identifier."/";
	}
} 
