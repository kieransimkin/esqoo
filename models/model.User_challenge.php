<?php
class SQ_User_challenge extends SQ_Class_DBSQ { 
	function delete() { 
		self::query("delete from user_challenge where id=? LIMIT 1",array($this->id));
	}
}
