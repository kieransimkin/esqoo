<?php
class Website extends DBSQL { 
	static function get_menu($user_id) { 
		$res=Website::getAll('user_id=? and DeleteDate is null',array($user_id));
		$ret=array();
		if (is_array($res)) { 
			foreach ($res as $item) { 
				$ret[$item->id]=$item->ServerName;
			}
		}
		return $ret;
	}
}
