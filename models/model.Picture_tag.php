<?php
class SQ_Picture_tag extends SQ_Class_DBSQ { 
	public function save() { 
		return parent::save(true);
	}
}
