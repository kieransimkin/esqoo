<?php
class AccountController extends LockedController { 
	public function logoutAPI($arg='',$input=array()) { 
		$this->user_token->delete();
		/*
		setcookie('UserID','',time()-3600,'/');
		setcookie('TokenID','',time()-3600,'/');
		setcookie('Token','',time()-3600,'/');
		*/
		return array();
	}
	public function logoutUI($arg='',$input=array()) { 
		$this->logoutAPI();
		$this->redirect('/auth/login/');
	}
}
