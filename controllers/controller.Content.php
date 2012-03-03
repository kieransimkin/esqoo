<?php
class ContentController extends LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function uploadUI($arg='',$input=array()) { 
		$form=$this->get_upload_form($input);
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
		$form=$this->get_upload_form($input);
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
	 private function get_upload_form($input,$forcesubmit=false) { 
		$form=new Form('upload');
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
		if (strlen(@$input['HashType'])<1) { 
			$this->api_error(6,"HashType field is required");
		}
		if (	@$input['HashType']!='SHA1' && 
			@$input['HashType']!='SHA256' && 
			@$input['HashType']!='SHA384' && 
			@$input['HashType']!='SHA512' && 
			@$input['HashType']!='MD5') { 
				$this->api_error(7,"HashType must be either 'SHA1', 'SHA256', 'SHA384', 'SHA512' or 'MD5'");
		}
		if (!isset($input['Chunk'])) { 
			$this->api_error(2,"Chunk field is required");	
		}
		if (!isset($input['ChunkHash']) || strlen($input['ChunkHash'])<1) { 
			$this->api_error(4,"ChunkHash field is required");	
		}
		if (!isset($input['AssetID'])) { 
			$input['AssetID']='null';
		}
		if ($input['AssetID']!=='null' && $input['AssetID']!=(int)$input['AssetID']) { 
			$this->api_error(3,"If AssetID is specified, it must be an integer or 'null'");
		}
		if ($this->api_validation_success()) { 
			if (!isset($input['Name']) || strlen($input['Name'])<1) { 
				$input['Name']='Untitled';
			}
			if (!isset($input['ChunkSize']) || $input['ChunkSize']==='null') { 
				$input['ChunkSize']=$input['Size'];
			}
		}
	}
}
