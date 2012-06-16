<?php
class SQ_User_plugin extends SQ_Class_DBSQ { 
	function save() { 
		return parent::save(true);
		$cache=SQ_User_plugin_cache_list::get($this->user_id,'user_id');
		$cache->purge_cache();
	}
} 
