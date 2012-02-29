<?php
class MVC { 
	public static function dispatch($uri) { 
		if ($uri=='/' || $uri=='') { 
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
		if (strlen($action)<1) { 
			$action='index';
		}
		Site::loadAndConnect();
		$controller_class = ucwords($controller).'Controller';
		if ($api) { 
			$funcname = strtolower(str_replace('-','',$action)).'API';
		} else { 
			if ($_POST['source']==='dialog') { 
				ob_start();
				$funcname = strtolower(str_replace('-','',$action)).'Dialog';
			} else { 
				$funcname = strtolower(str_replace('-','',$action)).'UI';
			}
		}
		if (!class_exists($controller_class)) { 
			self::throw404($controller_class,$funcname);
		}
		$new_controller = new $controller_class($controller, $action);

		if (!method_exists($new_controller, $funcname)) { 
			self::throw404($controller_class, $funcname);
		}
		if (!$api) { 
			$new_controller->setView($controller.'/view.'.$action.".php");
		}
		$res=$new_controller->$funcname($arg,array_merge($_GET,$_POST));
		if (!is_array($res)) {
			$res=(array)@$res;
		}
		if ($_POST['source']==='dialog') { 
			$html=ob_get_contents();
			ob_end_clean();
			echo json_encode(array(
				'html'=>$html,
				'rettype'=>$res['rettype'],
				'width'=>@$res['width'],
				'minwidth'=>@$res['minwidth'],
				'height'=>@$res['height'],
				'minheight'=>@$res['minheight'],
				'defaulttab'=>@$ret['defaulttab']));
			die;
		} else if (!$api) { 
			header('X-UA-Compatible: IE=edge,chrome=1');
			$new_controller->autoloadJS();
			$new_controller->autoloadCSS();
			$new_controller->render();
		} else { 
			if (!$new_controller->api_validation_success()) { 
				$res['ErrorCount']=count($new_controller->api_errors);
				$res['Errors']=$new_controller->api_error_array();
			} else {
				$res['ErrorCount']=0;
			}
			if (strlen($_REQUEST['ResponseFormat'])<1) { 
				$_REQUEST['ResponseFormat']='xml';
			}
			$new_controller->api_response($res,$_REQUEST['ResponseFormat']);
		}
	}

	function throw404($controller_class=null, $funcname=null) {
		self::render404($controller_class, $funcname);
		die;
	}

	function render404($controller_class=null, $funcname=null) {
		header("HTTP/1.1 404 Not Found");
		?>
<!DOCTYPE html>
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL <?=htmlentities($_SERVER['REQUEST_URI'])?> was not found on this server.</p>
<?php if ($controller_class and $funcname) { ?><p><em>Unknown method <strong><?=$funcname?> </strong> on <strong><?=$controller_class?></strong></em></p><?php } ?>
</body></html>
	<?php }

}
