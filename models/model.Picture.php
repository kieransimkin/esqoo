<?php
class Picture extends DBSQL { 
	public static $_cachedfields=array('digital_negative__asset_id','web_small__asset_id','web_medium__asset_id','web_large__asset_id','web_fullsize__asset_id','thumbnail_small__asset_id','thumbnail_large__asset_id','square__asset_id');
	public function generate_thumbnail($size) { 
		$max=$this->album->user->get_picture_size($size);
		if ($size=='square' || substr($size,0,10)=='thumbnail_') { 
			$fname=$this->thumbnail_fullsize__asset->get_filename();
		} else { 
			$fname=$this->web_fullsize__asset->get_filename();
		}
		$image=new Imagick($fname);
		$image->thumbnailImage($max,$max,true);
		$image->setImageFormat('jpeg');
	        $image->setImageCompressionQuality(90);
		$asset=Asset::get();
		$data=(string)$image;
		$asset->Size=strlen($data);
		$asset->ChunkSize=strlen($data);
		$asset->MimeType='image/jpeg';
		$asset->HashType='MD5';
		$asset->AssetHash=md5($data);
		$assetid=$asset->save();
		$fp=fopen($asset->get_filename(),"wb");
		fwrite($fp,$data);
		fclose($fp);
		$var=str_replace('-','_',$size).'__asset_id';
		$this->$var=$assetid;
		$ret=$this->save();
		return $this->save();
	}
	public function import_raw() { 

	}
} 
