<?php
class Rich_editor extends DBSQL { 
	static function get_menu() { 
		$res=Rich_editor::getAll();
		$ret=array();
		foreach ($res as $item) { 
			$ret[$item->id]=$item->Tag;
		}
		return $ret;
	}
}
