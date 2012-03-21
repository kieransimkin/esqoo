<?php
class User extends DBSQL { 
	public function format_date() { 
		return date('Y-m-d');
	}
	public function get_picture_sizes() { 
		return User_picture_size::getAll('user_id=?',array($this->id));
	}
	public function get_picture_size($type='web-small') { 
		if ($type!='web-small' && $type!='web-medium' && $type!='web-large' && $type!='thumbnail-large' && $type!='thumbnail-small' && $type!='square') { 
			throw new Exception('type must be one of web-small, web-medium, web-large, thumbnail-large, thumbnail-small or square');
			return null;
		}
		return $this->getOne('select `size` from user_picture_size where user_id=? and picture_size_type=?',array($this->id,$type));
	}
}
