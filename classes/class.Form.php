<?php
require_once 'HTML/QuickForm2.php';
class SQ_Class_Form extends HTML_QuickForm2 { 
	private static $_setup=false;
	public function __construct($id, $method = 'post', $attributes = null) {
		if (!self::$_setup) { 
			HTML_QuickForm2_Factory::registerElement('div','HTML_QuickForm2_Container_Div');
			HTML_QuickForm2_Factory::registerElement('section','HTML_QuickForm2_Container_Section');
			self::$_setup=true;
		}
		if (!isset($attributes) || !isset($attributes['action'])) { 
			if (!isset($attributes)) { 
				$attributes=array();
			}
			$attributes['action']=$_SERVER['REQUEST_URI'];
		}
		parent::__construct($id,$method,$attributes,true);
	}
	public function setAPIDataSources($input,$object,$forcesubmit=false) { 
		$id=$this->getId();
		if (isset($input['_qf__'.$id]) || $forcesubmit) { 
			$this->setDataSources(array(new Submit_Array_DataSource($input), new DBSQL_DataSource($object)));
		} else { 
			$this->setDataSources(array(new DBSQL_DataSource($object)));
		}
	}
}
class DBSQL_DataSource implements HTML_QuickForm2_DataSource { 
	protected $values;

	public function __construct($object) { 
		$this->values=$object;
	}
	public function getValue($name) { 
		try { 
			return $this->values->$name;
		} catch (DBSQ_Exception $e) { 
			return null;
		}

	}
}
class Submit_Array_DataSource extends HTML_QuickForm2_DataSource_Array implements HTML_QuickForm2_DataSource_Submit {
	protected $values;
	public function __construct($input) { 
		$this->values=$input;
	}
	public function getValue($name) { 
		return $this->values[$name];
	}
	public function getUpload($name) { 
		return null;
	}
}
