<?php
class SQ_Asset_chunk extends SQ_Class_DBSQ { 
	public function save() { 
		return parent::save(true);
	}
} 
