<?php
class Picture_tag extends DBSQL { 
	public function save() { 
		return parent::save(true);
	}
}
