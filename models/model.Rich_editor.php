<?php
class Rich_editor extends DBSQL { 
	function get_menu() { 
		$res=Rich_editor::getAll();
		$ret=array();
		foreach ($res as $item) { 
			$ret[$item->id]=$item->Tag;
		}
		var_dump($ret);
		return $ret;
	}
}
