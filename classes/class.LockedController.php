<?php
class LockedController extends Controller { 
	function __construct($controller,$action) { 
		parent::__construct($controller,$action);
		$this->_verifyAuth(array_merge($_COOKIE,$_POST));
	}
	private function _verifyAuth($input) { 
		if (strlen(@$input['UserID'])<1 || strlen(@$input['TokenID'])<1 || strlen(@$input['Token'])<1) { 
			$this->redirect('/auth/login?forward='.urlencode($_SERVER['REQUEST_URI']));
		}
	}
}
