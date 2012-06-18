<?php
class SQ_Class_MVC extends SQ_Class { 
	static public $controller=null;
	public static function dispatch($uri) { 
		SQ_Class_Site::loadINI();
		$plugin=null;
		if ($_SERVER['HTTP_HOST']!=SQ_Class_Site::$config['cp_hostname']) { 
			$controller_class = 'SQ_Controller_Public';
		} else { 
			if ($uri=='/' || $uri=='') { 
				$controller='dashboard';
				$action='index';
				$arg=null;
			} else { 
				if (substr($uri,0,1)=='/') { 
					$uri=substr($uri,1);
				}
				if (substr(strtolower($uri),0,8)=="plugins/") { 
					$uri=substr($uri,8);
					$bits=explode('/',$uri,2);
					$plugin=$bits[0];
					$uri=$bits[1];
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
			if (strpos($action,'?')!==FALSE) { 
				$action=substr($action,0,strpos($action,'?'));
			}
			if (strlen($action)<1) { 
				$action='index';
			}
			$controller_class = 'SQ_Controller_'.ucwords($controller);
		}
		if ($plugin!=null) { 
			require_once("plugins/$plugin/controllers/controller.".ucwords($controller).".php");
			$controller_class = 'SQ_Plugin_Controller_'.ucwords($controller);
		}
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
		try { 
			if (!is_subclass_of($controller_class,'SQ_Class_DetachedController')) { 
				SQ_Class_Site::connect();
			}
			if (!class_exists($controller_class)) { 
				self::throw404($controller_class,$funcname);
			}
			self::$controller = $new_controller = new $controller_class($controller, $action);
			if (is_subclass_of($controller_class,"SQ_Class_LockedPluginController") && !self::$controller->user->plugin_enabled($plugin)) { 
				self::throw404($controller_class,$funcname);
			}
		} catch (Exception $e) { 
			self::throw404($controller_class,$funcname);
		}
		if (method_exists($new_controller,'remap')) { 
			$res=$new_controller->remap(substr($uri,strlen($controller)),array_merge($_GET,$_POST));
		} else { 
			if (!method_exists($new_controller, $funcname)) { 
				self::throw404($controller_class, $funcname);
			}
			if (!$api) { 
				$new_controller->setView($controller.'/view.'.$action.".php");
			}
			$res=$new_controller->$funcname($arg,array_merge($_GET,$_POST));
		}
		if ($res instanceof SQ_Class_DBSQ) { 
			$res=$res->getFilteredDataArray();
		}
		if (!is_array($res)) {
			$res=(array)@$res;
		}
		if ($_POST['source']==='dialog') { 
			$html=ob_get_contents();
			ob_end_clean();
			echo json_encode($new_controller->get_dialog_response($html,$res));
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
			$res=$new_controller->api_expand_object_response($res);
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
