<?php
class PublicController extends DetachedController { 
	function remap($uri,$input=array()) { 
		include('Smarty/Smarty.class.php');
		$username=substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
		$user=User::get($username,'Username',true);
		$this->displayPage($user,$page);
		die;	
	}
	private function displayPage($user,$page) { 
		$smarty=new Smarty();
		$smarty->left_delimiter='{!';
		$smarty->right_delimiter='}';
		$smarty->setTemplateDir(dirname(__FILE__).'/../themes/'.$user->ThemeIdentifier.'/');
		$smarty->setCompileDir(dirname(__FILE__).'/../cache/compiled-templates/');
		$smarty->setCacheDir(dirname(__FILE__).'/../cache/template-cache/');

		$smarty->assign('PageTitle','Ned');

		//** un-comment the following line to show the debug console
		$smarty->debugging = true;

		$smarty->display('template.page.html');

	}
} 
