<?php
class JSManager extends ResourceManager {

	protected $ext = "js";

	function getPath($file) {
		return $this->approot . '/js/'.$file.'.js';
	}

	function getTag($path) {
		return '<script src="'.$path.'" />';
	}

	function minify($file) {
		return JSMin::minify($file);
	}
}
