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
				$pluginuricache=SQ_Plugin_uri_cache::get($user->id,'id',true);
				$pluginuris=explode("\n",$pluginuricache->PluginURIs);
				$found=false;
				foreach ($pluginuris as $pluginuri) { 
					$tmp=explode(' ',$pluginuri,2);
					$uritag=$tmp[1];
					list($identifier,$controller)=explode(',',$tmp[0]);
					if (substr($username.$uri,0,strlen($uritag))==$uritag && strlen($username.$uri)>=strlen($uritag)) { 
						$found=true;
						break;
					}
				}	
				if ($found) { 
					$this->displayPlugin($user,$identifier,$controller,$uri);
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
	private function displayPlugin($user,$identifier,$controller,$uri) { 

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
