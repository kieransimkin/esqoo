<?php
class TagController extends LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function indexUI($arg='',$input=array()) { 

	}
	/*************************
	 *  ╺┳┓╻┏━┓╻  ┏━┓┏━╸┏━┓  *
	 *   ┃┃┃┣━┫┃  ┃ ┃┃╺┓┗━┓  *
	 *  ╺┻┛╹╹ ╹┗━╸┗━┛┗━┛┗━┛  *
	 *************************/
	function addDialog($arg='',$input=array()) { 
		$form=$this->get_tag_form($input);
		if ($form->validate()) { 
			$this->createAPI($arg,$input);
			$this->showMessage(_('Tag created'));
			$this->addFlexigridReloadSelector('#taglist');
			return $this->formSuccess();
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	function manageDialog($arg='',$input=array()) { 
		$tag=$this->getAPI('',array('TagID'=>$arg));
		$input['TagID']=$arg;
		$form=$this->get_tag_form($input,$tag);
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
	function get_tag_form($input,$tag,$forcesubmit=false) { 
		$form=new Form('add');
		$form->setAPIDataSources($input,$tag,$forcesubmit);
		$form->addElement('text','Name',array())->setLabel(_('Name'))->addRule('required',_('Required'));
		$form->addElement('textarea','Description',array())->setLabel(_('Description'));
		return $form;
	}
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	function createAPI($arg='',$input=array()) { 
		$form=$this->get_tag_form($input,null,true);
		if (!$form->validate()) { 
			$this->api_form_validation_error($form);
		} else { 
			$tag=Tag::get();
			$tag->Name=$input['Name'];
			$tag->Description=$input['Description'];
			$tag->user_id=$this->user->id;
			$tag->save();
			$tag->set_visible_api_fields($this->get_tag_fields());
			return $tag;
		}
	}
	public function listAPI($arg='',$input=array()) { 
		$suffix=DBSQL::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$tags=Tag::getAll('user_id=? and DeleteDate is null and Name like ?',array($this->user->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$tags=Tag::getAll('user_id=? and DeleteDate is null',array($this->user->id),null,$suffix);
		}
		DBSQL::set_all_visible_api_fields($tags,$this->get_tag_fields());
		$numrows=DBSQL::foundRows();
		return $this->flexigridResponse($tags,$input['Page'],$numrows);
	}
	public function getAPI($arg='',$input=array()) { 
		$tag=$this->ensure_api_tag($input);
		if ($this->api_validation_success()) { 
			$tag->set_visible_api_fields($this->get_tag_fields());
			return $tag;
		}
	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_tag_fields() { 
		return array('id','Name','Description');
	}
	private function ensure_api_tag($input) { 
		$tag=null;
		if (strlen(@$input['TagID'])<1) { 
			$this->api_error(1,_("TagID field is required"));
		} else { 
			try { 
				$tag=Tag::get($input['TagID']);
				if ($tag->user_id!=$this->user->id) { 
					$this->api_error(2,_("TagID not found"));
					$tag=null;
				}
			} catch (DBSQ_Exception $e) { 
				$this->api_error(2,_("TagID not found"));
			}
			if (is_null($tag)) { 
				$this->api_error(2,_("TagID not found"));
			}
		}
		return $tag;
	}
}
