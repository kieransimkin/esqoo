<?php
class SQ_User_picture_size extends DBSQL { 
	function save() { 
		return parent::save(true);
	}
} 
