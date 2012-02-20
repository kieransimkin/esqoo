<?php
class AuthController extends OpenController {
	function getsaltAPI($arg,$input) { 
		$ret=array();
		$user=$this->ensure_api_user($input);
		var_dump((string)$user);
		var_dump($input);
		if ($this->api_validation_success()) { 
			$salt=User_salt::create(array('user'=>$user,'salt'=>Helper::randomAlphaNumString(64)));
			var_dump($salt);
		}
		return $ret;
	}
}
