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
	static function gobble() {
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
	/* Stolen from Wordpress: */
	static function sanitize_file_name( $filename ) {
	    $filename_raw = $filename;
	    $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}");
	    $filename = str_replace($special_chars, '', $filename);
	    $filename = preg_replace('/[\s-]+/', '-', $filename);
	    $filename = trim($filename, '.-_');
	    return $filename;
	}
	static function url_friendly_encode($url) { 
		return urlencode(iconv('UTF-8','ASCII//TRANSLIT',str_replace(' ','-',$url)));
	}
	static function getGps($exifCoord, $hemi) {

	    $degrees = count($exifCoord) > 0 ? Helper::gps2Num($exifCoord[0]) : 0;
	    $minutes = count($exifCoord) > 1 ? Helper::gps2Num($exifCoord[1]) : 0;
	    $seconds = count($exifCoord) > 2 ? Helper::gps2Num($exifCoord[2]) : 0;

	    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

	    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

	}
	static function gps2Num($coordPart) {
	    $parts = explode('/', $coordPart);

	    if (count($parts) <= 0)
		return 0;

	    if (count($parts) == 1)
		return $parts[0];

	    return floatval($parts[0]) / floatval($parts[1]);
	}


}
