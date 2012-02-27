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
		$form->addDataSource(new DBSQL_DataSource($user));
		$form->addElement('text','FirstName',array())->setLabel(_('First Name(s)'))->addRule('required',_('Required'));
		$form->addElement('text','LastName',array())->setLabel(_('Last Name'))->addRule('required',_('Required'));
		$form->addElement('text','Email',array())->setLabel(_('Email'))->addRule('required',_('Required'));
		$form->addElement('text','Address1',array())->setLabel(_('Address Line 1'))->addRule('required',_('Required'));
		$form->addElement('text','Address2',array())->setLabel(_('Address Line 2'));
		$form->addElement('text','Town',array())->setLabel(_('Town/City'))->addRule('required',_('Required'));
		$form->addElement('text','County',array())->setLabel(_('County/State'));
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
	//	if (isset($input['FirstName']
	}
}
