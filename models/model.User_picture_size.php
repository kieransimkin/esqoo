<?php
class User_picture_size extends DBSQL { 
	function save() { 
		return parent::save(true);
	}
} 
