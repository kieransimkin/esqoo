<?php
class SQ_Controller_Blog extends SQ_Class_LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function postUI($arg='',$input=array()) { 
		$post=$this->get_post($arg);
		$form=$this->get_post_form($input,$post);
		$form->addElement('submit','submit',array('data-blog-publish-button'=>'true'))->setValue('Publish');
		if ($form->validate()) { 
			$this->postAPI($arg,$input);
			$this->showMessage(_('Blog entry posted'));
			// Redirect to the new blog entry
		}
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
	function quickpostDialog($arg='',$input=array()) { 
		$form=$this->get_post_form($input,null);
		if ($form->validate()) { 
			$this->postAPI($arg,$input);
			$this->showMessage(_('Blog entry posted'));
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
	 private function get_post_form($input,$post,$forcesubmit=false) { 
		$form=new SQ_Class_Form('post');
		$form->setAPIDataSources($input,$post,$forcesubmit);
		$form->addElement('text','Title',array())->setLabel(_('Title'))->addRule('required',_('Required'));
		$form->addElement('textarea','Content',array('class'=>'esqoo-qrichedit'))->setLabel(_('Content'))->addrule('required',_('Required'));
		return $form;
	 }
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	public function postAPI($arg='',$input=array()) {
		$oldpost=$this->get_post($input['PostID']);
		$form=$this->get_post_form($input,$oldpost,true);
		if (!$form->validate()) { 
			$this->api_form_validation_error($form);
		}
		if ($this->api_validation_success()) { 
			$post=SQ_Post::get();
			$post->user_id=$this->user->id;
			$post->Title=$input['Title'];
			$post->Content=$input['Content'];
			$post->save();
			return $post;
		}
	}
	public function listAPI($arg='',$input=array()) { 
		$suffix=SQ_Class_DBSQ::getSqlSuffix($input);
		if ($input['SearchField']=='Title' && strlen($input['SearchQuery'])>0) { 
			$posts=SQ_Post::getAll('user_id=? and AND DeleteDate is null and Title like ?',array($this->user->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$posts=SQ_Post::getAll('user_id=? AND DeleteDate is null',array($this->user->id),null,$suffix);
		}
		SQ_Class_DBSQ::set_all_visible_api_fields($posts,$this->get_post_fields());
		$numrows=SQ_Class_DBSQ::foundRows();
		return $this->flexigridResponse($posts,$input['Page'],$numrows);
	}
	public function getAPI($arg='',$input=array()) { 
		$post=$this->get_post($input['PostID']);
		if ($post!=null) { 
			$post->set_visible_api_fields($this->get_post_fields());
		}
		return $post;
	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_post_fields() { 
		return array("Title","id","Content","CreateDate","PublishDate","ModifyDate","DeleteDate");
	}
	private function get_post($arg) { 
		if (strlen($arg)>0) { 
			try {
				$post=SQ_Post::get($arg,'id','row',true);
				if ($post->user_id!=$this->user->id) { 
					$post=null;
				}
			} catch (DBSQ_Exception $e) { 
				// Post not found
				$post=null;
			}
			
		} else {
			$post=null;
		}
		return $post;
	}
}
