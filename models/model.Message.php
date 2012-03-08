<?php
class Message extends DBSQL { 
	public function save() { 
		parent::save(true);
	}
} 
