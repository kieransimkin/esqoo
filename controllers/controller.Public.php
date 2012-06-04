<?php
class SQ_Controller_Public extends SQ_Class_DetachedController { 
	function remap($uri,$input=array()) { 
		$username=substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
		$user=SQ_User::get($username,'Username',true);
		if ($uri=='/') { 
			$page=SQ_Page::get($user->default__page_id);
		} else { 
			try { 
				$pageuri=SQ_Page_uri::get($username.$uri,'URITag',true);
				$page=SQ_Page::get($pageuri->page_id);
			} catch (DBSQ_Exception $e) { 
				SQ_Class_MVC::throw404();
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
