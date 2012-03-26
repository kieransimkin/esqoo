<?php
class Post_tag extends DBSQL { 
	public function save() { 
		return parent::save(true);
	}
}
