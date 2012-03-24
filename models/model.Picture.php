<?php
class Picture extends DBSQL { 
	public static $_cachedfields=array('digital_negative__asset_id','web_small__asset_id','web_medium__asset_id','web_large__asset_id','web_fullsize__asset_id','thumbnail_small__asset_id','thumbnail_large__asset_id','square__asset_id');
	public static function assert_picture_size_type($type) { 
		if ($type!='web-fullsize' && $type!='web-small' && $type!='web-medium' && $type!='web-large' && $type!='thumbnail-large' && $type!='thumbnail-small' && $type!='square') { 
			throw new Exception('type must be one of web-small, web-medium, web-large, thumbnail-large, thumbnail-small or square');
		}
	}
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
		return $this->save();
	}
	public function get_url($size='web-small') { 
		self::assert_picture_size_type($size);
		return '/asset/picture/'.$size.'/'.$this->id.'-'.Helper::url_friendly_encode($this->Name);
	}
	public function get_url_array() { 
		$ret=array();
		foreach (array('web-fullsize','web-small','web-medium','web-large','thumbnail-large','thumbnail-small','square') as $type) { 
			$ret[$type]=$this->get_url($type);
		}
		return $ret;
	}
	public function import_raw() { 

	}
} 
