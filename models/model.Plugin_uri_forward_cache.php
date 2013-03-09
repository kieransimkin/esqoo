<?php
// This model represents a view rather than a normal database table. To update the activated plugins list update `plugin_uri_forward` instead.
class SQ_Plugin_uri_forward_cache extends SQ_Class_DBSQ { 
	public static $_cachedfields=array('PluginURIForwards');
	function save() { 
		throw new Exception('You can\'t save this view, update plugin_uri_forward instead');
	}
} 
