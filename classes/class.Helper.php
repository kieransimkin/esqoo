<?php
class Helper { 
	static function randomAlphaNumString($length=8,$includecaps=true) { 
		if ($includecaps) {
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		} else {
			$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		}
		$string = '';
		for ($i = 0; $i < $length; $i++) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		return $string;
	}
	static function function gobble() {
		$consonants = array ("b","c","d","f","g","h","j","k","m","n","p","q","r","s","t","v","w","x","z" );
		$vowels = array ("a","e","u");
		$length=mt_rand(3,4);
		$c=0;
		$string="";
		while ($c<$length) {
			$string.=$consonants[mt_rand(0,18)];
			$string.=$vowels[mt_rand(0,2)];
			++$c;
		}
		if(mt_rand(0,10000)%2 == 0){
			$string = substr($string,0,strlen($string)-1);
		}
		$string.=mt_rand(10,99);
		return strtolower($string);
	}
}
