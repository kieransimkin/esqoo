<?php
class SQ_Class_Plugin extends SQ_Class { 
	public $identifier=null;
	public $xml=null;
	private static $cache=[];
	// ::get does a cached lookup
	public static function get($identifier) { 
		if (array_key_exists($identifier,self::$cache)) { 
			return self::$cache[$identifier];
		}
		return self::$cache[$identifier]=new SQ_Class_Plugin($identifier);
	}
	public static function enumerate() { 
		$ret=array();
		$dir="plugins/";
		$plugindir=opendir($dir);
		while (($file = readdir($plugindir)) !== false) {
			if ($file=='.' || $file=='..') { 
				continue;
			}
			$type=filetype($dir.$file);
			if ($type=='dir') { 
				$ret[]=SQ_Class_Plugin::get($file);
			}
		}
		closedir($plugindir);
		return $ret;
	}
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
	function activate() { 

	}
	function deactivate() { 

	}
} 
