<?php
class SQ_Controller_Album extends SQ_Class_LockedController { 
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
			$this->addFlexigridReloadSelector('#albumlist');
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
			$this->addFlexigridReloadSelector('#albumlist');
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
		$form=new SQ_Class_Form('editalbum');
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
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$albums=SQ_Album::getAll('user_id=? and UserVisible=\'true\' AND DeleteDate is null and Name like ?',array($this->user->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$albums=SQ_Album::getAll('user_id=? and UserVisible=\'true\' AND DeleteDate is null',array($this->user->id),null,$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($albums,$this->get_album_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($albums,$input['Page'],$numrows);
	}
	public function listmediaAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);
		$input['ListType']=strtolower($input['ListType']);
		$this->ensure_valid_listtype($input);
		if ($this->api_validation_success()) { 
			if ($input['ListType']=='pictures') { 
				return $this->listpicturesAPI($arg,$input);
			} else if ($input['ListType']=='videos') { 
				return $this->listvideosAPI($arg,$input);
			} else if ($input['ListType']=='audios') { 
				return $this->listaudiosAPI($arg,$input);
			} else if ($input['ListType']=='files') { 
				return $this->listfilesAPI($arg,$input);
			} else if ($input['ListType']=='all') { 
				return $this->listallAPI($arg,$input);
			}
		}
	}
	public function listpicturesAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$pictures=SQ_Picture::getAll('album_id=? and DeleteDate is null and Name like ?',array($album->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$pictures=SQ_Picture::getAll('album_id=? AND DeleteDate is null',array($album->id),null,$suffix);
		}
		SQ_Class_DBSQ::add_all_computed_field($pictures,'PictureURLs','get_url_array');	
		SQ_Class_DBSQ::set_all_visible_api_fields($pictures,$this->get_picture_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($pictures,$input['Page'],$numrows);
	}
	public function listvideosAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$videos=SQ_Video::getAll('album_id=? and DeleteDate is null and Name like ?',array($album->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$videos=SQ_Video::getAll('album_id=? AND DeleteDate is null',array($album->id),null,$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($videos,$this->get_video_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($videos,$input['Page'],$numrows);
	}
	public function listaudiosAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$audios=SQ_Audio::getAll('album_id=? and DeleteDate is null and Name like ?',array($album->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$audios=SQ_Audio::getAll('album_id=? AND DeleteDate is null',array($album->id),null,$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($audios,$this->get_audio_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($audios,$input['Page'],$numrows);
	}
	public function listfilesAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$files=SQ_File::getAll('album_id=? and DeleteDate is null and Name like ?',array($album->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$files=SQ_File::getAll('album_id=? AND DeleteDate is null',array($album->id),null,$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($files,$this->get_file_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($files,$input['Page'],$numrows);
	}
	public function listallAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);

	}
	public function getAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);
		if ($this->api_validation_success()) { 
			$album->set_visible_api_fields($this->get_album_fields());
			return $album;
		}	
	}
	public function getstubAPI($arg='',$input=array()) { 
		$album=SQ_Album::get();
		$album->user_id=$this->user->id;
		$album->Name=_('Untitled Album').' '.$this->user->format_date();
		$album->save();
		$album->set_visible_api_fields($this->get_album_fields());
		return $album;
	}
	public function stubcompleteAPI($arg='',$input=array()) { 
		$album=$this->ensure_api_album($input);
		if ($this->api_validation_success()) { 
			$album->UserVisible='true';
			$album->save();
			return $album;
		}
	}
	public function createAPI($arg='',$input=array()) { 
		$form=$this->get_album_form($input,null,true);
		$album=SQ_Album::get();
		if (!$form->validate()) { 
			$this->api_form_validation_error($form);
		}
		if ($this->api_validation_success()) { 
			$album->setFromFilteredArray($input,$this->get_album_fields());
			$album->UserVisible='true';
			$album->user_id=$this->user->id;
			$album->set_visible_api_fields($this->get_album_fields());
			$album->save();
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
			$album->setFromFilteredArray($input,$this->get_album_fields());
			$album->UserVisible='true';
			$album->set_visible_api_fields($this->get_album_fields());
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
	private function get_picture_fields() { 
		return array("Name","id","Description","ModifyDate");
	}
	private function get_video_fields() { 
		return array("Name","id","Description","ModifyDate");
	}
	private function get_audio_fields() { 
		return array("Name","id","Description","ModifyDate");
	}
	private function get_file_fields() { 
		return array("Name","id","Description","ModifyDate");
	}
	private function ensure_api_album($input) { 
		$album=null;
		if (strlen(@$input['AlbumID'])<1) { 
			$this->api_error(1,_("AlbumID field is required"));
		} else { 
			try { 
				$album=SQ_Album::get($input['AlbumID']);
				if ($album->user_id!=$this->user->id) { 
					$this->api_error(2,_("AlbumID not found"));
					$album=null;
				}
			} catch (DBSQ_Exception $e) { 
				$this->api_error(2,_("AlbumID not found"));
			}
			if (is_null($album)) { 
				$this->api_error(2,_("AlbumID not found"));
			}
		}
		return $album;
	}
	private function ensure_valid_listtype($input) { 
		if (strlen($input['ListType'])<1) { 
			$this->api_error(3,_('ListType is required'));
		}
		if ($input['ListType']!='pictures' && $input['ListType']!='videos' && $input['ListType']!='audios' && $input['ListType']!='files' && $input['ListType']!='all') { 
			$this->api_error(4,_('ListType must be either pictures, videos, audios, files or all.'));
		}
	}
}
