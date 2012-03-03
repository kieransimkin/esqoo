<?php
require_once(dirname(__FILE__).'/dbsq/dbsq.class.php');
class DBSQL extends DBSQ { 
	private $_visiblefields=array();
	function delete() { 
		$this->DeleteDate=date("c");
		$this->save();
	}
	function save($nomodifydate=false) { 
		if (!$nomodifydate) { 
			$this->ModifyDate=date("c");
		}
		parent::save();
	}
	function set_visible_api_fields($fields=array()) { 
		if (!is_array($fields)) { 
			throw new DBSQ_Exception('Fields array is empty');
			return;
		}
		$this->_visiblefields=$fields;
		return $this;
	}
	function getFilteredDataArray() { 
		$ldata=$this->getDataArray();
		$ret=array();
		foreach ($this->_visiblefields as $field) { 
			$ret[$field]=$ldata[$field];
		}
		return $ret;
	}
}
