<?php
class Rich_editor extends DBSQL { 
	function get_menu() { 
		$res=self::getAll();
		$ret=array();
		foreach ($res as $item) { 
			$ret[$item['rich_editor_id']]=$item['Tag'];
		}
		return $ret;
	}
}
