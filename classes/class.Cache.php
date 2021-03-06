<?php
class SQ_Class_CacheError extends SQ_Class {
	private $error=null;
	function __construct($err) { 
		$this->error=$err;	
	}
	function __toString() { 
		return $this->error;
	}
};
class SQ_Class_Cache extends SQ_Class { 
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
	static public function unsetKey($namespace,$key) { 
		switch (self::$backend) { 
			case 'flatfile':
				return self::unsetFileKey($namespace,$key);
				break;
		}
	}
	static private function getFileKey($namespace,$key) { 
		$name=self::get_file_storage_name($namespace,$key);
		if (!file_exists($name)) { 
			return new SQ_Class_CacheError('miss');
		}
		$data=file_get_contents($name);
		if ($data==false) { 
			return new SQ_Class_CacheError('miss');
		}
		return json_decode($data);
	}
	static private function setFileKey($namespace,$key,$value) { 
		$name=self::get_file_storage_name($namespace,$key);
		self::make_cache_directory($name);
		$fp=fopen($name,"wb");
		fputs($fp,json_encode($value));
		fclose($fp);
	}
	static private function unsetFileKey($namespace,$key) { 
		$name=self::get_file_storage_name($namespace,$key);
		@unlink($name);
	}
	static private function make_cache_directory($filename) { 
		@mkdir(dirname($filename),0777,true);
	}
	static private function get_file_storage_name($namespace,$key) { 
		return dirname(__FILE__).'/../cache/'.SQ_Class_Helper::sanitize_file_name($namespace).'/'.SQ_Class_Helper::sanitize_file_name($key);
	}
}
