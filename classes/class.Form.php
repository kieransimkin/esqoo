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
class DBSQL_DataSource implements HTML_QuickForm2_DataSource { 
	protected $values;

	public function __construct($object) { 
		$this->values=$object;
	}
	public function getValue($name) { 
		var_dump($values);
		return $values->$name;
	}
}
