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
		$this->view->form=$form;
	}
	/*********************
	 *  ┏━╸┏━┓┏━┓┏┳┓┏━┓  *
	 *  ┣╸ ┃ ┃┣┳┛┃┃┃┗━┓  *
	 *  ╹  ┗━┛╹┗╸╹ ╹┗━┛  *
	 *********************/
	 private function get_post_form($input,$post,$forcesubmit=false) { 
		$form=new Form('post');
		$form->setAPIDataSources($input,$post,$forcesubmit);
		$form->addElement('select','website_id',array(),array('options'=>Website::get_menu($this->user->id)))->setLabel(_('Website'))->addRule('required',_('Required'));
		return $form;
	 }

}