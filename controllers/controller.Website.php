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
	function templatesUI($arg='',$input=array()) { 

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

}
