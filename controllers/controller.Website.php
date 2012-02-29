<?php
class WebsiteController extends LockedController { 
	/*************************
	 *  ╺┳┓╻┏━┓╻  ┏━┓┏━╸┏━┓  *
	 *   ┃┃┃┣━┫┃  ┃ ┃┃╺┓┗━┓  *
	 *  ╺┻┛╹╹ ╹┗━╸┗━┛┗━┛┗━┛  *
	 *************************/
	function indexDialog ($arg='',$input=array()) { 
		$form=$this->get_website_selector_form($input);
		if ($form->validate()) { 
			return $this->formTargetBlank('http://'.$this->getwebsiteAPI($arg,$input)->ServerName);
		} else { 
			return $this->formFail($form,'35%','550');
		}
	}
	/*********************
	 *  ┏━╸┏━┓┏━┓┏┳┓┏━┓  *
	 *  ┣╸ ┃ ┃┣┳┛┃┃┃┗━┓  *
	 *  ╹  ┗━┛╹┗╸╹ ╹┗━┛  *
	 *********************/
	private function get_website_selector_form($input,$forcesubmit=false) { 
		$form=new Form('websiteselect');
		$form->addElement("select","website_id",array(),array('options'=>Website::get_menu($this->user->id)))->setLabel(_('Website'))->addRule('required',_('Required'));
		return $form;
	}
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	 public function getwebsiteAPI($arg='',$input=array()) { 
		$website=Website::get($input['website_id'],'id','row',true);
		$website->set_visible_api_fields($this->get_website_fields());
		return $website;
	 }
	 public function websitelistAPI($arg='',$input=array()) { 
		$websites=Website::getAll('user_id=? and deletedate is null',array($this->user->id));
		foreach ($websites as &$website) { 
			$website->set_visible_api_fields($this->get_website_fields());
		}
		return $websites;
	 }
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	 private function get_website_fields() { 
		return array('ServerName');
	 }

}
