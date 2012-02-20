<?php
class MVC { 
	public static function dispatch($uri) { 
		if ($uri=='/') { 
			$controller='dashboard';
			$action='index';
		}
		$bits=explode('/',$uri);
		var_dump($bits);
		print $uri;
	}
}
