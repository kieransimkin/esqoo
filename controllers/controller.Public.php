<?php
class PublicController extends DetachedController { 
	function remap($uri,$input=array()) { 
		include('Smarty/Smarty.class.php');
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
		$cache->Content=$this->renderPage($user,$page);
		$cache->Size=strlen($cache->Content);
		$cache->HashType='MD5';
		$cache->CacheHash=md5($cache->Content);
		$cacheid=$cache->save();
		$page->page_cache_id=$cacheid;
		$page->save();
		$this->sendPageCache($cacheid);
	}
	private function renderPage($user,$page) { 
		$smarty=new Smarty();
		$smarty->left_delimiter='{!';
		$smarty->right_delimiter='}';
		$smarty->setTemplateDir(dirname(__FILE__).'/../themes/'.$user->ThemeIdentifier.'/');
		$smarty->setCompileDir(dirname(__FILE__).'/../cache/compiled-templates/');
		$smarty->setCacheDir(dirname(__FILE__).'/../cache/template-cache/');

		$smarty->assign('PageTitle','Ned');

		//** un-comment the following line to show the debug console
		$smarty->debugging = true;
		return $smarty->fetch('template.page.html');
	}
} 
