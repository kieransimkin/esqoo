<?php
require_once 'HTML/QuickForm2.php';
class Form extends HTML_QuickForm2 { 
	public function __construct($id, $method = 'post', $attributes = null, $trackSubmit = true) {
		if (!isset($attributes) || !isset($attributes['action'])) { 
			if (!isset($attributes)) { 
				$attributes=array();
			}
			$attributes['action']=$_SERVER['REQUEST_URI'];
		}
		parent::__construct($id,$method,$attributes,$trackSubmit);
	}

} 
