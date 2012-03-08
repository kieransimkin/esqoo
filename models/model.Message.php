<?php
class Message extends DBSQL { 
	public function save() { 
		parent::save(true);
	}
	static public function seen($userid) { 
		DBSQL::query('update message set CompleteDate=? where user_id=?',array(date('Y-m-d h:i:s'),$userid));
	}
} 
