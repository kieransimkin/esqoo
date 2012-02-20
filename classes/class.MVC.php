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
		$controller_class = ucwords($controller).'Controller';
		if ($api) { 
			$funcname = strtolower(str_replace('-','',$action)).'API';
		} else { 
			$funcname = strtolower(str_replace('-','',$action)).'UI';
		}
		if (!class_exists($controller_class)) { 
			self::throw404($controller_class,$funcname);
		}
		$new_controller = new $controller_class($controller, $action);
		$new_controller->setView($controller.'/view.'.$action.".php");
		$new_controller->action = $action;
		$new_controller->controller = strtolower($controller);
		$new_controller->autoloadJS();

		if (!method_exists($new_controller, $funcname)) { 
			self::throw404($controller_class, $funcname);
		}
		$new_controller->$funcname($arg);
		$new_controller->render();
	}

	function throw404($controller_class=null, $funcname=null) {
		self::render404($controller_class, $funcname);
		die;
	}

	function render404($controller_class=null, $funcname=null) {
		header("HTTP/1.1 404 Not Found");
		?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL <?=htmlentities($_SERVER['REQUEST_URI'])?> was not found on this server.</p>
<?php if ($controller_class and $funcname) { ?><p><em>Unknown method <strong><?=$funcname?> </strong> on <strong><?=$controller_class?></strong></em></p><?php } ?>
</body></html>
	<?php }

}
