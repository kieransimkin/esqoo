<?php
class SQ_Controller_Page extends SQ_Class_LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function addUI($arg='',$input=array()) { 
		$page=$this->get_page($arg);
		$form=$this->get_page_form($input,$page);
		$form->addElement('submit','submit',array('data-page-publish-button'=>'true'))->setValue('Publish');
		if ($form->validate()) { 
			$this->addAPI($arg,$input);
			$this->showMessage(_('Page Added'));
			// Redirect to the new page
		}
		$this->view->setTemplate('fullpage-fixed');
		$this->view->form=$form;
	}
	function indexUI($arg='',$input=array()) { 
		return;
	}
	/*************************
	 *  ╺┳┓╻┏━┓╻  ┏━┓┏━╸┏━┓  *
	 *   ┃┃┃┣━┫┃  ┃ ┃┃╺┓┗━┓  *
	 *  ╺┻┛╹╹ ╹┗━╸┗━┛┗━┛┗━┛  *
	 *************************/
	function quickaddDialog($arg='',$input=array()) { 
		$form=$this->get_page_form($input,null);
		if ($form->validate()) { 
			$this->addAPI($arg,$input);
			$this->showMessage(_('Page Addded'));
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
	 private function get_page_form($input,$post,$forcesubmit=false) { 
		$form=new SQ_Class_Form('page');
		$theme=SQ_Class_Theme::get($this->user->ThemeIdentifier);
		$form->setAPIDataSources($input,$post,$forcesubmit);
		$form->addElement('text','Title',array())->setLabel(_('Title'))->addRule('required',_('Required'));
		$form->addElement('text','URL',array())->setLabel(_('URL'))->addRule('required',_('Required'));
		$form->addElement("static","contentarea",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');
		$form->addElement("static","contentarea2",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');
		$form->addElement("static","contentarea3",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');
		$form->addElement("static","contentarea4",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');
		$form->addElement("static","contentarea5",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');
		$form->addElement("static","contentarea6",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');
		$form->addElement("static","contentarea7",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');
		$form->addElement("static","contentarea8",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');
		$form->addElement("static","contentarea9",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');
		$form->addElement("static","contentarea10",array())->setContent('<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus velit erat, mattis vel fringilla in, pellentesque nec tortor. Duis vitae placerat neque. Pellentesque nisl lorem, bibendum vel euismod et, dictum vel nisi. Curabitur sed ipsum urna, vitae aliquam velit. Duis vitae orci sed ipsum iaculis tristique. Duis tortor quam, aliquet vitae mollis nec, laoreet ac erat. Nullam dapibus fringilla consequat. Integer dignissim tincidunt neque id accumsan. In ut cursus ipsum. Ut pretium molestie libero in blandit. Maecenas vel commodo dolor. Curabitur ac massa sit amet metus laoreet ultricies at vitae diam. ');

		$form->addSelect('TemplateIdentifier')
			->setLabel("Template")
			->loadOptions($theme->getKeyValueTemplateList())
			->addRule('required',_('Required'));
		$form->addSelect('LayoutIdentifier')
		     ->setLabel("Layout")
		     ->loadOptions($theme->getKeyValueLayoutList());
		return $form;
	 }
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	public function addAPI($arg='',$input=array()) {
		$oldpage=$this->get_page($input['PageID']);
		$form=$this->get_page_form($input,$oldpage,true);
		if (!$form->validate()) { 
			$this->api_form_validation_error($form);
		}
		if ($this->api_validation_success()) { 
			$page=SQ_Page::get();
			$page->user_id=$this->user->id;
			$page->Title=$input['Title'];
			if ($oldpage) { 
				$page->parent__page=$oldpage->id;
			}
			$page->save();
			return $page;
		}
	}
	public function listAPI($arg='',$input=array()) { 
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Title' && strlen($input['SearchQuery'])>0) { 
			$pages=SQ_Page::getAll('user_id=? and AND DeleteDate is null and Title like ?',array($this->user->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$pages=SQ_Page::getAll('user_id=? AND DeleteDate is null',array($this->user->id),null,$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($pages,$this->get_page_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($pages,$input['Page'],$numrows);
	}
	public function getAPI($arg='',$input=array()) { 
		$page=$this->get_page($input['PageID']);
		if ($page!=null) { 
			$page->set_visible_api_fields($this->get_page_fields());
		}
		return $page;
	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_page_fields() { 
		return array("Title","id","TemplateIdentifier","LayoutIdentifier","CreateDate","PublishDate","ModifyDate","DeleteDate");
	}
	private function get_page($arg) { 
		if (strlen($arg)>0) { 
			try {
				$page=SQ_Page::get($arg,'id','row',true);
				if ($page->user_id!=$this->user->id) { 
					$page=null;
				}
			} catch (DBSQ_Exception $e) { 
				// Post not found
				$page=null;
			}
		} else {
			$page=null;
		}
		return $page;
	}
}
