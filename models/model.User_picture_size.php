<?php
class SQ_User_picture_size extends SQ_Class_DBSQ { 
	function save() { 
		return parent::save(true);
	}
} 
