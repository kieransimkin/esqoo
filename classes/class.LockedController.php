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
			$user=User::get($input['UserID'],'id','row',true);
			$usertoken=User_token::get($input['TokenID']);
			var_dump($usertoken);
			if ($usertoken->user_id!=$user->id) { 
				print"g1".$usertoken->user_id."-".$user->id;
				die;
				$this->_redirectAuthFail();
			}
			if ($usertoken->token!=$input['Token']) { 
				print"g2";
				die;
				$this->_redirectAuthFail();
			}
		} catch (DBSQ_Exception $e) { 
			print $e;
			die;
			$this->_redirectAuthFail();
		}
		$this->user=$user;
		$this->user_token=$usertoken;
	}
	private function _redirectAuthFail() { 
		$this->redirect('/auth/login/?forward='.urlencode($_SERVER['REQUEST_URI']));
	}
}
