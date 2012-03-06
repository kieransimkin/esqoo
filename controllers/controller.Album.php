<?php
class AlbumController extends LockedController { 
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
	public function addDialog($arg='',$input=array()) { 
		$form=$this->get_album_form($input,null);
		if ($form->validate()) { 
			$this->createAPI('',$input);
			$this->showMessage(_('Album created'));
			return $this->formSuccess();	
		} else { 
			return $this->formFail($form,'30%','550');
		}
	}
	public function manageDialog($arg='',$input=array()) { 
		$album=$this->getAPI('',array('AlbumID'=>$arg));
		$input['AlbumID']=$arg;
		$form=$this->get_album_form($input,$album);
		if ($form->validate()) { 
			$this->updateAPI('',$input);
			$this->showMessage(_('Album updated'));
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
	private function get_album_form($input,$album,$forcesubmit=false) {
		$form=new Form('editalbum');
		$form->setAPIDataSources($input,$album,$forcesubmit);
		$form->addElement("text","Name",array())->setLabel(_('Album Name'))->addRule('required',_('Required'));
		$form->addElement("textarea","Description",array())->setLabel(_('Description'));
		return $form;
	}
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	public function listAPI($arg='',$input=array()) { 
		$suffix=DBSQL::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$albums=Album::getAll('user_id=? and DeleteDate is null and Name like ?',array($this->user->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$albums=Album::getAll('user_id=? and DeleteDate is null',array($this->user->id),null,$suffix);
		}
		DBSQL::set_all_visible_api_fields($albums,$this->get_album_fields());
		$numrows=DBSQL::foundRows();
		return $this->flexigridResponse($albums,$input['Page'],$numrows);
	}
	public function getAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);
		if ($this->api_validation_success()) { 
			$album->set_visible_api_fields($this->get_album_fields());
			return $album;
		}	
	}
	public function createAPI($arg='',$input=array()) { 
		$form=$this->get_album_form($input,null,true);
		$album=null;
		if (!$form->validate()) { 
			$this->api_form_validation_error($form);
		}
		if ($this->api_validation_success()) { 

		}
		return $album;
	}
	public function updateAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);
		$form=$this->get_album_form($input,$album,true);
		if (!$form->validate()) { 
			$this->api_form_validation_error($form);
		}
		if ($this->api_validation_success()) { 
			foreach ($this->get_album_fields() as $field) { 
				if ($field=='id') { 
					continue;
				}
				$album->$field=$input[$field];
			}
			$album->save();
		}
		return $album;
	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_album_fields() { 
		return array("Name","id","Description");
	}
	private function ensure_api_album($input) { 
		$album=null;
		if (strlen(@$input['AlbumID'])<1) { 
			$this->api_error(1,"AlbumID field is required");
		} else { 
			try { 
				$album=Album::get($input['AlbumID']);
				if ($album->user_id!=$this->user->id) { 
					$this->api_error(2,"AlbumID not found");
					$album=null;
				}
			} catch (DBSQ_Exception $e) { 
				$this->api_error(2,"AlbumID not found");
			}
			if (is_null($album)) { 
				$this->api_error(2,"AlbumID not found");
			}
		}
		return $album;
	}
}
