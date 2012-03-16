<?php
class Ui_theme extends DBSQL { 
	static function get_menu() { 
		$res=UI_theme::getAll();
		$ret=array();
		if (is_array($res)) { 
			foreach ($res as $item) { 
				$ret[$item->id]=$item->Tag;
			}
		}
		return $ret;
	}
}
