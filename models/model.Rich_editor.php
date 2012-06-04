<?php
class SQ_Rich_editor extends SQ_Class_DBSQ { 
	static function get_menu($type='Visual') { 
		$type=ucfirst(strtolower(@$type));
		if ($type!='Visual' && $type!='Code') { 
			throw new Exception('Type must be Code or Visual');
		}
		$res=SQ_Rich_editor::getAll('`Type`=?',array($type));
		$ret=array();
		if (is_array($res)) { 
			foreach ($res as $item) { 
				$ret[$item->id]=$item->Tag;
			}
		}
		return $ret;
	}
}
