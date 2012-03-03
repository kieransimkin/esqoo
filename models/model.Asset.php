<?php
class Asset extends DBSQL { 
	static public function searchPartiallyUploaded($chunkhash,$hashtype,$name,$userid,$data) { 
		return false;
	}
	public function getRemainingChunks() { 
		$chunks=Asset_chunk::getAll('asset_id=?',array($this->id));
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
} 
