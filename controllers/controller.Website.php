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
		$plugin=new SQ_Class_Plugin($arg);
		$form=$this->get_activate_plugin_form($input,$plugin);
		if ($form->validate()) { 
			$this->activatepluginAPI($arg,array('Identifier'=>$plugin->identifier));
			$this->addFlexigridReloadSelector('#pluginlist');
			$this->showMessage(_('Plugin Activated'));
			return $this->formSuccess();
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	public function deactivatepluginDialog($arg='',$input=array()) { 
		$plugin=new SQ_Class_Plugin($arg);
		$form=$this->get_deactivate_plugin_form($input,$plugin);
		if ($form->validate()) { 
			$this->deactivatepluginAPI($arg,array('Identifier'=>$plugin->identifier));
			$this->addFlexigridReloadSelector('#pluginlist');
			$this->showMessage(_('Plugin Deactivated'));
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
		$form->addElement("static","contentarea",array())->setContent('<h3>'.$plugin->xml->Name.'</h3>'.$plugin->xml->Identifier.'<br />'._('Are you sure you wish to activate this plugin?'));
		return $form;
	}
	private function get_deactivate_plugin_form($input,$plugin,$forcesubmit=false) {
		$form=new SQ_Class_Form('deactivateplugin');
		$form->setAPIDataSources($input,$plugin,$forcesubmit);
		$form->addElement("static","contentarea",array())->setContent('<h3>'.$plugin->xml->Name.'</h3>'.$plugin->xml->Identifier.'<br />'._('Are you sure you wish to deactivate this plugin?'));
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
		$plugin=SQ_Class_Plugin::get($input['Identifier']);
		$plugin->activate($this->user);
		return $plugin;
	}
	public function deactivatepluginAPI($arg='',$input=array()) { 
		$plugin=SQ_Class_Plugin::get($input['Identifier']);
		$plugin->deactivate($this->user);
		return $plugin;
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
