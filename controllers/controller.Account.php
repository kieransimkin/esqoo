<?php
class Account extends LockedController { 
	public function logoutAPI() { 
		$this->user_token->delete();
		/*
		setcookie('UserID','',time()-3600,'/');
		setcookie('TokenID','',time()-3600,'/');
		setcookie('Token','',time()-3600,'/');
		*/
		return array();
	}
	public function logoutUI { 
		$this->logoutAPI();
		$this->redirect('/auth/login/');
	}
}
