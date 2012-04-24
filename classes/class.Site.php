<?php
function _($s) { 
	return $s;
}

class Site { 
   private static $instance;
   public static $approot=null;
   public static $config=null;
   static function connect() { 
	self::connectDB();
   }
   static function setAppRoot() { 
	if (is_null(self::$approot)) { 
		self::$approot=realpath(dirname(__FILE__).'/../');
	}
   }
   static function loadINI() { 
	self::setAppRoot();
	if (is_null(self::$config)) { 
		self::$config=parse_ini_file(self::$approot.'/config.ini', INI_SCANNER_RAW);
	}
   }
   static function connectDB() { 
	DBSQL::setMySQLCredentials(self::$config['mysql_user'],self::$config['mysql_pass'],self::$config['mysql_db'],self::$config['mysql_host']);
   }

} 
