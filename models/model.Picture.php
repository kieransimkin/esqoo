<?php
class Picture extends DBSQL { 
	public static $_cachedfields=array('digital_negative__asset_id','web_small__asset_id','web_medium__asset_id','web_large__asset_id','web_fullsize__asset_id','thumbnail_small__asset_id','thumbnail_large__asset_id','square__asset_id');
	public function generate_thumbnail($size) { 
		$max=$this->album->user->get_picture_size($size);
		$image=new Imagick($this->web_fullsize__asset->get_filename());
		$image->thumbnailImage($max,$max,true);
		$image->setImageFormat('jpeg');
	        $image->setImageCompressionQuality(90);
		header('Content-type: image/jpeg');
		echo $image;
		die;
	}
} 
