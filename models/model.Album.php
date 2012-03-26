<?php
class Album extends DBSQL { 
	public function save() { 
		$name=$this->Name;
		$origname=$name;
		$c=1;
		try { 
			$thisid=$this->id;
		} catch (DBSQ_Exception $e) { 
			$thisid=-1;
		}
		while (($alb=Album::album_exists($this->user_id, $name))) { 
			if ($alb==$thisid) { 
				break;
			}
			$name=$origname.' ('.$c.')';
			++$c;
		}
		$this->Name=$name;
		return parent::save();
	}
	static function album_exists($user_id,$name) { 
		$res=DBSQL::getOne('select id from album where user_id=? and UserVisible=\'true\' and DeleteDate is null and Name=?',array($user_id,$name));
		if (is_null($res)) { 
			return false;
		} else {
			return $res;
		}
	}
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
