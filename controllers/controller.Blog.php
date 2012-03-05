<?php
class BlogController extends LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function postUI($arg='',$input=array()) { 
		if (strlen($arg)>0) { 
			try {
				$post=Post::get($arg,'id','row',true);
			} catch (DBSQ_Exception $e) { 
				// Post not found
				$post=null;
			}
		} else {
			$post=null;
		}
		$form=$this->get_post_form($input,$post);
		if ($form->validate()) { 
			$this->postAPI($arg='',$input);
			$this->showMessage(_('Blog entry posted'));
			// Redirect to the new blog entry
		}
		$this->view->form=$form;
	}
	/*************************
	 *  ╺┳┓╻┏━┓╻  ┏━┓┏━╸┏━┓  *
	 *   ┃┃┃┣━┫┃  ┃ ┃┃╺┓┗━┓  *
	 *  ╺┻┛╹╹ ╹┗━╸┗━┛┗━┛┗━┛  *
	 *************************/
	function quickpostDialog($arg='',$input=array()) { 
		$form=$this->get_post_form($input,null);
		if ($form->validate()) { 
			$this->postAPI($arg='',$input);
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
		$form=new Form('post');
		$form->setAPIDataSources($input,$post,$forcesubmit);
		$form->addElement('text','Title',array())->setLabel(_('Title'))->addRule('required',_('Required'));
		$form->addElement('textarea','Content',array())->setLabel(_('Content'))->addrule('required',_('Required'));
		return $form;
	 }
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	public function postAPI ($arg='',$input=array()) {

	}
}
