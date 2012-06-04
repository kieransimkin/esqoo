<?php
class SQ_Audio_tag extends DBSQL { 
	public function save() { 
		return parent::save(true);
	}
}
