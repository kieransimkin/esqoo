<?php
class SQ_Controller_Auth extends SQ_Class_OpenController {
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function loginUI($arg='',$input=array()) { 
		$this->view->challenge=$this->getchallengeAPI('',array());
		$this->view->forward=@$input['forward'];
	}
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/

	/**
	 * This function returns a challenge, which you must hash together 
	 * with your password before calling authenticate with the generated
	 * hash.
	 */
	function getchallengeAPI($arg='',$input=array()) { 
		$ret=array();
		$challenge=SQ_User_challenge::create(array('challenge'=>SQ_Class_Helper::randomAlphaNumString(64)));
		$ret['Challenge']=$challenge->challenge;
		$ret['ChallengeID']=$challenge->id;
		return $ret;
	}
	/**
	 * This function takes your hashed password response, and if it matches
	 * the password stored on file, returns an authentication token. This 
	 * authentication token then becomes your pass to execute privileged
	 * functions via the API.
	 */
	function authenticateAPI($arg='',$input=array()) { 
		$ret=array();
		$user=$this->ensure_api_user($input);
		$challenge=$this->ensure_api_challenge($user,$input);
		$this->ensure_response_authorized($user,$challenge,$input);
		if (!is_null($challenge)) { 
			$challenge->delete();
		}
		if ($this->api_validation_success()) { 
			$token=SQ_User_token::create(array('user'=>$user,'token'=>SQ_Class_Helper::randomAlphaNumString(255)));
			$ret['UserID']=$user->id;
			$ret['TokenID']=$token->id;
			$ret['Token']=$token->token;
			setcookie('UserID',$user->id,time()+31556926,'/');
			setcookie('TokenID',$token->id,time()+31556926,'/');
			setcookie('Token',$token->token,time()+31556926,'/');
		} else { 
			// Auth failed, generate a new challenge
			$challenge=SQ_User_challenge::create(array('challenge'=>SQ_Class_Helper::randomAlphaNumString(64)));
			$ret['Challenge']=$challenge->challenge;
			$ret['ChallengeID']=$challenge->id;
		}
		return $ret;
	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function ensure_response_authorized($user,$challenge,$input) { 
		if (strlen(@$input['Response'])<1) { 
			$this->api_error(5,_("Response field is required"));
		}
		if (strlen(@$input['HashType'])<1) { 
			$this->api_error(6,_("HashType field is required"));
		}
		if (	@$input['HashType']!='SHA1' && 
			@$input['HashType']!='SHA256' && 
			@$input['HashType']!='SHA384' && 
			@$input['HashType']!='SHA512' && 
			@$input['HashType']!='MD5') { 
				$this->api_error(7,_("HashType must be either 'SHA1', 'SHA256', 'SHA384', 'SHA512' or 'MD5'"));
		}
		return $this->ensure_hash_match($user,$challenge,$input);
	}
	private function ensure_hash_match($user,$challenge,$input) { 
		$hash=hash(strtolower($input['HashType']),$challenge->challenge.$user->Password);
		if (strtolower(@$input['Response'])!=$hash) { 
			$this->api_error(8,_("Authentication failed"));
			return null;
		}
		return true;
	}
	private function ensure_api_challenge($user,$input) { 
		if (strlen(@$input['ChallengeID'])<1) { 
			$this->api_error(3,_("ChallengeID field is required"));
			return null;
		}
		try { 
			$challenge=SQ_User_challenge::get($input['ChallengeID'],'id','col',true);
		} catch (DBSQ_Exception $e) { 
			$challenge=null;
		}
		if (is_null($challenge)) { 
			$this->api_error(4,_("ChallengeID not found"));
			return null;
		}
		return $challenge;
	}
	private function ensure_api_user($input) {
		$user=null;
		if (strlen(@$input['UserID'])<1 && strlen(@$input['Username'])<1 && strlen(@$input['Email'])<1) { 
			$this->api_error(1,_("Username, Email, or UserID field is required"));
		} else { 
			try {
				if (strlen(@$input['Username'])>0) { 
					$user=SQ_User::get($input['Username'],'Username','col',true);
				} else if (strlen(@$input['Email'])>0) { 
					$user=SQ_User::get($input['Email'],'Email','col',true);
				} else if (strlen(@$input['UserID'])>0) { 
					$user=SQ_User::get($input['UserID'],'id','col',true);
				}
			} catch (DBSQ_Exception $e) { 
				$user=null;
			}
			if (is_null($user)) { 
				$this->api_error(2,_("Username, Email or UserID not found"));
			}
		}
		return $user;
	}

}
