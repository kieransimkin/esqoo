<?php
class PublicController extends DetachedController { 
	function remap($uri,$input=array()) { 
		var_dump($uri);
		var_dump($input);
		include('Smarty/Smarty.class.php');
		$smarty=new Smarty();
		$username=substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
		$user=User::get($username,'Username',true);
		print $user->ThemeIdentifier;

		die;	
	}
} 
