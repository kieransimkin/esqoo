<?php
class Site { 
   private static $instance;
   static function get() {
        if (self::$instance)
            return self::$instance;
        self::$instance = new Site;
        return self::$instance;
    }

} 
