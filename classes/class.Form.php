<?php
require_once 'HTML/QuickForm2.php';
class Form extends HTML_QuickForm2 { 
	public function __construct($id, $method = 'post', $attributes = null) {
		if (!isset($attributes) || !isset($attributes['action'])) { 
			if (!isset($attributes)) { 
				$attributes=array();
			}
			$attributes['action']=$_SERVER['REQUEST_URI'];
		}
		parent::__construct($id,$method,$attributes,true);
	}
	public function setAPIDataSources($input,$object) { 
		$id=$this->getId();
		if (isset($input['_qf__'.$id])) { 
			$this->setDataSources(array(new Submit_Array_DataSource($input), new DBSQL_DataSource($user)));
		} else { 
			$this->setDataSources(array(new DBSQL_DataSource($user)));
		}
	}
}
class DBSQL_DataSource implements HTML_QuickForm2_DataSource { 
	protected $values;

	public function __construct($object) { 
		$this->values=$object;
	}
	public function getValue($name) { 
		return $this->values->$name;
	}
}
class Submit_Array_DataSource extends HTML_QuickForm2_DataSource_Array implements HTML_QuickForm2_DataSource_Submit {
	protected $values;
	public function __construct($input) { 
		$this->values=$input;
	}
	public function getValue($name) { 
		return (array_key_exists($name,$this->values)) ? $this->values[$name] : null;
	}
	public function getUpload($name) { 
		return null;
	}
}
