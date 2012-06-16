<?php
// This model represents a view rather than a normal database table. To update the activated plugins list update `user_plugin` instead.
class SQ_User_plugin_cache_list extends SQ_Class_DBSQ { 
	public static $_cachedfields=array('IdentifierList');
	function save() { 
		throw new Exception('You can\'t save this view, update user_plugin instead');
	}
} 
