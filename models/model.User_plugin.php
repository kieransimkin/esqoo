<?php
class SQ_User_plugin extends SQ_Class_DBSQ { 
	function save() { 
		return parent::save(true);
	}
} 
