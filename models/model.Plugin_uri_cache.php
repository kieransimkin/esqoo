<?php
// This model represents a view rather than a normal database table. To update the activated plugins list update `plugin_uri` instead.
class SQ_Plugin_uri_cache extends SQ_Class_DBSQ { 
	public static $_cachedfields=array('PluginURIs');
	function save() { 
		throw new Exception('You can\'t save this view, update plugin_uri instead');
	}
} 
