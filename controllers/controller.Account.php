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
		$user=$this->getdetailsAPI();
		$form=$this->get_details_form($input,$user);
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
	private function get_details_form($input,$user) { 
		$form=new Form('details');
		$form->setDataSources(array(new Array_DataSource($input), new DBSQL_DataSource($user)));
		var_dump($form->getDataSources());
		$form->addElement('text','FirstName',array())->setLabel(_('First Name(s)'))->addRule('required',_('Required'));
		$form->addElement('text','LastName',array())->setLabel(_('Last Name'))->addRule('required',_('Required'));
		$form->addElement('text','Email',array())->setLabel(_('Email'))->addRule('required',_('Required'));
		$form->addElement('text','Address1',array())->setLabel(_('Address Line 1'))->addRule('required',_('Required'));
		$form->addElement('text','Address2',array())->setLabel(_('Address Line 2'));
		$form->addElement('text','Town',array())->setLabel(_('Town/City'))->addRule('required',_('Required'));
		$form->addElement('text','County',array())->setLabel(_('County/State'));
		return $form;
	}
	function getdetailsAPI($arg='',$input=array()) { 
		return $this->user;
	}
	function setdetailsAPI($arg='',$input=array()) { 
		$ret=array();
		$user=$this->getdetailsAPI();
		$form=$this->get_details_form($input,$user);
		if (!$form->validate()) { 

		}
		return $ret;
	}
}
