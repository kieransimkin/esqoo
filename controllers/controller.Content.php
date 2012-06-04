<?php
class ContentController extends LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function uploadUI($arg='',$input=array()) { 
		$album=$this->foreign_api_action('AlbumController','get-stub');
		$form=$this->get_upload_form($input,$album,$this->validate_album_id($arg));
		if ($form->validate()) { 
	

		}
		$this->view->form=$form;
	}
	/*************************
	 *  ╺┳┓╻┏━┓╻  ┏━┓┏━╸┏━┓  *
	 *   ┃┃┃┣━┫┃  ┃ ┃┃╺┓┗━┓  *
	 *  ╺┻┛╹╹ ╹┗━╸┗━┛┗━┛┗━┛  *
	 *************************/
	function quickuploadDialog($arg='',$input=array()) { 
		$album=$this->foreign_api_action('AlbumController','get-stub');
		$form=$this->get_upload_form($input,$album,$this->validate_album_id($arg));
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
	 private function get_upload_form($input,$album,$defaultalbum,$forcesubmit=false) { 
		$form=new Form('upload');
		$form->addElement('hidden','new_album_id',array(),array())->setValue($album->id);
		$form->addElement('hidden','albumlist',array(),array())->setValue(json_encode(SQ_Album::get_autocomplete_array($this->user->id)));
		$form->addElement('hidden','new_album_name',array(),array())->setValue($album->Name);
		$af=$form->addElement('text','album',array('data-combobox-source-selector'=>'#albumlist-0','class'=>'esqoo-combobox esqoo-uploadq-album'));
		if ($defaultalbum) { 
			$af->setValue($defaultalbum->Name);
		} else { 
			$af->setValue($album->Name);
		}
		$form->addElement('file','upload',array('class'=>'upload-form','multiple'=>'multiple'))->setLabel(_('Select files'));
		return $form;
	 }
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	public function uploadAPI($arg='',$input=array()) { 
		if (!isset($input['Size']) || $input['Size']!=(int)$input['Size']) { 
			$this->api_error(1,_("Size field must be specified and must be an integer"));
		}
		if (!isset($input['Chunk']) || $input['Chunk']!=(int)$input['Chunk']) { 
			$this->api_error(2,_("Chunk field is required and must be an integer"));
		}
		if (!isset($input['AssetID'])) { 
			$input['AssetID']='null';
		}
		if ($input['AssetID']!=='null' && $input['AssetID']!=(int)$input['AssetID']) { 
			$this->api_error(3,_("If AssetID is specified, it must be an integer or 'null'"));
		}
		if (!isset($input['MimeType']) || strlen($input['MimeType'])<1) { 
			$this->api_error(8,_("MimeType must be specified"));
		}
		if (!isset($_FILES['Data']) || strlen($input['Data']=file_get_contents($_FILES['Data']['tmp_name']))<1) { 
			$this->api_error(9,_("Data must be specified"));
		}
		if (!$this->validate_album_id($input['AlbumID'])) { 
			$this->api_error(10,_("AlbumID not found"));
		}
		if ($this->api_validation_success()) { 
			if (!isset($input['Name']) || strlen($input['Name'])<1) { 
				$input['Name']='Untitled';
			}
			if (!isset($input['ChunkSize']) || $input['ChunkSize']==='null') { 
				$input['ChunkSize']=$input['Size'];
			}
			$asset=null;
			$chunkdone=false;
			$input['ChunkHash']=hash('sha256',$input['Data']);
			$input['HashType']='SHA256';
			if ($input['AssetID']==='null') { 
				if (($asset=SQ_Asset::searchPartiallyUploaded($input['ChunkHash'],$input['HashType'],$input['Name'],$this->user->id,$input['Data']))) {  
					$chunkdone=true;
				} else { 
					$asset=SQ_Asset::get();
				}
			} else { 
				try { 
					$asset=SQ_Asset::get($input['AssetID']);
					if ($asset->user_id!=$this->user->id) { 
						$this->api_error(5,_("AssetID not found"));
						return;
					}
				} catch (DBSQ_Exception $e) { 
					$this->api_error(5,_("AssetID not found"));
					return;
				}
			}
			$asset->Name=$input['Name'];
			$asset->Size=$input['Size'];
			$asset->ChunkSize=$input['ChunkSize'];
			$asset->user_id=$this->user->id;
			$asset->MimeType=$input['MimeType'];
			$asset->save();
			$asset->set_visible_api_fields($this->get_asset_fields());

			if (!$chunkdone) { 
				$chunk=SQ_Asset_chunk::get();
				$chunk->asset_id=$asset->id;
				$chunk->Chunk=$input['Chunk'];
				$chunk->ChunkSize=$input['ChunkSize'];
				$chunk->HashType=$input['HashType'];
				$chunk->ChunkHash=$input['ChunkHash'];
				$chunk->save();
				$chunkdata=SQ_Asset_chunk_data::get();
				$chunkdata->asset_chunk_id=$chunk->id;
				$chunkdata->Data=$input['Data'];
				$chunkdata->save();
			}
			$remainingchunks=$asset->getRemainingChunks();
			shuffle($remainingchunks);
			$remainingchunkcount=count($remainingchunks);
			if ($remainingchunkcount>10) { 
				array_splice($remainingchunks,10);
			}
			$ret=array('Asset'=>$asset,'RemainingChunks'=>$remainingchunks,'RemainingChunkCount'=>$remainingchunkcount);
			if ($remainingchunkcount===0) { 
				$ret=array_merge($ret,$asset->compile_asset($input['AlbumID']));

				if (array_key_exists('Picture',$ret)) { 
					$ret['Picture']->set_visible_api_fields($this->get_picture_fields());
					$ret['Picture']->add_computed_field('PictureURLs','get_url_array');
				} else if (array_key_exists('Video',$ret)) { 
					$ret['Video']->set_visible_api_fields($this->get_video_fields());
				} else if (array_key_exists('Audio',$ret)) { 
					$ret['Audio']->set_visible_api_fields($this->get_audio_fields());
				} else { 
					$ret['File']->set_visible_api_fields($this->get_file_fields());
				}
			}
			return $ret;
		}
	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_asset_fields() { 
		return array('id','Name','Size','ChunkSize','MimeType');
	}
	private function get_picture_fields() {
		return array('id','Name','Description');
	}
	private function get_video_fields() { 
		return array('id','Name','Description');
	}
	private function get_audio_fields() { 
		return array('id','Name','Description');
	}
	private function get_file_fields() { 
		return array('id','Name','Description','MimeType');
	}
	private function validate_album_id($aid) { 
		try { 
			$album=SQ_Album::get($aid);
			if ($album->user_id!=$this->user->id) { 
				return false;
			}
			return $album;
		} catch (DBSQ_Exception $e) { 
			return false;
		}
	}

}
