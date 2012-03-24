<?php
class User extends DBSQL { 
	public function format_date() { 
		return date('Y-m-d');
	}
	public function get_picture_sizes() { 
		return User_picture_size::getAll('user_id=?',array($this->id));
	}
	public function get_picture_size($type='web-small') { 
		Picture::assert_picture_size_type($type);
		return $this->getOne('select `size` from user_picture_size where user_id=? and picture_size_type=?',array($this->id,$type));
	}
}
