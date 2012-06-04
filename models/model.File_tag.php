<?php
class SQ_File_tag extends DBSQL { 
	public function save() { 
		return parent::save(true);
	}
}
