<?php
class UI_theme extends DBSQL { 
	static function get_menu() { 
		$res=UI_theme::getAll();
		$ret=array();
		foreach ($res as $item) { 
			$ret[$item->id]=$item->Tag;
		}
		return $ret;
	}
}
