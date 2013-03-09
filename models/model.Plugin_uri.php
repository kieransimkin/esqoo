<?php
class SQ_Plugin_uri extends SQ_Class_DBSQ { 
	public static $_cachedfields=array();
	function save() { 
		$ret=parent::save(true);
		$this->purge_cache_list();
		return $ret;
	}
	function delete() { 
		$user_id=$this->user_id;
		$ret=self::query("delete from plugin_uri where id=? LIMIT 1", array($this->id));
		$this->purge_cache_list($user_id);
		return $ret;
	}
	static function create($data) { 
		$plugin=parent::create($data);
		$plugin->purge_cache_list();
		return $plugin;
	}
	private function purge_cache_list($user_id=null) { 
		if (is_null($user_id)) { 
			$user_id=$this->user_id;
		}
		$cache=SQ_Plugin_uri_cache::get($user_id,'user_id');
		$cache->purge_cache();

	}
} 
