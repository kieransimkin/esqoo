<?php
class SQ_Controller_Account extends SQ_Class_LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	public function logoutUI($arg='',$input=array()) { 
		$this->logoutAPI();
		$this->redirect('/auth/login/');
	}
	/*************************
	 *  ╺┳┓╻┏━┓╻  ┏━┓┏━╸┏━┓  *
	 *   ┃┃┃┣━┫┃  ┃ ┃┃╺┓┗━┓  *
	 *  ╺┻┛╹╹ ╹┗━╸┗━┛┗━┛┗━┛  *
	 *************************/
	public function detailsDialog($arg='',$input=array()) { 
		$user=$this->getdetailsAPI();
		$form=$this->get_details_form($input,$user);
		if ($form->validate()) {
			$this->updatedetailsAPI($arg,$input);
			$this->showMessage(_('Account details updated'));
			return $this->formSuccess();
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	public function settingsDialog($arg='',$input=array()) { 
		$settings=$this->getsettingsAPI($arg,$input);
		$themesettingsform=$this->get_theme_settings_form($input,$settings);
		$editorsettingsform=$this->get_editor_settings_form($input,$settings);
		$invalid=false;
		if (!$themesettingsform->validate()) {
			$invalid=$themesettingsform->getId();
		}
		if (!$editorsettingsform->validate()) { 
			$invalid=$editorsettingsform->getId();
		}
		if (!$invalid) {
			$this->updatesettingsAPI($arg,$input);
			$this->showMessage(_('Account settings updated'));
			return $this->formSuccess();
		} else { 
			return $this->tabbedDialogFail(array(_('Theming')=>$themesettingsform,_('Editor')=>$editorsettingsform),'30%','550',null,null,$invalid);
		}
	}
	public function passwordDialog($arg='',$input=array()) { 
		$form=$this->get_password_form($input);
		if ($form->validate()) {
			$this->updatepasswordAPI($arg,$input);
			$this->showMessage(_('Password updated'));
			return $this->formSuccess();
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	/*********************
	 *  ┏━╸┏━┓┏━┓┏┳┓┏━┓  *
	 *  ┣╸ ┃ ┃┣┳┛┃┃┃┗━┓  *
	 *  ╹  ┗━┛╹┗╸╹ ╹┗━┛  *
	 *********************/
	private function get_theme_settings_form($input,$settings,$forcesubmit=false) { 
		$form=new SQ_Class_Form('theme_settings');
		$form->setAPIDataSources($input,$settings,$forcesubmit);
		$form->addElement("select","daytime__ui_theme_id",array('onchange'=>'esqoo_ui.update_theme(\'daytime\',$(this));'),array('options'=>SQ_Ui_theme::get_menu('Daytime')))->setLabel(_('Lights-on Theme'))->addRule('required',_('Required'));
		$form->addElement("select","nighttime__ui_theme_id",array('onchange'=>'esqoo_ui.update_theme(\'nighttime\',$(this));'),array('options'=>SQ_Ui_theme::get_menu('Nighttime')))->setLabel(_('Lights-off Theme'))->addRule('required',_('Required'));
		$form->addElement("button","lightswitch",array('onclick'=>'esqoo_ui.flick_light_switch(); return false;','data-icon-primary'=>'ui-icon-lightbulb'),array('content'=>_('Flick Light Switch')));
		return $form;
	}
	private function get_editor_settings_form($input,$settings,$forcesubmit=false) { 
		$form=new SQ_Class_Form('editor_settings');
		$form->setAPIDataSources($input,$settings,$forcesubmit);
		$form->addElement("select","visual__rich_editor_id",array(),array('options'=>SQ_Rich_editor::get_menu('Visual')))->setLabel(_('Visual Editor'))->addRule('required',_('Required'));
		$form->addElement("select","code__rich_editor_id",array(),array('options'=>SQ_Rich_editor::get_menu('Code')))->setLabel(_('Code Editor'))->addRule('required',_('Required'));
		return $form;
	}
	private function get_details_form($input,$user,$forcesubmit=false) { 
		$form=new SQ_Class_Form('details');
		$form->setAPIDataSources($input,$user,$forcesubmit);
		$form->addElement('text','FirstName',array())->setLabel(_('First Name(s)'))->addRule('required',_('Required'));
		$form->addElement('text','LastName',array())->setLabel(_('Last Name'))->addRule('required',_('Required'));
		$form->addElement('text','Email',array())->setLabel(_('Email'))->addRule('required',_('Required'));
		$form->addElement('text','Address1',array())->setLabel(_('Address Line 1'))->addRule('required',_('Required'));
		$form->addElement('text','Address2',array())->setLabel(_('Address Line 2'));
		$form->addElement('text','Town',array())->setLabel(_('Town/City'))->addRule('required',_('Required'));
		$form->addElement('text','County',array())->setLabel(_('County/State'));
		return $form;
	}
	private function get_password_form($input,$forcesubmit=false) { 
		$form=new SQ_Class_Form('password');
		$form->setAPIDataSources($input,null,$forcesubmit);
		$form->addElement('password','OldPassword',array())->setLabel(_('Old Password'))->addRule('required',_('Required'));
		$form->addElement('password','NewPassword',array())->setLabel(_('New Password'))->addRule('required',_('Required'));
		$form->addElement('password','NewPasswordAgain',array())->setLabel(_('New Password (Again)'))->addRule('required',_('Required'));
		return $form;
	}
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	public function logoutAPI($arg='',$input=array()) { 
		$this->user_token->delete();
		setcookie('UserID','',time()-3600,'/');
		setcookie('TokenID','',time()-3600,'/');
		setcookie('Token','',time()-3600,'/');
		return array();
	}
	public function getsettingsAPI($arg='',$input=array()) { 
		$this->user->set_visible_api_fields($this->get_settings_fields());
		return $this->user;
	}
	public function updatesettingsAPI($arg='',$input=array()) { 
		$settings=$this->getsettingsAPI();
		$form=$this->get_theme_settings_form($input,$settings,true);
		if (!$form->validate()) {
			$this->api_form_validation_error($form);
		}
		$form=$this->get_editor_settings_form($input,$settings,true);
		if (!$form->validate()) { 
			$this->api_form_validation_error($form);
		}
		if ($this->api_validation_success()) { 
			$settings->setFromFilteredArray($input,$this->get_settings_fields());
			$settings->save();
		}
		return $settings;
	}
	public function updatedaystateAPI($arg='',$input=array()) { 
		$input['DayState']=strtolower($input['DayState']);
		if ($input['DayState']!='daytime' && $input['DayState']!='nighttime') { 
			$this->api_error(1,'DayState must be Daytime or Nighttime');	
		}
		if ($this->api_validation_success()) { 
			$this->user->DayState=ucfirst($input['DayState']);
			$this->user->save();
			$this->user->set_visible_api_fields(array('DayState','id'));
			return $this->user;
		}
	}
	public function getdetailsAPI($arg='',$input=array()) { 
		$this->user->set_visible_api_fields($this->get_details_fields());
		return $this->user;
	}
	public function updatedetailsAPI($arg='',$input=array()) { 
		$user=$this->getdetailsAPI();
		$form=$this->get_details_form($input,$user,true);
		if (!$form->validate()) { 
			$this->api_form_validation_error($form);
		}
		if ($this->api_validation_success()) { 
			$user->setFromFilteredArray($input,$this->get_details_fields());
			$user->save();
		}
		return $user;
	}
	public function updatepasswordAPI($arg='',$input=array()) { 
		if ($this->api_validation_success()) { 

		}
	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_details_fields() { 
		return array('FirstName','LastName','Email','Address1','Address2','Town','County','country_id');
	}
	private function get_settings_fields() { 
		return array('visual__rich_editor_id','code__rich_editor_id','daytime__ui_theme_id','nighttime__ui_theme_id');
	}
}
