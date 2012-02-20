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
	self::connectDB();
   }
   static function loadINI() { 
	self::$config=parse_ini_file(self::$approot.'/config.ini', INI_SCANNER_RAW);
   }
   static function connectDB() { 
	DBSQL::setMySQLCredentials(self::$config['mysql_user'],self::$config['mysql_pass'],self::$config['mysql_db'],self::$config['mysql_host']);
   }

} 
