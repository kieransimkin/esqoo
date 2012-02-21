<?php
class AuthController extends OpenController {
	function getchallengeAPI($arg,$input) { 
		$ret=array();
		$challenge=User_challenge::create(array('challenge'=>Helper::randomAlphaNumString(64)));
		$ret['Challenge']=$challenge->challenge;
		$ret['ChallengeID']=$challenge->id;
		return $ret;
	}
	function authenticateAPI($arg,$input) { 
		$ret=array();
		$user=$this->ensure_api_user($input);
		$challenge=$this->ensure_api_challenge($user,$input);
		$this->ensure_response_authorized($user,$challenge,$input);
		if (!is_null($challenge)) { 
			$challenge->delete();
		}
		if ($this->api_validation_success()) { 
			$token=User_token::create(array('user'=>$user,'token'=>Helper::randomAlphaNumString(255)));
			$ret['UserID']=$user->id;
			$ret['TokenID']=$token->id;
			$ret['Token']=$token->token;
			setcookie('UserID',$user->id,time()+31556926,'/');
			setcookie('TokenID',$token->id,time()+31556926,'/');
			setcookie('Token',$token->token,time()+31556926,'/');
		} else { 
			// Auth failed, generate a new challenge
			$challenge=User_challenge::create(array('challenge'=>Helper::randomAlphaNumString(64)));
			$ret['Challenge']=$challenge->challenge;
			$ret['ChallengeID']=$challenge->id;
		}
		return $ret;
	}
	function loginUI($arg,$input) { 
		$this->view->challenge=$this->getchallengeAPI('',array());
		$this->view->forward=@$input['Forward'];
	}
	private function ensure_response_authorized($user,$challenge,$input) { 
		if (strlen(@$input['Response'])<1) { 
			$this->api_error(5,"Response field is required");
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
		return $this->ensure_hash_match($user,$challenge,$input);
	}
	private function ensure_hash_match($user,$challenge,$input) { 
		$hash=hash(strtolower($input['HashType']),$challenge->challenge.$user->password);
		if (strtolower(@$input['Response'])!=$hash) { 
			$this->api_error(8,"Authentication failed");
			return null;
		}
		return true;
	}
	private function ensure_api_challenge($user,$input) { 
		if (strlen(@$input['ChallengeID'])<1) { 
			$this->api_error(3,"ChallengeID field is required");
			return null;
		}
		try { 
			$challenge=User_challenge::get($input['ChallengeID'],'id','col',true);
		} catch (DBSQ_Exception $e) { 
			$challenge=null;
		}
		if (is_null($challenge)) { 
			$this->api_error(4,"ChallengeID not found");
			return null;
		}
		return $challenge;
	}
	private function ensure_api_user($input) {
		$user=null;
		if (strlen(@$input['UserID'])<1 && strlen(@$input['Username'])<1 && strlen(@$input['Email'])<1) { 
			$this->api_error(1,"Username, Email, or UserID field is required");
		} else { 
			try {
				if (strlen(@$input['UserID'])>0) { 
					$user=User::get($input['UserID'],'id','col',true);
				} else if (strlen(@$input['Username'])>0) { 
					$user=User::get($input['Username'],'username','col',true);
				} else if (strlen(@$input['Email'])>0) { 
					$user=User::get($input['Email'],'email','col',true);
				}
			} catch (DBSQ_Exception $e) { 
				$user=null;
			}
			if (is_null($user)) { 
				$this->api_error(2,"Username, Email or UserID not found");
			}
		}
		return $user;
	}

}
