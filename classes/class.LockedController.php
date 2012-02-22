<?php
class LockedController extends Controller { 
	function __construct($controller,$action) { 
		parent::__construct($controller,$action);
		$this->_verifyAuth(array_merge($_COOKIE,$_POST));
	}
	private function _verifyAuth($input) { 
		if (strlen(@$input['UserID'])<1 || strlen(@$input['TokenID'])<1 || strlen(@$input['Token'])<1) { 
			$this->_redirectAuthFail();
		}
		try { 
			$user=User::get($input['UserID']);
			$usertoken=User_token::get($input['TokenID']);
			if ($token->user_id!=$user->id) { 
				$this->_redirectAuthFail();
			}
			if ($token->token!=$input['Token']) { 
				$this->_redirectAuthFail();
			}
		} catch (DBSQ_Exception $e) { 
			$this->_redirectAuthFail();
		}
		$this->user=$user;
	}
	private function _redirectAuthFail() { 
		$this->redirect('/auth/login/?forward='.urlencode($_SERVER['REQUEST_URI']));
	}
}
