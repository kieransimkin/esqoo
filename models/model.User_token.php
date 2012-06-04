<?php
class SQ_User_token extends DBSQL { 
	function delete() { 
		self::query("delete from user_token where id=? LIMIT 1",array($this->id));
	}
}
