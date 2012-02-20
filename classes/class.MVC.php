<?php
class MVC { 
	public static function dispatch($uri) { 
		if ($uri=='/') { 
			$controller='dashboard';
			$action='index';
		}
		if (substr($uri,0,1)=='/') { 
			$uri=substr($uri,1);
		}
		$bits=explode('/',$uri);
		var_dump($bits);
		print $uri;
	}
}
