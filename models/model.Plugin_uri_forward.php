<?php
class SQ_Plugin_uri_forward extends SQ_Class_DBSQ { 
	public static $_cachedfields=array();
	function save() { 
		$ret=parent::save(true);
		$cache=SQ_Plugin_uri_forward_cache::get($this->user_id,'user_id');
		$cache->purge_cache();
		return $ret;
	}
} 
