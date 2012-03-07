<?php
class ContentController extends LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function uploadUI($arg='',$input=array()) { 
		$album=Album::get();
		$album->user_id=$this->user->id;
		$album->save();
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
		$album=Album::get();
		$album->user_id=$this->user->id;
		$album->save();
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
		$form->addElement('hidden','albumlist',array(),array())->setValue(json_encode(Album::get_autocomplete_array($this->user->id)));
		$af=$form->addElement('text','album',array('data-combobox-source-selector'=>'#albumlist-0','class'=>'esqoo-combobox esqoo-uploadq-album'));
		if ($defaultalbum) { 
			$af->setValue($defaultalbum->Name);
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
			$this->api_error(1,"Size field must be specified and must be an integer");
		}
		if (!isset($input['Chunk'])) { 
			$this->api_error(2,"Chunk field is required");	
		}
		if (!isset($input['AssetID'])) { 
			$input['AssetID']='null';
		}
		if ($input['AssetID']!=='null' && $input['AssetID']!=(int)$input['AssetID']) { 
			$this->api_error(3,"If AssetID is specified, it must be an integer or 'null'");
		}
		if (!isset($input['MimeType']) || strlen($input['MimeType'])<1) { 
			$this->api_error(8,"MimeType must be specified");
		}
		if (!isset($_FILES['Data']) || strlen($input['Data']=file_get_contents($_FILES['Data']['tmp_name']))<1) { 
			$this->api_error(9,"Data must be specified");
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
				if (($asset=Asset::searchPartiallyUploaded($input['ChunkHash'],$input['HashType'],$input['Name'],$this->user->id,$input['Data']))) {  
					$chunkdone=true;
				} else { 
					$asset=Asset::get();
				}
			} else { 
				try { 
					$asset=Asset::get($input['AssetID']);
					if ($asset->user_id!=$this->user->id) { 
						$this->api_error(5,"AssetID not found");
						return;
					}
				} catch (DBSQ_Exception $e) { 
					$this->api_error(5,"AssetID not found");
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
				$chunk=Asset_chunk::get();
				$chunk->asset_id=$asset->id;
				$chunk->Chunk=$input['Chunk'];
				$chunk->ChunkSize=$input['ChunkSize'];
				$chunk->HashType=$input['HashType'];
				$chunk->ChunkHash=$input['ChunkHash'];
				$chunk->save();
				$chunkdata=Asset_chunk_data::get();
				$chunkdata->asset_chunk_id=$chunk->id;
				$chunkdata->Data=$input['Data'];
				$chunkdata->save();
				//$chunk->Data=$input['Data'];
			}
			$remainingchunks=$asset->getRemainingChunks();
			shuffle($remainingchunks);
			$remainingchunkcount=count($remainingchunks);
			if ($remainingchunkcount===0) { 
				$this->compile_asset($asset);
			}
			if ($remainingchunkcount>10) { 
				array_splice($remainingchunks,10);
			}
			return array('Asset'=>$asset,'RemainingChunks'=>$remainingchunks,'RemainingChunkCount'=>$remainingchunkcount);
		}
	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_asset_fields() { 
		return array('id','Size','ChunkSize','MimeType');
	}
	private function compile_asset($asset) { 
		$res=DBSQ::query('select * from asset_chunk_data where asset_id=? order by chunk asc',array($asset->id));
		
		foreach ($res as $chunk) { 
			
		}
	}
	private function validate_album_id($aid) { 
		try { 
			$album=Album::get($aid);
			if ($album->user_id!=$this->user->id) { 
				return false;
			}
			return $album;
		} catch (DBSQ_Exception $e) { 
			return false;
		}
	}

}
