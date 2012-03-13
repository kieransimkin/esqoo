<?php
class Message extends DBSQL { 
	public function save() { 
		parent::save(true);
	}
	public function seen() { 
		$this->CompleteDate=date('Y-m-d h:i:s');
		return $this->save();
	}
} 
