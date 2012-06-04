<?php
class SQ_Tag extends DBSQL { 
	static function get_menu($user_id) { 
		$res=SQ_Tag::getAll('DeleteDate is null and user_id=?',array($user_id));
		$ret=array();
		if (is_array($res)) { 
			foreach ($res as $item) { 
				$ret[$item->id]=$item->Name;
			}	
		}
		return $ret;
	}
}
