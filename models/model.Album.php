<?php
class Album extends DBSQL { 
	static function get_menu($user_id) { 
		$res=Album::getAll('DeleteDate is null and user_id=? and UserVisible=\'true\'',array($user_id));
		$ret=array();
		if (is_array($res)) { 
			foreach ($res as $item) { 
				$ret[$item->id]=$item->Name;
			}
		}
		return $ret;
	}
	static function get_autocomplete_array($user_id) { 
		$res=Album::getAll('DeleteDate is null and user_id=? AND UserVisible=\'true\'',array($user_id));
		$ret=array();
		if (is_array($res)) { 
			foreach ($res as $item) { 
				$ret[]=array('label'=>$item->Name,'value'=>$item->id);
			}
		}
		return $ret;
	}
} 
