<?php
class SQ_User extends SQ_Class_DBSQ { 
	public static $_cachedfields=array('ThemeIdentifier','default__page_id');
	public function format_date() { 
		return date('Y-m-d');
	}
	public function get_picture_sizes() { 
		return SQ_User_picture_size::getAll('user_id=?',array($this->id));
	}
	public function get_picture_size($type='web-small') { 
		SQ_Picture::assert_picture_size_type($type);
		return $this->getOne('select `size` from user_picture_size where user_id=? and picture_size_type=?',array($this->id,$type));
	}
	public function plugin_enabled($plugin) { 
		$plugins=$this->get_plugins();
		if (in_array($plugin,$plugins)) { 
			return true;
		} else { 
			return false;
		}
	}
	public function get_plugins() { 
		$plugins=SQ_User_plugin_cache_list::get($this->id,'user_id');
		return explode(',',$plugins->IdentifierList);
	}
}
