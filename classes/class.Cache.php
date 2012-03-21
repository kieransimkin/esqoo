<?php
class CacheError {
	private $error=null;
	function __construct($err) { 
		$this->error=$err;	
	}
	function __toString() { 
		return $this->error;
	}
};
class Cache { 
	static public $backend='flatfile';
	static public function getKey($namespace,$key) { 
		switch (self::$backend) { 
			case 'flatfile':
				return self::getFileKey($namespace,$key);
			break;
		}
	}
	static public function setKey($namespace,$key,$value) { 
		switch (self::$backend) { 
			case 'flatfile':
				return self::setFileKey($namespace,$key,$value);
			break;
		}
	}
	static private function getFileKey($namespace,$key) { 
		$name=self::get_file_storage_name($namespace,$key);
		return new CacheError('miss');
	}
	static private function setFileKey($namespace,$key,$value) { 
		$name=self::get_file_storage_name($namespace,$key);
		self::make_cache_directory($name);
		$fp=fopen($name,"w");
		fputs($value);
		fclose($fp);
	}
	static private function make_cache_directory($filename) { 
		@mkdir(dirname($filename),0777,true);
	}
	static private function get_file_storage_name($namespace,$key) { 
		return dirname(__FILE__).'/../cache/'.Helper::sanitize_file_name($namespace).'/'.Helper::sanitize_file_name($key);
	}
}
