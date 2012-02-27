<?php
class AccountController extends LockedController { 
	public function logoutAPI($arg='',$input=array()) { 
		$this->user_token->delete();
		setcookie('UserID','',time()-3600,'/');
		setcookie('TokenID','',time()-3600,'/');
		setcookie('Token','',time()-3600,'/');
		return array();
	}
	public function logoutUI($arg='',$input=array()) { 
		$this->logoutAPI();
		$this->redirect('/auth/login/');
	}
	public function detailsDialog($arg='',$input=array()) { 
		$form=new Form('hello');
		$form->addElement('text','name',array('size'=>'20'))->setLabel('hello');
		if ($form->validate) { 
			return $this->formSuccess();
		} else { 
			return $this->formFail($form);
		}
	}
	public function settingsDialog($arg='',$input=array()) { 
		print "Bar";
	}
}
