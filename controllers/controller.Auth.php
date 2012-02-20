<?php
class AuthController extends OpenController {
	function getsaltAPI($arg,$input) { 
		$ret=array();
		$user=$this->ensure_api_user($input);
		if ($this->api_validation_success()) { 
			$salt=User_salt::create(array('user'=>$user,'salt'=>Helper::randomAlphaNumString(64)));
			$ret['Salt']=$salt->salt;
			$ret['UserID']=$salt->user_id;
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
