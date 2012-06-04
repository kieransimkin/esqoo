<?php
class SQ_Video_tag extends SQ_Class_DBSQ { 
	public function save() { 
		return parent::save(true);
	}
}
