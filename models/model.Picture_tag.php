<?php
class SQ_Picture_tag extends DBSQL { 
	public function save() { 
		return parent::save(true);
	}
}
