<?php
require_once(dirname(__FILE__).'/dbsq/dbsq.class.php');
class DBSQL extends DBSQ { 
	function delete() { 
		$this->deletedate=date("c");
		$this->save();
	}
	function save() { 
		$this->modifydate=date("c");
		parent::save();
	}
}
