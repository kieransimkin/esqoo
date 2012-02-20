<?php
class Site { 
   private static $instance;
   public static $approot;
   function __construct() { 
	$this->approot=realpath(dirname(__FILE__).'/../');
   }
   static function get() {
        if (self::$instance)
            return self::$instance;
        self::$instance = new Site;
	
        return self::$instance;
    }

} 
