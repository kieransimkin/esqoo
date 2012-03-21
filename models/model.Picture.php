<?php
class Picture extends DBSQL { 
	public static $_cachedfields=array('digital_negative__asset_id','web_small__asset_id','web_medium__asset_id','web_large__asset_id','web_fullsize__asset_id','thumbnail_small__asset_id','thumbnail_large__asset_id','square__asset_id');
	public function generate_thumbnail($size) { 
	
		var_dump($size);
		var_dump($this->user->get_picture_size($size));
	}
} 
