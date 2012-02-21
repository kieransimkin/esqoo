<?php
class User_challenge extends DBSQL { 
	function delete() { 
		self::query("delete from user_challenge where id=? LIMIT 1",array($this->id));
	}
}
