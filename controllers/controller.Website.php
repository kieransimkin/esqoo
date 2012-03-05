<?php
class WebsiteController extends LockedController { 
	/*************************
	 *  ╺┳┓╻┏━┓╻  ┏━┓┏━╸┏━┓  *
	 *   ┃┃┃┣━┫┃  ┃ ┃┃╺┓┗━┓  *
	 *  ╺┻┛╹╹ ╹┗━╸┗━┛┗━┛┗━┛  *
	 *************************/
	function indexUI ($arg='',$input=array()) { 
		$this->redirect('http://'.$this->user->Username.'.'.Site::$config['cp_hostname']);
	}
}
