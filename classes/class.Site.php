<?php
function _($s) { 
	return $s;
}

class Site { 
   private static $instance;
   public static $approot;
   public static $config;
   static function loadAndConnect() { 
	self::$approot=realpath(dirname(__FILE__).'/../');
	self::loadINI();
   }
    function loadINI() { 
	self::$config=parse_ini_file(self::$approot.'/config.ini', INI_SCANNER_RAW);
    }

} 
