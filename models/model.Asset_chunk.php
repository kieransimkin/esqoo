<?php
class SQ_Asset_chunk extends DBSQL { 
	public function save() { 
		return parent::save(true);
	}
} 
