<?php
class Asset extends DBSQL { 
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
	public function complete() { 
		$this->UploadCompleteDate=date('Y-m-d h:i:s');
		return $this->save();
	}
} 
