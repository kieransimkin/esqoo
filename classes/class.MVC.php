<?php
class MVC { 
	public static function dispatch($uri) { 
		if ($uri=='/') { 
			$controller='dashboard';
			$action='index';
			$arg=null;
		} else { 
			if (substr($uri,0,1)=='/') { 
				$uri=substr($uri,1);
			}
			$bits=explode('/',$uri);
			$controller=$bits[0];
			$action=$bits[1];
			if ($bits[2]=='api') { 
				$api=true;
				$arg=$bits[3];
			} else { 
				$api=false;
				$arg=$bits[2];
			}
		}
		print "Controller: $controller<br>\n";
		print "Action: $action<br>\n";
		print "Arg: $arg<br>\n";
		print "Api: ".print_r($api,true)."<br>\n";
		var_dump($bits);
		print $uri;
	}
}
