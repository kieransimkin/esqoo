<?php
class AuthController extends OpenController {
	function getsaltAPI($arg,$input) { 
		$ret=array();
		$user=$this->ensure_api_user($input);
		if ($this->api_validation_success()) { 

		}
		return $ret;
	}
}
