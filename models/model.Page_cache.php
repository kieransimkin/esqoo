<?php
class Page_cache extends DBSQL { 
	public static $_cachedfields=array('Content','CreateDate','Size','CacheHash');
	public function save() { 
		return parent::save(true);
	}
	public function output() { 
		header("Last-Modified: ".gmdate("D, d M Y H:i:s",strtotime($this->CreateDate))." GMT");
		header("Etag: ".$this->CacheHash);
		header('Content-type: text/html');
		if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == gmdate("D, d M Y H:i:s",strtotime($this->CreateDate))." GMT" ||
		    trim($_SERVER['HTTP_IF_NONE_MATCH']) == $this->CacheHash) {
		    header("HTTP/1.1 304 Not Modified");
		    die;
		} 
		header('Content-length: '.$this->Size);
		echo $this->Content;
		die;
	}
} 
