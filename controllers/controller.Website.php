<?php
class SQ_Controller_Website extends SQ_Class_LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function indexUI ($arg='',$input=array()) { 
		$this->redirect('http://'.$this->user->Username.'.'.SQ_Class_Site::$config['cp_hostname']);
	}
	function themesUI($arg='',$input=array()) { 

	}
	function menusUI($arg='',$input=array()) { 

	}
	function picturesizesUI($arg='',$input=array()) { 

	}
	function pluginsUI($arg='',$input=array()) { 

	}
	/*************************
	 *  ╺┳┓╻┏━┓╻  ┏━┓┏━╸┏━┓  *
	 *   ┃┃┃┣━┫┃  ┃ ┃┃╺┓┗━┓  *
	 *  ╺┻┛╹╹ ╹┗━╸┗━┛┗━┛┗━┛  *
	 *************************/
	public function activatepluginDialog($arg='',$input=array()) { 
		$plugin=SQ_Class_Plugin::get($arg);
		$form=$this->get_activate_plugin_form($input,$plugin);
		if ($form->validate()) { 
			$this->activatepluginAPI($arg);
			$this->addFlexigridReloadSelector('#pluginlist');
			$this->showMessage(_('Plugin Activated'));
			return $this->formSuccess();
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	public function deactivatepluginDialog($arg='',$input=array()) { 
		$plugin=SQ_Class_Plugin::get($arg);
		$form=$this->get_deactivate_plugin_form($input,$plugin);
		if ($form->validate()) { 
			$this->deactivatepluginAPI($arg);
			$this->addFlexigridReloadSelector('#pluginlist');
			$this->showMessage(_('Plugin Deactivated'));
			return $this->formSuccess();
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	public function plugininfoDialog($arg='',$input=array()) { 
		$plugin=SQ_Class_Plugin::get($arg);
		$form=$this->get_plugin_info_form($input,$plugin);
		if ($form->validate()) { 
			return $this->formSuccess();
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	public function activatethemeDialog($arg='',$input=array()) { 
		$theme=SQ_Class_Theme::get($arg);
		$form=$this->get_activate_theme_form($input,$theme);
		if ($form->validate()) { 
			$this->activatethemeAPI($arg);
			$this->addFlexigridReloadSelector('#themelist');
			$this->showMessage(_('Theme activated'));
			return $this->formSuccess();
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	public function themeinfoDialog($arg='',$input=array()) { 
		$theme=SQ_Class_Theme::get($arg);
		$form=$this->get_theme_info_form($input,$theme);
		if ($form->validate()) { 
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
	private function get_activate_plugin_form($input,$plugin,$forcesubmit=false) {
		$form=new SQ_Class_Form('activateplugin');
		$form->setAPIDataSources($input,$plugin,$forcesubmit);
		$form->addElement("static","contentarea",array())->setContent($plugin->getInfoBlockHTML().'<br />'._('Are you sure you wish to activate this plugin?'));
		return $form;
	}
	private function get_deactivate_plugin_form($input,$plugin,$forcesubmit=false) {
		$form=new SQ_Class_Form('deactivateplugin');
		$form->setAPIDataSources($input,$plugin,$forcesubmit);
		$form->addElement("static","contentarea",array())->setContent($plugin->getInfoBlockHTML().'<br />'._('Are you sure you wish to deactivate this plugin?'));
		return $form;
	}
	private function get_plugin_info_form($input,$plugin,$forcesubmit=false) { 
		$form=new SQ_Class_Form('plugininfo');
		$form->setAPIDataSources($input,$plugin,$forcesubmit);
		$form->addElement("static","plugininfo",array())->setContent($plugin->getInfoBlockHTML());
		return $form;
	}
	private function get_activate_theme_form($input,$theme,$forcesubmit=false) { 
		$form=new SQ_Class_Form('activatetheme');
		$form->setAPIDataSources($input,$theme,$forcesubmit);
		$form->addElement("static","contentarea",array())->setContent($theme->getInfoBlockHTML().'<br />'._('Are you sure you wish to switch theme?'));
		return $form;
	}
	private function get_theme_info_form($input,$theme,$forcesubmit=false) { 
		$form=new SQ_Class_Form('themeinfo');
		$form->setAPIDataSources($input,$theme,$forcesubmit);
		$form->addElement("static","themeinfo",array())->setContent($theme->getInfoBlockHTML());
		return $form;
	}
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	public function pluginlistAPI($arg='',$input=array()) { 
		$plugins=SQ_Class_Plugin::enumerate();
		$userplugins=SQ_User_plugin::getAll('user_id=?',array($this->user->id));
		$userplugina=array();
		foreach ($userplugins as $userplugin) { 
			$userplugina[]=$userplugin->Identifier;
		}
		$pluginres=array();
		foreach ($plugins as $plugin) { 
			if (in_array((string)$plugin->xml->Identifier,$userplugina)) { 
				$enabled='True';
			} else { 
				$enabled='False';
			}
			$pluginres[]=array(	'Identifier'=>$plugin->identifier,
					   	'XMLIdentifier'=>(string)$plugin->xml->Identifier,
						'Name'=>(string)$plugin->xml->Name,
						'Enabled'=>$enabled);
		}
		return array("Page"=>1,"RowCount"=>count($pluginres),"Rows"=>$pluginres);
	}
	public function activatepluginAPI($arg='',$input=array()) { 
		$plugin=SQ_Class_Plugin::get($arg);
		$plugin->activate($this->user);
		return $plugin;
	}
	public function deactivatepluginAPI($arg='',$input=array()) { 
		$plugin=SQ_Class_Plugin::get($arg);
		$plugin->deactivate($this->user);
		return $plugin;
	}
	public function activatethemeAPI($arg='',$input=array()) { 
		$theme=SQ_Class_Theme::get($arg);
		$theme->activate($this->user);
		return $theme;
	}
	public function themelistAPI($arg='',$input=array()) { 
		$themes=SQ_Class_Theme::enumerate();
		$themeres=array();
		foreach ($themes as $theme) { 
			if ((string)$theme->xml->Identifier==$this->user->ThemeIdentifier) { 
				$enabled='True';
			} else { 
				$enabled='False';
			}
			$themeres[]=array(	'Identifier'=>$theme->identifier,
					   	'XMLIdentifier'=>(string)$theme->xml->Identifier,
						'Name'=>(string)$theme->xml->Name,
						'Enabled'=>$enabled);
		}
		return array("Page"=>1,"RowCount"=>count($themeres),"Rows"=>$themeres);
	}

}
