<?php
class AuthController extends OpenController {
	function getchallengeAPI($arg,$input) { 
		$ret=array();
		$user=$this->ensure_api_user($input);
		if ($this->api_validation_success()) { 
			$challenge=User_challenge::create(array('user'=>$user,'challenge'=>Helper::randomAlphaNumString(64)));
			$ret['Challenge']=$challenge->challenge;
			$ret['UserID']=$challenge->user_id;
			$ret['ChallengeID']=$challenge->id;
		}
		return $ret;
	}
	function authenticateAPI($arg,$input) { 
		$ret=array();
		$user=$this->ensure_api_user($input);
		$challenge=$this->ensure_api_challenge($user,$input);
		$this->ensure_response_authorized($user,$challenge,$input);
		$challenge->delete();
		if ($this->api_validation_success()) { 
			$token=User_token::create(array('user'=>$user,'token'=>Helper::randomAlphaNumString(255)));
			$ret['UserID']=$user->id;
			$ret['TokenID']=$token->id;
			$ret['Token']=$token->token;
			setcookie('UserID',$user->id);
			setcookie('TokenID',$token->id);
			setcookie('Token',$token->token);
		} else if (!is_null($user) && !is_null($challenge)) { 
			// Auth failed, generate a new challenge
			$challenge=User_challenge::create(array('user'=>$user,'challenge'=>Helper::randomAlphaNumString(64)));
			$ret['Challenge']=$challenge->challenge;
			$ret['UserID']=$user->id;
			$ret['ChallengeID']=$challenge->id;
		}
		return $ret;
	}
	private function ensure_response_authorized($user,$challenge,$input) { 
		if (strlen(@$input['Response'])<1) { 
			$this->api_error(5,"Response field is required");
		}
		if (strlen(@$input['ResponseHashType'])<1) { 
			$this->api_error(6,"ResponseHashType field is required");
		}
		if (@$input['ResponseHashType']!='SHA1' && @$input['ResponseHashType']!='SHA256' && @$input['ResponseHashType']!='SHA384' && @$input['ResponseHashType']!='SHA512' && @$input['ResponseHashType']!='MD5') { 
			$this->api_error(7,"ResponseHashType must be either 'SHA1', 'SHA256', 'SHA384', 'SHA512' or 'MD5'");
		}
		return $this->ensure_hash_match($user,$challenge,$input);
	}
	private function ensure_hash_match($user,$challenge,$input) { 
		$hash=hash(strtolower($input['ResponseHashType']),$challenge->challenge.$user->password);
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
		$challenge=User_challenge::get($input['ChallengeID']);
		if (is_null($challenge)) { 
			$this->api_error(4,"ChallengeID not found");
			return null;
		}
		if ($challenge->user_id != $user->id) { 
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
			if (strlen(@$input['UserID'])>0) { 
				$user=User::get($input['UserID']);
			} else if (strlen(@$input['Username'])>0) { 
				$user=User::get($input['Username'],'username');
			} else if (strlen(@$input['Email'])>0) { 
				$user=User::get($input['Email'],'email');
			}
			if (is_null($user)) { 
				$this->api_error(2,"Username, Email or UserID not found");
			}
		}
		return $user;
	}

}
