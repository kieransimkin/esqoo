<?php
class PublicController extends DetachedController { 
	function remap($uri,$input=array()) { 
		$username=substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
		$user=User::get($username,'Username',true);
		if ($uri=='/') { 
			$page=Page::get($user->default__page_id);
		} else { 
			try { 
				$pageuri=Page_uri::get($username.$uri,'URITag',true);
				$page=Page::get($pageuri->page_id);
			} catch (DBSQ_Exception $e) { 
				MVC::throw404();
			}
		}
		$this->displayPage($user,$page);
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
		$cache=Page_cache::get($cacheid);
		$cache->output();
	}
	private function generatePage($user,$page) { 
		Site::connect();
		$cache=Page_cache::get();
		$theme=new Theme($user->ThemeIdentifier);
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
