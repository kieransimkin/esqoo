<?php
class Asset extends DBSQL { 
	public static $_cachedfields=array('MimeType','Size','AssetHash','HashType');
	static public function searchPartiallyUploaded($chunkhash,$hashtype,$name,$userid,$data) { 
		return false;
	}
	public function getRemainingChunks() { 
		$chunks=DBSQ::getAll('select id, Chunk from asset_chunk where asset_id=?',array($this->id),'Asset_chunk');
		$total=ceil($this->Size/$this->ChunkSize);
		$array=array();
		for ($c=0;$c<$total;$c++) { 
			$array[$c]=$c;
		}
		foreach ($chunks as $chunk) { 
			unset($array[$chunk->Chunk]);	
		}
		return array_keys($array);
	}
	public function get_filename() { 
		return dirname(__FILE__).'/../assets/'.$this->id;
	}	
	static public function get_filename_from_id($asset_id) { 
		return dirname(__FILE__).'/../assets/'.$asset_id;
	}
	public function complete($hashtype,$hash,$mimetype) { 
		$this->AssetHash=$hash;
		$this->HashType=$hashtype;
		$this->MimeType=$mimetype;
		$this->UploadCompleteDate=date('Y-m-d h:i:s');
		return $this->save();
	}
	public function output() { 
		header('Content-type: '.$this->MimeType);
		header('Content-length: '.$this->Size);
		readfile($this->get_filename());
		die;
	}
	public function is_picture() {
		switch ($this->MimeType) {  
			case 'image/jpeg':
			case 'image/pjpeg':
			case 'image/png':
			case 'image/gif':
			case 'image/tiff':
			case 'image/bmp':
			case 'image/x-windows-bmp':
			case 'image/x-tiff':
			case 'image/svg+xml':
				return true;
			default:
				if ($this->is_camera_raw()) { 
					return true;
				} else { 
					return false;
				}
		}
	}
	public function is_camera_raw() { 
		switch ($this->MimeType) { 
 			case 'image/x-3fr':
 			case 'image/x-adobe-dng':
 			case 'image/x-arw':
 			case 'image/x-bay':
 			case 'image/x-canon-cr2':
 			case 'image/x-canon-crw':
 			case 'image/x-cap':
 			case 'image/x-cr2':
 			case 'image/x-crw':
 			case 'image/x-dcr':
 			case 'image/x-dcraw':
 			case 'image/x-dcs':
 			case 'image/x-dng':
 			case 'image/x-drf':
 			case 'image/x-eip':
 			case 'image/x-erf':
 			case 'image/x-fff':
 			case 'image/x-fuji-raf':
 			case 'image/x-iiq':
 			case 'image/x-k25':
 			case 'image/x-kdc':
 			case 'image/x-mef':
 			case 'image/x-minolta-mrw':
 			case 'image/x-mos':
 			case 'image/x-mrw':
 			case 'image/x-nef':
 			case 'image/x-nikon-nef':
 			case 'image/x-nrw':
 			case 'image/x-olympus-orf':
 			case 'image/x-orf':
 			case 'image/x-panasonic-raw':
 			case 'image/x-pef':
 			case 'image/x-pentax-pef':
 			case 'image/x-ptx':
 			case 'image/x-pxn':
 			case 'image/x-r3d':
 			case 'image/x-raf':
 			case 'image/x-raw':
 			case 'image/x-rw2':
 			case 'image/x-rwl':
 			case 'image/x-rwz':
 			case 'image/x-sigma-x3f':
 			case 'image/x-sony-arw':
 			case 'image/x-sony-sr2':
 			case 'image/x-sony-srf':
 			case 'image/x-sr2':
 			case 'image/x-srf':
 			case 'image/x-x3f':
				return true;
			default:
				return false;
		}
	}
	function is_audio() { 
		switch($this->MimeType) { 
			case 'audio/mp4':	
			case 'audio/mpeg':
			case 'audio/ogg':
			case 'audio/vorbis':
			case 'audio/x-ms-wma':
			case 'audio/x-ms-wax':
			case 'audio/vnd.rn-realaudio':
			case 'audio/vnd.wave':
			case 'audio/webm':
			case 'audio/3gpp':
				return true;
			default:
				return false;
		}
	}
	function is_video() { 
		switch($this->MimeType) { 
			case 'video/mpeg':
			case 'video/mp4':
			case 'video/ogg':
			case 'video/quicktime':
			case 'video/webm':
			case 'video/x-matroska':
			case 'video/x-ms-wmv':
			case 'video/3gpp':
				return true;
			default: 
				return false;
		}
	}
	public function compile_asset($albumid) { 
		$res=DBSQ::query('select asset_chunk_data.Data,asset_chunk_data.id from asset_chunk left join asset_chunk_data on asset_chunk_data.asset_chunk_id=asset_chunk.id where asset_chunk.asset_id=? order by chunk asc',array($this->id));
		if (PEAR::isError($res)) { 
			die('Asset compile failed'.print_r($res,true));
		}
		$fp=fopen($this->get_filename(),"wb");
		while ($chunk=&$res->fetchRow(DB_FETCHMODE_ASSOC)) { 
			fwrite($fp,$chunk['Data']);
			DBSQ::query('delete from asset_chunk_data where id=? limit 1',array($chunk['id']));
		}
		fclose($fp);
		$finfo=finfo_open(FILEINFO_MIME_TYPE);
		$this->complete('MD5',md5_file($this->get_filename()),finfo_file($finfo,$this->get_filename()));
		if ($this->is_picture()) { 
			return $this->create_asset_picture($albumid);	
		} else if ($this->is_video()) { 
			return $this->create_asset_video($albumid);
		} else if ($this->is_audio()) { 
			return $this->create_asset_audio($albumid);
		} else {	
			// Unrecognised MIME type!
			return $this->create_asset_file($albumid);
		}
	}
	private function create_asset_picture($albumid) { 
		$israw=$this->is_camera_raw();
		$pic=Picture::get();
		$pic->album_id=$albumid;
		$pic->Name=$this->Name;
		$pic->Description='';
		$pic->GUID='fooo';
		$pic->digital_negative__asset_id=$this->id;
		if (!$israw) { 
			$pic->web_fullsize__asset_id=$this->id;
			$pic->thumbnail_fullsize__asset_id=$this->id;
		} else { 
			$pic->import_raw();
		}
		$pic->save();
		return array('AssetType'=>'Picture','Picture'=>$pic);
	}
	private function create_asset_video($albumid) { 
		$vid=Video::get();
		$vid->album_id=$albumid;
		$vid->Name=$this->Name;
		$vid->Description='';
		$vid->GUID='fooo';
		$vid->digital_negative__asset_id=$this->id;
		$vid->save();
		return array('AssetType'=>'Video','Video'=>$vid);
	}
	private function create_asset_audio($albumid) { 
		$audio=Audio::get();
		$audio->album_id=$albumid;
		$audio->Name=$this->Name;
		$audio->Description='';
		$audio->GUID='fooo';
		$audio->digital_negative__asset_id=$this->id;
		$audio->save();
		return array('AssetType'=>'Audio','Audio'=>$audio);
	}
	private function create_asset_file($albumid) { 
		$file=File::get();
		$file->album_id=$albumid;
		$file->Name=$this->Name;
		$file->Description='';
		$file->GUID='fooo';
		$file->MimeType=$this->MimeType;
		$file->digital_negative__asset_id=$this->id;
		$file->save();
		return array('AssetType'=>'File','File'=>$file);
	}
} 
