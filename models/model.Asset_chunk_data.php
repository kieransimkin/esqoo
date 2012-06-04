<?php
class SQ_Asset_chunk_data extends DBSQL { 
	public function save() { 
		return parent::save(true);
	}
} 
