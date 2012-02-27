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
		$form=new Form('details');
		$user=$this->getdetailsAPI();
		var_dump($user->email);
		$form->addDataSource(new DBSQL_DataSource($user));
		$form->addElement('text','firstname',array())->setLabel(_('First Name(s)'))->addRule('required',_('Required'));
		$form->addElement('text','lastname',array())->setLabel(_('Last Name'))->addRule('required',_('Required'));
		$form->addElement('text','email',array())->setLabel(_('Email'))->addRule('required',_('Required'));
		$form->addElement('text','addr1',array())->setLabel(_('Address Line 1'))->addRule('required',_('Required'));
		$form->addElement('text','addr2',array())->setLabel(_('Address Line 2'));
		$form->addElement('text','town',array())->setLabel(_('Town/City'))->addRule('required',_('Required'));
		$form->addElement('text','county',array())->setLabel(_('County/State'));
		if ($form->validate()) { 
			return $this->formSuccess();
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	public function settingsDialog($arg='',$input=array()) { 
		$form=new Form('settings');
		if ($form->validate()) { 
			return $this->formSuccess();
		} else { 
			return $this->formFail($form);
		}
	}
	function getdetailsAPI($arg='',$input=array()) { 
		return $this->user;
	}
	function setdetailsAPI($arg='',$input=array()) { 

	}
}
