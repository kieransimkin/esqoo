<?php
class SQ_Message extends SQ_Class_DBSQ { 
	public function save() { 
		return parent::save(true);
	}
	public function seen() { 
		$this->CompleteDate=date('Y-m-d h:i:s');
		return $this->save();
	}
} 
