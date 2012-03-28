<?php
class PictureController extends LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function indexUI($arg='',$input=array()) { 
		$form=$this->get_index_browse_form($input);
		if ($form->validate()) { 

		}
		$this->view->setTemplate('fullpage');
		$this->view->form=$form;
	}
	/*********************
	 *  ┏━╸┏━┓┏━┓┏┳┓┏━┓  *
	 *  ┣╸ ┃ ┃┣┳┛┃┃┃┗━┓  *
	 *  ╹  ┗━┛╹┗╸╹ ╹┗━┛  *
	 *********************/
	private function get_index_browse_form($input=array()) { 
		$form=new Form('indexbrowse');
		$selectedstate=null;
		$albummenu=Album::get_menu($this->user->id);
		$tagmenu=Tag::get_menu($this->user->id);
		$tagcontainerclass='';
		$albumcontainerclass='';
		$albumid='';
		$tagid='';
		$input['View']=strtolower($input['View']);
		$view='thumbnailbrowse';
		if (strlen($input['View'])>0 && ($input['View']=='flexigrid' || $input['View']=='thumbnailbrowse' || $input['View']=='mediaslide')) { 
			$view=$input['View'];
		}
		if (strlen($input['AlbumID'])>0) { 
			if (array_key_exists($input['AlbumID'],$albummenu)) { 
				$selectedstate='album';
				$albumid=$input['AlbumID'];
				$albumcontainerclass=' ui-widget-header';
			}
		} else if (strlen($input['TagID'])>0) { 
			if (array_key_exists($input['TagID'],$tagmenu)) { 
				$selectedstate='tag';
				$tagid=$input['TagID'];
				$tagcontainerclass=' ui-widget-header';
			}
		}
		$tagmenu['']=_('Not Selected');
		$albummenu['']=_('Not Selected');
		$form->addElement('script','vars',array(),array('content'=>"esqoo_picture_index.selectedstate='".$selectedstate."';\n"));	
		$container=$form->addElement('div','topheadingcontainer',array('class'=>'esqoo-picture-browse-top-heading-container ui-widget-content ui-helper-clearfix ui-corner-all'));
		$albumtagcontainer=$container->addElement('div','albumtagcontainer',array('class'=>'esqoo-picture-browse-top-heading-album-tag-container'));
		$albumcontainer=$albumtagcontainer->addElement('div','albumcontainer',array('class'=>'esqoo-picture-browse-top-heading-album-container ui-corner-all'.$albumcontainerclass));
		$albumcontainer->addElement('select','AlbumID',array('data-width'=>'65%'),array('options'=>$albummenu))->setLabel(_('Album'))->setValue($albumid);
		$tagcontainer=$albumtagcontainer->addElement('div','tagcontainer',array('class'=>'esqoo-picture-browse-top-heading-tag-container ui-corner-all'.$tagcontainerclass));
		$tagcontainer->addElement('select','TagID',array('data-width'=>'65%'),array('options'=>$tagmenu))->setLabel(_('Tag'))->setValue($tagid);
		$viewcontainer=$container->addElement('div','viewcontainer',array('class'=>'esqoo-picture-browse-top-heading-view-container'));
		$viewcontainer->addElement('select','View',array('data-width'=>'65%'),array('options'=>array('flexigrid'=>_('List'),'thumbnailbrowse'=>_('Thumbnails'),'mediaslide'=>_('MediaSlide'))))->setLabel(_('View'))->setValue($view);
		return $form;
	}
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	function getrawexifAPI($arg='',$input=array()) { 
		$picture=$this->ensure_api_picture($input);
		if ($this->api_validation_success()) { 
			$picture->add_computed_field('EXIF','get_raw_exif_data');
			$picture->set_visible_api_fields($this->get_picture_fields());
			return $picture;
		}
	}
	function getexifAPI($arg='',$input=array()) { 
		$picture=$this->ensure_api_picture($input);
		if ($this->api_validation_success()) { 
			$picture->add_computed_field('EXIF','get_exif_data');
			$picture->set_visible_api_fields($this->get_picture_fields());
			return $picture;
		}
	}
	function getsimplifiedexifAPI($arg='',$input=array()) {
		$picture=$this->ensure_api_picture($input);
		if ($this->api_validation_success()) { 
			$picture->add_computed_field('EXIF','get_simplified_exif_data');
			$picture->set_visible_api_fields($this->get_picture_fields());
			return $picture;
		}
	}
	function getAPI($arg='',$input=array()) { 
		$picture=$this->ensure_api_picture($input);
		if ($this->api_validation_success()) { 
			$picture->add_computed_field('PictureURLs','get_url_array');
			$picture->add_computed_field('Tags','get_tag_array');
			$picture->set_visible_api_fields($this->get_picture_fields());
			return $picture;
		}
	}
	function getfullAPI($arg='',$input=array()) { 
		$picture=$this->ensure_api_picture($input);
		if ($this->api_validation_success()) { 
			$picture->add_computed_field('PictureURLs','get_url_array');
			$picture->add_computed_field('Tags','get_tag_array');
			$picture->add_computed_field('EXIF','get_simplified_exif_data');
			$picture->set_visible_api_fields($this->get_picture_fields());
			return $picture;
		}
	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_picture_fields() {
		return array('id','Name','Description');
	}
	private function ensure_api_picture($input) { 
		$picture=null;
		if (strlen(@$input['PictureID'])<1) { 
			$this->api_error(1,_("PictureID field is required"));
		} else { 
			try { 
				$picture=Picture::get($input['PictureID']);
				if ($picture->album->user_id!=$this->user->id) { 
					$this->api_error(2,_("PictureID not found"));
					$picture=null;
				}
			} catch (DBSQ_Exception $e) { 
				$this->api_error(2,_("PictureID not found"));
			}
			if (is_null($picture)) { 
				$this->api_error(2,_("PictureID not found"));
			}
		}
		return $picture;
	}
} 
