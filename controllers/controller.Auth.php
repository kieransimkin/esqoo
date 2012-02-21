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
	function authAPI($arg,$input) { 
		$ret=array();
		$user=$this->ensure_api_user($input);
		if ($this->api_validation_success()) { 

		}
	}
}
