<?php
class SQ_Controller_Tag extends SQ_Class_LockedController { 
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
		$form=$this->get_tag_form($input,null);
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
			$this->updateAPI($arg,$input);
			$this->showMessage(_('Tag updated'));
			$this->addFlexigridReloadSelector('#taglist');
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
		$form=new SQ_Class_Form('add');
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
		}
		if ($this->api_validation_success()) { 
			$tag=SQ_Tag::get();
			$tag->Name=$input['Name'];
			$tag->Description=$input['Description'];
			$tag->user_id=$this->user->id;
			$tag->save();
			$tag->set_visible_api_fields($this->get_tag_fields());
			return $tag;
		}
	}
	public function listAPI($arg='',$input=array()) { 
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$tags=SQ_Tag::getAll('user_id=? and DeleteDate is null and Name like ?',array($this->user->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$tags=SQ_Tag::getAll('user_id=? and DeleteDate is null',array($this->user->id),null,$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($tags,$this->get_tag_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($tags,$input['Page'],$numrows);
	}
	public function getAPI($arg='',$input=array()) { 
		$tag=$this->ensure_api_tag($input);
		if ($this->api_validation_success()) { 
			$tag->set_visible_api_fields($this->get_tag_fields());
			return $tag;
		}
	}
	public function updateAPI($arg='',$input=array()) { 
		$tag=$this->ensure_api_tag($input);
		$form=$this->get_tag_form($input,$album,true);
		if (!$form->validate()) { 
			$this->api_form_validation_error($form);
		}
		if ($this->api_validation_success()) { 
			$tag->setFromFilteredArray($input,$this->get_tag_fields());
			$tag->set_visible_api_fields($this->get_tag_fields());
			$tag->save();
		}
		return $album;
	}
	public function listmediaAPI($arg='',$input=array()) { 
		$tag=$this->ensure_api_tag($input);
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
		$tag=$this->ensure_api_tag($input);
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$pictures=DBSQ::getAll('select picture.* from picture inner join picture_tag on picture_tag.picture_id = picture.id where picture_tag.tag_id=? and picture.DeleteDate is null and picture.Name like ?',array($tag->id,'%'.$input['SearchQuery'].'%'),'SQ_Picture',$suffix);
		} else { 
			$pictures=DBSQ::getAll('select picture.* from picture inner join picture_tag on picture_tag.picture_id = picture.id where picture_tag.tag_id=? AND picture.DeleteDate is null',array($tag->id),'SQ_Picture',$suffix);
		}
		SQ_Class_DBSQ::add_all_computed_field($pictures,'PictureURLs','get_url_array');	
		SQ_Class_DBSQ::set_all_visible_api_fields($pictures,$this->get_picture_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($pictures,$input['Page'],$numrows);
	}
	public function listvideosAPI($arg='',$input=array()) { 
		$tag=$this->ensure_api_tag($input);
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$videos=DBSQ::getAll('select video.* from video inner join video_tag on video_tag.video_id = video.id where video_tag.tag_id=? and video.DeleteDate is null and video.Name like ?',array($tag->id,'%'.$input['SearchQuery'].'%'),'SQ_Video',$suffix);
		} else { 
			$videos=DBSQ::getAll('select video.* from video inner join video_tag on video_tag.video_id = video.id where video_tag.tag_id=? AND video.DeleteDate is null',array($tag->id),'SQ_Video',$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($videos,$this->get_video_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($videos,$input['Page'],$numrows);
	}
	public function listaudiosAPI($arg='',$input=array()) { 
		$tag=$this->ensure_api_tag($input);
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$audios=DBSQ::getAll('select audio.* from audio inner join audio_tag on audio_tag.audio_id = audio.id where audio_tag.tag_id=? and audio.DeleteDate is null and audio.Name like ?',array($tag->id,'%'.$input['SearchQuery'].'%'),'SQ_Audio',$suffix);
		} else { 
			$audios=DBSQ::getAll('select audio.* from audio inner join audio_tag on audio_tag.audio_id = audio.id where audio_tag.tag_id=? AND audio.DeleteDate is null',array($tag->id),'SQ_Audio',$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($audios,$this->get_audio_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($audios,$input['Page'],$numrows);
	}
	public function listfilesAPI($arg='',$input=array()) { 
		$tag=$this->ensure_api_tag($input);
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$files=DBSQ::getAll('select file.* from file inner join file_tag on file_tag.file_id = file.id where file_tag.tag_id=? and file.DeleteDate is null and file.Name like ?',array($tag->id,'%'.$input['SearchQuery'].'%'),'SQ_File',$suffix);
		} else { 
			$files=DBSQ::getAll('select file.* from file inner join file_tag on file_tag.file_id = file.id where file_tag.tag_id=? AND file.DeleteDate is null',array($tag->id),'SQ_File',$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($files,$this->get_file_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($files,$input['Page'],$numrows);
	}
	public function listallAPI($arg='',$input=array()) { 
		$tag=$this->ensure_api_tag($input);

	}
	

	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_tag_fields() { 
		return array('id','Name','Description');
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
	private function ensure_api_tag($input) { 
		$tag=null;
		if (strlen(@$input['TagID'])<1) { 
			$this->api_error(1,_("TagID field is required"));
		} else { 
			try { 
				$tag=SQ_Tag::get($input['TagID']);
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
