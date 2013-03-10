<?php
class SQ_Controller_Public extends SQ_Class_DetachedController { 
	function remap($uri,$input=array()) { 
		$username=substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
		$user=SQ_User::get($username,'Username',true);
		if ($uri=='/') { 
			$page=SQ_Page::get($user->default__page_id);
			$this->displayPage($user,$page);
			return;
		} else { 
			try { 
				$pageuri=SQ_Page_uri::get($username.$uri,'URITag',true);
				$page=SQ_Page::get($pageuri->page_id);
				$this->displayPage($user,$page);
				return;
			} catch (DBSQ_Exception $e) { }
			try {
				$uriforward=SQ_Uri_forward::get($username.$uri,'URITag',true);
				switch ($uriforward->Code) { 
					case '302':
						header("HTTP/1.1 302 Moved Temporarily");	
						break;
					case '303':
						header("HTTP/1.1 303 See Other");	
						break;
					case '307':
						header("HTTP/1.1 307 Temporary Redirect");	
						break;
					case '308':
						header("HTTP/1.1 308 Permanent Redirect");	
						break;
					case '301':
					default:
						header("HTTP/1.1 301 Moved Permanently");	
						break;
				}
				header("Location: ".$uriforward->DestinationURI);
				die;
			} catch (DBSQ_Exception $e) { }
			try { 
				$pluginuricache=SQ_Plugin_uri_cache::get($user->id,'user_id',true);
				$pluginuris=explode("\n",$pluginuricache->PluginURIs);
				$found=false;
				foreach ($pluginuris as $pluginuri) { 
					$tmp=explode(' ',$pluginuri,2);
					$uritag=$tmp[1];
					list($identifier,$controller)=explode(',',$tmp[0]);
					if (substr($username.$uri,0,strlen($uritag))==$uritag && strlen($username.$uri)>=strlen($uritag) && $user->plugin_enabled($identifier)) { 
						$found=true;
						break;
					}
				}	
				if ($found) { 
					$this->displayPluginPage($user,$identifier,$controller,$uri,$uritag);
					return;
				}
			} catch (DBSQ_Exception $e) { }
			try {
				$pluginuriforwardcache=SQ_Plugin_uri_forward_cache::get($user->id,'user_id',true);
				$pluginuriforwards=explode("\n",$pluginuriforwardcache->PluginURIForwards);
				$found=false;
				foreach ($pluginuriforwards as $pluginuriforward) { 
					$tmp=explode(' ',$pluginuriforward,3);
					$code=$tmp[0];
					$destination=$tmp[1];
					$uritag=$tmp[2];
					if (substr($username.$uri,0,strlen($uritag))==$uritag && strlen($username.$uri)>=strlen($uritag)) { 
						$found=true;
						break;
					}
				}
				if ($found) { 
					switch ($code) { 
						case '302':
							header("HTTP/1.1 302 Moved Temporarily");	
							break;
						case '303':
							header("HTTP/1.1 303 See Other");	
							break;
						case '307':
							header("HTTP/1.1 307 Temporary Redirect");	
							break;
						case '308':
							header("HTTP/1.1 308 Permanent Redirect");	
							break;
						case '301':
						default:
							header("HTTP/1.1 301 Moved Permanently");	
							break;
					}
					header("Location: ".$destination);
					die;
				}
			} catch (DBSQ_Exception $e) { }
			SQ_Class_MVC::throw404();
		}
	}
	private function displayPluginPage($user,$identifier,$controller,$uri,$uritag) { 
		$this->generatePluginPage($user,$identifier,$controller,$uri,$uritag);	
	}
	private function generatePluginPage($user,$identifier,$controller,$uri,$uritag) { 
		SQ_Class_Site::connect();
		require_once(dirname(__FILE__)."/../plugins/$identifier/controllers/controller.".ucwords($controller).".php");
		$controller_class = 'SQ_Plugin_Controller_'.ucwords($controller);
		$new_controller=null;
		try { 
			if (!class_exists($controller_class)) { 
				SQ_Class_MVC::throw404($controller_class,$uri);
			}
			$new_controller = new $controller_class($controller, $action);
		} catch (Exception $e) { 
			SQ_Class_MVC::throw404($controller_class,$funcname);
		}
		$theme=new SQ_Class_Theme($user->ThemeIdentifier);
		if (method_exists($new_controller,'remap')) { 
			$res=$new_controller->remap(substr($uri,strlen($uritag)-strlen($user->Username)),array_merge($_GET,$_POST),$theme,substr($uritag,strlen($user->Username)),$uri);
		} else { 
			$turl=substr($uri,strlen($uritag)-strlen($user->Username));
			if (substr($turl,0,1)=='/') { 
				$turl=substr($turl,1);
			}
			$bits=explode('/',$turl);
			$action=$bits[0];
			if ($bits[1]=='api') { 
				$api=true;
				$arg=$bits[2];
			} else { 
				$api=false;
				$arg=$bits[1];
			}
			if (strpos($action,'?')!==FALSE) { 
				$action=substr($action,0,strpos($action,'?'));
			}
			if (strlen($action)<1) { 
				$action='index';
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

			if (!method_exists($new_controller, $funcname)) { 
				SQ_Class_MVC::throw404($controller_class, $funcname);
			}
			$res=$new_controller->$funcname($arg,array_merge($_GET,$_POST),$theme);
		}
		if ($res instanceof SQ_Class_DBSQ) { 
			$res=$res->getFilteredDataArray();
		}
		if (!is_array($res)) {
			$res=(array)@$res;
		}
		if ($api) { 
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
		die;
	}
	private function displayPage($user,$page) { 
		try { 
			$pagecacheid=$page->page_cache_id;
			if (is_null($pagecacheid)) { 
				$this->generatePage($user,$page);
			} else { 
				$this->sendPageCache($pagecacheid);
			}
		} catch (Exception $e) { 
			$this->generatePage($user,$page);
		}
	}
	private function sendPageCache($cacheid) { 
		$cache=SQ_Page_cache::get($cacheid);
		$cache->output();
	}
	private function generatePage($user,$page) { 
		SQ_Class_Site::connect();
		$cache=SQ_Page_cache::get();
		$theme=new SQ_Class_Theme($user->ThemeIdentifier);
		$cache->Content=$theme->renderPage($page);
		$cache->Size=strlen($cache->Content);
		$cache->HashType='MD5';
		$cache->CacheHash=md5($cache->Content);
		$cacheid=$cache->save();
		$page->page_cache_id=$cacheid;
		$page->save();
		$this->sendPageCache($cacheid);
	}
} 
